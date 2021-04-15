<!DOCTYPE html>
<html>
    <head>
        <title>Аватарки</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body>

        <form action="/merge" method="post" enctype="multipart/form-data">

        <label for="frame-img"><p>Выберите шаблоны для аватарок</p></label>

        <select name="frame" id="frame-img">
            <option value="">Выберите шаблон</option>
            <option value="Best_Sales">Best sales</option>
            <option value="h5">H5</option>
        </select>

          <p>
            <label for="photo-img"><p>Выберите фото</p></label>
            <input id="photo-img" type="file" name="photo">
          </p>
            <button name="btn" type="submit">Далее</button>

        </form>

    </body>
</html>
