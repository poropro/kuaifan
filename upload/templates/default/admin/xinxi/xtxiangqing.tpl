{#include file="common/header.tpl" title_top="1" title="短信息"#}

[查看信息]<br/>
<a href="{#get_link('a|id')#}&amp;a=xitong">返回列表</a><br/>
-------------<br/>
收件人:{#$X.groupid_cn#}<br/>
发送标题:{#$X.subject#}<br/>
发送时间:{#$X.inputtime|date_format:"%Y-%m-%d %H:%M:%S"#}<br/>
信息内容:{#$X.content#} <br/>

<a href="{#get_link('a|xtdel|id')#}&amp;a=xitong&amp;xtdel={#$X.id#}">删除此群发信息</a><br/>
注明:删除后未查看本条信息的用户也看不到本条信息了<br/>


{#include file="admin/footer.tpl"#}
{#include file="common/footer.tpl"#}
