{#include file="common/header.tpl" title="关闭订单"#}


<a href="{#kuaifan getlink='c'#}&amp;c=xiangqing">订单详情</a>&gt;关闭交易<br/>
------------- <br/>
{#form set="头" notvs="1"#}
关闭理由:<br/>
{#form set="输入框|名称:'status_close{#$TIME2#}'" vs="1"#}
{#form set="文本框|名称:'status_close{#$TIME2#}'" notvs="1"#}<br/>
{#kuaifan vs="1" set="
  <anchor>确定关闭
  <go href='{#get_link()#}' method='post' accept-charset='utf-8'>
  <postfield name='status_close' value='$(status_close{#$TIME2#})'/>
  <postfield name='dosubmit' value='1'/>
  </go> </anchor>
"#}
{#form set="按钮|名称:dosubmit,值:确定关闭" notvs="1"#}
{#form set="尾" notvs="1"#}<br/>


提示:理由请填写关闭的原因，例如: 拍错重拍、不想买了。<br/>



{#include file="common/footerb.tpl"#}
{#include file="common/footer.tpl"#}
