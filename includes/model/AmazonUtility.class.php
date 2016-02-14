<?php

// AmazonUtility::function()
// self::$variable

class AmazonUtility
{
	private static $AWSAccessKey = "YOURACCESSKEY";
	private static $AWSSecretKey = "YOURSECRETKEY";
	private static $AWSAssociateTag = "YOURASSOCIATETAG";
	
	public static function ItemLookup($item_id)
	{
		$request = self::AWSSignedRequest('com', array(
			'Operation' => 'ItemLookup',
			'ItemId' => $item_id,
			'ResponseGroup' => 'Large'
			));
		
		return file_get_contents($request);
	}
	
	public static function ItemSearch($keyword, $searchIndex, $itemPage)
	{
		$request = AmazonUtility::AWSSignedRequest(
			'com', array(
			'Operation' => "ItemSearch",
			'Keywords' => $keyword,
			'SearchIndex' => $searchIndex,
			'ResponseGroup' => "ItemIds",
			'Condition' => 'New',
			'ItemPage' => $itemPage
			));
			
		$file_contents = file_get_contents($request);
		$attempts = 1;
		while($file_contents === false)
		{
			if($attempts >= 5) break;
			usleep(500000);
			$file_contents = file_get_contents($request);

			$attempts++;
		}
		return $file_contents;
	}
	
	public static function AWSSignedRequest($region, $params, $version='2011-08-01')
	{
		/*
		Source: http://www.ulrichmierendorff.com/software/aws_hmac_signer.html
		
		Copyright (c) 2009-2012 Ulrich Mierendorff

		Permission is hereby granted, free of charge, to any person obtaining a
		copy of this software and associated documentation files (the "Software"),
		to deal in the Software without restriction, including without limitation
		the rights to use, copy, modify, merge, publish, distribute, sublicense,
		and/or sell copies of the Software, and to permit persons to whom the
		Software is furnished to do so, subject to the following conditions:

		The above copyright notice and this permission notice shall be included in
		all copies or substantial portions of the Software.

		THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
		IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
		FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL
		THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
		LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
		FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
		DEALINGS IN THE SOFTWARE.
		*/
		
		/*
		Parameters:
			$region - the Amazon(r) region (ca,com,co.uk,de,fr,co.jp)
			$params - an array of parameters, eg. array("Operation"=>"ItemLookup",
							"ItemId"=>"B000X9FLKM", "ResponseGroup"=>"Small")
			$public_key - your "Access Key ID"
			$private_key - your "Secret Access Key"
			$version (optional)
		*/
		
		// some paramters
		$method = 'GET';
		$host = 'webservices.amazon.'.$region;
		$uri = '/onca/xml';
		
		// additional parameters
		$params['Service'] = 'AWSECommerceService';
		$params['AWSAccessKeyId'] = self::$AWSAccessKey;
		// GMT timestamp
		$params['Timestamp'] = gmdate('Y-m-d\TH:i:s\Z');
		// API version
		$params['Version'] = $version;
		if (self::$AWSAssociateTag !== NULL) {
			$params['AssociateTag'] = self::$AWSAssociateTag;
		}
		
		// sort the parameters
		ksort($params);
		
		// create the canonicalized query
		$canonicalized_query = array();
		foreach ($params as $param=>$value)
		{
			$param = str_replace('%7E', '~', rawurlencode($param));
			$value = str_replace('%7E', '~', rawurlencode($value));
			$canonicalized_query[] = $param.'='.$value;
		}
		$canonicalized_query = implode('&', $canonicalized_query);
		
		// create the string to sign
		$string_to_sign = $method."\n".$host."\n".$uri."\n".$canonicalized_query;
		
		// calculate HMAC with SHA256 and base64-encoding
		$signature = base64_encode(hash_hmac('sha256', $string_to_sign, self::$AWSSecretKey, TRUE));
		
		// encode the signature for the request
		$signature = str_replace('%7E', '~', rawurlencode($signature));
		
		// create request
		$request = 'http://'.$host.$uri.'?'.$canonicalized_query.'&Signature='.$signature;
		
		return $request;
	}

}

?>
