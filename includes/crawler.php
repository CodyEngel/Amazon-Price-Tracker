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

					$wishListItemIds = array();

					// check if anyone is watching this item where the price is at or below the extracted price
					// TODO set is active to false after sending out emails
					if($statement = $DBO->prepare("SELECT person_wish_list_item_id, email_address, amazon_product_title
													FROM person_wish_list_item AS pwli
													LEFT JOIN email AS e
														ON e.person_id = pwli.person_id
													LEFT JOIN amazon_product AS ap
														ON ap.amazon_product_asin = pwli.amazon_product_asin
													WHERE person_wish_list_item_desired_price >= ?
													AND pwli.amazon_product_asin = ?
													AND pwli.person_wish_list_item_is_tracking = 1"))
					{
						$statement->bind_param("ss", $price, $amazon_asin);

						$statement->execute();

						$statement->bind_result($personWishListItemId, $resultEmailAddress, $resultAmazonProductTitle);

						while($statement->fetch())
						{
							array_push($wishListItemIds, $personWishListItemId);
							// email
							smtpmailer($resultEmailAddress, "unmarkettest@gmail.com", "Unmarket", $resultAmazonProductTitle . " IS ON SALE!", "Thank you for using UnMarket.net, we wanted to let you know your patience has paid off! It seems like only yesterday you were searching for " .
																									$resultAmazonProductTitle . " and wanted to buy it, but didn't like the price. Well we've got great news for you. " .
																									$resultAmazonProductTitle . " is on sale right now for $" . ($price/100) . ". We strongly recommend purchasing this item as soon as possible as Amazon may raise the price back up.
																									You can purchase the product from the following link: http://www.amazon.com/dp/" . $amazon_asin . "/?tag=unmarket-20" );
						}

						$statement->close();
					}

					if($update_statement = $DBO->prepare("UPDATE person_wish_list_item SET person_wish_list_item_is_tracking = b'0' WHERE person_wish_list_item_id IN (?)"))
					{
						$update_statement->bind_param("s", implode (", ", $wishListItemIds));

						$update_statement->execute();

						$update_statement->close();
					}

					// insert into db
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

	function smtpmailer($to, $from, $from_name, $subject, $body)
	{ 
		GLOBAL $gmail_username;
		GLOBAL $gmail_password;

		$mail = new PHPMailer();  // create a new object
		$mail->IsSMTP(); // enable SMTP
		$mail->SMTPDebug = 0;  // debugging: 1 = errors and messages, 2 = messages only
		$mail->SMTPAuth = true;  // authentication enabled
		$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
		$mail->Host = 'smtp.gmail.com';
		$mail->Port = 465; 
		$mail->Username = $gmail_username;  
		$mail->Password = $gmail_password;           
		$mail->SetFrom($from, $from_name);
		$mail->Subject = $subject;
		$mail->Body = $body;
		$mail->AddAddress($to);
		if(!$mail->Send()) {
			$error = 'Mail error: '.$mail->ErrorInfo;
			echo $error; 
			return false;
		} else {
			$error = 'Message sent!';
			echo $error;
			return true;
		}
	}