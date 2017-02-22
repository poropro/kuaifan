{#include file="common/header.tpl" title_top="1" title="修改栏目 - 投稿/权限/收费"#}

[投稿/权限/收费]<br/>
<a href="{#kuaifan getlink='set'#}&amp;edit={#$lanmudb.id#}">返回</a><br/>
<a href="{#kuaifan getlink='set'#}">返回列表</a><br/>
-------------<br/>
{#form set="头" notvs="1"#}

【访问权限】:<br/>
	{#$n=1#}{#foreach from=$zudata item=lists#}
		{#$n++#}.{#$lists.name#}:{#form set="列表框|名称:priv_visit_{#$lists.groupid#}{#$TIME2#}" list="允许:0,禁止:1" default=$priv['visit'][$lists.groupid]#}<br/>		
	{#foreachelse#}
		没有任何会员组无法设置此项<br/>
	{#/foreach#}
	<b>@权限应用到子栏目:</b>{#form set="列表框|名称:visit_zi{#$TIME2#}" list="否:0,是:1" default='0'#}<br/><br/>
	
【投稿权限】:<br/>
	{#$n=1#}{#foreach from=$zudata item=lists#}
		{#$n++#}.{#$lists.name#}:{#form set="列表框|名称:priv_add_{#$lists.groupid#}{#$TIME2#}" list="禁止:0,允许:1" default=$priv['add'][$lists.groupid]#}<br/>
	{#foreachelse#}
		没有任何会员组无法设置此项<br/>
	{#/foreach#}
	<b>@权限应用到子栏目:</b>{#form set="列表框|名称:add_zi{#$TIME2#}" list="否:0,是:1" default='0'#}<br/><br/>

【投稿不需审核】:<br/>
	{#$n=1#}{#foreach from=$zudata item=lists#}
		{#$n++#}.{#$lists.name#}:{#form set="列表框|名称:priv_shen_{#$lists.groupid#}{#$TIME2#}" list="按会员组:0,是:1,否:2" default=$priv['shen'][$lists.groupid]#}<br/>
	{#foreachelse#}
		没有任何会员组无法设置此项<br/>
	{#/foreach#}
	<b>@权限应用到子栏目:</b>{#form set="列表框|名称:shen_zi{#$TIME2#}" list="否:0,是:1" default='0'#}<br/><br/>
	
	
【其他设置】:<br/>
	[①收费设置]:<br/>
	1.投稿奖励:{#form set="输入框|名称:setting_presentpoint{#$TIME2#},宽:5" data_value=$lanmudbset.presentpoint#}点<br/>
	会员在此栏目发表信息时可以得到的点数，为负数时减积分。<br/>

	2.默认收取:{#form set="输入框|名称:setting_defaultchargepoint{#$TIME2#},宽:5" data_value=$lanmudbset.defaultchargepoint#}
	{#form set="列表框|名称:setting_paytype{#$TIME2#}" list="积分:0,'{#$KUAIFAN.amountname#}':1" default=$lanmudbset.paytype#}<br/>
	会员在此栏目下查看信息时，该信息默认的收费，当这里设置为0时，发布页设置了，可只对设置的文章收费。<br/>

	3.重复收费设置:{#form set="输入框|名称:setting_repeatchargedays{#$TIME2#},宽:5" data_value=$lanmudbset.repeatchargedays#}<br/>
	请填写整数，表示多少天内不重复收费，最小设置为1天。<br/>
	
	
	[②审后设置]:<br/>
	4.通过审核后禁止修改:{#form set="列表框|名称:setting_shenhehou{#$TIME2#}" list="禁止修改:0,可修改:1" default=$lanmudbset.shenhehou#}<br/>
	建议:论坛选可修改，非论坛选禁止修改。<br/>
	
	5.通过审核后禁止删除:{#form set="列表框|名称:setting_shenheshan{#$TIME2#}" list="禁止删除:0,可删除:1" default=$lanmudbset.shenheshan#}<br/>
	建议:论坛选可删除，非论坛选禁止删除。<br/>
	
	
	[③评论设置]:<br/>
	6.是否允许游客评论:{#form set="列表框|名称:setting_pinglun_guest{#$TIME2#}" list="按评论配置:0,是:1,否:2" default=$lanmudbset.pinglun_guest#}<br/>
	
	7.是否需要审核:{#form set="列表框|名称:setting_pinglun_check{#$TIME2#}" list="按评论配置:0,是:1,否:2" default=$lanmudbset.pinglun_check#}<br/>
	
	8.评论积分奖励:{#form set="输入框|名称:setting_pinglun_add_point{#$TIME2#},宽:5" data_value=$lanmudbset.pinglun_add_point#}<br/>
	
	9.评论被删除扣除积分:{#form set="输入框|名称:setting_pinglun_del_point{#$TIME2#},宽:5" data_value=$lanmudbset.pinglun_del_point#}<br/>

	10.会员可以删除自己的评论:{#form set="列表框|名称:setting_pinglun_guest_del{#$TIME2#}" list="按评论配置:0,是:1,否:2" data_value=$lanmudbset.pinglun_guest_del#}<br/>
	
	11.会员评论支持上传附件格式:{#form set="输入框|名称:setting_pinglun_format{#$TIME2#}" data_value=$lanmudbset.pinglun_format#}<br/>

	12.最多支持上传附件数量:{#form set="输入框|名称:'setting_pinglun_format_num{#$TIME2#}',值:'{#$lanmudbset.pinglun_format_num#}',宽:5"#}<br/>

	13.评论支持@+昵称+空格提醒:{#form set="列表框|名称:setting_pinglun_auser{#$TIME2#}" list="按评论配置:0,是:1,否:2" data_value=$lanmudbset.pinglun_auser#}<br/>
	
	14.评论使用ubb标签转换:{#form set="列表框|名称:setting_pinglun_ubb{#$TIME2#}" list="按评论配置:0,是:1,否:2" data_value=$lanmudbset.pinglun_ubb#}<br/>
	
	(第6-14项留空按<a href="{#kuaifan getlink='a|set'#}&amp;a=pinglun&amp;config=1">评论配置</a>)<br/>
	
	[④限时设置]:<br/>
	15.投稿限时:{#form set="输入框|名称:setting_xianshi_tg{#$TIME2#},宽:5" data_value=$lanmudbset.xianshi_tg#}(单位:秒,填0不限)<br/>
	
	16.评论限时:{#form set="输入框|名称:setting_xianshi_hf{#$TIME2#},宽:5" data_value=$lanmudbset.xianshi_hf#}(单位:秒,填0不限)<br/>
	
	17.注册多久可进入:{#form set="输入框|名称:setting_xianshi_zcsj{#$TIME2#},宽:5" data_value=$lanmudbset.xianshi_zcsj#}(单位:分,填0不限)<br/>
	
	18.注册多久可投稿:{#form set="输入框|名称:setting_xianshi_zctg{#$TIME2#},宽:5" data_value=$lanmudbset.xianshi_zctg#}(单位:分,填0不限)<br/>
	
	19.注册多久可评论:{#form set="输入框|名称:setting_xianshi_zcpl{#$TIME2#},宽:5" data_value=$lanmudbset.xianshi_zcpl#}(单位:分,填0不限)<br/>
	
	{#if $moxingdb.type=='bbs'#}	
		[⑤论坛设置]:<br/>
		20.版主ID:{#form set="输入框|名称:setting_bbs_banzhu{#$TIME2#}" data_value=$lanmudbset.bbs_banzhu#}<br/>
		多个版主用符号“|”分隔<br/>
		
		21.加精华奖励:{#form set="输入框|名称:setting_bbs_jinghua{#$TIME2#},宽:5" data_value=$lanmudbset.bbs_jinghua#}点<br/>
		信息被加精华时可以得到的点数，为负数时减积分。<br/>
		
		22.加顶置奖励:{#form set="输入框|名称:setting_bbs_dingzhi{#$TIME2#},宽:5" data_value=$lanmudbset.bbs_dingzhi#}点<br/>
		信息被加顶置时可以得到的点数，为负数时减积分。<br/>
	{#/if#}
	
	<b>@设置应用到子栏目:</b>{#form set="列表框|名称:set_zi{#$TIME2#}" list="否:0,是:1" default='0'#}<br/>

	
{#if $VS==1#}	
  <anchor title="提交">[提交保存]
  <go href="{#get_link()#}" method="post" accept-charset="utf-8">
  
  <postfield name="visit_zi" value="$(visit_zi{#$TIME2#})"/>
  <postfield name="add_zi" value="$(add_zi{#$TIME2#})"/>
  <postfield name="set_zi" value="$(set_zi{#$TIME2#})"/>
  <postfield name="shen_zi" value="$(shen_zi{#$TIME2#})"/>
  
  {#if $moxingdb.type=='bbs'#}
	<postfield name="setting_bbs_banzhu" value="$(setting_bbs_banzhu{#$TIME2#})"/>
	<postfield name="setting_bbs_jinghua" value="$(setting_bbs_jinghua{#$TIME2#})"/>
	<postfield name="setting_bbs_dingzhi" value="$(setting_bbs_dingzhi{#$TIME2#})"/>
  {#/if#}
  
  <postfield name="setting_xianshi_tg" value="$(setting_xianshi_tg{#$TIME2#})"/>
  <postfield name="setting_xianshi_hf" value="$(setting_xianshi_hf{#$TIME2#})"/>
  <postfield name="setting_xianshi_zcsj" value="$(setting_xianshi_zcsj{#$TIME2#})"/>
  <postfield name="setting_xianshi_zctg" value="$(setting_xianshi_zctg{#$TIME2#})"/>
  <postfield name="setting_xianshi_zcpl" value="$(setting_xianshi_zcpl{#$TIME2#})"/>
  
  <postfield name="setting_pinglun_guest" value="$(setting_pinglun_guest{#$TIME2#})"/>
  <postfield name="setting_pinglun_check" value="$(setting_pinglun_check{#$TIME2#})"/>
  <postfield name="setting_pinglun_add_point" value="$(setting_pinglun_add_point{#$TIME2#})"/>
  <postfield name="setting_pinglun_del_point" value="$(setting_pinglun_del_point{#$TIME2#})"/>
  <postfield name="setting_pinglun_guest_del" value="$(setting_pinglun_guest_del{#$TIME2#})"/>
  <postfield name="setting_pinglun_format" value="$(setting_pinglun_format{#$TIME2#})"/>
  <postfield name="setting_pinglun_format_num" value="$(setting_pinglun_format_num{#$TIME2#})"/>
  <postfield name="setting_pinglun_auser" value="$(setting_pinglun_auser{#$TIME2#})"/>
  <postfield name="setting_pinglun_ubb" value="$(setting_pinglun_ubb{#$TIME2#})"/>
  
  <postfield name="setting_presentpoint" value="$(setting_presentpoint{#$TIME2#})"/>
  <postfield name="setting_defaultchargepoint" value="$(setting_defaultchargepoint{#$TIME2#})"/>
  <postfield name="setting_paytype" value="$(setting_paytype{#$TIME2#})"/>
  <postfield name="setting_repeatchargedays" value="$(setting_repeatchargedays{#$TIME2#})"/>
  <postfield name="setting_shenhehou" value="$(setting_shenhehou{#$TIME2#})"/>
  <postfield name="setting_shenheshan" value="$(setting_shenheshan{#$TIME2#})"/>
	
  {#foreach from=$zudata item=lists#}
	<postfield name="priv_add_{#$lists.groupid#}" value="$(priv_add_{#$lists.groupid#}{#$TIME2#})"/>
	<postfield name="priv_shen_{#$lists.groupid#}" value="$(priv_shen_{#$lists.groupid#}{#$TIME2#})"/>
	<postfield name="priv_visit_{#$lists.groupid#}" value="$(priv_visit_{#$lists.groupid#}{#$TIME2#})"/>
  {#/foreach#}

  <postfield name="dosubmit" value="1"/>
  </go> </anchor>
{#/if#}  

{#form set="按钮|名称:dosubmit,值:提交保存" notvs="1"#}
{#form set="尾" notvs="1"#}
<br/>
注明:应用到子栏目为本次有效。<br/>


{#include file="admin/footer.tpl"#}
{#include file="common/footer.tpl"#}
