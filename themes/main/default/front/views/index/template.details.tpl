<div id="details_products" class="hidden">
	<div class="close"><a class="icon-close"></a></div>
	<div class="title">
		<div class="img {if empty($content.details.image_link)}empty{/if}">{if !empty($content.details.image_link)}<div style="background-image:url('{$content.details.image_link|trim}')"></div>{/if}</div>
		<div class="details">
			<h2>{$content.details.title_link|truncate:50}</h2>
			<a href="{$content.details.canonical_link}" target="_blank" title="{$content.details.canonical_link}" class="merchant"><img src="http://www.{$content.details.domain_link}/favicon.ico">{$content.details.domain_link}</a>
		</div>
		<div class="price{if $content.details.weekly_price_link} with_previous_price{/if}">{$content.details.last_price_link} {if $content.details.devise_link=="EUR"}&euro;{else}{$content.details.devise_link}{/if}{if $content.details.weekly_price_link}<span>{$content.details.weekly_price_link} {if $content.details.devise_link=="EUR"}&euro;{else}{$content.details.devise_link}{/if}</span>{/if}</div>
	</div>
	<menu>
		<li><a rel="tab:graph"{if $content.tab=='graph'} class="current"{/if}>Price history</a></li>
		<li><a rel="tab:coupons"{if $content.tab=='coupons'} class="current"{/if}>Coupons</a></li>
		<li class="right"><a href="{$content.details.canonical_link}" target="_blank" class="sly-button buy">buy</a></li>
		<li class="right"><a href="a/remove-link/id:{$content.details.id_link}/" class="icon-remove"></a></li>
		<li class="right"><a href="" class="icon-tag"></a></li>
		<li class="right"><a href="" class="icon-link"></a></li>
		<li class="right"><a href="" class="icon-star"></a></li>
	</menu>
	<div class="confirm-box">
		<h1>Delete this product?</h1>
		<a href="" class="sly-button" rel="yes">Yes</a>
		<a href="" class="sly-button nop" rel="no">No</a>
	</div>
	<div class="tabs">
		<div class="graph{if $content.tab=='graph'} current{/if}">
			{literal}
			<div id="dataGraph"
				data-width="550"
				data-height="385"
				data-ystep="10"
				data-inputs="[{'color':'#5bc1e9', 'pointLabel':'{$value}&euro;', 'points':{/literal}{$content.states}{literal}}]"></div>
			{/literal}
			<div class="stats">
				<span class="history">
					Price History
					<span>{$content.details.since} days</span>
				</span>
				<span class="lowest">
					Lowest
					<span>{$content.details.min_price} {$content.details.devise_link}</span>
				</span>
				<span class="highest">
					Highest
					<span>{$content.details.max_price} {$content.details.devise_link}</span>
				</span>
				<span class="average">
					Average
					<span>{$content.details.average_price} {$content.details.devise_link}</span>
				</span>
			</div>
		</div>
		<div class="coupons{if $content.tab=='coupons'} current{/if}">
			<h3 style="text-align: center;">Coupons are unavailable at the moment.</h3>
		</div>
	</div>
</div>