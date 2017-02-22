{#$__seo_head="
	<link type='text/css' rel='stylesheet' href='{#$smarty.const.CSS_PATH#}v2_tongyong.css' />
"#}
{#include file="common/header.tpl" title="{#$SEO.title#}"#}

<div class="daohang">
<a href="{#kuaifan getlinks='vs|sid'#}&amp;m=huiyuan&amp;c=index">会员中心</a>&gt;<a href="{#kuaifan getlink='c|messageid'#}&amp;c=shoujian">收件箱</a>
</div>

<div class="pnpage">
<img src="{#$smarty.const.IMG_PATH#}nophoto_45.gif"/> <br/>
发送:{#$message.send_from_id#}<br/>
标题:{#$message.subject#}({#$message.message_time|date_format:"%Y-%m-%d %H:%M:%S"#})<br/>
内容:{#$message.content|ubb_xinxi#} 
</div>

{#include file="common/footerb.html.tpl"#}
{#include file="common/footer.tpl"#}
