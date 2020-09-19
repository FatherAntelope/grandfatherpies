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
    <link rel="icon" href="/logo.png" type="image/x-icon">
    <title>GFP Parser</title>
</head>
<style>
    body
    {
        display: flex;
        height: 100vh;
        align-items: center;
        justify-content: center;
        background-image: url('background.png');
    }
    .search-box
    {
        width: 80%;
    }
</style>
<body>

    <!------------------White box (segment)------------------>
    <form method="post" id="resultForm" class="search-box">
        <div class="ui inverted segment  raised">
            <!------------------Top header (Name Project)------------------>
            <h2 class="ui icon center aligned header green">
                <div class="content">GrandFatherPies Parser</div>
            </h2>
            <div class="field">
                <a href="/">
                    <img class="ui mini left floated image" src="/logo.png">
                </a>
                <!--Box the search keywords-->
                <div class="ui fluid action input">
                    <input type="text" placeholder="Ключевые слова. Например: AI" name="keywords" onkeyup="this.value=this.value.replace(/^\s/,'')" required>
                    <!------------------The long animation button------------------>
                    <button type="submit" class="ui animated teal button">
                        <!------------------Show this text if the button inactive------------------>
                        <div class="visible content">Найти</div>
                        <!------------------Show this icon if the button active------------------>
                        <div class="hidden content">
                            <i class="search icon"></i>
                        </div>
                    </button>
                </div>
            </div>
            <br>
            <!------------------Search filter settings------------------->
            <div class="ui inverted accordion">
                <div class="title">
                    <i class="dropdown teal icon"></i>
                    Дополнительно
                </div>
                <div class="content">
                    <div class="ui checkbox hint" style="margin-bottom: 15px; margin-top: 5px" data-content="Задействовать парсер, если слова не найдены (Экспериментально. Только для EN)">
                        <input type="checkbox" name="parseractive" >
                        <label style="color: #FFFFFFE6">Парсер</label>
                    </div>

                    <!------------------Radioboxex for language selection------------------>
                    <div class="ui form">
                        <div class="inline fields">
                            <label style="color: #FFFFFFE6">Язык поиска:</label>
                            <div class="field">
                                <div class="ui radio checkbox">
                                    <input type="radio" name="checkLanguage" value=0 disabled>
                                    <label style="color: #FFFFFFE6">Любой</label>
                                </div>
                            </div>
                            <div class="field">
                                <div class="ui radio checkbox">
                                    <input type="radio" name="checkLanguage" value=1 checked="checked">
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

    <!--The dimmer of search-->
    <div class="ui page dimmer" id="loadShow">
        <div class="segment">
            <div class="ui active dimmer">
                <h2 class="ui icon header" style="color: white">
                    <i class="spinner loading icon"></i>
                    <div class="content">
                        Выполняем поиск запроса...
                        <div class="sub header" style="color: #cbcbcb">
                            Если вы активировали парсер, то это займет некоторое время
                        </div>
                    </div>
                </h2>
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

    //dynamic search launch
    $(document).ready(function () {
        $("#resultForm").submit(function () {
            $('#loadShow').dimmer('show');
            $.ajax({
                type: 'POST',
                url: "/parser.php",
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

