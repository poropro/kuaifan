{#* wml头部文件 *#}
{#kuaifan header="wml"#} {#* Content-Type:wml *#}
<?xml version="1.0" encoding="{#$smarty.const.CHARSET#}"?>
<!DOCTYPE wml PUBLIC "-//WAPFORUM//DTD WML 1.1//EN" "http://www.wapforum.org/DTD/wml_1.1.xml">
<wml>
{#if $gotourl#}
	<card id="main" title="{#$title#}" ontimer="{#$gotourl#}">
	<timer value="{#$gototime*10#}"/>
{#else#}
	<card id="main" title="{#$title#}">
{#/if#}
<p>
{#if $gotourl#}
	<small><a href="{#$gotourl#}" title="点击手动跳转">{#$gototime#}秒后自动转跳</a></small><br/>
{#/if#}