{#$__seo_head="
	<link type='text/css' rel='stylesheet' href='{#$smarty.const.CSS_PATH#}v2_tongyong.css' />
"#}
{#include file="common/header.tpl" title="{#$SEO.title#}"#}


{#form set="头" notvs="1"#}

{#if $message#}
	<div class="daohang">
	<a href="{#kuaifan getlinks='vs|sid'#}&amp;m=huiyuan&amp;c=index">会员中心</a>&gt;<a href="{#kuaifan getlink='c|messageid'#}&amp;c=shoujian">收件箱</a>
	</div>
	<div class="pnpage">
	<img src="{#kuaifan touxiang=$message.huiyuan.userid size='小'#}?t={#$TIME2#}"/><br/>
	发送:<a href="{#kuaifan getlinks='vs|sid'#}&amp;m=huiyuan&amp;c=ziliao&amp;username={#$message.send_from_id#}">{#$message.send_from_id|colorname#}</a><br/>
	标题:{#$message.subject#}({#$message.message_time|date_format:"%Y-%m-%d %H:%M:%S"#})<br/>
	{#$subcon = str_replace("回复:", "", $message.subject)#}	
	{#$subcon = "回复:"|cat:$subcon#}
	内容:{#$message.content|ubb_xinxi#} <br/>
	<a href="{#kuaifan getlink='c|messageid|username'#}&amp;c=laiwang&amp;username={#$message.send_from_id#}">查看与TA的来往信息</a><br/>
	</div>
{#elseif $huiyuan#}
	<div class="daohang">
	<a href="{#kuaifan getlinks='vs|sid'#}&amp;m=huiyuan&amp;c=index">返回会员中心</a>
	</div>
	<div class="pnpage">
	<img src="{#kuaifan touxiang=$huiyuan.userid size='小'#}?t={#$TIME2#}"/><br/>
	收信人:<a href="{#kuaifan getlinks='vs|sid'#}&amp;m=huiyuan&amp;c=ziliao&amp;username={#$huiyuan.username#}">{#$huiyuan|colorname#}</a><br/>
	<a href="{#kuaifan getlink='c|username'#}&amp;c=laiwang&amp;username={#$huiyuan.username#}">查看与TA的来往信息</a><br/>
	</div>
{#else#}
	<div class="daohang">
	<a href="{#kuaifan getlinks='vs|sid'#}&amp;m=huiyuan&amp;c=index">返回会员中心</a><br/>
	</div>
	<div class="pnpage">
	收信人:<br/>
	{#form set="列表框|名称:'usertype'" list="用户名:username,用户ID:userid"#}{#form set="输入框|名称:'username',宽:5"#}
	</div>
{#/if#}
<div class="pnpage">
标 题:<br/>
{#form set="输入框|名称:'subject'" data_value=$subcon#}<br/>
内 容:<br/>
{#form set="输入框|名称:'content'" vs="1"#}
{#form set="文本框|名称:'content'" notvs="1"#}<br/>

{#if $yzmpeizhi.xinxi#}
	<img id="yzm_img" src="{#kuaifan getlink='m|c'#}&amp;m=api&amp;c=yanzhengma"/>
	<a id="yzm_a" href="{#kuaifan getlink='yanzhengma'#}">换一张</a><br/>
	{#form set="输入框|名称:'yanzhengma',placeholder:'验证码'"#}<br/>
	{#literal#}<script type="text/javascript">
		function set_refresh_code(id1,id2){if(document.getElementById(id1)){var temp_src=document.getElementById(id1).src;temp_src=temp_src.replace("&amp;","&");document.getElementById(id2).onclick=function(){if(temp_src.indexOf("?")){document.getElementById(id1).src=temp_src+"&_refresh="+Math.random()}else{document.getElementById(id1).src=temp_src+"?_refresh="+Math.random()};return false}}}
		set_refresh_code('yzm_img', 'yzm_img');
		set_refresh_code('yzm_img', 'yzm_a');
	</script>{#/literal#}
{#/if#}

{#kuaifan vs="1" set="
	<anchor>发送
	<go href='{#get_link()#}' method='post' accept-charset='utf-8'>
	<postfield name='usertype' value='$(usertype)'/>
	<postfield name='username' value='$(username)'/>
	<postfield name='subject' value='$(subject)'/>
	<postfield name='content' value='$(content)'/>
	<postfield name='yanzhengma' value='$(yanzhengma)'/>
	<postfield name='ip' value='{#yanzhengmaip()#}'/>
	<postfield name='dosubmit' value='1'/>
	</go> </anchor>
"#}
{#form set="按钮|名称:dosubmit,值:发送" notvs="1"#}
{#form set="尾" notvs="1"#}
<br/>
提示: 禁止给自己或非注册用户发送消息。
</div>


{#include file="common/footerb.html.tpl"#}
{#include file="common/footer.tpl"#}
