{#include file="common/header.tpl" title_top="1" title="安全配置"#}


	{#form set="头" notvs="1"#}
	
	【安全配置】 <br/>
	SQL防御:{#form set="列表框|名称:open_sql{#$TIME2#}" list="开启:1,关闭:0" default="0" data_value=$peizhi.open_sql#}<br/>
	开启后可拦截攻击者注入恶意代码，可以防御诸如跨站脚本攻击（XSS）、SQL注入攻击等恶意攻击行为。<br/>

	CSRF防御:{#form set="列表框|名称:open_csrf{#$TIME2#}" list="开启:1,关闭:0" default="0" data_value=$peizhi.open_csrf#}<br/>
	开启后可有效防御CSRF攻击、跨域提交数据、重复提交表单等操作。<br/>

	POST防御:{#form set="列表框|名称:open_post{#$TIME2#}" list="addslashes:addslashes,stripslashes:stripslashes,htmlspecialchars:htmlspecialchars,strip_tags:strip_tags,不使用防御:'0'" default="0" data_value=$peizhi.open_post#}<br/>
	使用post防御的选项相关作用请查阅百度，推荐使用:addslashes。<br/>
	
	
	后台访问域名:<br/>
	{#form set="输入框|名称:admin_url{#$TIME2#}" data_value=$peizhi.admin_url#}<br/>
	例如：admin.domain.com，绑定后，只能通过该域名登陆，配置保存在 /caches/cache_peizhi.php中，修改参数admin_url可手动取消绑定。<br/>
	
	
	
	后台日志:{#form set="列表框|名称:admin_islog{#$TIME2#}" list="启用:1,关闭:0" default="0" data_value=$peizhi.admin_islog#}<br/>
	
	两次刷新间隔:<br/>
	{#form set="输入框|名称:minrefreshtime{#$TIME2#}" data_value=$peizhi.minrefreshtime#} <a href="{#kuaifan getlink='a'#}&amp;a=white">白名单({#$white|count#})</a><br/>
	单位为毫秒(如填写&quot; 2000 &quot;则为2秒，填0或留空关闭此功能)，请填写会员连续两次刷新网页的最短间隔。<br/>

	【验证码启用】 <br/>
	注册会员:{#form set="列表框|名称:verification_zhuce{#$TIME2#}" list="启用:1,禁用:0" default="0" data_value=$verification.zhuce#} <br/>
	会员登录:{#form set="列表框|名称:verification_denglu{#$TIME2#}" list="启用:1,禁用:0" default="0" data_value=$verification.denglu#} <br/>
	找回密码:{#form set="列表框|名称:verification_zhaohui{#$TIME2#}" list="启用:1,禁用:0" default="0" data_value=$verification.zhaohui#} <br/>
	发布内容:{#form set="列表框|名称:verification_fabu{#$TIME2#}" list="启用:1,禁用:0" default="0" data_value=$verification.fabu#} <br/>
	发布评论:{#form set="列表框|名称:verification_pinglun{#$TIME2#}" list="启用:1,禁用:0" default="0" data_value=$verification.pinglun#} <br/>
	发短信息:{#form set="列表框|名称:verification_xinxi{#$TIME2#}" list="启用:1,禁用:0" default="0" data_value=$verification.xinxi#} <br/>
	后台登录:{#form set="列表框|名称:verification_houtai{#$TIME2#}" list="启用:1,禁用:0" default="0" data_value=$verification.houtai#} <br/>


	{#kuaifan vs="1" set="
	  <anchor title='提交'>[提交修改]
	  <go href='{#get_link()#}' method='post' accept-charset='utf-8'>
	  <postfield name='open_sql' value='$(open_sql{#$TIME2#})'/>
	  <postfield name='open_csrf' value='$(open_csrf{#$TIME2#})'/>
	  <postfield name='open_post' value='$(open_post{#$TIME2#})'/>
	  <postfield name='admin_url' value='$(admin_url{#$TIME2#})'/>
	  <postfield name='admin_islog' value='$(admin_islog{#$TIME2#})'/>
	  <postfield name='minrefreshtime' value='$(minrefreshtime{#$TIME2#})'/>
	  
	  <postfield name='verification_zhuce' value='$(verification_zhuce{#$TIME2#})'/>
	  <postfield name='verification_denglu' value='$(verification_denglu{#$TIME2#})'/>
	  <postfield name='verification_zhaohui' value='$(verification_zhaohui{#$TIME2#})'/>
	  <postfield name='verification_fabu' value='$(verification_fabu{#$TIME2#})'/>
	  <postfield name='verification_pinglun' value='$(verification_pinglun{#$TIME2#})'/>
	  <postfield name='verification_xinxi' value='$(verification_xinxi{#$TIME2#})'/>
	  <postfield name='verification_houtai' value='$(verification_houtai{#$TIME2#})'/>
	  
	  <postfield name='dosubmit' value='1'/>
	  </go> </anchor>
	"#}
	
	{#form set="按钮|名称:dosubmit,值:提交修改" notvs="1"#}
	{#form set="尾" notvs="1"#}
	<br/>

{#include file="admin/footer.tpl"#}
{#include file="common/footer.tpl"#}
