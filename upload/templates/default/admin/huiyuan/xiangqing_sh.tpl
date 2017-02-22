{#include file="common/header.tpl" title_top="1" title="会员信息"#}

[会员信息]<br/>
<a href="{#get_link('a|userid')#}&amp;a=shenhe">返回列表</a><br/>
-------------<br/>
{#kuaifan_huiyuan_shenhe set="列表名:lists,会员ID:GET[userid],显示数目:20,搜索变量名:key,标题长度:15,填补字符:...,分页显示:1,分页名:pagelist,分页变量名:pp"#}

{#foreach from=$lists item=list#}
	【基本信息】<br/>
	用户名: {#$list.username#}<br/>
	昵称: {#$list.nickname|htmlspecialchars#}<br/>
	手机: {#$list.mobile#}<br/>
	邮箱: {#$list.email#}<br/>
	会员模型: {#$modellistarr[$list.modelid]#}<br/>
	注册时间: {#$list.regdate|date_format:"%Y-%m-%d %H:%M:%S"#}<br/>
	注册IP: {#$list.regip#}<br/>
	1.<a href="{#get_link('a|n')#}&amp;a=shenhe&amp;n=tg">通过申请</a><br/>
	2.<a href="{#get_link('a|n')#}&amp;a=shenhe&amp;n=tgm">通过申请并发邮件通知</a><br/>
	3.<a href="{#get_link('a|n')#}&amp;a=shenhe&amp;n=btg">拒绝申请</a><br/>
	4.<a href="{#get_link('a|n')#}&amp;a=shenhe&amp;n=btgm">拒绝申请并发邮件通知</a><br/>
{#foreachelse#}
	没有任何会员。<br/>
{#/foreach#}


{#include file="admin/footer.tpl"#}
{#include file="common/footer.tpl"#}
