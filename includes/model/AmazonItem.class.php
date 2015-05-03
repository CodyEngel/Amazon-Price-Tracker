<?php

class AmazonItem
{
	private $ReturnedXml;

	private $ItemId;
	private $TrackedPrice;
	
	// Item Details
	
	private $DetailsPageUrl;
	private $SalesRank;
	private $SmallImage;
	private $MediumImage;
	private $LargeImage;
	
	private $Title;
	private $Description;
	private $ListAmount;
	private $ListCurrencyCode;
	private $ListFormattedPrice;
	
	private $ProductGroup;
	private $ProductTypeName;
	private $Publisher;
	private $Studio;
	
	private $OfferAmount;
	private $OfferCurrencyCode;
	private $OfferFormattedPrice;
	
	private $AmountSavedAmount;
	private $AmountSavedCurrencyCode;
	private $AmountSavedFormattedPrice;
	private $AmountSavedPercent;
	
	private $AvailabilityType;
	private $AvailabilityMinimumHours;
	private $AvailabilityMaximumHours;
	
	private $SuperSaverShippingEligible;
	
	private $CustomerReviewsIFrame;	
	
	private $SimilarProductIds = array();
	// Item Details

	public $ASIN;
	public $ProductTitle;
	public $Price;
	public $FormattedPrice;
	public $ProductDescription;
	public $ImageURL;
	
	function __construct($item_id, $item_xml = "")
	{
		$this->ItemId = $item_id;
		$this->ReturnedXml = $item_xml;
		
		$this->ParseAmazon();

		// strval ensures this is encapsulated as "parameters":value
		$this->ASIN 				= strval($this->ItemId);
		$this->ProductTitle 		= strval($this->Title);
		$this->Price 				= strval($this->SetCurrentPrice());
		$this->FormattedPrice 		= strval($this->SetCurrentPriceFormatted());
		$this->ProductDescription 	= strval($this->Description);
		$this->ImageURL 			= strval($this->SetImageURL());
	}
	
	public function __get($property) {
		switch ($property) {
			case "ASIN":
				return $this->ItemId;
				break;
			case "IsNotNull":
				return $this->ReturnedXml != null;
				break;
			case "Title":
				return $this->Title;
				break;
			case "ListAmount":
				return $this->ListFormattedPrice;
				break;
			case "OfferAmount":
				return $this->OfferFormattedPrice;
				break;
			case "Price":
				if($this->TrackedPrice) return "$".number_format($this->TrackedPrice/100, 2);
				if($this->OfferFormattedPrice) return $this->OfferFormattedPrice;
				return "N/A";
				break;
			case "Description":
				return $this->Description;
				break;
			case "ImageURL":
				if($this->LargeImage != "") return $this->LargeImage;
				else if($this->MediumImage != "") return $this->MediumImage;
				else if($this->SmallImage != "") return $this->SmallImage;
				else return "http://placehold.it/350";
				break;
			case "DetailsURL":
				return $this->DetailsPageUrl;
				break;

		} // switch
	} // get

	private function SetCurrentPrice()
	{
		if($this->TrackedPrice) return $this->TrackedPrice;
		if($this->OfferAmount) return $this->OfferAmount;
		return -1;
	}

	private function SetCurrentPriceFormatted()
	{
		if($this->TrackedPrice) return "$".number_format($this->TrackedPrice/100, 2);
		if($this->OfferFormattedPrice) return $this->OfferFormattedPrice;
		return "N/A";
	}

	private function SetImageURL()
	{
		if($this->LargeImage != "") return $this->LargeImage;
		else if($this->MediumImage != "") return $this->MediumImage;
		else if($this->SmallImage != "") return $this->SmallImage;
		else return "http://placehold.it/350";
	}
	
