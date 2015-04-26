<?php
	require_once("/simple_html_dom.php");
	
	function extract_numbers($string)
	{
		return preg_replace("/[^0-9]/", '', $string);
	}
	
	$html = file_get_html("http://www.amazon.com/gp/product/B00GXSJ5VI");
	echo extract_numbers($html->find("span[id=actualPriceValue]", 0)->find("b[class=priceLarge]", 0)->plaintext);
?>