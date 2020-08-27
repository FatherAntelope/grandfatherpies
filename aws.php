<?php
require $_SERVER['DOCUMENT_ROOT'].'/path/aws.phar';

use Aws\DynamoDb\Exception\DynamoDbException;
use Aws\DynamoDb\Marshaler;
use Aws\DynamoDb\DynamoDbClient;

//echo "<pre>";

function getArrayWords($keywords)
{
    $arr = array();

    $blackWords = ['оn', 'in', 'at', 'over', 'under', 'with', 'my', 'to', 'from', 'into', 'out', 'of', 'off', 'by', 'till', 'for', 'and', 'but', 'or', 'if', 'so', 'the', 'as',
        'а', 'без', 'в', 'до', 'для', 'за', 'и', 'из', 'к', 'на', 'над', 'но','о', 'об', 'от', 'по', 'под', 'пред', 'при', 'про', 'с', 'у', 'через'];


    $pieces = explode(" ", $keywords);

    $piecesWords = array_diff($pieces, $blackWords);

    foreach ($piecesWords as $word)
    {
        $arr[] = " ".mb_strtolower($word)." ";
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
        $mp = array(':Publisher'=> $valueKeyCondition);

        foreach($arrWords as $key=>$value) {
            $substr = ":Word".strval($key);
            $mp[$substr]=$value;
            $str.="contains(Keywords, ".$substr.") or ";
        }


        $str = substr($str,0,-4);
        $marshaler = new Marshaler();
        $eav = $marshaler->marshalItem($mp);

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
$string = 'in data';

$limit = 10;
require_once 'settingAWS.php';
$db = new DynamoDataBase($access_key, $secret_key, $region);
$attributes = "ID, SerialNumber, Title, Keywords";

$tableData = $db->getTableData($tableNameEnglish, $attributes, $string, 'Springer');

print_r($tableData);
*/
?>
