{#include file="common/header.tpl" title="搜索"#}

<a href="{#kf_url('index')#}">首页</a>-&gt;搜索
<br/>
{#form set="头|地址:'{#str_replace(':','\:',get_link('page|key|c'))#}'" notvs="1"#}
{#form set="输入框|名称:key{#$TIME2#},值:'{#$smarty.request.key#}'"#}
{#kuaifan vs="1" set="
  <anchor>搜索
  <go href='{#get_link('page|key|c')#}' method='post' accept-charset='utf-8'>
  <postfield name='key' value='$(key{#$TIME2#})'/>
  <postfield name='dosubmit' value='1'/>
  </go> </anchor>
"#}
{#form set="按钮|名称:dosubmit,值:搜索" notvs="1"#}
{#form set="尾" notvs="1"#}
<br/>-------------<br/>

<a href="{#kf_url('index')#}">首页</a>-&gt;搜索
<br/>

{#include file="common/footerb.tpl"#}
{#include file="common/footer.tpl"#}