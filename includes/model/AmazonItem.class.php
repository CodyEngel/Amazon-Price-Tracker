<?php
//require_once("/includes/model/AmazonUtility.class.php");

class AmazonItem
{
	private $mItemId;
	private $mReturnedXml;
	private $mTrackedPrice;
	
	// Item Details
	
	private $mDetailsPageUrl;
	private $mSalesRank;
	private $mSmallImage;
	private $mMediumImage;
	private $mLargeImage;
	
	private $mTitle;
	private $mDescription;
	private $mListAmount;
	private $mListCurrencyCode;
	private $mListFormattedPrice;
	
	private $mProductGroup;
	private $mProductTypeName;
	private $mPublisher;
	private $mStudio;
	
	private $mOfferAmount;
	private $mOfferCurrencyCode;
	private $mOfferFormattedPrice;
	
	private $mAmountSavedAmount;
	private $mAmountSavedCurrencyCode;
	private $mAmountSavedFormattedPrice;
	private $mAmountSavedPercent;
	
	private $mAvailabilityType;
	private $mAvailabilityMinimumHours;
	private $mAvailabilityMaximumHours;
	
	private $mSuperSaverShippingEligible;
	
	private $mCustomerReviewsIFrame;
	
	private $mSimilarProductIds = array();
	// Item Details
	
	function __construct($item_id, $item_xml = "")
	{
		$this->mItemId = $item_id;
		$this->mReturnedXml = $item_xml;
		
		$this->ParseAmazon();
	}
	
	public function __get($property) {
		switch ($property) {
			case "IsNotNull":
				return $this->mReturnedXml != null;
				break;
			case "Title":
				return $this->mTitle;
				break;
			case "ListAmount":
				return $this->mListFormattedPrice;
				break;
			case "OfferAmount":
				return $this->mOfferFormattedPrice;
				break;
			case "Price":
				if($this->mTrackedPrice) return "$".number_format($this->mTrackedPrice/100, 2);
				if($this->mOfferFormattedPrice) return $this->mOfferFormattedPrice;
				return "N/A";
				break;
			case "Description":
				return $this->mDescription;
				break;
			case "ImageURL":
				if($this->mLargeImage != "") return $this->mLargeImage;
				else if($this->mMediumImage != "") return $this->mMediumImage;
				else if($this->mSmallImage != "") return $this->mSmallImage;
				else return "http://placehold.it/350";
				break;
			case "DetailsURL":
				return $this->mDetailsPageUrl;
				break;

		} // switch
	} // get
	
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
			$statement->bind_param("s", $this->mItemId);

			$statement->execute();

			$statement->bind_result($amazon_product_xml);

			$statement->fetch();

			$this->mReturnedXml = $amazon_product_xml;

			//echo "mReturnedXml: " . $this->mReturnedXml . "<br/>";
			//echo "Query Result: " . $amazon_product_xml . "<br/>";

			//echo "mReturnedXml Length: " . strlen($this->mReturnedXml) . "<br/>";

			$statement->close();

