{#$__seo_head="
	<link type='text/css' rel='stylesheet' href='{#$smarty.const.CSS_PATH#}v2_tongyong.css' />
"#}
{#include file="common/header.tpl" title="修改个人信息"#}

<div class="daohang">
<a href="{#kuaifan getlinks='vs|sid'#}&amp;m=huiyuan&amp;c=index">返回会员中心</a>
</div>

<div class="pnpage">
{#form set="头" notvs="1"#}
昵称: <br/>
{#form set="输入框|名称:'info_nickname{#$TIME2#}'" data_value=$huiyuan_val.nickname#}<br/>
签名:(<a href="{#kuaifan getlink='a'#}&amp;a=qianming">说明</a>) <br/>
{#form set="输入框|名称:'info_qianming{#$TIME2#}'" data_value=$huiyuan_val.qianming vs=1#}
{#form set="文本框|名称:'info_qianming{#$TIME2#}'" data_value=$huiyuan_val.qianming notvs=1#}<br/>

{#foreach from=$huiyuan_val.field item=detail key=k#}
	{#$detail.name#}:{#if $detail.minlength>0#}*{#/if#}{#if $detail.tips#}({#$detail.tips#}){#/if#}<br/>
	{#if $detail.type == 'text'#}
		{#form set="输入框|名称:detail_{#$detail.field#}{#$TIME2#}" data_value=$huiyuan_val.detail[$k]#}
	{#/if#}
	{#if $detail.type == 'textarea'#}
		{#form set="输入框|名称:detail_{#$detail.field#}{#$TIME2#}" data_value=$huiyuan_val.detail[$k] vs="1"#}
		{#form set="文本框|名称:detail_{#$detail.field#}{#$TIME2#}" data_value=$huiyuan_val.detail[$k] notvs="1"#}
	{#/if#}
	{#if $detail.type == 'box'#}
		{#$_box = str_replace('|',':',$detail['options'])#}
		{#$_box = str_replace('\r\n',',',$_box)#}
		{#$_box = str_replace(chr(13)+chr(10),',',$_box)#}
		{#$_box = str_replace(chr(13),',',$_box)#}
		{#$_box = str_replace(chr(10),'',$_box)#}
		{#form set="列表框|名称:detail_{#$detail.field#}{#$TIME2#}" list=$_box default=$huiyuan_val.detail[$k]#}
	{#/if#}
	{#if $detail.type == 'number'#}
		{#form set="输入框|名称:detail_{#$detail.field#}{#$TIME2#}" data_value=$huiyuan_val.detail[$k]#}
	{#/if#}
	{#if $detail.type == 'datetime'#}
		{#$_setting = string2array($detail.setting)#}
		{#form set="输入框|名称:detail_date_{#$detail.field#}{#$TIME2#}" data_value=date($_setting.format,$huiyuan_val.detail[$k])#}
	{#/if#}
	<br/>
{#/foreach#}


{#if $VS=='1'#}
	<anchor title="提交">提交修改
	<go href="{#get_link()#}" method="post" accept-charset="utf-8">
	<postfield name="info_nickname" value="$(info_nickname{#$TIME2#})"/>
	<postfield name="info_qianming" value="$(info_qianming{#$TIME2#})"/>
	{#foreach from=$huiyuan_val.field item=detail#}
		{#if $detail.type == 'datetime'#}
			<postfield name="detail_date_{#$detail.field#}" value="$(detail_date_{#$detail.field#}{#$TIME2#})"/>
		{#else#}
			<postfield name="detail_{#$detail.field#}" value="$(detail_{#$detail.field#}{#$TIME2#})"/>
		{#/if#}
	{#/foreach#}		
	<postfield name="dosubmit" value="1"/>
	</go> </anchor>
{#/if#}

{#form set="按钮|名称:dosubmit,值:提交修改" notvs="1"#}
{#form set="尾" notvs="1"#}
<br/>

注意:带*号的字段为必填资料
</div>


{#kuaifan tongji="正在" title="修改个人信息"#}
{#include file="common/footerb.html.tpl"#}
{#include file="common/footer.tpl"#}
