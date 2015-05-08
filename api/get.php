<?php
require_once("../includes/config.php");

switch($_GET["type"])
{
	case "search":
		echo SearchByKeyword($_GET["keyword"], $_GET["searchIndex"]);
		break;
	case "product":
		echo GetProductWithid($_GET["id"]);
		break;
	case "productPriceHistory":
		echo GetProductPriceHistoryWithId($_GET["id"]);
		break;
	default:
		echo "denied";
}

/** Functions **/
function SearchByKeyword($keyword, $searchIndex)
{
	global $DBO;

	if($statement = $DBO ->prepare("INSERT INTO amazon_search_log (amazon_search_log_keyword, amazon_search_log_search_index) VALUES (?, ?)"))
	{
		$statement->bind_param("ss", $keyword, $searchIndex);

		$statement->execute();

		$statement->close();
	}

	$amazonSearch = new AmazonSearch($keyword, $searchIndex);
	return json_encode(array("data" => $amazonSearch->SearchResultItems));
}

function GetProductWithId($id)
{
	return json_encode(array("data" => new AmazonItem($id)));
}

function GetProductPriceHistoryWithId($id)
{
	global $DBO;

	$amazon_product_price_history = array();

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

	return json_encode(array("data" => $amazon_product_price_history));
}