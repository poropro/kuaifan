{#include file="common/header.tpl" title_top="1" title="添加会员"#}

{#if $smarty.get.mo#}
	<a href="{#kuaifan getlink='a|mo'#}">会员</a>&gt;<a href="{#kuaifan getlink='mo'#}">选模型</a>&gt;添加会员<br/>
	【基本信息】<br/>
	{#form set="头" notvs="1"#}
	用户ID: {#form set="输入框|名称:info_userid{#$TIME2#},宽:10" data_value=0#}<br/>
	用户名: {#form set="输入框|名称:info_username{#$TIME2#}"#}<br/>
	昵称: {#form set="输入框|名称:info_nickname{#$TIME2#}"#}<br/>
	邮箱: {#form set="输入框|名称:info_email{#$TIME2#}"#}<br/>
	会员组: {#form set="列表框|名称:info_groupid{#$TIME2#}" list=array_flip($grouplist) default=$bypoint#}<br/>
	会员模型: {#$modellistarr[$smarty.get.mo]['title']#}<br/>
	注册时间: {#form set="输入框|名称:info_regdate{#$TIME2#}" data_value=date('Y-m-d H:i:s')#}<br/>
	最后登录: {#form set="输入框|名称:info_lastdate{#$TIME2#}"#}<br/>
	注册IP: {#form set="输入框|名称:info_regip{#$TIME2#}" data_value=getip()#}<br/>
	最后IP: {#form set="输入框|名称:info_lastip{#$TIME2#}"#}<br/>
	登录次数: {#form set="输入框|名称:info_loginnum{#$TIME2#}" data_value=0#}<br/>
	积分点数: {#form set="输入框|名称:info_point{#$TIME2#}" data_value=$point#}<br/>
	{#$KUAIFAN.amountname#}总数: {#form set="输入框|名称:info_amount{#$TIME2#}" data_value=$amount#}<br/>
	{#if $field#}【详细信息】<br/>{#/if#}
	{#foreach from=$field item=detail key=k#}
		{#$detail.name#}:
		{#if $detail.type == 'text'#}
			{#form set="输入框|名称:detail_{#$detail.field#}{#$TIME2#}" data_value=$detail.defaultvalue#}
		{#/if#}
		{#if $detail.type == 'textarea'#}
			{#form set="输入框|名称:detail_{#$detail.field#}{#$TIME2#}" data_value=$detail.defaultvalue#}
			{#form set="文本框|名称:detail_{#$detail.field#}{#$TIME2#}" data_value=$detail.defaultvalue#}
		{#/if#}
		{#if $detail.type == 'box'#}
			{#$_box = str_replace('|',':',$detail['options'])#}
			{#$_box = str_replace('\r\n',',',$_box)#}
			{#$_box = str_replace(chr(13)+chr(10),',',$_box)#}
			{#$_box = str_replace(chr(13),',',$_box)#}
			{#$_box = str_replace(chr(10),'',$_box)#}
			{#form set="列表框|名称:detail_{#$detail.field#}{#$TIME2#}" list=$_box default=$detail.defaultvalue#}
		{#/if#}
		{#if $detail.type == 'number'#}
			{#form set="输入框|名称:detail_{#$detail.field#}{#$TIME2#}" data_value=$detail.defaultvalue#}
		{#/if#}
		{#if $detail.type == 'datetime'#}
			{#$_setting = string2array($detail.setting)#}
			{#if $detail.defaultvalue#}
				{#form set="输入框|名称:detail_date_{#$detail.field#}{#$TIME2#}" data_value=$detail.defaultvalue#}
			{#else#}
				{#form set="输入框|名称:detail_date_{#$detail.field#}{#$TIME2#}" data_value=date($_setting.format)#}
			{#/if#}
		{#/if#}
		<br/>
	{#/foreach#}
	{#if $VS=='1'#}
		<anchor title="提交">[提交添加]
		<go href="{#get_link()#}" method="post" accept-charset="utf-8">
		<postfield name="info_userid" value="$(info_userid{#$TIME2#})"/>
		<postfield name="info_username" value="$(info_username{#$TIME2#})"/>
		<postfield name="info_nickname" value="$(info_nickname{#$TIME2#})"/>
		<postfield name="info_email" value="$(info_email{#$TIME2#})"/>
		<postfield name="info_groupid" value="$(info_groupid{#$TIME2#})"/>
		<postfield name="info_modelid" value="{#$smarty.get.mo#}"/>
		<postfield name="info_regdate" value="$(info_regdate{#$TIME2#})"/>
		<postfield name="info_lastdate" value="$(info_lastdate{#$TIME2#})"/>
		<postfield name="info_regip" value="$(info_regip{#$TIME2#})"/>
		<postfield name="info_lastip" value="$(info_lastip{#$TIME2#})"/>
		<postfield name="info_loginnum" value="$(info_loginnum{#$TIME2#})"/>
		<postfield name="info_point" value="$(info_point{#$TIME2#})"/>
		<postfield name="info_amount" value="$(info_amount{#$TIME2#})"/>
		{#foreach from=$field item=detail#}
			{#if $detail.type == 'datetime'#}
				<postfield name="detail_date_{#$detail.field#}" value="$(detail_date_{#$detail.field#}{#$TIME2#})"/>
			{#else#}
				<postfield name="detail_{#$detail.field#}" value="$(detail_{#$detail.field#}{#$TIME2#})"/>
			{#/if#}
		{#/foreach#}
		<postfield name="dosubmit" value="1"/>
		</go> </anchor>
	{#/if#}
	
	{#form set="隐藏|名称:info_modelid,值:{#$smarty.get.mo#}" notvs="1"#}
	{#form set="按钮|名称:dosubmit,值:提交添加" notvs="1"#}
	{#form set="尾" notvs="1"#}<br/>
	注意：“用户ID”填写0、留空或者填写的已存在则自动生成。<br/>
{#else#}
	<a href="{#kuaifan getlink='a|mo'#}">会员</a>&gt;选模型<br/>
	[会员模型]<br/>
	{#foreach from=$modellistarr item=moxing#}
		<a href="{#kuaifan getlink='mo'#}&amp;mo={#$moxing.id#}">{#$moxing.title#}</a><br/>
	{#/foreach#}
{#/if#}


{#include file="admin/footer.tpl"#}
{#include file="common/footer.tpl"#}
