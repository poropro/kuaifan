{#include file="common/header.tpl" title_top="1" title="添加友链"#}

<a href="{#kuaifan getlink='a'#}">返回列表</a><br/>
-------------<br/>

	{#form set="头" notvs="1"#}

	分类:{#form set="列表框|名称:catid{#$TIME2#}" list=$fenleiarr#}<br/>
	名称:{#form set="输入框|名称:title{#$TIME2#}"#}<br/>
	简称:{#form set="输入框|名称:titlej{#$TIME2#}"#}(建议两字)<br/>
	地址:{#form set="输入框|名称:url{#$TIME2#}" data_value="http://"#}(带“http://”)<br/>
	排序:{#form set="输入框|名称:listorder{#$TIME2#}" data_value="0"#}(越大越靠前)<br/>
	简介:{#form set="输入框|名称:content{#$TIME2#}" data_value="这位站长比校忙，还没有时间填写。"#}<br/>
	类型:{#form set="列表框|名称:type{#$TIME2#}" list="审核中:0,正常:1,黑名单:2"#}<br/>


	{#kuaifan vs="1" set="
	  <anchor>[提交保存]
	  <go href='{#get_link()#}' method='post' accept-charset='utf-8'>
	  <postfield name='catid' value='$(catid{#$TIME2#})'/>
	  <postfield name='title' value='$(title{#$TIME2#})'/>
	  <postfield name='titlej' value='$(titlej{#$TIME2#})'/>
	  <postfield name='url' value='$(url{#$TIME2#})'/>
	  <postfield name='listorder' value='$(listorder{#$TIME2#})'/>
	  <postfield name='content' value='$(content{#$TIME2#})'/>
	  <postfield name='type' value='$(type{#$TIME2#})'/>
	  <postfield name='dosubmit' value='1'/>
	  </go> </anchor>
	"#}
	
	{#form set="按钮|名称:dosubmit,值:提交保存" notvs="1"#}
	{#form set="尾" notvs="1"#}
	<br/>

{#include file="admin/footer.tpl"#}
{#include file="common/footer.tpl"#}