	/* Item Setup */
	private function ParseAmazon()
	{
		global $DBO;
		/*
			check if item exists in database amazon_product table

			amazon_product_asin		char(10) PK
			amazon_product_title	varchar(1000)
			amazon_product_xml 		mediumtext
			product_category_id 	int(10) UN

			create a prepared statement
			if ($stmt = $mysqli->prepare("SELECT District FROM City WHERE Name=?")) {

			    bind parameters for markers
			    $stmt->bind_param("s", $city);

			    execute query
			    $stmt->execute();

			    bind result variables
			    $stmt->bind_result($district);

			    fetch value
			    $stmt->fetch();

			    printf("%s is in district %s\n", $city, $district);

			    close statement
			    $stmt->close();
			}
		*/

		if($statement = $DBO->prepare("SELECT amazon_product_xml FROM amazon_product WHERE amazon_product_asin =?"))
		{
			$statement->bind_param("s", $this->ItemId);

			$statement->execute();

			$statement->bind_result($amazon_product_xml);

			$statement->fetch();

			$this->ReturnedXml = $amazon_product_xml;

			//echo "mReturnedXml: " . $this->ReturnedXml . "<br/>";
			//echo "Query Result: " . $amazon_product_xml . "<br/>";

			//echo "mReturnedXml Length: " . strlen($this->ReturnedXml) . "<br/>";

			$statement->close();

			if($statement = $DBO->prepare("SELECT amazon_product_price_price FROM amazon_product_price WHERE amazon_product_asin = ? ORDER BY amazon_product_price_timestamp DESC LIMIT 1"))
			{
				$statement->bind_param("s", $this->ItemId);

				$statement->execute();

				$statement->bind_result($amazon_product_price);

				$statement->fetch();

				$this->TrackedPrice = $amazon_product_price;

				$statement->close();
			}
		}
		else
		{
			$this->ReturnedXml = "";
			//echo "statement didn't work";
		}

		if(strlen($this->ReturnedXml) == 0)
		{
			//echo "getting from Amazon API<br/>";
			do
			{
				$this->ReturnedXml = AmazonUtility::ItemLookup($this->ItemId);
				if($this->ReturnedXml == FALSE) usleep(500000); // sleep for half a second
			}
			while($this->ReturnedXml == FALSE);
		}
		
		if($this->ReturnedXml !== FALSE)
		{
			$pxml = simplexml_load_string($this->ReturnedXml);
			if($pxml !== FALSE)
			{
				if($pxml->Items->Item != null)
				{
					$this->DetailsPageUrl				= $pxml->Items->Item->DetailPageURL;
					$this->SalesRank					= $pxml->Items->Item->SalesRank;
					$this->SmallImage					= $pxml->Items->Item->SmallImage->URL;
					$this->MediumImage					= $pxml->Items->Item->MediumImage->URL;
					$this->LargeImage					= $pxml->Items->Item->LargeImage->URL;
					
					if($pxml->Items->Item->ItemAttributes != null)
					{
						$this->Title						= $pxml->Items->Item->ItemAttributes->Title;
						$this->ListAmount					= $pxml->Items->Item->ItemAttributes->ListPrice->Amount;
						$this->ListCurrencyCode				= $pxml->Items->Item->ItemAttributes->ListPrice->CurrencyCode;
						$this->ListFormattedPrice			= $pxml->Items->Item->ItemAttributes->ListPrice->FormattedPrice;
						
						$this->ProductGroup					= $pxml->Items->Item->ItemAttributes->ProductGroup;
						$this->ProductTypeName				= $pxml->Items->Item->ItemAttributes->ProductTypeName;
						$this->Publisher					= $pxml->Items->Item->ItemAttributes->Publisher;
						$this->Studio						= $pxml->Items->Item->ItemAttributes->Studio;
					}
					
					if($pxml->Items->Item->Offers != null && $pxml->Items->Item->Offers->Offer != null)
					{
						$this->OfferAmount					= $pxml->Items->Item->Offers->Offer->OfferListing->Price->Amount;
						$this->OfferCurrencyCode 			= $pxml->Items->Item->Offers->Offer->OfferListing->Price->CurrencyCode;
						$this->OfferFormattedPrice			= $pxml->Items->Item->Offers->Offer->OfferListing->Price->FormattedPrice;
						
						$this->AmountSavedAmount			= $pxml->Items->Item->Offers->Offer->OfferListing->AmountSaved->Amount;
						$this->AmountSavedCurrencyCode 		= $pxml->Items->Item->Offers->Offer->OfferListing->AmountSaved->CurrencyCode;
						$this->AmountSavedFormattedPrice	= $pxml->Items->Item->Offers->Offer->OfferListing->AmountSaved->FormattedPrice;
						$this->AmountSavedPercent			= $pxml->Items->Item->Offers->Offer->OfferListing->PercentageSaved;
						
						$this->AvailabilityType			= $pxml->Items->Item->Offers->Offer->OfferListing->AvailabilityAttributes->AvailabilityType;
						$this->AvailabilityMinimumHours	= $pxml->Items->Item->Offers->Offer->OfferListing->AvailabilityAttributes->MinimumHours;
						$this->AvailabilityMaximumHours	= $pxml->Items->Item->Offers->Offer->OfferListing->AvailabilityAttributes->MaximumHours;
						
						$this->SuperSaverShippingEligible	= $pxml->Items->Item->Offers->Offer->OfferListing->IsEligibleForSuperSaverShipping;
					}
					if($pxml->Items->Item->EditorialReviews != null && $pxml->Items->Item->EditorialReviews->EditorialReview != null)
					{
						$this->Description = preg_replace('#<br\s*/?>#i', "\n",
												strip_tags($pxml->Items->Item->EditorialReviews->EditorialReview->Content, "<br><br/>")
											);
					}
					
					$this->CustomerReviewsIFrame = $pxml->Items->Item->CustomerReviews->IFrameUrl;
					
					if($pxml->Items->Item->SimilarProducts)
					{
						foreach($pxml->Items->Item->SimilarProducts->SimilarProduct AS $SimilarProduct)
						{
							array_push($this->SimilarProductIds, $SimilarProduct->ASIN);
						}
					}
				}
			}
		}

		/*
			insert item into database amazon_product table

			amazon_product_asin		char(10) PK
			amazon_product_title	varchar(1000)
			amazon_product_xml 		mediumtext
			product_category_id 	int(10) UN

			// prepare and bind
			$stmt = $conn->prepare("INSERT INTO MyGuests (firstname, lastname, email) VALUES (?, ?, ?)");
			$stmt->bind_param("sss", $firstname, $lastname, $email);

			// set parameters and execute
			$firstname = "John";
			$lastname = "Doe";
			$email = "john@example.com";
			$stmt->execute();
		*/
		if($statement = $DBO->prepare("INSERT IGNORE INTO amazon_product (amazon_product_asin, amazon_product_title, amazon_product_xml) VALUES (?, ?, ?)"))
		{
			$statement->bind_param("sss", $this->ItemId, $this->Title, $this->ReturnedXml);

			$statement->execute();

			$statement->close();
		}
		else
		{
			//echo "insert statement failed";
		}
	}
	/* Item Setup*/
}

?>