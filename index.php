<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/path/semantic.min.css"/>
    <link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.1.8/components/icon.min.css'>
    <script src="/path/jquery.min.js"></script>
    <script src="/path/semantic.min.js"></script>
    <link rel="icon" href="logo.png" type="image/x-icon">
    <title>GFP Parser</title>
</head>
<body style="background-image: url('background.png');">

    <!------------------Белый блок (сегмент)------------------>
    <form method="post" id="resultForm">
        <div class="ui inverted segment  raised" style="width: 1000px; position: absolute; left: 50%; top:30%; margin-left: -500px;">
            <!------------------Верхний заголовок------------------>

            <h2 class="ui icon center aligned header green">
                <div class="content">GrandFatherPies Parser</div>
            </h2>
            <div class="field">
                <a href="/">
                    <img class="ui mini left floated image" src="/logo.png">
                </a>
                <div class="ui fluid action input">
                    <input type="text" placeholder="Ключевые слова" name="keywords" onkeyup="this.value=this.value.replace(/^\s/,'')" required>
                    <!------------------Длинная анимационная кнопка------------------>
                    <button type="submit" class="ui animated teal button">
                        <!------------------Текст, если кнопка не выделена------------------>
                        <div class="visible content">Найти</div>
                        <!------------------Текст, если кнопка выделена------------------>
                        <div class="hidden content">
                            <i class="search icon"></i>
                        </div>
                    </button>
                </div>
            </div>
            <br>
            <div class="ui inverted accordion">
                <div class="title">
                    <i class="dropdown teal icon"></i>
                    Дополнительно
                </div>
                <div class="content">
                    <!--
                    <div class="ui form hint" data-content="Максимальное количество страниц поиска">
                        <div class="field two wide">
                            <input type="number" class="two wide" value="100" name="maxpages" required>
                        </div>
                    </div>

                    <div class="ui checkbox hint" data-content="Выдает только те результаты, которые содержат PDF-документы">
                        <input type="checkbox" name="openaccess">
                        <label style="color: #FFFFFFE6">Общедоступные публикации</label>
                    </div>

                    <div class="ui checkbox" style="margin-left: 20px; margin-bottom: 15px; margin-top: 15px">
                        <input type="checkbox" name="bucketname">
                        <label style="color: #FFFFFFE6">Импортировать документы в базу данных</label>
                    </div>
                    -->
                    <div class="ui checkbox hint" style="margin-bottom: 15px; margin-top: 5px" data-content="Задействовать парсер, если слова не найдены">
                        <input type="checkbox" name="parseractive" >
                        <label style="color: #FFFFFFE6">Парсер</label>
                    </div>

                    <!------------------Радиобоксы выбора языка поиска------------------>
                    <div class="ui form">
                        <div class="inline fields">
                            <label style="color: #FFFFFFE6">Язык поиска:</label>
                            <div class="field">
                                <div class="ui radio checkbox">
                                    <input type="radio" name="checkLanguage" value=0 checked="checked">
                                    <label style="color: #FFFFFFE6">Любой</label>
                                </div>
                            </div>
                            <div class="field">
                                <div class="ui radio checkbox">
                                    <input type="radio" name="checkLanguage" value=1 >
                                    <label style="color: #FFFFFFE6">Английский</label>
                                </div>
                            </div>
                            <div class="field">
                                <div class="ui radio checkbox">
                                    <input type="radio" name="checkLanguage" value=2>
                                    <label style="color: #FFFFFFE6">Русский</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>


    <div class="ui page dimmer" id="loadShow">
        <div class="segment">
            <div class="ui active dimmer">
                <div class="ui massive text loader">Выполняем запрос...</div>
            </div>
        </div>
    </div>




<script>
    $('.ui.accordion')
        .accordion()
    ;
    $('.hint')
        .popup()
    ;
    $(document).ready(function () {
        $("#resultForm").submit(function () {
            $('#loadShow').dimmer('show');
            $.ajax({
                type: 'POST',
                url: "parser.php",
                data: $(this).serialize(),
            }).done(function() {
                $('#loadShow').dimmer('hide');
                $(location).attr('href', '/search.php');
            });
            return false;
        });
    });
</script>

</body>
</html>

