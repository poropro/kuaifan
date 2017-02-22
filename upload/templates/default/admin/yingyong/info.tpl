{#include file="common/header.html5.tpl" title_top="1" title="应用详情"#}

[<a href="{#$admin_indexurl#}&amp;c=yingyong">返回</a>]<br/>
{#if !$local#}
[<a href="{#$admin_indexurl#}&amp;c=yingyong&amp;a=tianjia2">查找更多应用</a>]<br/>
{#else#}
[<a href="{#$admin_indexurl#}&amp;c=yingyong&amp;a=tianjia">查找本地应用</a>]<br/>
{#/if#}

----------<br/>
【应用详情{#if $local#}(本地){#/if#}】<br/>
应用名称: {#$getarr.title#} <br/>
标识(适应): {#$getarr.identifie#}({#$getarr.code#}) <br/>
版本(版本日期): {#$getarr.version#}({#$getarr.versiondate#}) <br/>
{#if !$local#}
类型: {#$getarr.type#} <br/>
{#/if#}
功能介绍: {#if $getarr.ability#}{#$getarr.ability#}{#else#}无{#/if#} <br/>
{#if $getarr.description#}详细介绍: {#$getarr.description#} <br/>{#/if#}
作者(网址): {#$getarr.author#}({#if $getarr.url#}<a href="{#$getarr.url#}" target="_blank">{#$getarr.url#}</a>{#else#}无{#/if#}) <br/>
{#if !$local#}
上传时间: {#date("Y-m-d H:i:s",$getarr.indate)#} <br/>
浏览/安装: {#$getarr.view#}次/{#$getarr.install#}次 <br/>
{#/if#}
{#if $nowapp#}
    ----------<br/>
    【已安装版本】 <br/>
    版本日期: {#$nowapp.v#} <br/>
    安装时间: {#date("Y-m-d H:i:s",$nowapp.intime)#} <br/>
    {#if $getarr.isup#}
        <a href="{#$admin_indexurl#}&amp;c=yingyong&amp;a=az&amp;local={#$local#}&amp;app={#$getarr.identifie#}&time={#time()#}"><img src="{#$smarty.const.IMG_PATH#}cloud.png"/>更新至版本{#$getarr.versiondate#}</a>
    {#else#}
        <span style="color:#269E26;text-decoration:underline">已经是最新版本无须更新</span>
    {#/if#}
{#else#}
    <a href="{#$admin_indexurl#}&amp;c=yingyong&amp;a=az&amp;local={#$local#}&amp;app={#$getarr.identifie#}&time={#time()#}"><img src="{#$smarty.const.IMG_PATH#}install.png"/>安装此_{#if $getarr.type#}{#$getarr.type#}{#else#}应用插件{#/if#}</a>
{#/if#}
<br/>

{#include file="admin/footer.html.tpl"#}
{#include file="common/footer.html.tpl"#}
