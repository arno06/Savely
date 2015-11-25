{if !$request_async}{include file="includes/template.head.tpl"}{/if}
<div id="box_login" style="display:none;">
	<div id="connect">
		<h4>Connect</h4>
		{form_login->display}
	</div>
</div>
<div id="box_register" style="display:none;">
	<div id="register">
		<h4>Register</h4>
		{form_register->display}
	</div>
</div>

<div class="index content">
	<div class="home">
		<div class="call_to_action">
			<h3>Save products you want to shop later</h3>
		</div>
	</div>
	<div class="links">
		{foreach from=$content.products item="link"}
			<div class="link" id="link_{$link.id_link}" rel="shoplater:graph" data-id="{$link.id_link}">
				<div>
					<div class="img {if empty($link.image_link)}empty{/if}">{if !empty($link.image_link)}<div style="background-image:url('{$link.image_link|trim}')"></div>{/if}</div>
					<div class="details">
						<h2>{$link.title_link|truncate:54:"..."}</h2>
						<a href="{$link.canonical_link}" target="_blank" title="{$link.canonical_link}" class="merchant"><img src="http://www.{$link.domain_link}/favicon.ico">{$link.domain_link}</a>
					</div>
					<div class="price{if $link.weekly_price_link} with_previous_price{/if}">{$link.last_price_link} {if $link.devise_link=="EUR"}&euro;{else}{$link.devise_link}{/if}{if $link.weekly_price_link}<span>{$link.weekly_price_link} {if $link.devise_link=="EUR"}&euro;{else}{$link.devise_link}{/if}</span>{/if}</div>
				</div>
				<div class="hover">
					<a href="" class="coupons" rel="shoplater:coupons" data-id="{$link.id_link}">coupons</a>
					<a href="{$link.canonical_link}" class="buy">buy</a>
				</div>
			</div>
		{/foreach}
	</div>
</div>

<div id="overlay_products" class="hidden"></div>

{if !$request_async}{include file="includes/template.footer.tpl"}{/if}