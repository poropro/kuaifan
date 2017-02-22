{#include file="common/header.tpl" title_top="1" title="账户记录"#}


<a href="{#$admin_indexurl#}&amp;c=chongzhi">在线充值</a>&gt;<a href="{#kuaifan getlink='a|id'#}&amp;a=jilu">账户记录</a>&gt;详情<br/>

------------- <br/>
【记录详情】
<br/>会员: <a href="{#$admin_indexurl#}&amp;c=huiyuan&amp;a=xiangqing&amp;userid={#$jilu.userid#}&amp;go_url={#urlencode(get_link('','&'))#}">{#$jilu.huiyuan.username#}</a>
<br/>标题: {#$jilu.title#}
<br/>时间: {#$jilu.time|date_format:"%Y-%m-%d %H:%M:%S"#}
<br/>{#$jilu.type_cn2#}: {#$jilu.add_cn#}{#$jilu.num#}{#$jilu.type_cn#}
<br/>余额: {#$jilu.nums#}{#$jilu.type_cn#}
<br/>产生IP: {#$jilu.ip#}{#$jilu.ip_cn#}
<br/><a href="{#kuaifan getlink='a'#}&amp;a=shanchu">删除此记录</a>
<br/>


{#include file="admin/footer.tpl"#}
{#include file="common/footer.tpl"#}