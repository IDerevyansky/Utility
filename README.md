# Analyze used vs unused files

> Scans the project with support for @/ and ~/ aliases to identify all files of a specified extension (e.g., .vue). Compares them against actual imports to determine used and unused files, generates two reports with usage percentages, and respects glob-pattern ignore rules.
> 

## **Install Dependencies**

The script uses `tsx` to run TypeScript directly without compiling.

```bash
npm install tsx typescript --save-dev
```

## **Adjust Script Settings**

Open `file-reader.ts` and check:

- `includeDirs` — directories to scan
- `ignore` — glob patterns for files/folders to exclude
- `scanExts` — file extensions to scan for imports
- `targetExts` — which files are considered components/targets
- `alias` — your path aliases (`@` and `~`)

## **Run the Script**

From the project root:

```bash
npx tsx file-reader.ts
```

If everything is fine, you’ll see output like:

```bash
USED: 45/120 (37.50%)
UNUSED: 75/120 (62.50%)
→ saved to used_files.txt and unused_files.txt
```


# Slider cards

> Slider for site with animation the flipping cards in a circle.
> <img src = 'https://github.com/IDerevyansky/Utility/blob/master/Slider_cards/Slider_pre.png?raw=true'>

# Parser

> Script parsing to quote of xml file cbr.ru. Get quote currency eur and usd. Parser weather with general page yandex.ru

# Automatically change password

> Script activate by cron server. In to time how work cron, script automatically generate password, in view two strings. And send to mail, showed in script.
> <img src = 'https://github.com/IDerevyansky/Utility/blob/master/Change/snapshot_psw.png?raw=true'>

# Search images in directory

> Script do search images to directory with select size, or diapason size.
