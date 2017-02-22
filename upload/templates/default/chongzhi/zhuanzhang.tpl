{#include file="common/header.tpl" title="{#$zhifu.title#}"#}


<a href="{#kuaifan getlinks='vs|sid'#}&amp;m=dingdan&amp;c=chongzhi">在线充值</a>&gt;{#$zhifu.title#}<br/>
------------- <br/>
账户余额: {#$huiyuan.amount#}{#$KUAIFAN.amountname#}<br/>.<br/>
{#form set="头" notvs="1"#}
充值金额:({#$KUAIFAN.amountname#})<br/>
{#form set="输入框|名称:'amount{#$TIME2#}'"#}<br/>
{#kuaifan vs="1" set="
  <anchor>确定充值
  <go href='{#get_link()#}' method='post' accept-charset='utf-8'>
  <postfield name='amount' value='$(amount{#$TIME2#})'/>
  <postfield name='dosubmit' value='1'/>
  </go> </anchor>
"#}
{#form set="按钮|名称:dosubmit,值:确定充值" notvs="1"#}
{#form set="尾" notvs="1"#}<br/>

{#if $zhifu.rate#}
	手续费:{#$zhifu.rate#}%; 如:充值100{#$KUAIFAN.amountname#}则手续费{#$zhifu.rate#}{#$KUAIFAN.amountname#}。<br/>
{#/if#}
注意:单笔金额不能低于1{#$KUAIFAN.amountname#}<br/>



{#include file="common/footerb.tpl"#}
{#include file="common/footer.tpl"#}
