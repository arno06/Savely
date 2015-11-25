{if $user_is.USER}
<header class="connected">
	<div>
		<h1 id="main_logo"><a href="a/"></a></h1>
		<menu class="main">
			<li><a href="a/explore/" class="toggle" rel="#explore_stick">Explore</a></li>
			<li><a href="a/lists/" class="toggle" rel="#lists_stick">My lists</a></li>
		</menu>
		<menu class="secondary">
			<li class="add"><a href="" class="icon-plus" rel="#addForm"></a></li>
			<li class="search"><a href="" class="icon-search"></a></li>
			<li class="account"><a href="" class="icon-user toggle" rel="#account_stick"></a></li>
			<li class="notifications"><!--<a href="a/notifications/" class="awaiting"><span>1</span></a>--><a href="a/notifications/" class="icon-alarm"></a></li>
		</menu>
	</div>
</header>
{include file="includes/template.lists_stick.tpl"}
{include file="includes/template.explore_stick.tpl"}
{include file="includes/template.account_stick.tpl"}
	<div class="save{if $controller != "a"} hidden{/if}" id="addForm">
		<h3>Save products, save money</h3>
		{form_addUrl->display}
		<p>... or get the web browser <a href="">extensions</a></p>
	</div>
{else}
<header class="disconnected">
	<div>
		<h1 id="main_logo"><a href="a/"></a></h1>
		<menu class="secondary">
			<li><a href="#login" rel="Dabox[box_login]">connect</a></li>
			<li><a href="#register" rel="Dabox[box_register]">register</a></li>
		</menu>
	</div>
</header>
{/if}