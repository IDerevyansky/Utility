<!DOCTYPE html>
<html>
    <head>
        <title>Аватарки</title>
        <link rel="icon" href="ico.ico" type="image/x-icon" sizes="64x64">
     
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
                <!-- UIkit CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/uikit@3.6.19/dist/css/uikit.min.css" />
        <link rel="stylesheet" href="style.css" />

        <!-- UIkit JS -->
        <script src="https://cdn.jsdelivr.net/npm/uikit@3.6.19/dist/js/uikit.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/uikit@3.6.19/dist/js/uikit-icons.min.js"></script>
    </head>
    <body>

    <div class="uk-section">
        <div class="uk-container uk-width-1-3">

        <!-- <h4 class="uk-h4 uk-text-center uk-margin-medium-bottom uk-text-bold">Выберите шаблоны для аватарок</h4> -->

               <div class="body-section">
               
                    <form action="/merge" method="post" enctype="multipart/form-data">

                        <select class="uk-select uk-border-rounded" name="frame" >
                            <option value="">Выберите шаблон</option>
                            <option value="Best_Sales">Best sales</option>
                            <option value="h5">H5</option>
                        </select>

                        <div class="uk-margin">
                            
                           
                            <div  class="uk-width-1-1" uk-form-custom="target: true">
                            <input name="photo" type="file">
                            <input class="uk-button uk-button-default uk-width-1-1 uk-border-rounded" type="text" placeholder="Выберите файл">
                            </div>

                        </div>


                        <button class="uk-button uk-button-primary uk-border-rounded uk-width-1-1 uk-button-large uk-margin-top" name="btn" type="submit">Далее</button>

                        </form>
                            
               </div> 

          

        </div>
    </div>
    <div class="uk-text-center uk-padding-small uk-position-bottom-center disc">Produced by IDerevyansky for Alpari</div>

    </body>
</html>
