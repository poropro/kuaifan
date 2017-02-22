{#$__seo_head="
	<link type='text/css' rel='stylesheet' href='{#$smarty.const.CSS_PATH#}v2_tongyong.css' />
"#}
{#include file="common/header.tpl" title="订单详情"#}

<div class="daohang">
<a href="{#kuaifan getlinks='vs|sid'#}&amp;m=huiyuan&amp;c=index">会员中心</a>&gt;<a href="{#kuaifan getlink='c|id'#}&amp;c=index">我的订单</a>
</div>

{#if $dingdan.maijia#}
	<div class="m_title">【卖家信息】</div>
	<div class="pnpage">
	{#$isvip = get_vip($dingdan.maijia)#}
	昵称: <a href="{#kuaifan getlinks='vs|sid'#}&amp;m=huiyuan&amp;c=ziliao&amp;userid={#$dingdan.maijia.userid#}">{#$dingdan.maijia.nickname#}</a>
	{#if $isvip#}<img src="{#$isvip.img#}" alt="{#$isvip.name#}"/>{#/if#}
	<br/>用户名(ID): {#$dingdan.maijia.username#}({#$dingdan.maijia.userid#})
	<br/>联系电话: {#if $dingdan.maijia.mobile#}{#$dingdan.maijia.mobile#}{#else#}无{#/if#}
	<br/>电子邮箱: {#if $dingdan.maijia.email#}{#$dingdan.maijia.email#}{#else#}无{#/if#}
	<br/>最后在线时间: {#if $dingdan.maijia.indate#}{#$dingdan.maijia.indate|date_format:"%Y-%m-%d %H:%M:%S"#}{#else#}无{#/if#}
	</div>
{#else#}
	<div class="pnpage">此订单系统订单</div>
{#/if#}

<div class="m_title">【订单信息】</div>
	<div class="pnpage">
	产品标题: {#if $dingdan.titleurl#}<a href="{#$dingdan.titleurl#}">{#$dingdan.title#}</a>{#else#}{#$dingdan.title#}{#/if#}
	<br/>订单编号: {#$dingdan.id#}
	<br/>下单时间: {#$dingdan.inputtime|date_format:"%Y-%m-%d %H:%M:%S"#}
	<br/>付款时间: {#if $dingdan.paytime#}{#$dingdan.paytime|date_format:"%Y-%m-%d %H:%M:%S"#}{#else#}未付款{#/if#}
	<br/>成交时间: {#if $dingdan.oktime#}{#$dingdan.oktime|date_format:"%Y-%m-%d %H:%M:%S"#}{#else#}正在交易中{#/if#}
	<br/>交易状态: {#$dingdan.status_cn#}
	<br/>产品单价: {#$dingdan.price#}{#$dingdan.price_type_cn#}
	<br/>购买数量: {#$dingdan.num#}
	<br/>商品总价: {#$dingdan.price*$dingdan.num#}{#$dingdan.price_type_cn#}
	{#if $dingdan.content#}<br/>产品说明: {#$dingdan.content#}{#/if#}
	{#if $dingdan.tocontent#}
		
		{#if $dingdan.status=='1'#}
			{#form set="头" notvs="1"#}
			<br/>付款留言:<br/>
				{#form set="输入框|名称:'tocontent{#$TIME2#}'" data_value=$dingdan.tocontent vs="1"#}
				{#form set="文本框|名称:'tocontent{#$TIME2#}'" data_value=$dingdan.tocontent notvs="1"#}<br/>
			{#kuaifan vs="1" set="
			  <anchor>[修改留言]
			  <go href='{#get_link()#}' method='post' accept-charset='utf-8'>
			  <postfield name='tocontent' value='$(tocontent{#$TIME2#})'/>
			  <postfield name='dosubmit' value='1'/>
			  </go> </anchor>
			"#}
			{#form set="按钮|名称:dosubmit,值:修改留言" notvs="1"#}
			{#form set="尾" notvs="1"#}
		{#else#}
			<br/>付款留言: {#$dingdan.tocontent|htmlspecialchars#}
		{#/if#}
	{#/if#}
	</div>
{#$url = get_link('id|c')#}
<div class="pnpage">
{#if $dingdan.status=='0'#}
	<a href="{#$url#}&amp;c=fukuan&amp;id={#$dingdan.id#}">立即付款</a>.<a href="{#$url#}&amp;c=guanbi&amp;id={#$dingdan.id#}">关闭交易</a><br/>
	账户余额: {#$huiyuan.amount#}{#$KUAIFAN.amountname#}, {#$huiyuan.point#}积分<br/>
{#elseif $dingdan.status=='2'#}
	<a href="{#$url#}&amp;c=shouhuo&amp;id={#$dingdan.id#}">确认收货</a><br/>
{#/if#}
</div>




{#include file="common/footerb.html.tpl"#}
{#include file="common/footer.tpl"#}
