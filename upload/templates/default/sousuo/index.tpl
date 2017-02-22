{#include file="common/header.tpl" title="{#$smarty.request.key#} - 搜索"#}

<a href="{#kf_url('index')#}">首页</a>-&gt;搜索
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


{#kuaifan_sousuo set="列表名:lists,显示数目:10,搜索变量名:key,标题长度:15,填补字符:...,分页显示:1,分页名:pagelist,分页变量名:page"#}
{#foreach from=$lists item=list#}
	<a href="{#$list.url#}&amp;key={#$smarty.request.key|urlencode#}">{#$list._n#}.{#$list.title#}</a><br/>
	{#htmlneirong($list.description)#}<br/>
{#foreachelse#}
	抱歉,没有找到"{#$smarty.request.key#}"相关的文章内容.建议您:<br/>
	1.查看输入关键词是否有误;<br/>
	2.简化输入关键词,如"北京的人口是由什么构成的"简化为"北京 人口 构成".<br/>
{#/foreach#}

{#$pagelist#}
<br/>
<a href="{#kf_url('index')#}">首页</a>-&gt;搜索
<br/>


{#include file="common/footerb.tpl"#}
{#include file="common/footer.tpl"#}