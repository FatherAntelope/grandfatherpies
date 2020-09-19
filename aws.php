<?php
require $_SERVER['DOCUMENT_ROOT'].'/path/aws.phar';

//AWS package initialization
use Aws\DynamoDb\Exception\DynamoDbException;
use Aws\DynamoDb\Marshaler;
use Aws\DynamoDb\DynamoDbClient;

/**
 * @param string $keywords words from the search string
 * @return array returns pieces of words in lowercase for search
 **/
function getArrayWords($keywords) {
    $arr = array();

    $blackWords = ['оn', 'in', 'at', 'over', 'under', 'with', 'my', 'to', 'from', 'into', 'out', 'of', 'off', 'by', 'till', 'for', 'and', 'but', 'or', 'if', 'so', 'the', 'as',
        'а', 'без', 'в', 'до', 'для', 'за', 'и', 'из', 'к', 'на', 'над', 'но','о', 'об', 'от', 'по', 'под', 'пред', 'при', 'про', 'с', 'у', 'через'];

    /**
     * @var array store the array words of $keywords
     */
    $pieces = explode(" ", $keywords);
    $piecesWords = array_diff($pieces, $blackWords);

    foreach ($piecesWords as $word) {
        $arr[] = " ".mb_strtolower($word)." ";
    }
    return $arr;
}

/**
 * Class DynamoDataBase
 * connection and data find in the DynamoDB
 */
class DynamoDataBase {
    protected $client;

    /**
     * DynamoDataBase constructor.
     * Connection to DynamoDB
     * @param string $access_key  for connection to DDB
     * @param string $secret_key for connection to DDB
     * @param string $region for connection to DDB
     */
    public function __construct($access_key, $secret_key, $region) {
        $this->client = DynamoDbClient::factory(array(
            'credentials' => array(
                'key' => $access_key,
                'secret' => $secret_key
            ),
            'region' => $region,
            'version' => 'latest'
        ));
    }


    /**
     * @param string $tableName table name to connection
     * @param string $projectionExpression a string of column names separated by commas to get
     * @param string $keywords search words
     * @param string $valueKeyCondition table key by which data will be received
     * @return \Aws\Result|string
     */
    public function getTableData($tableName, $projectionExpression, $keywords, $valueKeyCondition) {
        /**
         * @var array an array of words to search in the DynamoDB table
         */
        $arrWords = getArrayWords($keywords);

        if(count($arrWords) == 0)
            return "error";

        $str = '';

        /**
         * @var array keys for ExpressionAttributeValues
         * @param string $valueKeyCondition Springer or Cyberleninka
         */
        $mp = array(':ValueKeyCondition'=> $valueKeyCondition);

        //fills in keys for ExpressionAttributeValues and a string to filter
        foreach($arrWords as $key=>$value) {
            $substr = ":Word".strval($key);
            $mp[$substr]=$value;
            $str.="contains(Keywords, ".$substr.") or ";
        }

        //cuts " or "
        $str = substr($str,0,-4);

        $marshaler = new Marshaler();

        //converts keys to Json
        $eav = $marshaler->marshalItem($mp);

        //params for query
        $params = [
            'TableName' => $tableName,
            'ProjectionExpression' => $projectionExpression,
            'KeyConditionExpression' => 'Publisher = :ValueKeyCondition',
            'FilterExpression' => $str,
            'ExpressionAttributeValues'=> $eav
        ];


        /**
         * if $params is built incorrectly, it will return an error
         */
        try {
            return $this->client->query($params);
        } catch (DynamoDbException $e) {
            return $e->getMessage();
        }
    }
}
/*
require_once $_SERVER['DOCUMENT_ROOT'].'/settingAWS.php';
$db = new DynamoDataBase($access_key, $secret_key, $region);
$keywords = "Big data";
$tableData = $db->getTableData($tableNameEnglish, $attributes, $keywords, '0');

echo "<pre>";
print_r($tableData);
*/
?>
