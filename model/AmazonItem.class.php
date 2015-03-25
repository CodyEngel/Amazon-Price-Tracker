<?php
require_once("/model/AmazonUtility.class.php");

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
	
	function __construct($item_id)
	{
		$this->mItemId = $item_id;
		
		$this->ParseAmazon($item_id);
	}
	
	public function __get($property) {
		switch ($property) {
			case "id":
				return $this->user_id;
				break;
			case "name":
				return $this->user_name;
				break;
		} // switch
	} // get
	
	/* Item Setup */
	private function ParseAmazon()
	{
		$this->mReturnedXml = AmazonUtility::ItemLookup($this->mItemId);
		
		if($this->mReturnedXml === FALSE) {
			echo "Request failed :(";
		}
		else {
			$pxml = simplexml_load_string($this->mReturnedXml);
			if($pxml === FALSE) {
				echo "Response could not be parsed :(";
			}
			else {
				$this->mDetailsPageUrl				= $pxml->Items->Item->DetailPageUrl;
				$this->mSalesRank					= $pxml->Items->Item->SalesRank;
				$this->mSmallImage					= $pxml->Items->Item->SmallImage->Url;
				$this->mMediumImage					= $pxml->Items->Item->MediumImage->Url;
				$this->mLargeImage					= $pxml->Items->Item->LargeImage->Url;
				
				$this->mTitle						= $pxml->Items->Item->ItemAttributes->Title;
				$this->mListAmount					= $pxml->Items->Item->ItemAttributes->ListPrice->Amount;
				$this->mListCurrencyCode			= $pxml->Items->Item->ItemAttributes->ListPrice->CurrencyCode;
				$this->mListFormattedPrice			= $pxml->Items->Item->ItemAttributes->ListPrice->FormattedPrice;
				
				$this->mProductGroup				= $pxml->Items->Item->ItemAttributes->ProductGroup;
				$this->mProductTypeName				= $pxml->Items->Item->ItemAttributes->ProductTypeName;
				$this->mPublisher					= $pxml->Items->Item->ItemAttributes->Publisher;
				$this->mStudio						= $pxml->Items->Item->ItemAttributes->Studio;
				
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
				
				$this->mCustomerReviewsIFrame		= $pxml->Items->Item->CustomerReviews->IFrameUrl;
				
				foreach($pxml->Items->Item->SimilarProducts->SimilarProduct AS $SimilarProduct)
				{
					array_push($this->mSimilarProductIds, $SimilarProduct->ASIN);
				}
			}
		}
	}
	/* Item Setup*/
}

?>