<?php

class AmazonPrice
{
	public $ASIN;
	public $Price;
	public $Timestamp;
	
	function __construct($asin, $price, $timestamp)
	{
		$this->ASIN 		= $asin;
		$this->Price 		= $price;
		$this->Timestamp 	= $timestamp;
	}

	public function __get($property) {
		switch ($property) {
			case "ASIN":
				return $this->ASIN;
				break;
			case "Price":
				return number_format($this->Price/100, 2);
				break;
			case "Timestamp":
				return $this->Timestamp;
				break;
		}
	}

}