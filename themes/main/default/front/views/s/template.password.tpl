{if !$request_async}{include file="includes/template.head.tpl"}{/if}
<div class="s content">
	<div>
		{include file="s/includes/template.menu.tpl"}
		<div class="main">
			<h1>Change your password</h1>
			<p>Change the password you use for login in.</p>
			{form_password->display}
		</div>
	</div>
</div>
{if !$request_async}{include file="includes/template.footer.tpl"}{/if}