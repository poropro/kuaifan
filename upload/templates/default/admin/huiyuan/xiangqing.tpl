{#include file="common/header.tpl" title_top="1" title="会员信息"#}

[会员信息]<br/>
{#if $smarty.get.go_url#}
	<a href="{#$smarty.get.go_url|goto_url#}">返回来源地址</a>
{#else#}
	<a href="{#get_link('a|userid')#}&amp;a=index">返回列表</a> 
{#/if#}<br/>

-------------<br/>
{#kuaifan_huiyuan set="列表名:lists,详情:1,会员ID:GET[userid],显示数目:20,搜索变量名:key,标题长度:15,填补字符:...,分页显示:1,分页名:pagelist,分页变量名:pp"#}

{#foreach from=$lists item=list#}
	<a href="{#get_link('a')#}&amp;a=xiugai">修改</a>-<a href="{#get_link('a')#}&amp;a=shanchu">删除</a>-{#if $list.islock#}<u>已锁定</u>{#else#}<a href="{#get_link('a')#}&amp;a=suoding">锁定</a>{#/if#}-<a href="{#get_link('a')#}&amp;a=jiesuo">解锁</a><br/>
	【基本信息】<br/>
	<img src="{#kuaifan touxiang=$list.userid size='中'#}?t={#$TIME2#}"/><br/>
	用户ID: {#$list.userid#}<br/>
	用户名: {#$list.username#}<br/>
	昵称: {#$list|colorname#}<br/>
	手机: {#$list.mobile#}<br/>
	邮箱: {#$list.email#}<br/>
	会员组: {#$grouplist[$list.groupid]#}<br/>
	会员模型: {#$modellistarr[$list.modelid]#}<br/>
	注册时间: {#$list.regdate|date_format:"%Y-%m-%d %H:%M:%S"#}<br/>
	最后登录: {#$list.lastdate|date_format:"%Y-%m-%d %H:%M:%S"#}<br/>
	注册IP: {#$list.regip#}<br/>
	最后IP: {#$list.lastip#}<br/>
	登录次数: {#$list.loginnum#}<br/>
	积分点数: {#$list.point#}<br/>
	{#$KUAIFAN.amountname#}总数: {#$list.amount#}<br/>
	个性签名: {#$list|qianming#}<br/>
	{#if $list.field#}【详细信息】<br/>{#/if#}
	{#foreach from=$list.field item=detail key=k#}
		{#$detail.name#}:
		{#if $detail.type == 'box' && $detail.outputtype == '1'#}
			{#$_box = str_replace('|','=>',$detail['options'])#}
			{#$_box = str_replace('\r\n',',',$_box)#}
			{#$_box = str_replace(chr(13)+chr(10),',',$_box)#}
			{#$_box = str_replace(chr(13),',',$_box)#}
			{#$_box = str_replace(chr(10),'',$_box)#}
			{#$_box = string2array("array({#$_box#})")#}
			{#$_box = array_flip($_box)#}
			{#$_box[$list.detail[$k]]#}
		{#elseif $detail.type == 'datetime'#}
			{#$_setting = string2array($detail.setting)#}
			{#date($_setting.format,$list.detail[$k])#}
		{#else#}
			{#$list.detail[$k]#}
		{#/if#}
		 <br/>
	{#/foreach#}
	.<br/>
	{#if $list.vip#}
		是否vip会员:是{#if $list.overduedate<$smarty.const.SYS_TIME#}(已过期){#/if#}<br/>
		vip过期时间:{#$list.overduedate|date_format:"%Y-%m-%d %H:%M:%S"#}<br/>
	{#elseif $list.overduedate#}
		是否vip会员:已过期<br/>
		vip过期时间:{#$list.overduedate|date_format:"%Y-%m-%d %H:%M:%S"#}<br/>
	{#else#}
		是否vip会员:否<br/>
	{#/if#}
	.<br/>
	SID标识: {#if $list.usersid#}<a href="{#kuaifan getlink='a|sid'#}&amp;a=shenfen&amp;sf={#$list.usersid#}" target="_blank">{#$list.usersid#}</a>{#else#}无(登录后自动生成){#/if#}<br/>
{#foreachelse#}
	没有任何会员。<br/>
{#/foreach#}


{#include file="admin/footer.tpl"#}
{#include file="common/footer.tpl"#}
