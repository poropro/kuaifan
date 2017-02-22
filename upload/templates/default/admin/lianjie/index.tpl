{#include file="common/header.tpl" title_top="1" title="友情链接"#}

[友情链接管理]<br/>
-------------<br/>
{#$url = get_link('m|allow|vs|c','','1')#}
<a href="{#$url#}&amp;a=add"><u>添加链接</u></a>|<a href="{#$url#}&amp;a=fenlei">分类</a>|<a href="{#$url#}&amp;a=peizhi">配置</a><br/>

{#form set="头|地址:'{#str_replace(':','\:',get_link('pp|key'))#}'" notvs="1"#}
{#form set="输入框|名称:key{#$TIME2#},值:'{#$smarty.request.key#}',宽:12"#}
{#kuaifan vs="1" set="
  <anchor>搜索
  <go href='{#get_link('pp|key')#}' method='post' accept-charset='utf-8'>
  <postfield name='key' value='$(key{#$TIME2#})'/>
  <postfield name='dosubmit' value='1'/>
  </go> </anchor>
"#}

{#form set="按钮|名称:dosubmit,值:搜索" notvs="1"#}
{#form set="尾" notvs="1"#}
<br/>

{#$typelink#}<br/>
{#$orderlink#}<br/>

{#kuaifan_pc set="列表名:lists,显示数目:10,分页显示:1,分页名:pagelist,分页变量名:pp,数据表:lianjie,排序:{#$ordersql#}" where="{#$wheresql#}"#}
ID.名称(分类)|进|出<br/>
{#foreach from=$lists item=list#}
	{#if $list.type=='0'#}<b>[审]</b>{#/if#}{#if $list.type=='2'#}<b>[黑]</b>{#/if#}<a href="{#$url#}&amp;a=edit&amp;id={#$list.id#}">{#$list.id#}.{#$list.title#}</a>({#$list.catid_cn#})|{#$list.from#}|{#$list.read#}<br/>
{#foreachelse#}
	<u>没有任何友情链接</u><br/>
{#/foreach#}

{#$pagelist#}
<br/>
[*<a href="{#kuaifan getlinks='vs'#}&amp;l=lianjie&amp;a=shuoming&amp;m=explain&amp;go_url={#urlencode(get_link('','&'))#}">系统说明</a>*]<br/>


{#include file="admin/footer.tpl"#}
{#include file="common/footer.tpl"#}
