{#include file="common/header.tpl" title="友情链接"#}

<a href="{#kf_url('index')#}">首页</a>&gt;<a href="{#kuaifan getlink='id|c'#}&amp;c=index">友链中心</a>&gt;详情
<br/>
------------- <br/>
网站名称:{#$YL.title#}<br/>
网站地址:{#$YL.url#}<br/>
网站介绍:{#$YL.content|htmlspecialchars#}<br/>
<a href="{#kuaifan getlink='z'#}&amp;z=1">精彩</a>({#$YL.zhichi#}) <a href="{#kuaifan getlink='z'#}&amp;z=2">失望</a>({#$YL.buzhichi#})<br/>.<br/>

{#if $YL.type == 1#}<a href="{#kuaifan getlink='dosubmit'#}&amp;dosubmit=1">立即访问&gt;&gt;</a>{#else#}<u>此链未通过审核,不提供访问</u>{#/if#}<br/>
------------- <br/>

{#if $YL.userid == $smarty.const.US_USERID && $YL.userid > 0#}
	添加时间:{#$YL.inputtime|date_format:"%Y-%m-%d %H:%M"#} <br/>
	访问次数:{#$YL.read#}  <br/>
	最后访问IP:{#$YL.readip#}  <br/>
	最后访问时间:{#$YL.readtime#}  <br/>
	来访次数:{#$YL.from#}  <br/>
	最后来访IP:{#$YL.fromip#}  <br/>
	最后来访时间:{#$YL.fromtime#}  <br/>
	{#if $YL.fromnum > 0#}<u>*来访次数达到{#$YL.fromnum#}次时自动通过审核。</u><br/>{#/if#}
	<a href="{#kuaifan getlink='c'#}&amp;c=guanli&amp;del={#$YL.id#}">删除本条记录</a><br/>
	<a href="{#kuaifan getlink='c'#}&amp;c=guanli">自助管理友链</a><br/>
{#else#}
	最后出站:{#$YL.readtime#}<br/>
	最后进站:{#$YL.fromtime#}<br/>
{#/if#}

{#include file="common/footerb.tpl"#}
{#include file="common/footer.tpl"#}
