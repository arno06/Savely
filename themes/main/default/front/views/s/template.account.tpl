{if !$request_async}{include file="includes/template.head.tpl"}{/if}
<div class="s content">
	<div>
	{include file="s/includes/template.menu.tpl"}
	<div class="main">
		<h1>Edit your account</h1>
		<p>Change the basic settings of your account and your language settings.</p>
		<p><a href="s/password/">Change your password</a></p>
		{form_account->display}
	</div>
	</div>
</div>
{if !$request_async}{include file="includes/template.footer.tpl"}{/if}