<?php
require_once("/includes/model/AmazonUtility.class.php");
require_once("/includes/model/AmazonItem.class.php");

class AmazonSearch
{
	private $mKeyword;
	private $mSearchIndex;
	private $mItemPage;
	
	private $mReturnedXml;
	
	private $mSearchResultIds = array();
	private $mSearchResultItems = array();
	
	function __construct($keyword, $searchIndex = "All")
	{
		$this->mKeyword 					= $keyword;
		$this->mSearchIndex 				= $searchIndex;
		$this->mItemPage 					= 1;
		$this->mSearchResultItemsRetrieved	= false;
		
		$this->ParseSearch();
	}
	
	public function __get($property) {
		switch ($property) {
			case "SearchResultIds":
				return $this->mSearchResultIds;
				break;
			case "SearchResultItems":
				return $this->mSearchResultItems;
				break;
			case "TotalResults":
				return count($this->mSearchResultItems);
				break;
		} // switch
	} // get
	
	private function ParseSearch()
	{
		$this->mReturnedXml = AmazonUtility::ItemSearch($this->mKeyword, $this->mSearchIndex, $this->mItemPage);
		
		if($this->mReturnedXml !== FALSE)
		{
			$pxml = simplexml_load_string($this->mReturnedXml);
			if($pxml !== FALSE)
			{
				foreach($pxml->Items->Item AS $item)
				{
					$ASIN = $item->ASIN;
					array_push($this->mSearchResultIds, $ASIN);
					array_push($this->mSearchResultItems, new AmazonItem($ASIN));
				}
			}
		}
	}
}

?>