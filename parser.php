<?php
session_start();
require_once 'settingAWS.php';
require_once 'aws.php';
//echo "<pre>";
$db = new DynamoDataBase($access_key, $secret_key, $region);
$_SESSION['keywords'] = $_POST['keywords'];

//$_POST['checkLanguage'] = 1;

$results = null;

if($_POST['checkLanguage'] == 0) {
    //На любом языке
}
elseif ($_POST['checkLanguage'] == 1) {
    //На английском языке
    $results = $db->getTableData($tableNameEnglish, $attributes, $_SESSION['keywords'], 'Springer');
    print_r($results);
    if(($results['Count'] == 0 || gettype($results) == "string") && isset($_POST['parseractive'])) {
        $commandLine = "springerMetaInfo.exe -accesskey={$access_key} -secretkey={$secret_key} -region={$region} -pktype={$pktype} -pkname=\"{$pkname}\" -sktype={$sktype} -skname=\"{$skname}\" -tablename=\"{$tableNameEnglish}\" -keywords=\"{$_SESSION['keywords']}\" -maxpages={$maxpages} -bucketname=\"{$bucketname}\"";

        exec($commandLine);
        $results = $db->getTableData($tableNameEnglish, $attributes, $_SESSION['keywords'], 'Springer');
    }
}
elseif ($_POST['checkLanguage'] == 2) {
    //На русском языке
    $results = $db->getTableData($tableNameRussian, $attributes, $_SESSION['keywords'], 'Cyberleninka');
    if(($results['Count'] == 0 || gettype($results) == "string") && isset($_POST['parseractive'])) {
        $commandLine = " ";
        exec($commandLine);
        $results = $db->getTableData($tableNameRussian, $attributes, $_SESSION['keywords'], 'Cyberleninka');
    }

}
$_SESSION['checkLanguage'] = $_POST['checkLanguage'];



$arr = array(
    'Items' => $results['Items'],
    'Count' => $results['Count'],
    'ScannedCount' => $results['ScannedCount']
);
$_SESSION['resultsSearch'] = $arr;
?>