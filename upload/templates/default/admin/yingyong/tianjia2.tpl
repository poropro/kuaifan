{#include file="common/header.html5.tpl" title_top="1" title="在线安装应用"#}

[<a href="{#$admin_indexurl#}&amp;c=yingyong">返回</a>]<br/>
[<a href="{#$admin_indexurl#}&amp;c=yingyong&amp;a=tianjia">查看本地应用</a>]<br/>

<div style="background-color:#E5F0E9;padding:3px">
    {#form set="头|地址:'{#str_replace(':','\:',get_link('page|keyw'))#}'"#}
    {#form set="输入框|名称:keyw,值:'{#$smarty.request.keyw#}',宽:12"#}
    {#form set="按钮|名称:dosubmit,值:搜索"#}
    {#form set="尾"#}<br/>
    类型:<a href="{#get_link('page|keyw|type')#}">全部</a>|<a href="{#get_link('page|keyw|type')#}&amp;type={#'应用插件'|urlencode#}">应用插件</a>|<a href="{#get_link('page|keyw|type')#}&amp;type={#'模板风格'|urlencode#}">模板风格</a>
</div>

应用名称(版本)<br/>
----------<br/>
{#foreach from=$getarr['list'] item=list name=foo#}
	{#$smarty.foreach.foo.index+1#}.{#$list.title#}({#$list.identifie#}_{#$list.versiondate#})<br/>
	{#if $list.ability#}<small>{#$list.ability#}</small><br/>{#/if#}
    <a href="{#$admin_indexurl#}&amp;c=yingyong&amp;a=info&amp;app={#$list.identifie#}"><img src="{#$smarty.const.IMG_PATH#}install.png"/>安装此_{#$list.type#}</a><br/>
	{#if !$smarty.foreach.foo.last#}
		----------<br/>
	{#/if#}
{#foreachelse#}
	没有任何可安装的插件。<br/>
{#/foreach#}
{#if $getarr.pagelist#}{#$getarr.pagelist#}<br/>{#/if#}


{#include file="admin/footer.html.tpl"#}
{#include file="common/footer.html.tpl"#}
