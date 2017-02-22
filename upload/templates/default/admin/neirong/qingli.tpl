{#include file="common/header.tpl" title_top="1" title="一键清理数据"#}
[清理数据]<br/>
-------------<br/>

	{#form set="头" notvs="1"#}
	
	A级.所有模型:{#form set="列表框|名称:ql_moxing{#$TIME2#}" list="是:1,否:0" default="0"#}<br/>
	B级.所有栏目:{#form set="列表框|名称:ql_lanmu{#$TIME2#}" list="是:1,否:0" default="0"#}<br/>
	C级.所有内容:{#form set="列表框|名称:ql_neirong{#$TIME2#}" list="是:1,否:0" default="0"#}<br/>
	D级.所有附件:{#form set="列表框|名称:ql_fujian{#$TIME2#}" list="是:1,否:0" default="0"#}<br/>
	D级.所有评论:{#form set="列表框|名称:ql_pinglun{#$TIME2#}" list="是:1,否:0" default="0"#}<br/>

	{#kuaifan vs="1" set="
	  <anchor title='提交'>[提交清理]
	  <go href='{#get_link()#}' method='post' accept-charset='utf-8'>
	  <postfield name='ql_moxing' value='$(ql_moxing{#$TIME2#})'/>
	  <postfield name='ql_lanmu' value='$(ql_lanmu{#$TIME2#})'/>
	  <postfield name='ql_neirong' value='$(ql_neirong{#$TIME2#})'/>
	  <postfield name='ql_fujian' value='$(ql_fujian{#$TIME2#})'/>
	  <postfield name='ql_pinglun' value='$(ql_pinglun{#$TIME2#})'/>
	  <postfield name='dosubmit' value='1'/>
	  </go> </anchor>
	"#}
	
	{#form set="按钮|名称:dosubmit,值:提交清理" notvs="1"#}
	{#form set="尾" notvs="1"#}
	<br/>
	注明:A &gt; B &gt; C &gt; D。<br/>
	删除C级则D级也会被删除;删除B级则C、D级也会被删除;删除A级则B、C、D级也会被删除。<br/>

{#include file="admin/footer.tpl"#}
{#include file="common/footer.tpl"#}
