<?php
require_once("/includes/model/AmazonUtility.class.php");

class AmazonItem
{
	private $mItemId;
	private $mReturnedXml;
	
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
				return $this->mOfferFormattedPrice != null ? $this->mOfferFormattedPrice : "DIGITAL";
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
		do
		{
			$this->mReturnedXml = AmazonUtility::ItemLookup($this->mItemId);
			if($this->mReturnedXml == FALSE) usleep(500000); // sleep for half a second
		}
		while($this->mReturnedXml == FALSE);
		
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
	}
	/* Item Setup*/
}

?>