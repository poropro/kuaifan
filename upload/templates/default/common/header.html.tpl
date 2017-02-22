{#* html头部文件 *#}
{#kuaifan header="html"#} {#* Content-Type:html *#}
<?xml version="1.0" encoding="UTF-8" ?>
<!DOCTYPE html PUBLIC " -//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=UTF-8" />
<meta name="viewport" content="width=device-width; initial-scale=1.0; minimum-scale=1.0; maximum-scale=2.0" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style" content="black" />
<title>{#$title#}</title>
{#if $gotourl#}<meta http-equiv="refresh" content="{#$gototime#};URL={#$gotourl#} "/>{#/if#}
{#if $SEO.keywords#}<meta name="keywords" content="{#$SEO.keywords#}" />{#/if#}
{#if $SEO.description#}<meta name="description" content="{#$SEO.description#}" />{#/if#}
<link type="image/x-icon" rel="shortcut icon" href="{#$KUAIFAN.site_dir#}favicon.ico" />
<link type="text/css" rel="stylesheet" href="{#$smarty.const.CSS_PATH#}style.css" />
{#$__seo_head#}</head>
<body>
{#if $title_top#}<div style="background: #E3E3E3;border-bottom: 3px solid #f68e00;padding: 0px 3px; margin-bottom:8px;">{#$title#}</div>{#/if#}
{#if $gotourl#}
	<small><a href="{#$gotourl#}" title="点击手动跳转">{#$gototime#}秒后自动转跳</a></small><br/>
{#/if#}