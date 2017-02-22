{#include file="common/header.tpl" title_top="1" title="评论配置"#}
[评论配置]<br/>
<a href="{#kuaifan getlink='config'#}">返回列表</a><br/>
-------------<br/>

	{#form set="头" notvs="1"#}

	是否允许游客评论:{#form set="列表框|名称:pinglun_guest{#$TIME2#}" list="是:1,否:0" default="0" data_value=$setting.pinglun_guest#}<br/>
	
	是否需要审核:{#form set="列表框|名称:pinglun_check{#$TIME2#}" list="是:1,否:0" default="0" data_value=$setting.pinglun_check#}<br/>

	评论积分奖励:{#form set="输入框|名称:'pinglun_add_point{#$TIME2#}',值:'{#$setting.pinglun_add_point#}',宽:5"#}<br/>
	
	评论被删除扣除积分:{#form set="输入框|名称:'pinglun_del_point{#$TIME2#}',值:'{#$setting.pinglun_del_point#}',宽:5"#}<br/>
	
	会员可以删除自己的评论:{#form set="列表框|名称:pinglun_guest_del{#$TIME2#}" list="是:1,否:0" default="0" data_value=$setting.pinglun_guest_del#}<br/>
	
	会员评论支持上传附件格式:{#form set="输入框|名称:pinglun_format{#$TIME2#}" data_value=$setting.pinglun_format#}<br/>

	最多支持上传附件数量:{#form set="输入框|名称:'pinglun_format_num{#$TIME2#}',值:'{#$setting.pinglun_format_num#}',宽:5"#}<br/>

	评论支持@+昵称+空格提醒:{#form set="列表框|名称:pinglun_auser{#$TIME2#}" list="是:1,否:0" default="0" data_value=$setting.pinglun_auser#}<br/>
	
	评论使用ubb标签转换:{#form set="列表框|名称:pinglun_ubb{#$TIME2#}" list="是:1,否:0" default="0" data_value=$setting.pinglun_ubb#}<br/>
	
	评论显示可识别浏览器:{#form set="列表框|名称:pinglun_browser{#$TIME2#}" list="是:1,否:0" default="0" data_value=$setting.pinglun_browser#}<br/>
	

	{#kuaifan vs="1" set="
	  <anchor title='提交'>[提交修改]
	  <go href='{#get_link()#}' method='post' accept-charset='utf-8'>
	  <postfield name='pinglun_guest' value='$(pinglun_guest{#$TIME2#})'/>
	  <postfield name='pinglun_check' value='$(pinglun_check{#$TIME2#})'/>
	  <postfield name='pinglun_add_point' value='$(pinglun_add_point{#$TIME2#})'/>
	  <postfield name='pinglun_del_point' value='$(pinglun_del_point{#$TIME2#})'/>
	  <postfield name='pinglun_guest_del' value='$(pinglun_guest_del{#$TIME2#})'/>
	  <postfield name='pinglun_format' value='$(pinglun_format{#$TIME2#})'/>
	  <postfield name='pinglun_format_num' value='$(pinglun_format_num{#$TIME2#})'/>
	  <postfield name='pinglun_auser' value='$(pinglun_auser{#$TIME2#})'/>
	  <postfield name='pinglun_ubb' value='$(pinglun_ubb{#$TIME2#})'/>
	  <postfield name='pinglun_browser' value='$(pinglun_browser{#$TIME2#})'/>
	  <postfield name='dosubmit' value='1'/>
	  </go> </anchor>
	"#}
	
	{#form set="按钮|名称:dosubmit,值:提交修改" notvs="1"#}
	{#form set="尾" notvs="1"#}
	<br/>
	注明:多个上传附件格式使用符号“|”隔开。<br/>

{#include file="admin/footer.tpl"#}
{#include file="common/footer.tpl"#}
