{if !$request_async}{include file="includes/template.head.tpl"}{/if}

{form_add->display}

{if isset($content.parsing)}
	<pre style="padding:10px;margin:10px;background:#fafafa;font-size:14px;">{$content.parsing}
	</pre>
{/if}

{if !$request_async}{include file="includes/template.footer.tpl"}{/if}