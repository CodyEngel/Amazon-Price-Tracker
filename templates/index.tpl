{*config_load file="test.conf" section="setup"*}
{include file="header.tpl" title=$title}

<div id="page_wrapper">
    <div id="content_wrapper">
        <form id="form_search">
            <input id="input_search" name="keyword" placeholder="Search by product or ASIN" value="{$search_keyword}" />
            <input id="button_search" class="button" type="submit" value="Search" />
        </form>
        {* Trending Products *}
        {foreach from=$amazon_search->SearchResultItems item=search_item}
        	{include file="amazon_search_item.tpl" search_item=$search_item}
        {/foreach}
    </div>
</div>

{include file="footer.tpl"}
