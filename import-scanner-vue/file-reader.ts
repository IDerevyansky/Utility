// file-reader.ts
import { readdirSync, Dirent, writeFileSync, readFileSync } from "fs";
import { join, resolve, relative, sep, dirname, extname, basename } from "path";
import { fileURLToPath } from "url";

type AliasMap = Record<string, string>; // { "@": "./src", "~": "./src" }

type Options = {
  includeDirs: string[];
  ignore?: string[];
  scanExts: string[];    // file extensions to scan for imports (ts/js/vue)
  targetExts: string[];  // file extensions considered as components/targets (e.g., .vue)
  alias?: AliasMap;      // aliases like { "@": "./src", "~": "./src" }
};

// ---------- glob → RegExp (supports **, *, ?) + fix for /** ----------
function globToRegExp(glob: string): RegExp {
  // normalize slashes and remove leading ./ for consistency
  const normRaw = glob.replace(/\\/g, "/").replace(/^\.\//, "");

  // If pattern starts with "**/", make the leading part optional (also matches root)
  const startsWithDoubleStar = normRaw.startsWith("**/");
  const norm = startsWithDoubleStar ? normRaw.slice(3) : normRaw;

  // escape RegExp special characters except * and ?
  const escaped = norm.replace(/([.+^=!:${}()|[\]\\/])/g, "\\$1");
  const DOUBLE = "§§__DOUBLESTAR__§§";

  // replace ** → token, then replace * and ?
  let withToken = escaped.replace(/\*\*/g, DOUBLE);
  withToken = withToken.replace(/\*/g, "[^/]*").replace(/\?/g, "[^/]");

  // restore ** as .*
  let tail = withToken.replace(new RegExp(DOUBLE, "g"), ".*");

  // if original pattern (after removing **/) ended with '/**' — match the folder itself and everything inside it
  // '/pages/**' -> '/pages(?:/.*)?'
  if (norm.endsWith("/**")) {
    tail = tail.replace(/\/\.\*\$$/, "(?:\\/.*)?$");
  }

  // if original started with "**/", add optional leading part
  // result: ^(?:.*/)?<tail> — "may have subdirectories or not" + the rest of the pattern
  const body = (startsWithDoubleStar ? "(?:.*\\/)?": "") + tail;

  return new RegExp("^" + body + "$");
}
function buildIgnoreMatchers(ignore: string[] = []): RegExp[] {
  // remove empty entries and leading ./
  const cleaned = ignore
    .filter(Boolean)
    .map(s => s.replace(/^\.\//, ""));
  return cleaned.map(globToRegExp);
}
function shouldIgnore(relPath: string, matchers: RegExp[]): boolean {
  // normalize path to '/' format, without leading ./
  const p = relPath.split(sep).join("/").replace(/^\.\//, "");
  return matchers.some((rx) => rx.test(p));
}

// ---------- file system traversal ----------
function listFilesFromRoot(root: string, matchers: RegExp[], results: string[]) {
  let entries: Dirent[];
  try {
    entries = readdirSync(root, { withFileTypes: true });
  } catch {
    return;
  }

  for (const ent of entries) {
    
    const full = join(root, ent.name);
    const relFromCwd = relative(process.cwd(), full).split(sep).join("/");
    // console.log('[DBG]', relFromCwd);
    // check ignore rules before recursing
    if (shouldIgnore(relFromCwd, matchers)) continue;

    if (ent.isDirectory()) {
      // safeguard: if the directory itself should be ignored,
      // check with a trailing slash
      const dirKey = relFromCwd.endsWith("/") ? relFromCwd : relFromCwd + "/";
      if (shouldIgnore(dirKey, matchers)) continue;
      listFilesFromRoot(full, matchers, results);
    } else if (ent.isFile()) {
      results.push(full);
    }
  }
}
function listAllFiles(opts: Pick<Options,"includeDirs"|"ignore">): string[] {
  const results: string[] = [];
  const roots = opts.includeDirs.map((d) => resolve(d));
  const matchers = buildIgnoreMatchers(opts.ignore);

  for (const root of roots) {
    const rel = relative(process.cwd(), root).split(sep).join("/");
    if (shouldIgnore(rel, matchers) || shouldIgnore(rel + "/", matchers)) continue;
    listFilesFromRoot(root, matchers, results);
  }
  return results;
}

// ---------- utilities ----------
const hasExt = (p: string, exts: string[]) =>
  exts.some((e) => p.toLowerCase().endsWith(e.toLowerCase()));

function readTextSafe(p: string): string {
  try { return readFileSync(p, "utf-8"); } catch { return ""; }
}

function toKebabCase(name: string): string {
  // FooBarBaz -> foo-bar-baz
  return name
    .replace(/([a-z0-9])([A-Z])/g, "$1-$2")
    .replace(/_/g, "-")
    .toLowerCase();
}
function toPascalCase(name: string): string {
  // foo-bar -> FooBar
  return name
    .split(/[-_]/g)
    .filter(Boolean)
    .map(s => s.charAt(0).toUpperCase() + s.slice(1))
    .join("");
}

// ---------- resolve imports (+ aliases) ----------
function resolveWithAliases(spec: string, importerFile: string, alias: AliasMap | undefined): string | null {
  // relative paths
  if (spec.startsWith("./") || spec.startsWith("../")) {
    return resolve(dirname(importerFile), spec);
  }
  // aliases
  if (alias) {
    for (const key of Object.keys(alias)) {
      const prefix = key.endsWith("/") ? key : key + "/";
      if (spec === key) return resolve(alias[key]); // exact match like "@"
      if (spec.startsWith(prefix)) {
        const mappedBase = resolve(alias[key]);
        const rest = spec.slice(prefix.length);
        return resolve(mappedBase, rest);
      }
    }
  }
  return null; // bare packages not supported here
}

function resolveImportTarget(
  importerFile: string,
  spec: string,
  targetExts: string[],
  alias?: AliasMap
): string | null {
  const base = resolveWithAliases(spec, importerFile, alias);
  if (!base) return null;

  // if spec already has one of targetExts
  for (const ext of targetExts) {
    if (base.toLowerCase().endsWith(ext.toLowerCase())) {
      try { readFileSync(base); return base; } catch {}
    }
  }
  // without extension — try base + ext
  for (const ext of targetExts) {
    const p1 = base + ext;
    try { readFileSync(p1); return p1; } catch {}
  }
  // index.ext
  for (const ext of targetExts) {
    const p2 = join(base, "index" + ext);
    try { readFileSync(p2); return p2; } catch {}
  }
  return null;
}

// ---------- extract imports ----------
type ImportFound = {
  spec: string;
  resolvedPath: string|null;
  localNames: string[];
  importerFile: string;
};

function findImportsInCodeFile(
  filePath: string,
  content: string,
  targetExts: string[],
  alias?: AliasMap
): ImportFound[] {
  const imports: ImportFound[] = [];
  const importRegex = /import\s+([\s\S]*?)\s+from\s+['"]([^'"]+)['"]/g;
  const bareImportRegex = /import\s+['"]([^'"]+)['"]/g;
  const dynImportRegex = /import\s*\(\s*['"]([^'"]+)['"]\s*\)/g;

  let m: RegExpExecArray | null;

  function parseLocalNames(part: string): string[] {
    const names: string[] = [];
    const trimmed = part.trim();
    if (!trimmed) return names;

    const ns = trimmed.match(/^\*\s+as\s+([A-Za-z_$][\w$]*)/);
    if (ns) { names.push(ns[1]); return names; }

    const parts = trimmed.split(",").map(s=>s.trim());
    for (const p of parts) {
      if (!p) continue;
      if (p.startsWith("{") && p.endsWith("}")) {
        const inside = p.slice(1,-1);
        inside.split(",").forEach(s=>{
          const id = s.trim().split(/\s+as\s+/i).pop();
          if (id) names.push(id!.trim());
        });
      } else if (p && p !== "{}") {
        names.push(p);
      }
    }
    return names.filter(Boolean);
  }

  while ((m = importRegex.exec(content))) {
    const localPart = m[1] ?? "";
    const spec = m[2] ?? "";
    const localNames = parseLocalNames(localPart);
    const resolvedPath = resolveImportTarget(filePath, spec, targetExts, alias);
    if (resolvedPath) {
      imports.push({ spec, resolvedPath, localNames, importerFile: filePath });
    }
  }
  while ((m = bareImportRegex.exec(content))) {
    const spec = m[1] ?? "";
    const resolvedPath = resolveImportTarget(filePath, spec, targetExts, alias);
    if (resolvedPath) {
      imports.push({ spec, resolvedPath, localNames: [], importerFile: filePath });
    }
  }
  while ((m = dynImportRegex.exec(content))) {
    const spec = m[1] ?? "";
    const resolvedPath = resolveImportTarget(filePath, spec, targetExts, alias);
    if (resolvedPath) {
      imports.push({ spec, resolvedPath, localNames: [], importerFile: filePath });
    }
  }
  return imports;
}

// ---------- check if imports are used ----------
function isImportUsed(content: string, localNames: string[]): boolean {
  if (localNames.length === 0) return true; // bare/dynamic: considered used
  const withoutImports = content.replace(/^\s*import[\s\S]*?;?\s*$/gm, "");
  return localNames.some((name) => new RegExp(`\\b${name}\\b`).test(withoutImports));
}

// ---------- build "component index" from targetExts ----------
type ComponentIndex = Map<string /*kebab|pascal*/, Set<string /*abs paths*/>>;

function buildComponentIndex(allTargetFiles: string[]): ComponentIndex {
  const idx: ComponentIndex = new Map();
  for (const p of allTargetFiles) {
    const ext = extname(p);
    const base = basename(p, ext); // MyComp or index
    const dirName = basename(dirname(p));

    const candidates = new Set<string>();
    // name from file
    candidates.add(base);
    candidates.add(toPascalCase(base));
    candidates.add(toKebabCase(base));

    // if index.vue — use the directory name as the component name
    if (base.toLowerCase() === "index") {
      candidates.add(dirName);
      candidates.add(toPascalCase(dirName));
      candidates.add(toKebabCase(dirName));
    }

    for (const name of candidates) {
      const keb = toKebabCase(name);
      const pas = toPascalCase(name);
      // index in both forms:
      const keys = new Set([keb, pas]);
      for (const k of keys) {
        const s = idx.get(k) ?? new Set<string>();
        s.add(p);
        idx.set(k, s);
      }
    }
  }
  return idx;
}

// ---------- find globally used components in .vue ----------
const HTML_TAGS = new Set([
  "div","span","p","a","ul","ol","li","table","thead","tbody","tr","td","th",
  "section","article","header","footer","nav","main","aside","button","input",
  "textarea","select","option","img","canvas","svg","path","circle","rect","g",
  "label","form","fieldset","legend","video","audio","source","template","slot",
  "h1","h2","h3","h4","h5","h6","small","strong","em","code","pre","blockquote",
  "hr","br"
]);

function extractTemplate(content: string): string {
  const m = content.match(/<template[^>]*>([\s\S]*?)<\/template>/i);
  return m ? m[1] : "";
}

function findVueTemplateComponents(content: string): Set<string> {
  const tpl = extractTemplate(content);
  const used = new Set<string>();
  const tagRx = /<\s*([A-Za-z][A-Za-z0-9-]*)\b/g;
  let m: RegExpExecArray | null;
  while ((m = tagRx.exec(tpl))) {
    const tag = m[1];
    const isHtml =
      HTML_TAGS.has(tag.toLowerCase()) ||
      !(/[A-Z]/.test(tag) || tag.includes("-")); // heuristic: component usually PascalCase or contains '-'
    if (isHtml) continue;
    // normalize in both forms: kebab + pascal
    used.add(toKebabCase(tag));
    used.add(toPascalCase(tag));
  }
  return used;
}

// ---------- main analysis ----------
function analyzeProject(opts: Options) {
  const allFiles = listAllFiles({ includeDirs: opts.includeDirs, ignore: opts.ignore });

  const codeFiles = allFiles.filter((f) => hasExt(f, opts.scanExts));
  const targetFilesSet = new Set(
    allFiles.filter((f) => hasExt(f, opts.targetExts)).map((p) => resolve(p))
  );
  const targetFiles = Array.from(targetFilesSet);
  const componentIndex = buildComponentIndex(targetFiles);

  // map: targetFile -> { importers: Set<string>; anyUsed: boolean }
  const importedBy = new Map<string, { importers: Set<string>; anyUsed: boolean }>();

  // 1) local imports of target files
  for (const codeFile of codeFiles) {
    const content = readTextSafe(codeFile);
    if (!content) continue;

    const imports = findImportsInCodeFile(codeFile, content, opts.targetExts, opts.alias);
    for (const imp of imports) {
      if (!imp.resolvedPath) continue;
      const key = resolve(imp.resolvedPath);
      if (!targetFilesSet.has(key)) continue;
      const used = isImportUsed(content, imp.localNames);
      const entry = importedBy.get(key) ?? { importers: new Set<string>(), anyUsed: false };
      entry.importers.add(codeFile);
      entry.anyUsed = entry.anyUsed || used;
      importedBy.set(key, entry);
    }
  }

  // 2) globally used components in .vue (by template scan)
  for (const vueFile of codeFiles.filter(f => f.toLowerCase().endsWith(".vue"))) {
    const content = readTextSafe(vueFile);
    if (!content) continue;

    // find which target imports are already recorded as imported in this file
    const alreadyImportedTargets = new Set<string>();
    for (const [tgt, info] of importedBy.entries()) {
      if (info.importers.has(vueFile)) alreadyImportedTargets.add(tgt);
    }

    const tags = findVueTemplateComponents(content);
    for (const name of tags) {
      const candidates = componentIndex.get(name);
      if (!candidates) continue;
      for (const absPath of candidates) {
        // if this component is not imported in the file, count as "globally used"
        if (!alreadyImportedTargets.has(absPath)) {
          const entry = importedBy.get(absPath) ?? { importers: new Set<string>(), anyUsed: false };
          entry.importers.add(vueFile);
          entry.anyUsed = true; // global usage = used
          importedBy.set(absPath, entry);
        }
      }
    }
  }

  // 3) classification
  const used: string[] = [];
  const unusedImported: string[] = [];
  const neverImported: string[] = [];

  for (const tf of targetFiles) {
    const entry = importedBy.get(tf);
    if (!entry) {
      neverImported.push(tf);
    } else if (entry.anyUsed) {
      used.push(tf);
    } else {
      unusedImported.push(tf);
    }
  }
  const unused = [...unusedImported, ...neverImported];

  const total = targetFiles.length;
  const usedPct = total ? ((used.length / total) * 100).toFixed(2) : "0.00";
  const unusedPct = total ? ((unused.length / total) * 100).toFixed(2) : "0.00";

  return {
    used,
    unusedImported,
    neverImported,
    unused,
    total,
    usedPct,
    unusedPct,
  };
}

// ---------- write reports ----------
function writeReport(pathOut: string, header: string, items: string[]) {
  writeFileSync(pathOut, `${header}\n${items.join("\n")}\n`, "utf-8");
}

// ------------------ run (ESM) ------------------
const isDirectRun = fileURLToPath(import.meta.url) === resolve(process.argv[1] ?? "");
if (isDirectRun) {
  const opts: Options = {
    includeDirs: ["./", "./src"],
    ignore: [
      "node_modules/**",
      ".git/**",
      "dist/**",
      "build/**",
      "**/*.log",
      "**/*.tmp",
      "**/.DS_Store",
      "pages/**",     
      "layouts/**",   
      "**/pages/**",  
      "**/layouts/**" 
    ],
    scanExts: [".ts", ".tsx", ".js", ".jsx", ".vue"],
    targetExts: [".vue"],
    alias: {
      "@": "./src",
      "~": "./src",
    },
  };

  const res = analyzeProject(opts);

  const headerUsed   = `USED: ${res.used.length}/${res.total} (${res.usedPct}%)`;
  const headerUnused = `UNUSED: ${res.unused.length}/${res.total} (${res.unusedPct}%)`;

  writeReport("used_files.txt", headerUsed, res.used);
  writeReport("unused_files.txt", headerUnused, res.unused);

  console.log(headerUsed);
  console.log(headerUnused);
  console.log("→ saved to used_files.txt and unused_files.txt");
}
