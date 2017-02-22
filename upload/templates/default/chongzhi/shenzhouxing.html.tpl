{#$__seo_head="
	<link type='text/css' rel='stylesheet' href='{#$smarty.const.CSS_PATH#}v2_tongyong.css' />
"#}
{#include file="common/header.tpl" title="{#$zhifu.title#}"#}

<div class="daohang">
<a href="{#kuaifan getlinks='vs|sid'#}&amp;m=dingdan&amp;c=chongzhi">在线充值</a>&gt;{#$zhifu.title#}
</div>

<div class="pnpage">
账户余额: {#$huiyuan.amount#}{#$KUAIFAN.amountname#}<br/>.<br/>
{#form set="头" notvs="1"#}
	选择充值金额:<br/>
	{#form set="列表框|名称:'amount{#$TIME2#}'" list="神州行10元冲值卡:10,神州行30元冲值卡:30,神州行50元冲值卡:50,神州行100元冲值卡:100,神州行300元冲值卡:300" default=50#}<br/>

	神州行充值卡序列号:<br/>
	{#form set="输入框|名称:'cardNo{#$TIME2#}'"#}<br/>

	神州行充值卡密码:<br/>
	{#form set="输入框|名称:'cardPwd{#$TIME2#}'"#}<br/>
{#kuaifan vs="1" set="
  <anchor>确定充值
  <go href='{#get_link()#}' method='post' accept-charset='utf-8'>
  <postfield name='amount' value='$(amount{#$TIME2#})'/>
  <postfield name='cardNo' value='$(cardNo{#$TIME2#})'/>
  <postfield name='cardPwd' value='$(cardPwd{#$TIME2#})'/>
  <postfield name='dosubmit' value='1'/>
  </go> </anchor>
"#}
{#form set="按钮|名称:dosubmit,值:确定充值" notvs="1"#}
{#form set="尾" notvs="1"#}<br/>

{#if $zhifu.rate#}
	手续费:{#$zhifu.rate#}%; 如:充值100{#$KUAIFAN.amountname#}则手续费{#$zhifu.rate#}{#$KUAIFAN.amountname#}。<br/>
{#/if#}
注意:单笔金额不能低于1{#$KUAIFAN.amountname#}<br/>
<b>*请确认好信息后再确定充值</b>

</div>





{#include file="common/footerb.html.tpl"#}
{#include file="common/footer.tpl"#}
