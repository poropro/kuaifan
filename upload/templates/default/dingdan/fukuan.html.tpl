{#$__seo_head="
	<link type='text/css' rel='stylesheet' href='{#$smarty.const.CSS_PATH#}v2_tongyong.css' />
"#}
{#include file="common/header.tpl" title="付款订单"#}


<div class="daohang">
<a href="{#kuaifan getlink='c'#}&amp;c=xiangqing">订单详情</a>&gt;付款订单
</div>

<div class="pnpage">
{#form set="头" notvs="1"#}
请输入支付密码:<br/>
{#form set="密码框|名称:'zhifumima{#$TIME2#}'"#}<br/>
{#if $dingdan.status_type!='1'#}
	付款留言:<br/>
	{#form set="输入框|名称:'tocontent{#$TIME2#}'" data_value=$dingdan.tocontent vs="1"#}
	{#form set="文本框|名称:'tocontent{#$TIME2#}'" data_value=$dingdan.tocontent notvs="1"#}<br/>
{#/if#}
{#kuaifan vs="1" set="
  <anchor>确定付款
  <go href='{#get_link()#}' method='post' accept-charset='utf-8'>
  <postfield name='zhifumima' value='$(zhifumima{#$TIME2#})'/>
  <postfield name='tocontent' value='$(tocontent{#$TIME2#})'/>
  <postfield name='dosubmit' value='1'/>
  </go> </anchor>
"#}
{#form set="按钮|名称:dosubmit,值:确定付款" notvs="1"#}
{#form set="尾" notvs="1"#}<br/>


提示:24小时内支付密码输入错误三次则付款功能将封锁24小时。<br/>
*如果忘记支付密码请到<a href="{#kuaifan getlinks='vs|sid'#}&amp;m=huiyuan&amp;c=zhanghao&amp;a=zhifumima">支付密码管理</a>找回。
</div>




{#include file="common/footerb.html.tpl"#}
{#include file="common/footer.tpl"#}
