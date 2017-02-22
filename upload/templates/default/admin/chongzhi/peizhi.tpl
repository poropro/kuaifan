{#include file="common/header.tpl" title_top="1" title="充值方式"#}


<a href="{#$admin_indexurl#}&amp;c=chongzhi">在线充值</a>&gt;<a href="{#kuaifan getlink='a|id'#}">充值方式</a>&gt;配置<br/>
------------- <br/>
{#form set="头" notvs="1"#}
【{#$zhifu.type#}】<br/>
充值方式名称: <br/>
{#form set="输入框|名称:pay_title{#$TIME2#}" data_value=$zhifu.title#}<br/>
{#foreach from=$zhifu.setting_field key=k item=v#}
	{#$v#}:<br/>{#form set="输入框|名称:setting_{#$k#}{#$TIME2#}" data_value=$zhifu.setting[$k]#}<br/>
{#/foreach#}
充值方式描述: <br/>
{#form set="输入框|名称:pay_content{#$TIME2#}" data_value=$zhifu.content vs="1"#} 
{#form set="文本框|名称:pay_content{#$TIME2#}" data_value=$zhifu.content notvs="1"#}<br/>
充值手续费: <br/>
费率{#form set="输入框|名称:pay_rate{#$TIME2#},宽:8" data_value=$zhifu.rate#}(%)<br/>
排序:{#form set="输入框|名称:pay_pid{#$TIME2#},宽:5" data_value=$zhifu.pid#}<br/>
启用:{#form set="列表框|名称:pay_open{#$TIME2#}" list="启用:1,停用:0" default=$zhifu.open#}<br/>
在线支付?:{#if $zhifu.in#}是{#else#}否{#/if#}<br/>

{#if $VS==1#}
  <anchor>提交修改
  <go href='{#get_link()#}' method='post' accept-charset='utf-8'>
  <postfield name='pay_title' value='$(pay_title{#$TIME2#})'/>
  <postfield name='pay_content' value='$(pay_content{#$TIME2#})'/>
  <postfield name='pay_rate' value='$(pay_rate{#$TIME2#})'/>
  <postfield name='pay_pid' value='$(pay_pid{#$TIME2#})'/>
  <postfield name='pay_open' value='$(pay_open{#$TIME2#})'/>
  {#foreach from=$zhifu.setting_field key=k item=v#}
	  <postfield name='setting_{#$k#}' value='$(setting_{#$k#}{#$TIME2#})'/>
  {#/foreach#}
  <postfield name='dosubmit' value='1'/>
  </go> </anchor>
{#/if#}
{#form set="按钮|名称:dosubmit,值:提交修改" notvs="1"#}
{#form set="尾" notvs="1"#}<br/>
{#$zhifu.syscontent#}




{#include file="admin/footer.tpl"#}
{#include file="common/footer.tpl"#}