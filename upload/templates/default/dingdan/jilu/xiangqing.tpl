{#include file="common/header.tpl" title="账户记录详情"#}


<a href="{#kuaifan getlinks='vs|sid'#}&amp;m=huiyuan&amp;c=index">会员中心</a>&gt;<a href="{#kuaifan getlink='id'#}">账户记录</a><br/>
------------- <br/>
【记录详情】
<br/>标题: {#$jilu.title#}
<br/>时间: {#$jilu.time|date_format:"%Y-%m-%d %H:%M:%S"#}
<br/>{#$jilu.type_cn2#}: {#$jilu.add_cn#}{#$jilu.num#}{#$jilu.type_cn#}
<br/>余额: {#$jilu.nums#}{#$jilu.type_cn#}
<br/>产生IP: {#$jilu.ip#}{#$jilu.ip_cn#}
<br/><a href="{#kuaifan getlink='c'#}&amp;c=shanchu">删除此记录</a>
<br/>



{#include file="common/footerb.tpl"#}
{#include file="common/footer.tpl"#}
