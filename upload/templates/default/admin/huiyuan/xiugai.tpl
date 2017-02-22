{#include file="common/header.tpl" title_top="1" title="修改会员信息"#}

[会员信息]<br/>
<a href="{#get_link('a|userid')#}&amp;a=index">列表</a> &gt; <a href="{#get_link('a')#}&amp;a=xiangqing">详情页</a><br/>
-------------<br/>
{#kuaifan_huiyuan set="列表名:lists,详情:1,会员ID:GET[userid],显示数目:20,搜索变量名:key,标题长度:15,填补字符:...,分页显示:1,分页名:pagelist,分页变量名:pp"#}

{#foreach from=$lists item=list#}
	【基本信息】<br/>
	{#form set="头" notvs="1"#}
	<a href="{#get_link('a')#}&amp;a=touxiang">点击修改头像</a><br/>
	
	用户ID: {#$list.userid#}<br/>
	用户名: {#$list.username#}<br/>
	昵称: {#form set="输入框|名称:info_nickname{#$TIME2#}" data_value=$list.nickname#}<br/>
	昵称颜色: {#form set="输入框|名称:info_colorname{#$TIME2#},宽:6" data_value=$list.colorname#}
	<a href="{#get_link('vs','',1)#}&amp;m=explain&amp;l=xitong&amp;a=yanse&amp;go_url={#urlencode(get_link('','&'))#}"><small>颜色代码</small></a><br/>
	昵称加粗: {#form set="列表框|名称:info_boldname{#$TIME2#}" list="否:0,是:1" default=$list.boldname#}<br/>
	邮箱: {#form set="输入框|名称:info_email{#$TIME2#}" data_value=$list.email#}<br/>
	会员组: {#form set="列表框|名称:info_groupid{#$TIME2#}" list=array_flip($grouplist) default=$list.groupid#}<br/>
	会员模型: {#form set="列表框|名称:info_modelid{#$TIME2#}" list=array_flip($modellistarr) default=$list.modelid#}<br/>
	注册时间: 
	{#if $list.regdate#}
		{#form set="输入框|名称:info_regdate{#$TIME2#}" data_value=$list.regdate|date_format:"%Y-%m-%d %H:%M:%S"#}<br/>
	{#else#}
		{#form set="输入框|名称:info_regdate{#$TIME2#}"#}<br/>
	{#/if#}
	最后登录: 
	{#if $list.lastdate#}
		{#form set="输入框|名称:info_lastdate{#$TIME2#}" data_value=$list.lastdate|date_format:"%Y-%m-%d %H:%M:%S"#}<br/>
	{#else#}
		{#form set="输入框|名称:info_lastdate{#$TIME2#}"#}<br/>
	{#/if#}
	注册IP: {#form set="输入框|名称:info_regip{#$TIME2#}" data_value=$list.regip#}<br/>
	最后IP: {#form set="输入框|名称:info_lastip{#$TIME2#}" data_value=$list.lastip#}<br/>
	登录次数: {#form set="输入框|名称:info_loginnum{#$TIME2#}" data_value=$list.loginnum#}<br/>
	积分点数: {#form set="输入框|名称:info_point{#$TIME2#}" data_value=$list.point#}<br/>
	{#$KUAIFAN.amountname#}总数: {#form set="输入框|名称:info_amount{#$TIME2#}" data_value=$list.amount#}<br/>
	个性签名: {#form set="输入框|名称:info_qianming{#$TIME2#}" data_value=$list.qianming#}<br/>
	{#if $list.field#}【详细信息】<br/>{#/if#}	
	{#foreach from=$list.field item=detail key=k#}
		{#$detail.name#}:
		{#if $detail.type == 'text'#}
			{#form set="输入框|名称:detail_{#$detail.field#}{#$TIME2#}" data_value=$list.detail[$k]#}
		{#/if#}
		{#if $detail.type == 'textarea'#}
			{#form set="输入框|名称:detail_{#$detail.field#}{#$TIME2#}" data_value=$list.detail[$k] vs="1"#}
			{#form set="文本框|名称:detail_{#$detail.field#}{#$TIME2#}" data_value=$list.detail[$k] notvs="1"#}
		{#/if#}
		{#if $detail.type == 'box'#}
			{#$_box = str_replace('|',':',$detail['options'])#}
			{#$_box = str_replace('\r\n',',',$_box)#}
			{#$_box = str_replace(chr(13)+chr(10),',',$_box)#}
			{#$_box = str_replace(chr(13),',',$_box)#}
			{#$_box = str_replace(chr(10),'',$_box)#}
			{#form set="列表框|名称:detail_{#$detail.field#}{#$TIME2#}" list=$_box default=$list.detail[$k]#}
		{#/if#}
		{#if $detail.type == 'number'#}
			{#form set="输入框|名称:detail_{#$detail.field#}{#$TIME2#}" data_value=$list.detail[$k]#}
		{#/if#}
		{#if $detail.type == 'datetime'#}
			{#$_setting = string2array($detail.setting)#}
			{#form set="输入框|名称:detail_date_{#$detail.field#}{#$TIME2#}" data_value=date($_setting.format,$list.detail[$k])#}
		{#/if#}
		<br/>
	{#/foreach#}
	
	.<br/>
	是否vip会员:{#form set="列表框|名称:info_vip{#$TIME2#}" list="否:0,是:1" default=$list.vip#}<br/>
	vip过期时间:{#form set="输入框|名称:info_overduedate{#$TIME2#}" data_value=$list.overduedate|date_format:"%Y-%m-%d %H:%M:%S"#}<br/>
	1).非vip会员(含过期vip)用户组与积分绑定;<br/>2).如只修改会员组请设置该会员为vip。<br/>
	.<br/>
	新密码:(留空不修改)<br/>
	{#form set="输入框|名称:password{#$TIME2#}"#}<br/>
	
	{#if $VS=='1'#}
		<anchor title="提交">[提交保存]
		<go href="{#get_link()#}" method="post" accept-charset="utf-8">
		<postfield name="info_nickname" value="$(info_nickname{#$TIME2#})"/>
		<postfield name="info_colorname" value="$(info_colorname{#$TIME2#})"/>
		<postfield name="info_boldname" value="$(info_boldname{#$TIME2#})"/>
		<postfield name="info_email" value="$(info_email{#$TIME2#})"/>
		<postfield name="info_groupid" value="$(info_groupid{#$TIME2#})"/>
		<postfield name="info_modelid" value="$(info_modelid{#$TIME2#})"/>
		<postfield name="info_regdate" value="$(info_regdate{#$TIME2#})"/>
		<postfield name="info_lastdate" value="$(info_lastdate{#$TIME2#})"/>
		<postfield name="info_regip" value="$(info_regip{#$TIME2#})"/>
		<postfield name="info_lastip" value="$(info_lastip{#$TIME2#})"/>
		<postfield name="info_loginnum" value="$(info_loginnum{#$TIME2#})"/>
		<postfield name="info_point" value="$(info_point{#$TIME2#})"/>
		<postfield name="info_amount" value="$(info_amount{#$TIME2#})"/>
		<postfield name="info_qianming" value="$(info_qianming{#$TIME2#})"/>
		{#foreach from=$list.field item=detail#}
			{#if $detail.type == 'datetime'#}
				<postfield name="detail_date_{#$detail.field#}" value="$(detail_date_{#$detail.field#}{#$TIME2#})"/>
			{#else#}
				<postfield name="detail_{#$detail.field#}" value="$(detail_{#$detail.field#}{#$TIME2#})"/>
			{#/if#}
		{#/foreach#}		
		<postfield name="info_vip" value="$(info_vip{#$TIME2#})"/>
		<postfield name="info_overduedate" value="$(info_overduedate{#$TIME2#})"/>
		<postfield name="password" value="$(password{#$TIME2#})"/>
		<postfield name="dosubmit" value="1"/>
		</go> </anchor>
	{#/if#}
	
	{#form set="按钮|名称:dosubmit,值:提交保存" notvs="1"#}
	{#form set="尾" notvs="1"#}<br/>
{#foreachelse#}
	没有任何会员。<br/>
{#/foreach#}


{#include file="admin/footer.tpl"#}
{#include file="common/footer.tpl"#}
