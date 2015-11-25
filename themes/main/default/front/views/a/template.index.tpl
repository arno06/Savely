{if !$request_async}{include file="includes/template.head.tpl"}{/if}
<div class="a content">
	<div class="links">
	{foreach from=$content.user_links item="link"}
		<div class="link" id="link_{$link.id_link}" rel="shoplater:graph" data-id="{$link.id_link}">
			<div>
				<div class="img {if empty($link.image_link)}empty{/if}">{if !empty($link.image_link)}<div style="background-image:url('{$link.image_link|trim}')"></div>{/if}</div>
				<div class="details">
					<h2>{$link.title_link|truncate:54:"..."}</h2>
					<a href="{$link.canonical_link}" target="_blank" title="{$link.canonical_link}" class="merchant"><img src="http://www.{$link.domain_link}/favicon.ico">{$link.domain_link}</a>
				</div>
				<div class="price{if $link.weekly_price_link} with_previous_price{/if}{if $link.last_price_link == 0} price_unavailable{/if}">
					{if $link.last_price_link == 0}
						Price unavailable
					{else}
						{$link.last_price_link} {if $link.devise_link=="EUR"}&euro;{else}{$link.devise_link}{/if}
						{if $link.weekly_price_link}<span>{$link.weekly_price_link} {if $link.devise_link=="EUR"}&euro;{else}{$link.devise_link}{/if}</span>{/if}
					{/if}
				</div>
			</div>
			<div class="hover">
				<a href="" class="coupons" rel="shoplater:coupons" data-id="{$link.id_link}">coupons</a>
				<a href="{$link.canonical_link}" class="buy" target="_blank">buy</a>
			</div>
		</div>
	{/foreach}
	</div>
</div>

<div id="overlay_products" class="hidden"></div>

{if !$request_async}{include file="includes/template.footer.tpl"}{/if}