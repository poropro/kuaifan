{#*html5头部文件*#}
{#kuaifan header="html"#} {#* Content-Type:html *#}
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=2.0" /> 
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
	<small><a href="{#$gotourl#}" title="点击手动跳转"><span id="gototime">{#$gototime#}</span>秒后自动转跳</a></small><br/>
	<script type="text/javascript"> 
		setTimeout("gototime()",1000);
		function gototime(){
			var docgototime = document.getElementById("gototime").innerHTML;
			var re = new RegExp("^[0-9]+$");
			if (docgototime.search(re) != - 1) {
				if (docgototime > 1){
					document.getElementById("gototime").innerHTML = docgototime-1;
					setTimeout("gototime()",1000);
				}
			}
		}
	</script>
{#/if#}