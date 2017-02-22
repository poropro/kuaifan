{#include file="common/header.tpl" title_top="1" title="修改友链"#}

<a href="{#kuaifan getlink='a|id'#}">返回列表</a><br/>
<a href="{#kuaifan getlink='a'#}&amp;a=del">!删除此友链</a><br/>
<a href="{#kuaifan getlink='a'#}&amp;a=jilu">访问/来访纪录</a><br/>
-------------<br/>

	{#form set="头" notvs="1"#}

	分类:{#form set="列表框|名称:catid{#$TIME2#}" list=$fenleiarr data_value=$lianjie.catid#}<br/>
	名称:{#form set="输入框|名称:title{#$TIME2#}" data_value=$lianjie.title#}<br/>
	简称:{#form set="输入框|名称:titlej{#$TIME2#}" data_value=$lianjie.titlej#}(建议两字)<br/>
	地址:{#form set="输入框|名称:url{#$TIME2#}" data_value=$lianjie.url#}(带“http://”)<br/>
	排序:{#form set="输入框|名称:listorder{#$TIME2#}" data_value=$lianjie.listorder#}(越大越靠前)<br/>
	简介:{#form set="输入框|名称:content{#$TIME2#}" data_value=$lianjie.content#}<br/>
	类型:{#form set="列表框|名称:type{#$TIME2#}" list="审核中:0,正常:1,黑名单:2" data_value=$lianjie.type#}<br/>
	回链:<u>{#$KUAIFAN.site_domain#}{#$KUAIFAN.site_dir#}index.php?m=lianjie&amp;id={#$lianjie.id#}</u><br/>
	-----<br/>
	所属会员ID:{#form set="输入框|名称:userid{#$TIME2#},宽:5" data_value=$lianjie.userid#}<br/>
	添加时间:{#form set="输入框|名称:inputtime{#$TIME2#}" data_value=$lianjie.inputtime#}<br/>
	访问次数:{#form set="输入框|名称:read{#$TIME2#},宽:5" data_value=$lianjie.read#}<br/>
	最后访问IP:{#form set="输入框|名称:readip{#$TIME2#}" data_value=$lianjie.readip#}<br/>
	最后访问时间:{#form set="输入框|名称:readtime{#$TIME2#}" data_value=$lianjie.readtime#}<br/>
	来访次数:{#form set="输入框|名称:from{#$TIME2#},宽:5" data_value=$lianjie.from#}<br/>
	最后来访IP:{#form set="输入框|名称:fromip{#$TIME2#}" data_value=$lianjie.fromip#}<br/>
	最后来访时间:{#form set="输入框|名称:fromtime{#$TIME2#}" data_value=$lianjie.fromtime#}<br/>
	支持:{#form set="输入框|名称:zhichi{#$TIME2#},宽:3" data_value=$lianjie.zhichi#}
	不支持:{#form set="输入框|名称:buzhichi{#$TIME2#},宽:3" data_value=$lianjie.buzhichi#}<br/>

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
	  
	  <postfield name='userid' value='$(userid{#$TIME2#})'/>
	  <postfield name='inputtime' value='$(inputtime{#$TIME2#})'/>
	  <postfield name='read' value='$(read{#$TIME2#})'/>
	  <postfield name='readip' value='$(readip{#$TIME2#})'/>
	  <postfield name='readtime' value='$(readtime{#$TIME2#})'/>
	  <postfield name='from' value='$(from{#$TIME2#})'/>
	  <postfield name='fromip' value='$(fromip{#$TIME2#})'/>
	  <postfield name='fromtime' value='$(fromtime{#$TIME2#})'/>
	  <postfield name='zhichi' value='$(zhichi{#$TIME2#})'/>
	  <postfield name='buzhichi' value='$(buzhichi{#$TIME2#})'/>
	  <postfield name='dosubmit' value='1'/>
	  </go> </anchor>
	"#}
	
	{#form set="按钮|名称:dosubmit,值:提交保存" notvs="1"#}
	{#form set="尾" notvs="1"#}
	<br/>

{#include file="admin/footer.tpl"#}
{#include file="common/footer.tpl"#}
