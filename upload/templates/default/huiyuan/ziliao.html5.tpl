{#$__seo_head="
	<link type='text/css' rel='stylesheet' href='{#$smarty.const.CSS_PATH#}v3_list.css' />
	<link type='text/css' rel='stylesheet' href='{#$smarty.const.CSS_PATH#}v3_huiyuan.css' />
	<script type='text/javascript' src='{#$smarty.const.JS_PATH#}jquery_1.4.2.js'></script>
	<script type='text/javascript' src='{#$smarty.const.JS_PATH#}jquery.alert.js'></script>
"#}
{#include file="common/header.tpl" title="{#$huiyuan.nickname#} - 会员资料"#}

<div class="text-nav top_newsbg">
<a class="textn-left" href="{#if $smarty.get.go_url#}{#$smarty.get.go_url|goto_url#}{#else#}{#kuaifan getlinks='m|sid|vs'#}{#/if#}"></a>
会员资料
<a class="textn-right" href="{#kf_url('index')#}"></a>
</div>



<div class="user-infobox">
	{#$isvip = get_vip($huiyuan)#}
	<div class="bg-top">
		<div class="b-name">
			<a href="{#kuaifan getlink='c|userid'#}&amp;c=ziliao&amp;userid={#$huiyuan.userid#}">{#$huiyuan|colorname#}</a>
			{#if $grouplist.icon#}<img src="{#$grouplist.icon#}"/>{#/if#}
			{#if $isvip#}<img src="{#$isvip.img#}" alt="{#$isvip.name#}"/>{#/if#}
		</div>
	</div>
	<div class="bg-bottom">
		<div class="b-zu">
			用户名:{#$huiyuan.username#}(ID:{#$huiyuan.userid#})<br/>
			会员组:<a href="{#kuaifan getlink='c'#}&amp;c=zu">{#$grouplist.name#}</a>
		</div>
	</div>
	<div class="user-img"><img src="{#kuaifan touxiang=$huiyuan.userid size='中'#}?t={#$TIME2#}"/></div>
	<div class="user-indate">{#$huiyuan.indate_cn#}</div>
</div>


<div class="user-title">基本资料</div>
<div class="user-pad15">
	<ul class="user-titbox">
		<li class="jb"><a>会员模型</a><div>{#$modellistarr.title#}</div></li>
		<li class="jb"><a>最后在线</a><div>{#$huiyuan.indate|date_format:"%Y-%m-%d %H:%M:%S"#}</div></li>
		{#if $huiyuan.qianming#}<li class="qm">签名:{#$huiyuan|qianming#}</li>{#/if#}
	</ul>
	<ul class="user-titbox user-msg">
		<li class="jb"><a href="{#kuaifan getlink='m|c|username'#}&amp;m=xinxi&amp;c=fasong&amp;username={#$huiyuan.username#}">给他发送信息&gt;&gt;</a></li>
	</ul>
</div>


{#if $huiyuan.field#}
	<div class="user-title">详细信息</div>
	<div class="user-pad15">
		<ul class="user-titbox">
		{#foreach from=$huiyuan.field item=detail key=k#}
			<li class="qm">
			{#$detail.name#}:
			{#if $detail.type == 'box' && $detail.outputtype == '1'#}
				{#$_box = str_replace('|','=>',$detail['options'])#}
				{#$_box = str_replace('\r\n',',',$_box)#}
				{#$_box = str_replace(chr(13)+chr(10),',',$_box)#}
				{#$_box = str_replace(chr(13),',',$_box)#}
				{#$_box = str_replace(chr(10),'',$_box)#}
				{#$_box = string2array("array({#$_box#})")#}
				{#$_box = array_flip($_box)#}
				{#$_box[$huiyuan.detail[$k]]#}
			{#elseif $detail.type == 'datetime'#}
				{#$_setting = string2array($detail.setting)#}
				{#date($_setting.format,$huiyuan.detail[$k])#}
			{#else#}
				{#$huiyuan.detail[$k]#}
			{#/if#}
			</li>
		{#/foreach#}
		</ul>
	</div>
{#/if#}

<div class="user-title">最新发布</div>
<div class="user-pad15">
	{#kuaifan_neirong_fabu set="列表名:lists,会员名:{#$huiyuan.username#},状态:1,显示数目:7,标题长度:20,填补字符:..."#}
	<ul class="user-titbox">
		{#foreach from=$lists item=list#}
			<li class="nc">
				<div>{#$list.inputtime|dateaway#}</div>
				<a href="{#$list.url#}">{#$list.title#}</a>
			</li>
		{#foreachelse#}
			<li class="qm">Ta没有发布任何信息。</li>
		{#/foreach#}
	</ul>
</div>

{#$_urlarr.m='neirong'#}
{#$_urlarr.c='show'#}
{#$_urlarr.sid=$smarty.get.sid#}
{#$_urlarr.vs=$smarty.get.vs#}
<div class="user-title">最新回复</div>
<div class="user-pad15">
	{#kuaifan_neirong_pinglun set="列表名:lists,会员名:{#$huiyuan.username#},显示数目:7,标题长度:20,填补字符:..."#}
	<ul class="user-titbox">
		{#foreach from=$lists item=list#}
			<li class="nc">
				{#$_urlarr.catid=$list.commentid_.1#}
				{#$_urlarr.id=$list.commentid_.2#}
				<div>{#$list.creat_at|dateaway#}</div>
				<a href="{#url_rewrite('KF_neirongshow', $_urlarr)#}">{#$list.content#}</a>
			</li>
		{#foreachelse#}
			<li class="qm">Ta没有评论任何内容。</li>
		{#/foreach#}
	</ul>

</div>


<div class="user-title">会员动态</div>
<div class="user-pad15">
	{#kuaifan_tongji set="列表名:lists,ID列表:idlist,会员ID:GET[userid],显示数目:7,标题长度:15,填补字符:..."#}
	<ul class="user-titbox">
		{#foreach from=$lists item=list#}
			<li class="nc">
				<div>{#$list.time|dateaway#}</div>
				<a href="{#$list.url#}&amp;sid={#$SID#}&amp;vs={#$VS#}">{#$list.type#}{#$list.title#}</a>
			</li>
		{#foreachelse#}
			<li class="qm">Ta没有任何动态。</li>
		{#/foreach#}
	</ul>
</div>
<div class="user-bottom"></div>


{#kuaifan tongji="正在查看" title="{#$huiyuan.nickname#}的资料"#}
{#include file="common/footerb.html5.tpl"#}
{#include file="common/footer.tpl"#}
