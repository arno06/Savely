<menu id="lists_stick" class="hidden stick">
	{foreach from=$content.user_lists item="item" key="key"}
	<li><a href="list/{$item.permalink_list}/edit">{$item.name_list}</a></li>
	{/foreach}
	<li><a href="l/create/">New list</a></li>
</menu>