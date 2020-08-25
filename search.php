<?
session_start();
require_once 'aws.php';
require_once 'settingAWS.php';

//$db = new DynamoDataBase($access_key, $secret_key, $region);
//$attributes = "ID, Title, Abstract, Authors, Publisher, PublicationName, PublicationDate, Link, PDFLink";
//$tableData = $db->getTableData($tableName, $attributes, $limit, $_SESSION['keywords']);

if (isset($_SESSION['resultsSearch']))
    $tableData = $_SESSION['resultsSearch'];
else
    $tableData = "string";

?>
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
    <title>Результаты поиска</title>
</head>
<body style="background-image: url('background.png');">

<div class="ui inverted segment attached" style="position: sticky; z-index: 100">
    <!------------------Верхний заголовок------------------>
    <form class="ui container" id="resultForm">
        <div class="field">
            <a href="/">
                <img class="ui mini left floated image" src="/logo.png">
            </a>

            <div class="ui fluid action input">
                <input type="text" placeholder="Ключевые слова" name="keywords"  value="<? if(isset($_SESSION['keywords'])) echo $_SESSION['keywords']; ?>" required>
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
                    <input type="checkbox" name="parseractive">
                    <label style="color: #FFFFFFE6">Парсер</label>
                </div>

                <!------------------Радиобоксы выбора языка поиска------------------>
                <div class="ui form">
                    <div class="inline fields">
                        <label style="color: #FFFFFFE6">Язык поиска:</label>
                        <div class="field">
                            <div class="ui radio checkbox">
                                <input type="radio" name="checkLanguage" value=0 <? if($_SESSION['checkLanguage'] == 0) echo "checked"; ?>>
                                <label style="color: #FFFFFFE6">Любой</label>
                            </div>
                        </div>
                        <div class="field">
                            <div class="ui radio checkbox">
                                <input type="radio" name="checkLanguage" value=1 <? if($_SESSION['checkLanguage'] == 1) echo "checked"; ?>>
                                <label style="color: #FFFFFFE6">Английский</label>
                            </div>
                        </div>
                        <div class="field">
                            <div class="ui radio checkbox">
                                <input type="radio" name="checkLanguage" value=2 <? if($_SESSION['checkLanguage'] == 2) echo "checked"; ?>>
                                <label style="color: #FFFFFFE6">Русский</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </form>

</div>



<div class="ui container" style="margin-top: 10px; margin-bottom: 10px">
    <?
    if(gettype($tableData) != "string"){
        if($tableData['Count'] != 0) {
            echo "<p>Найдено: {$tableData['Count']}</p>";
            //print_r($tableData);
            for ($k = 0; $k < $tableData['Count']; $k++) {
                ?>
                <div class="ui raised link card fluid">
                    <div class="content">
                        <div class="header"><? echo $tableData['Items'][$k]['Title']['S']; ?></div>
                        <div class="right floated meta"><? echo $tableData['Items'][$k]['PublicationDate']['S']; ?></div>
                        <div class="meta">
                            <span class="category"><? echo $tableData['Items'][$k]['PublicationName']['S']; ?></span>
                        </div>
                        <div class="description">
                            <p><? echo $tableData['Items'][$k]['Abstract']['S']; ?></p>
                        </div>
                    </div>
                    <div class="extra content">
                        <div class="left floated author">
                            <?
                            for ($i = 0; $i < count($tableData['Items'][$k]['Authors']['L']); $i++) {
                                echo "<p style='margin-right: 5px' class=\"ui teal mini label\"> {$tableData['Items'][$k]['Authors']['L'][$i]['S']} </p>";
                            }
                            ?>
                        </div>
                        <div class="right floated author">
                            <h5 style="color: #00b5ad"><? echo $tableData['Items'][$k]['Publisher']['S']; ?></h5>
                        </div>
                    </div>
                    <div class="extra content">
                        <div class="right floated author">

                            <?php
                            if (isset($tableData['Items'][$k]['PDFLink']['S'])) {
                                ?>
                                <a href="<? echo $tableData['Items'][$k]['PDFLink']['S']; ?>"
                                   class="ui fade animated button green" tabindex="0">
                                    <div class="visible content">
                                        К документу
                                    </div>
                                    <div class="hidden content">
                                        <i class="file pdf icon"></i>
                                    </div>
                                </a>
                            <? } ?>


                            <a href="<? echo $tableData['Items'][$k]['Link']['S']; ?>" class="ui fade animated button green"
                               tabindex="0">
                                <div class="visible content">
                                    К источнику
                                </div>
                                <div class="hidden content">
                                    <i class="arrow right icon"></i>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            <? } ?>
            <div class="ui inverted borderless attached menu">
                <?
                for($i = 1; $i <= $tableData['Count'] / $limit; $i++){
                ?>
                    <a class="item <? if($i == 1) echo "active"; ?>"><? echo $i;?></a>
                <? } ?>
            </div>
        <? } else { ?>
            <div class="ui container raised segment">
                <h2 class="center aligned ui header">
                    <div class="content">
                        Ошибка 418
                        <div class="sub header">
                            Поисковый запрос не найден
                        </div>
                        <div class="ui image medium">
                            <img src="/error.png">
                        </div>
                    </div>
                </h2>
            </div>
        <?}
    }
    else { ?>
        <div class="ui container raised segment">
            <h2 class="center aligned ui header">
                <div class="content">
                    Ошибка 400
                    <div class="sub header">
                        Повторите свой запрос, возможно, он был очищен или база данных была удалена
                    </div>
                    <div class="ui image medium">
                        <img src="/error.png">
                    </div>
                </div>
            </h2>
        </div>
    <?}
    ?>
</div>



<div class="ui page dimmer" id="loadShow">
    <div class="segment">
        <div class="ui active dimmer">
            <div class="ui massive text loader">Выполняем запрос...</div>
        </div>
    </div>
</div>
</body>
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
</html>
