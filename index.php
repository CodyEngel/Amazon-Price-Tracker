<?php
require_once("/model/AmazonUtility.class.php");
require_once("/model/AmazonItem.class.php");
require_once("/model/AmazonSearch.class.php");
/*
	Test Item IDs

	1479274836
	B00KI03U8Q
*/

// API Operations http://docs.aws.amazon.com/AWSECommerceService/latest/DG/CHAP_OperationListAlphabetical.html
// API Request Limits - https://affiliate-program.amazon.com/gp/advertising/api/detail/faq.html

$search_request = AmazonUtility::AWSSignedRequest(
		'com', array(
		'Operation' => "ItemSearch",
		'Keywords' => "iphone 6",
		'SearchIndex' => "All",
		'ResponseGroup' => "ItemIds",
		'ItemPage' => "1"
		));
		
echo "<a href='".$search_request."'>Click Here!</a>";

//$Item1 = new AmazonItem(1479274836);
//$Item2 = new AmazonItem("B00KI03U8Q");
$Item3 = new AmazonItem("B00GXSJ5VI");
var_dump($Item3);
/*
$Search1 = new AmazonSearch("steve jobs");

var_dump($Search1->SearchResultIds);

foreach($Search1->SearchResultItems AS $SearchItem)
{
	var_dump($SearchItem);
}
*/
?>