{#$__seo_head="
	<link type='text/css' rel='stylesheet' href='{#$smarty.const.CSS_PATH#}v2_tongyong.css' />
"#}
{#include file="common/header.tpl" title="购买积分"#}

<div class="daohang">
<a href="{#kuaifan getlinks='vs|sid'#}&amp;m=huiyuan&amp;c=index">会员中心</a>&gt;购买积分
</div>

<div class="pnpage">
{#form set="头" notvs="1"#}
支出:(单位/{#$KUAIFAN.amountname#})<br/>
{#form set="输入框|名称:'money{#$TIME2#}'"#}<br/>
{#kuaifan vs="1" set="
  <anchor>确定购买
  <go href='{#get_link()#}' method='post' accept-charset='utf-8'>
  <postfield name='money' value='$(money{#$TIME2#})'/>
  <postfield name='dosubmit' value='1'/>
  </go> </anchor>
"#}
{#form set="按钮|名称:dosubmit,值:确定购买" notvs="1"#}
{#form set="尾" notvs="1"#}<br/>
-----<br/>

余额: {#$huiyuan.amount#}{#$KUAIFAN.amountname#}, {#$huiyuan.point#}积分<br/>
价格: 1{#$KUAIFAN.amountname#}={#$rmb_point_rate#}积分

</div>



{#include file="common/footerb.html.tpl"#}
{#include file="common/footer.tpl"#}
