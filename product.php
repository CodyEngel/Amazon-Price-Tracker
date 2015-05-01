<?php

require_once("/includes/config.php");
require_once("/libs/Smarty.class.php");

$smarty = new Smarty;

$id = "";
if(isset($_GET["id"]))
{
	$id = $_GET["id"];
	$amazon_product = new AmazonItem($id);
	

	$amazon_product_price_history = array();

	// GET PRICE HISTORY
	if($statement = $DBO->prepare("SELECT amazon_product_asin, amazon_product_price_price, amazon_product_price_timestamp FROM amazon_product_price WHERE amazon_product_asin = ? ORDER BY amazon_product_price_id"))
	{
		$statement->bind_param("s", $id);

		$statement->execute();

		$statement->bind_result($amazon_product_asin, $amazon_product_price, $amazon_product_price_timestamp);

		while($statement->fetch())
		{
			$amazon_price = new AmazonPrice($amazon_product_asin, $amazon_product_price, $amazon_product_price_timestamp);
			array_push($amazon_product_price_history, $amazon_price);
		}

		$statement->close();
	}

	$smarty->assign("amazon_product", $amazon_product);
	$smarty->assign("amazon_product_price_history", $amazon_product_price_history);
}

$smarty->display('product.tpl');
