{#include file="common/header.tpl" title="{#$SEO.title#}"#}


<a href="{#kuaifan getlinks='vs|sid'#}&amp;m=huiyuan&amp;c=index">会员中心</a>&gt;<a href="{#kuaifan getlink='c|messageid'#}&amp;c=shoujian">收件箱</a><br/>
-------------<br/>
<img src="{#$smarty.const.IMG_PATH#}nophoto_45.gif"/> <br/>
发送:{#$message.send_from_id#}<br/>
标题:{#$message.subject#}({#$message.message_time|date_format:"%Y-%m-%d %H:%M:%S"#})<br/>
内容:{#$message.content|ubb_xinxi#} <br/>

{#include file="common/footerb.tpl"#}
{#include file="common/footer.tpl"#}
