{#$__seo_head="
	<link type='text/css' rel='stylesheet' href='{#$smarty.const.CSS_PATH#}v3_list.css' />
	<link type='text/css' rel='stylesheet' href='{#$smarty.const.CSS_PATH#}v3_huiyuan.css' />
	<script type='text/javascript' src='{#$smarty.const.JS_PATH#}jquery_1.4.2.js'></script>
	<script type='text/javascript' src='{#$smarty.const.JS_PATH#}jquery.alert.js'></script>
"#}
{#include file="common/header.tpl" title="会员中心"#}

<div class="text-nav top_newsbg">
<a class="textn-left" href="{#if $smarty.get.go_url#}{#$smarty.get.go_url|goto_url#}{#else#}javascript:history.back(1);{#/if#}"></a>
会员中心
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
		{#if $huiyuan.email#}<li class="jb"><a>邮箱</a><div>{#$huiyuan.email#}</div></li>{#/if#}
		<li class="jb"><a>{#$KUAIFAN.amountname#}</a><div>{#$huiyuan.amount#}</div></li>
		<li class="jb"><a>积分</a><div>{#$huiyuan.point#}</div></li>
	</ul>
</div>


{#$link = get_link('vs|sid','',1)#}
<div class="user-title">管理中心</div>
<div class="user-pad15">
	<ul class="user-titbox">
		<li><a href="{#$link#}&amp;m=neirong&amp;c=guanli&amp;a=fabu"><img src="{#$smarty.const.IMG_PATH#}icon/m_2.png"/>在线发布信息</a></li>
		<li><a href="{#$link#}&amp;m=neirong&amp;c=guanli&amp;a=guanli"><img src="{#$smarty.const.IMG_PATH#}icon/m_3.png"/>我发布的信息管理</a></li>
		<li><a href="{#$link#}&amp;m=neirong&amp;c=guanli&amp;a=shoucang"><img src="{#$smarty.const.IMG_PATH#}icon/addcollect.png"/>我的收藏夹</a></li>
	</ul>
</div>


<div class="user-title">账户管理</div>
<div class="user-pad15">
	<ul class="user-titbox">
		<li><a href="{#$link#}&amp;m=dingdan&amp;c=chongzhi"><img src="{#$smarty.const.IMG_PATH#}icon/m_4.png"/>在线充值</a></li>
		<li><a href="{#$link#}&amp;m=dingdan&amp;c=index"><img src="{#$smarty.const.IMG_PATH#}icon/m_8.png"/>订单记录</a></li>
		<li><a href="{#$link#}&amp;m=dingdan&amp;c=jilu"><img src="{#$smarty.const.IMG_PATH#}icon/table-information.png"/>账户记录</a></li>
		<li><a href="{#$link#}&amp;m=dingdan&amp;c=duihuan"><img src="{#$smarty.const.IMG_PATH#}icon/coins_add.png"/>积分购买/兑换</a></li>
	</ul>
</div>

<div class="user-title">短信息</div>
<div class="user-pad15">
	<ul class="user-titbox">
		<li><a href="{#$link#}&amp;m=xinxi&amp;c=fasong"><img src="{#$smarty.const.IMG_PATH#}icon/m_9.png"/>发送短信息</a></li>
		<li><a href="{#$link#}&amp;m=xinxi&amp;c=shoujian"><img src="{#$smarty.const.IMG_PATH#}icon/m_11.png"/>收件箱</a><div>{#$new_arr.shouxin#}</div></li>
		<li><a href="{#$link#}&amp;m=xinxi&amp;c=fajian"><img src="{#$smarty.const.IMG_PATH#}icon/m_10.png"/>发件箱</a></li>
		<li><a href="{#$link#}&amp;m=xinxi&amp;c=xitong"><img src="{#$smarty.const.IMG_PATH#}icon/lightbulb.png"/>系统信息</a><div>{#$new_arr.xitong#}</div></li>
	</ul>
</div>

<div class="user-title">账号管理</div>
<div class="user-pad15">
	<ul class="user-titbox">
		<li><a href="{#$link#}&amp;m=huiyuan&amp;c=zhanghao&amp;a=xinxi"><img src="{#$smarty.const.IMG_PATH#}icon/user_edit.png"/>修改个人信息</a></li>
		<li><a href="{#$link#}&amp;m=huiyuan&amp;c=zhanghao&amp;a=touxiang"><img src="{#$smarty.const.IMG_PATH#}icon/vcard.png"/>修改头像</a></li>
		<li><a href="{#$link#}&amp;m=huiyuan&amp;c=zhanghao&amp;a=mima"><img src="{#$smarty.const.IMG_PATH#}icon/icon_key.png"/>修改邮箱/密码</a></li>
		<li><a href="{#$link#}&amp;m=huiyuan&amp;c=zhanghao&amp;a=shengji"><img src="{#$smarty.const.IMG_PATH#}icon/upload.png"/>会员自助升级</a></li>
		<li><a href="{#$link#}&amp;m=huiyuan&amp;c=zhanghao&amp;a=tuichu"><img src="{#$smarty.const.IMG_PATH#}icon/old-edit-redo.png"/>安全退出</a></li>
	</ul>
</div>
<div class="user-bottom"></div>



{#kuaifan tongji="在" title="会员中心"#}
{#include file="common/footerb.html5.tpl"#}
{#include file="common/footer.tpl"#}
