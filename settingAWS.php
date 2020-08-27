<?php
/**
 * @var string $attributes table column names to get
 * @var string $access_key key connect to AWS
 * @var string $secret_key key connect to AWS
 * @var string $region region connect to AWS
 * @var string $tableNameEnglish the name of the English table to connect
 * @var string $tableNameRussian the name of the Russian table to connect
 * @var string $bucketname the name of the storage of PDF documents in S3
 * @var string $pktype partition key type (N/S)
 * @var string $pkname partition key name
 * @var string $sktype sort key type (N/S)
 * @var string $skname sort key name
 * @var integer $maxpages maximum number of search pages for Springer
 */
$attributes = "ID, Title, Abstract, Authors, Publisher, PublicationName, PublicationDate, Link, PDFLink";
$access_key = 'AKIA5CG3AZL6UP7DIT64'; //СТАРЫЙ//AKIA5CG3AZL6UP7DIT64 | //НОВЫЙ//AKIAWD6E4DHGLUOJ3RM2
$secret_key = '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';//СТАРЫЙ//'/lUQplZ/C1Ptamo5yXsvB4E9vzU/S4cUDiq5wA63'; //НОВЫЙ//fKBW4IqhDPkGBGQHxhUqcl+NBepAPA4K2eQ4S+ee
$region = 'us-east-2';
$tableNameEnglish = 'englishDataTable';
$tableNameRussian = 'russianDataTable';
$bucketname = 'myuniquebucketname3287';
$pktype = 'S';
$pkname = 'Publisher';
$sktype = 'N';
$skname = 'ID';
$maxpages = 5;
$limit = 10; //not used yet
?>
