<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset={$configuration.global_encoding}">
        <meta name="viewport" content="width=device-width, user-scalable=no">
		<link rel="icon" type="image/png" href="{$path_to_theme}/imgs/logo.png" />
        <base href="{$configuration.server_url}"/>
		<title>{$head.title}</title>
        {if isset($content.canonical) && !empty($content.canonical)}
            <link rel="canonical" href="{$content.canonical}">
        {/if}
		<meta name="description" content="{$head.description}"/>
		<link type="text/css" rel="stylesheet" href="{$path_to_theme}/css/style.css">
{foreach from=$styles item=style}
		<link type="text/css" rel="stylesheet" href="{$style}">
{/foreach}
{foreach from="$scripts" item=script}
        <script type="text/javascript" src="{$script}"></script>
{/foreach}
	</head>
	<body>
{include file="includes/template.header.tpl"}