<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'].'/settingAWS.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/aws.php';

/**
 * Connection to DynamoDB
 * Get parameters are taken from settingAWS.php
 */
$db = new DynamoDataBase($access_key, $secret_key, $region);
$_SESSION['keywords'] = $_POST['keywords'];
//$_POST['checkLanguage'] = 1;
//$_POST['parseractive'] = true;
$results = null;

/**
 * If = 0, then search in English and Russian
 * If = 1, then search in only English
 * If = 2, then search in only Russian
 */
if($_POST['checkLanguage'] == 0) {

}
elseif ($_POST['checkLanguage'] == 1) {
    //search the data from the table $tableNameEnglish
    $results = $db->getTableData($tableNameEnglish, $attributes, $_SESSION['keywords'], 'Springer');

    //if no results are found or is there an error and the parser is activated then launch the parser
    if(($results['Count'] == 0 || gettype($results) == "string") && isset($_POST['parseractive'])) {

        /**
         * if the application is running on OS Windows, then enter springerMetaInfo.exe
         * if the application is running on OS Linux, then enter ./springerMetaInfo.elf
         */
        $commandLine = "./springerMetaInfo.elf -accesskey={$access_key} -secretkey={$secret_key} -apikey={$apikeySpringer} -region={$region} -pktype={$pktype} -pkname=\"{$pkname}\" -sktype={$sktype} -skname=\"{$skname}\" -tablename=\"{$tableNameEnglish}\" -keywords=\"{$_SESSION['keywords']}\" -maxpages={$maxpages} -bucketname=\"{$bucketname}\"";

        //launch the command line
        exec($commandLine);
        $results = $db->getTableData($tableNameEnglish, $attributes, $_SESSION['keywords'], 'Springer');
    }
}
elseif ($_POST['checkLanguage'] == 2) {
    //search the data from the table $tableNameRussian
    $results = $db->getTableData($tableNameRussian, $attributes, $_SESSION['keywords'], 'Cyberleninka');
    if(($results['Count'] == 0 || gettype($results) == "string") && isset($_POST['parseractive'])) {
        $commandLine = " ";
        exec($commandLine);
        $results = $db->getTableData($tableNameRussian, $attributes, $_SESSION['keywords'], 'Cyberleninka');
    }

}

//save the language selection to the session
$_SESSION['checkLanguage'] = $_POST['checkLanguage'];

$arr = array(
    'Items' => $results['Items'],
    'Count' => $results['Count'],
    'ScannedCount' => $results['ScannedCount']
);

//save the array data to the session
$_SESSION['resultsSearch'] = $arr;
//print_r($_SESSION['resultsSearch']);
?>