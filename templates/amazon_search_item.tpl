<a href="/product.php?id={$search_item->ASIN}">
	<div class="amazon_search_item_container">
		<div class="amazon_image">
			<img src="{$search_item->ImageURL}" />
			<h4 class="amazon_price">{$search_item->Price}</h4>
		</div>
		<h2 class="amazon_title">{$search_item->Title}</h2>
	</div>
</a>