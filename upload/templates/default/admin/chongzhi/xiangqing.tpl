{#include file="common/header.tpl" title_top="1" title="订单详情"#}


{#if $smarty.get.go_url#}
	<a href="{#$smarty.get.go_url|goto_url#}">返回来源地址</a><br/> 
{#/if#}
<a href="{#$admin_indexurl#}&amp;c=chongzhi">在线充值</a>&gt;<a href="{#kuaifan getlink='id|a|go_url'#}&amp;a=dingdan">订单列表</a>&gt;详情<br/> 

------------- <br/>
{#if $dingdan.maijia#}
	【卖家信息】
	{#$isvip = get_vip($dingdan.maijia)#}
	<br/>昵称: {#$dingdan.maijia.nickname#}
	{#if $isvip#}<img src="{#$isvip.img#}" alt="{#$isvip.name#}"/>{#/if#}
	<br/>用户名(ID): <a href="{#$admin_indexurl#}&amp;c=huiyuan&amp;a=xiangqing&amp;userid={#$dingdan.maijia.userid#}&amp;go_url={#urlencode(get_link('','&'))#}">{#$dingdan.maijia.username#}</a>({#$dingdan.maijia.userid#})
	<br/>联系电话: {#if $dingdan.maijia.mobile#}{#$dingdan.maijia.mobile#}{#else#}无{#/if#}
	<br/>电子邮箱: {#if $dingdan.maijia.email#}{#$dingdan.maijia.email#}{#else#}无{#/if#}
	<br/>最后在线时间: {#if $dingdan.maijia.indate#}{#$dingdan.maijia.indate|date_format:"%Y-%m-%d %H:%M:%S"#}{#else#}无{#/if#}
{#else#}
	此订单系统订单
{#/if#}
<br/>
【订单信息】
	<br/>买家会员: <a href="{#$admin_indexurl#}&amp;c=huiyuan&amp;a=xiangqing&amp;userid={#$dingdan.userid#}&amp;go_url={#urlencode(get_link('','&'))#}">{#$dingdan.huiyuan.username#}</a>
	<br/>订单编号: {#$dingdan.id#}
	<br/>下单时间: {#$dingdan.inputtime|date_format:"%Y-%m-%d %H:%M:%S"#}
	<br/>付款时间: {#if $dingdan.paytime#}{#$dingdan.paytime|date_format:"%Y-%m-%d %H:%M:%S"#}{#else#}未付款{#/if#}
	<br/>成交时间: {#if $dingdan.oktime#}{#$dingdan.oktime|date_format:"%Y-%m-%d %H:%M:%S"#}{#else#}正在交易中{#/if#}
	<br/>产品标题: {#$dingdan.title#}
	{#if $dingdan.content#}<br/>产品说明: {#$dingdan.content#}{#/if#}
	<br/>交易状态: {#$dingdan.status_cn#}
	<br/>产品单价: {#$dingdan.price#}{#$dingdan.price_type_cn#}
	<br/>购买数量: {#$dingdan.num#}
	<br/>商品总价: {#$dingdan.price*$dingdan.num#}{#$dingdan.price_type_cn#}
	{#if $dingdan.tocontent#}<br/><b>付款留言</b>: {#$dingdan.tocontent#}{#/if#}
	<br/>
	{#if $dingdan.status=='1' && $dingdan.touserid=='0' && $dingdan.toadmin=='1'#}
		<a href="{#kuaifan getlink='a'#}&amp;a=fahuo">已收到款,确定发货</a><br/>
	{#/if#}


{#include file="admin/footer.tpl"#}
{#include file="common/footer.tpl"#}