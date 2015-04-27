<?php
	set_time_limit(0);
	ignore_user_abort(true);
	require_once("/simple_html_dom.php");
	require_once("../includes/config.php");

	/*
		amazon_product_price

		amazon_product_price_id int(10) UN AI PK
		amazon_product_price_price int(10) UN
		amazon_product_price_timestamp timestamp
		amazon_product_asin char(10)
		currency_type_id smallint(5) UN

		10,000,000.00

	*/
	global $DBO;

	$amazon_url = "http://www.amazon.com/dp/";

	$amazon_asin_array = array();

	if($statement = $DBO->prepare("SELECT amazon_product_asin FROM amazon_product"))
	{
		$statement->execute();

		$statement->bind_result($amazon_asin);

		while($statement->fetch())
		{
			array_push($amazon_asin_array, $amazon_asin);
		}	

		$statement->close();
	}
	else
	{
	}

	foreach($amazon_asin_array AS $amazon_asin)
	{
		$count = 0;
		while($count < 5)
		{
			$html = file_get_html($amazon_url . $amazon_asin);
			$count++;

			// <span class="a-size-medium a-color-price">$21.14</span>
			// <span class="a-size-medium a-color-price offer-price a-text-normal">$13.10</span>
			if($html)
			{
				$price = $html->find("span[class=offer-price", 0)->plaintext;
				
				if($price == "")
				{
					$price = $html->find("b[class=priceLarge]", 0)->plaintext;

					if($price == "")
					{
						$price = $html->find("span[class=a-color-price]", 0)->plaintext;

						if($price == "")
						{
						}
					}
				}
				if($price != "")
				{
					$price = extract_numbers($price);
					if($insert_statement = $DBO->prepare("INSERT INTO amazon_product_price (amazon_product_price_price, amazon_product_asin) VALUES (?, ?)"))
					{
						$insert_statement->bind_param("ss", $price, $amazon_asin);

						$insert_statement->execute();

						$insert_statement->close();
					}
				}
				break; // page was loaded, we can successfully break
			}
			else
			{
				usleep(500000);
			}
		}
	}

	function extract_numbers($string)
	{
		return preg_replace("/[^0-9]/", '', $string);
	}