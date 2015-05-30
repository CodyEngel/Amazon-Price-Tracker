<?php
    require_once("/includes/simple_html_dom.php");
    /* Enter the Amazon Product ISIN */
    $amazonASIN = "B00S54E1AI";
    //$html = file_get_contents("http://www.amazon.com/dp/".$amazonASIN);
    $html = file_get_html("http://www.amazon.com/dp/".$amazonASIN);
    if($html)
    {
        $price = "";
        if($html->find("span[class=offer-price", 0))
        {
            $price = $html->find("span[class=offer-price", 0)->plaintext;
        }
        else if($html->find("b[class=priceLarge]", 0))
        {
            $price = $html->find("b[class=priceLarge]", 0)->plaintext;
        }
        else if($html->find("span[class=a-color-price]", 0))
        {
            $price = $html->find("span[class=a-color-price]", 0)->plaintext;
        }
        else if($html->find("span[id=priceblock_ourprice]", 0))
        {
            $price = $html->find("span[id=priceblock_ourprice]", 0)->plaintext;
        }
        else
        {
            $price = "not set";
        }

        echo $price;

        echo $html;
    }

?>