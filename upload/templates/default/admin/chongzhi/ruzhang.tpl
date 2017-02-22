{#include file="common/header.tpl" title_top="1" title="充值入账"#}

{#$url=get_link('a')#}
<a href="{#$url#}">在线充值</a>&gt;充值入账<br/>
-------------<br/>
{#form set="头" notvs="1"#}

用户名:<br/>
{#form set="列表框|名称:'usertype'" list="用户名:username,用户ID:userid"#}{#form set="输入框|名称:username{#$TIME2#},宽:5"#}<br/>
充值额度:<br/>
{#form set="输入框|名称:num{#$TIME2#}"#}<br/>
充值类型:{#form set="列表框|名称:type{#$TIME2#}" list="'{#$KUAIFAN.amountname#}':1,积分:0" default="1"#} <br/>
增加减少:{#form set="列表框|名称:add{#$TIME2#}" list="增加:add,减少:cut" default="add"#} <br/>
交易备注:<br/>
{#form set="输入框|名称:title{#$TIME2#}" vs="1"#} 
{#form set="文本框|名称:title{#$TIME2#}" notvs="1"#} <br/>


{#kuaifan vs="1" set="
  <anchor>[确定充值]
  <go href='{#get_link()#}' method='post' accept-charset='utf-8'>
  <postfield name='usertype' value='$(usertype)'/>
  <postfield name='username' value='$(username{#$TIME2#})'/>
  <postfield name='num' value='$(num{#$TIME2#})'/>
  <postfield name='type' value='$(type{#$TIME2#})'/>
  <postfield name='add' value='$(add{#$TIME2#})'/>
  <postfield name='title' value='$(title{#$TIME2#})'/>
  <postfield name='dosubmit' value='1'/>
  </go> </anchor>
"#}

{#form set="按钮|名称:dosubmit,值:确定充值" notvs="1"#}
{#form set="尾" notvs="1"#}
<br/>
注意:所有输入框和选项都不能为空。<br/>

{#include file="admin/footer.tpl"#}
{#include file="common/footer.tpl"#}