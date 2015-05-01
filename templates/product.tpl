{include file="header.tpl" title=$amazon_product->Title}

<div id="page_wrapper">
    <div id="content_wrapper">
        <div id="amazon_product_details">
            <h2>{$amazon_product->Title}</h2>
            <img src="{$amazon_product->ImageURL}" />
            <p class="amazon_product_description">
                {$amazon_product->Description}
            </p>
        </div>
        {* Price History *}
        <div id="amazon_product_price_history">
            <div class="button">Add To Wishlist</div>
            <h4>Price History</h4>
            <ul>
            {foreach from=$amazon_product_price_history item=amazon_price_item}
            	<li>{$amazon_price_item->Timestamp} - {$amazon_price_item->Price}</li>
            {/foreach}
            </ul>
        </div>
    </div>
</div>

{include file="footer.tpl"}