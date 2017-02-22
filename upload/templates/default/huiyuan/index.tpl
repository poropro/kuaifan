{#include file="common/header.tpl" title="会员中心"#}

<b>基本资料</b> <br/>
	<img src="{#kuaifan touxiang=$huiyuan.userid size='中'#}?t={#$TIME2#}"/><br/>
	{#$isvip = get_vip($huiyuan)#}
	<a href="{#kuaifan getlink='c|userid'#}&amp;c=ziliao&amp;userid={#$huiyuan.userid#}">{#$huiyuan|colorname#}</a>{#if $grouplist.icon#}<img src="{#$grouplist.icon#}"/>{#/if#}{#if $isvip#}<img src="{#$isvip.img#}" alt="{#$isvip.name#}"/>{#/if#}{#$huiyuan.indate_cn#}<br/>
	用户名: {#$huiyuan.username#}<br/>
	用户ID: {#$huiyuan.userid#}<br/>
	{#if $huiyuan.email#}邮箱:{#$huiyuan.email#}<br/>{#/if#}
	会员组: <a href="{#kuaifan getlink='c'#}&amp;c=zu">{#$grouplist.name#}</a><br/>
	账户余额: {#$huiyuan.amount#}{#$KUAIFAN.amountname#}, {#$huiyuan.point#}积分<br/>
	------------- <br/>
{#$link = get_link('m|c|a')#}
<b>管理中心</b> <br/>
	<a href="{#$link#}&amp;m=neirong&amp;c=guanli&amp;a=fabu"><img src="{#$smarty.const.IMG_PATH#}icon/m_2.png"/>在线发布信息</a><br/>
	<a href="{#$link#}&amp;m=neirong&amp;c=guanli&amp;a=guanli"><img src="{#$smarty.const.IMG_PATH#}icon/m_3.png"/>我发布的信息管理</a><br/>
	<a href="{#$link#}&amp;m=neirong&amp;c=guanli&amp;a=shoucang"><img src="{#$smarty.const.IMG_PATH#}icon/addcollect.png"/>我的收藏夹</a><br/>
	------------- <br/>

<b>账户管理</b> <br/>
	<a href="{#$link#}&amp;m=dingdan&amp;c=chongzhi"><img src="{#$smarty.const.IMG_PATH#}icon/m_4.png"/>在线充值</a><br/>
	<a href="{#$link#}&amp;m=dingdan&amp;c=index"><img src="{#$smarty.const.IMG_PATH#}icon/m_8.png"/>订单记录</a><br/>
	<a href="{#$link#}&amp;m=dingdan&amp;c=jilu"><img src="{#$smarty.const.IMG_PATH#}icon/table-information.png"/>账户记录</a><br/>
	<a href="{#$link#}&amp;m=dingdan&amp;c=duihuan"><img src="{#$smarty.const.IMG_PATH#}icon/coins_add.png"/>积分购买/兑换</a><br/>
	------------- <br/>
	
<b>短信息</b> <br/>
	<a href="{#$link#}&amp;m=xinxi&amp;c=fasong"><img src="{#$smarty.const.IMG_PATH#}icon/m_9.png"/>发送短信息</a><br/>
	<a href="{#$link#}&amp;m=xinxi&amp;c=shoujian"><img src="{#$smarty.const.IMG_PATH#}icon/m_11.png"/>收件箱</a>{#$new_arr.shouxin#}<br/>
	<a href="{#$link#}&amp;m=xinxi&amp;c=fajian"><img src="{#$smarty.const.IMG_PATH#}icon/m_10.png"/>发件箱</a><br/>
	<a href="{#$link#}&amp;m=xinxi&amp;c=xitong"><img src="{#$smarty.const.IMG_PATH#}icon/lightbulb.png"/>系统信息</a>{#$new_arr.xitong#}<br/>
	------------- <br/>
	
{#$link = get_link('c|a')#}
<b>账号管理</b> <br/>
	<a href="{#$link#}&amp;c=zhanghao&amp;a=xinxi"><img src="{#$smarty.const.IMG_PATH#}icon/user_edit.png"/>修改个人信息</a><br/>
	<a href="{#$link#}&amp;c=zhanghao&amp;a=touxiang"><img src="{#$smarty.const.IMG_PATH#}icon/vcard.png"/>修改头像</a><br/>
	<a href="{#$link#}&amp;c=zhanghao&amp;a=mima"><img src="{#$smarty.const.IMG_PATH#}icon/icon_key.png"/>修改邮箱/密码</a><br/>
	<a href="{#$link#}&amp;c=zhanghao&amp;a=shengji"><img src="{#$smarty.const.IMG_PATH#}icon/upload.png"/>会员自助升级</a><br/>
	<a href="{#$link#}&amp;c=zhanghao&amp;a=tuichu"><img src="{#$smarty.const.IMG_PATH#}icon/old-edit-redo.png"/>安全退出</a><br/>



{#kuaifan tongji="在" title="会员中心"#}
{#include file="common/footerb.tpl"#}
{#include file="common/footer.tpl"#}
