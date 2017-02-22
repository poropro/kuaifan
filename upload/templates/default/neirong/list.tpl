{#include file="common/header.tpl" title={#$SEO.title#} seo={#$SEO#}#}


{#get_pos($M.id)#}
<br/>
{#form set="头|地址:'{#str_replace(':','\:',get_link('page|key'))#}'" notvs="1"#}
{#form set="输入框|名称:key{#$TIME2#},值:'{#$smarty.request.key#}',宽:12"#}
{#kuaifan vs="1" set="
  <anchor>搜索
  <go href='{#get_link('page|key')#}' method='post' accept-charset='utf-8'>
  <postfield name='key' value='$(key{#$TIME2#})'/>
  <postfield name='dosubmit' value='1'/>
  </go> </anchor>
"#}
{#form set="按钮|名称:dosubmit,值:搜索" notvs="1"#}
{#form set="尾" notvs="1"#}
<br/>-------------<br/>


{#kuaifan_neirong set="列表名:lists,显示数目:20,模型:{#$M.module#},分类:{#$M.id#},状态:1,搜索变量名:key,标题长度:15,填补字符:...,分页显示:1,分页名:pagelist,分页变量名:page"#}
{#foreach from=$lists item=list#}
	{#$list._n#}.<a href="{#$list.url#}">{#$list.title#}{#if $list.thumb#}[图]{#/if#}</a><br/>
{#foreachelse#}
	此栏目尚未发布内容。<br/>
{#/foreach#}

{#$pagelist#}
<br/>
{#get_pos($M.id)#}<br/>

{#kuaifan tongji="查看" get=$getarr#}
{#include file="common/footerb.tpl"#}	
{#include file="common/footer.tpl"#}