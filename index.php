<?php
require_once("/model/AmazonUtility.class.php");
require_once("/model/AmazonItem.class.php");
/*
	Test Item IDs

	1479274836
	B00KI03U8Q
*/

// API Operations http://docs.aws.amazon.com/AWSECommerceService/latest/DG/CHAP_OperationListAlphabetical.html

$Item1 = new AmazonItem(1479274836);
$Item2 = new AmazonItem("B00KI03U8Q");

var_dump($Item1);
var_dump($Item2);

?>