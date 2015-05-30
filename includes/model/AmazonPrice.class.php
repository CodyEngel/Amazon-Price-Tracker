<?php

class AmazonPrice
{
	public $ASIN;
	public $Price;
	public $Timestamp;
	
	function __construct($asin, $price, $timestamp)
	{
		$this->ASIN 		= $asin;
		$this->Price 		= number_format($price/100, 2, ".", "");
		$this->Timestamp 	= $timestamp;
	}

}