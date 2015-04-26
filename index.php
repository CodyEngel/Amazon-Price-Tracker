<?php
require_once("/includes/model/AmazonUtility.class.php");
require_once("/includes/model/AmazonItem.class.php");
require_once("/includes/model/AmazonSearch.class.php");

$keyword = "";
if(isset($_GET["keyword"]))
{
	$keyword = $_GET["keyword"];
	$keyword = str_replace("+", " ", $keyword);
	$amazon_search = new AmazonSearch($keyword);
	//echo "Total Results: " . $amazon_search->TotalResults . "<br/>";
}
$request = AmazonUtility::AWSSignedRequest('com', array(
			'Operation' => 'ItemLookup',
			'ItemId' => "0385347405",
			'ResponseGroup' => 'Large'
			));
echo "<!-- Amazon XML: ".$request."-->";
/*
	Test Item IDs

	1479274836
	B00KI03U8Q


// API Operations http://docs.aws.amazon.com/AWSECommerceService/latest/DG/CHAP_OperationListAlphabetical.html
// API Request Limits - https://affiliate-program.amazon.com/gp/advertising/api/detail/faq.html

$request = AmazonUtility::AWSSignedRequest('com', array(
			'Operation' => 'ItemLookup',
			'ItemId' => "0385347405",
			'ResponseGroup' => 'Large'
			));

//$Item1 = new AmazonItem(1479274836);
//$Item2 = new AmazonItem("B00KI03U8Q");
$Item3 = new AmazonItem("B00GXSJ5VI");
print_r($Item3);

$Search1 = new AmazonSearch("steve jobs");

var_dump($Search1->SearchResultIds);

foreach($Search1->SearchResultItems AS $SearchItem)
{
	var_dump($SearchItem);
}
*/
require_once("/libs/Smarty.class.php");

$smarty = new Smarty;

//$smarty->force_compile = true;
//$smarty->debugging = true;
//$smarty->caching = true;
//$smarty->cache_lifetime = 120;
$smarty->assign("title", "");
$smarty->assign("amazon_search", $amazon_search);
$smarty->assign("search_keyword", $keyword);

$smarty->display('index.tpl');