{#include file="common/header.tpl" title="自助申请友情链接"#}

<a href="{#kf_url('index')#}">首页</a>&gt;<a href="{#kuaifan getlink='id|c'#}&amp;c=index">友链中心</a>&gt;申请加入
<br/>
------------- <br/>

{#form set="头" notvs="1"#}

网站名称:(最低4字,最多12字)<br/>
{#form set="输入框|名称:title{#$TIME2#}"#}<br/>
网站简称:(限制2个字)<br/>
{#form set="输入框|名称:titlej{#$TIME2#}"#}<br/>
网站地址:(请输入您站地址)<br/>
{#form set="输入框|名称:url{#$TIME2#}" data_value="http://"#}<br/>
网站简介:(最多80字)<br/>
{#form set="输入框|名称:content{#$TIME2#}" data_value="这位站长比校忙，还没有时间填写。"#}<br/>
所属分类:(请选择您站点相应的分类)<br/>
{#form set="列表框|名称:catid{#$TIME2#}" list=$fenleiarr#}<br/>


{#kuaifan vs="1" set="
  <anchor>[提交申请]
  <go href='{#get_link()#}' method='post' accept-charset='utf-8'>
  <postfield name='catid' value='$(catid{#$TIME2#})'/>
  <postfield name='title' value='$(title{#$TIME2#})'/>
  <postfield name='titlej' value='$(titlej{#$TIME2#})'/>
  <postfield name='url' value='$(url{#$TIME2#})'/>
  <postfield name='content' value='$(content{#$TIME2#})'/>
  <postfield name='dosubmit' value='1'/>
  </go> </anchor>
"#}

{#form set="按钮|名称:dosubmit,值:提交申请" notvs="1"#}
{#form set="尾" notvs="1"#}
<br/>
*<a href="{#kuaifan getlink='c'#}&amp;c=guanli">自助管理友链</a><br/>


{#include file="common/footerb.tpl"#}
{#include file="common/footer.tpl"#}