			if($statement = $DBO->prepare("SELECT amazon_product_price_price FROM amazon_product_price WHERE amazon_product_asin = ? ORDER BY amazon_product_price_timestamp DESC LIMIT 1"))
			{
				$statement->bind_param("s", $this->mItemId);

				$statement->execute();

				$statement->bind_result($amazon_product_price);

				$statement->fetch();

				$this->mTrackedPrice = $amazon_product_price;

				$statement->close();
			}
		}
		else
		{
			$this->mReturnedXml = "";
			//echo "statement didn't work";
		}

		if(strlen($this->mReturnedXml) == 0)
		{
			//echo "getting from Amazon API<br/>";
			do
			{
				$this->mReturnedXml = AmazonUtility::ItemLookup($this->mItemId);
				if($this->mReturnedXml == FALSE) usleep(500000); // sleep for half a second
			}
			while($this->mReturnedXml == FALSE);
		}
		
		if($this->mReturnedXml !== FALSE)
		{
			$pxml = simplexml_load_string($this->mReturnedXml);
			if($pxml !== FALSE)
			{
				if($pxml->Items->Item != null)
				{
					$this->mDetailsPageUrl				= $pxml->Items->Item->DetailPageURL;
					$this->mSalesRank					= $pxml->Items->Item->SalesRank;
					$this->mSmallImage					= $pxml->Items->Item->SmallImage->URL;
					$this->mMediumImage					= $pxml->Items->Item->MediumImage->URL;
					$this->mLargeImage					= $pxml->Items->Item->LargeImage->URL;
					
					if($pxml->Items->Item->ItemAttributes != null)
					{
						$this->mTitle						= $pxml->Items->Item->ItemAttributes->Title;
						$this->mListAmount					= $pxml->Items->Item->ItemAttributes->ListPrice->Amount;
						$this->mListCurrencyCode			= $pxml->Items->Item->ItemAttributes->ListPrice->CurrencyCode;
						$this->mListFormattedPrice			= $pxml->Items->Item->ItemAttributes->ListPrice->FormattedPrice;
						
						$this->mProductGroup				= $pxml->Items->Item->ItemAttributes->ProductGroup;
						$this->mProductTypeName				= $pxml->Items->Item->ItemAttributes->ProductTypeName;
						$this->mPublisher					= $pxml->Items->Item->ItemAttributes->Publisher;
						$this->mStudio						= $pxml->Items->Item->ItemAttributes->Studio;
					}
					
					if($pxml->Items->Item->Offers != null && $pxml->Items->Item->Offers->Offer != null)
					{
						$this->mOfferAmount					= $pxml->Items->Item->Offers->Offer->OfferListing->Price->Amount;
						$this->mOfferCurrencyCode 			= $pxml->Items->Item->Offers->Offer->OfferListing->Price->CurrencyCode;
						$this->mOfferFormattedPrice			= $pxml->Items->Item->Offers->Offer->OfferListing->Price->FormattedPrice;
						
						$this->mAmountSavedAmount			= $pxml->Items->Item->Offers->Offer->OfferListing->AmountSaved->Amount;
						$this->mAmountSavedCurrencyCode 	= $pxml->Items->Item->Offers->Offer->OfferListing->AmountSaved->CurrencyCode;
						$this->mAmountSavedFormattedPrice	= $pxml->Items->Item->Offers->Offer->OfferListing->AmountSaved->FormattedPrice;
						$this->mAmountSavedPercent			= $pxml->Items->Item->Offers->Offer->OfferListing->PercentageSaved;
						
						$this->mAvailabilityType			= $pxml->Items->Item->Offers->Offer->OfferListing->AvailabilityAttributes->AvailabilityType;
						$this->mAvailabilityMinimumHours	= $pxml->Items->Item->Offers->Offer->OfferListing->AvailabilityAttributes->MinimumHours;
						$this->mAvailabilityMaximumHours	= $pxml->Items->Item->Offers->Offer->OfferListing->AvailabilityAttributes->MaximumHours;
						
						$this->mSuperSaverShippingEligible	= $pxml->Items->Item->Offers->Offer->OfferListing->IsEligibleForSuperSaverShipping;
					}
					if($pxml->Items->Item->EditorialReviews != null && $pxml->Items->Item->EditorialReviews->EditorialReview != null)
					{
						$this->mDescription = strip_tags($pxml->Items->Item->EditorialReviews->EditorialReview->Content);
					}
					
					$this->mCustomerReviewsIFrame = $pxml->Items->Item->CustomerReviews->IFrameUrl;
					
					foreach($pxml->Items->Item->SimilarProducts->SimilarProduct AS $SimilarProduct)
					{
						array_push($this->mSimilarProductIds, $SimilarProduct->ASIN);
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
			$statement->bind_param("sss", $this->mItemId, $this->mTitle, $this->mReturnedXml);

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