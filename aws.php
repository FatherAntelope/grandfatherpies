<?php
require 'path/aws.phar';

use Aws\DynamoDb\Exception\DynamoDbException;
use Aws\DynamoDb\Marshaler;
use Aws\DynamoDb\DynamoDbClient;



function getArrayWords($keywords)
{
    $blackWords1 = ['оn', 'On', 'in', 'In', 'at', 'At', 'over', 'Over', 'under', 'Under', 'to', 'To', 'from', 'From'
        , 'into', 'Into', 'out', 'Out', 'of', 'Of', 'off', 'Off', 'by', 'By', 'till', 'Till', 'for', 'and', 'And', 'but', 'But', 'or', 'Or', 'if', 'If',
        'so', 'So', 'as', 'As', 'а', 'А', 'без','Без', 'в', 'В', 'до', 'До',
        'для', 'Для', 'за', 'За', 'и', 'И', 'из', 'Из', 'к', 'К', 'на', 'На', 'над', 'Над', 'но', 'Но', 'о', 'О', 'об','Об', 'от', 'От',
        'по', 'По', 'под', 'Под', 'пред', 'Пред', 'при', 'При', 'про', 'Про', 'с', 'С', 'у', 'У', 'через', 'Через'];

    $blackWords = ['оn', 'in', 'at', 'over', 'under', 'with', 'my', 'to', 'from', 'into', 'out', 'of', 'off', 'by', 'till', 'for', 'and', 'but', 'or', 'if', 'so', 'the', 'as',
        'а', 'без', 'в', 'до', 'для', 'за', 'и', 'из', 'к', 'на', 'над', 'но','о', 'об', 'от', 'по', 'под', 'пред', 'при', 'про', 'с', 'у', 'через'];

    $arr = array();
    $pieces = explode(" ", $keywords);

    $piecesWords = array_diff($pieces, $blackWords);

    foreach ($piecesWords as $word)
    {
        $arr[] = mb_strtoupper($word);
        $arr[] = mb_convert_case($word, MB_CASE_TITLE, "UTF-8");
        $arr[] = mb_strtolower($word);
    }

    return $arr;
}



class DynamoDataBase
{
    protected $client;
    public function __construct($access_key, $secret_key, $region)
    {
        $this->client = DynamoDbClient::factory(array(
            'credentials' => array(
                'key' => $access_key,
                'secret' => $secret_key
            ),
            'region' => $region,
            'version' => 'latest'
        ));
    }



    public function getTableData($tableName, $projectionExpression, $keywords, $valueKeyCondition)
    {
        $arrWords = getArrayWords($keywords);
        if(count($arrWords) == 0)
            return "error";
        $str = '';

        //Cyberleninka or Springer
        $mp = array(':Publisher'=> $valueKeyCondition); //подготавливаю почву для ExpressionAttributeValues)

        foreach($arrWords as $key=>$value){ //тут я очень всрато генерирую исходный ассоц.массив для джейсона для ExpressionAttributeValues и строчку для FilterExpression
            $substr = ":Word".strval($key);
            $mp[$substr]=$value;
            $str.="contains(Abstract, ".$substr.") or contains(Title, ".$substr.") or "; //не судите строго я второй день в php
        }


        $str = substr($str,0,-4); //отсекаю последний " or "
        $marshaler = new Marshaler();
        $eav = $marshaler->marshalItem($mp); //преобразовываю исходный ассоц. массив в джейсон

        $params = [
            'TableName' => $tableName,
            'ProjectionExpression' => $projectionExpression,
            'KeyConditionExpression' => 'Publisher = :Publisher',
            'FilterExpression' => $str,
            'ExpressionAttributeValues'=> $eav
        ];


        try {
            return $this->client->query($params);
        } catch (DynamoDbException $e) {
            return $e->getMessage();
        }
    }
}

/*
$string = 'Data base';

$limit = 10;
require_once 'settingAWS.php';
$db = new DynamoDataBase($access_key, $secret_key, $region);
$attributes = "ID, SerialNumber, Title, Keywords";

$tableData = $db->getTableData($tableNameEnglish, $attributes, $string, 'Springer');

print_r($tableData);
*/
?>
