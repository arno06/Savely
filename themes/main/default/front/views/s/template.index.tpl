{if !$request_async}{include file="includes/template.head.tpl"}{/if}
<div class="s content">
	<div>
		{include file="s/includes/template.menu.tpl"}
		<div class="main">
			<div class="item">
				<a href="s/account/">Edit your account</a>
				<p>Modify your account information, change your password or manage</p>
			</div>
			<div class="item">
				<a href="s/notifications/">Notifications</a>
				<p>Manage how you receive notifications from us</p>
			</div>
			<div class="item">
				<a href="s/connected-apps/">Connected apps</a>
				<p>You have authorized the following applications to access your account. To remove access, click the link next to each application.</p>
			</div>
			<div class="item">
				<a href="s/privacy/">Privacy</a>
				<p>Adjust your privacy settings, delete data or delete your account</p>
			</div>
		</div>
	</div>
</div>
{if !$request_async}{include file="includes/template.footer.tpl"}{/if}