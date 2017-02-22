{#include file="common/header.tpl" title_top="1" title="短信息"#}

[查看信息]<br/>
<a href="{#get_link('a|messageid')#}&amp;a=index">返回列表</a><br/>
-------------<br/>
{#if $X.status#}<b>！未读</b><br/>{#/if#}
收件人:{#$X.send_to_id#}<br/>
发件人:{#$X.send_from_id#}<br/>
发送标题:{#$X.subject#}<br/>
发送时间:{#$X.message_time|date_format:"%Y-%m-%d %H:%M:%S"#}<br/>
信息内容:{#$X.content#} <br/>

{#if $X.folder=='outbox'#}
	<a href="{#get_link('a')#}&amp;a=dell">拉出回收站</a>
{#else#}
	<a href="{#get_link('a')#}&amp;a=delh">放到回收站</a>
{#/if#}
<a href="{#get_link('a|del|messageid')#}&amp;a=index&amp;del={#$X.messageid#}">彻底删除</a><br/>
注明:回收站会员看不到,后台可以看到; 删除均看不到<br/>


{#include file="admin/footer.tpl"#}
{#include file="common/footer.tpl"#}
