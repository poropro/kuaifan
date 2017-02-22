{#$__seo_head="
	<link type='text/css' rel='stylesheet' href='{#$smarty.const.CSS_PATH#}v2_tongyong.css' />
"#}
{#include file="common/header.tpl" title="{#$huiyuan.nickname#} - 会员资料"#}

<div class="daohang">
{#if $smarty.get.go_url#}
	<a href="{#$smarty.get.go_url|goto_url#}">返回来源地址</a>
{#else#}
	<a href="{#kuaifan getlinks='vs|sid'#}&amp;m=huiyuan&amp;c=index">返回会员中心</a>
{#/if#}
</div>

<div class="pnpage">
<img src="{#kuaifan touxiang=$huiyuan.userid size='中'#}?t={#$TIME2#}"/><br/>
<a href="{#kuaifan getlink='m|c|username'#}&amp;m=xinxi&amp;c=fasong&amp;username={#$huiyuan.username#}">给他发送信息&gt;&gt;</a><br/>
{#$isvip = get_vip($huiyuan)#}
昵称: {#$huiyuan|colorname#}{#if $grouplist.icon#}<img src="{#$grouplist.icon#}"/>{#/if#}{#if $isvip#}<img src="{#$isvip.img#}" alt="{#$isvip.name#}"/>{#/if#}({#$huiyuan.indate_cn#})<br/>
用户名: {#$huiyuan.username#}<br/>
用户ID: {#$huiyuan.userid#}<br/>
会员组: <a href="{#kuaifan getlink='c'#}&amp;c=zu">{#$grouplist.name#}</a><br/>
会员模型: {#$modellistarr.title#}<br/>
最后在线时间: {#$huiyuan.indate|date_format:"%Y-%m-%d %H:%M:%S"#}<br/>
{#if $huiyuan.qianming#}签名: {#$huiyuan|qianming#}<br/>{#/if#}
</div>

{#if $huiyuan.field#}<div class="m_title">详细信息</div>{#/if#}
<div class="pnpage">
{#foreach from=$huiyuan.field item=detail key=k#}
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
	 <br/>
{#/foreach#}
</div>

<div class="m_title">最新发布</div>
<div class="pnpage">
{#kuaifan_neirong_fabu set="列表名:lists,会员名:{#$huiyuan.username#},状态:1,显示数目:7,标题长度:15,填补字符:..."#}
{#foreach from=$lists item=list#}
	<a href="{#$list.url#}">{#$list.title#}</a>({#$list.inputtime|dateaway#})<br/>
{#foreachelse#}
	Ta没有发布任何信息。<br/>
{#/foreach#}
</div>

{#$_urlarr.m='neirong'#}
{#$_urlarr.c='show'#}
{#$_urlarr.sid=$smarty.get.sid#}
{#$_urlarr.vs=$smarty.get.vs#}
<div class="m_title">最新回复</div>
<div class="pnpage">
{#kuaifan_neirong_pinglun set="列表名:lists,会员名:{#$huiyuan.username#},显示数目:7,标题长度:20,填补字符:..."#}
{#foreach from=$lists item=list#}
	{#$_urlarr.catid=$list.commentid_.1#}
	{#$_urlarr.id=$list.commentid_.2#}
	<a href="{#url_rewrite('KF_neirongshow', $_urlarr)#}">{#$list.content#}</a>({#$list.creat_at|dateaway#})<br/>
{#foreachelse#}
	Ta没有评论任何内容。<br/>
{#/foreach#}
</div>

<div class="m_title">会员动态</div>
<div class="pnpage">
{#kuaifan_tongji set="列表名:lists,ID列表:idlist,会员ID:GET[userid],显示数目:7,标题长度:15,填补字符:..."#}
{#foreach from=$lists item=list#}
	{#$list.type#}<a href="{#$list.url#}&amp;sid={#$SID#}&amp;vs={#$VS#}">{#$list.title#}</a>({#$list.time|dateaway#})<br/>
{#foreachelse#}
	Ta没有任何动态。<br/>
{#/foreach#}
</div>


{#kuaifan tongji="正在查看" title="{#$huiyuan.nickname#}的资料"#}
{#include file="common/footerb.html.tpl"#}
{#include file="common/footer.tpl"#}
