{#include file="common/header.tpl" title="结算提现"#}

[结算提现]<br/>
<a href="{#kuaifan getlink='c|pp'#}&amp;c=index">返回系统管理</a><br/>
------------- <br/>
<a href="{#kuaifan getlink='c|pp'#}&amp;c=jiesuan">点此刷新数据</a>
<br/>收入总额:{#$htmlarr.shouru#}{#$KUAIFAN.amountname#}
<br/>结算中:{#$htmlarr.jiesuan#}{#$KUAIFAN.amountname#}
<br/>已结算:{#$htmlarr.yijiesuan#}{#$KUAIFAN.amountname#}
<br/>可用余额:{#$htmlarr.kejiesuan#}{#$KUAIFAN.amountname#}
<br/>
{#form set="头" notvs="1"#}
{#form set="输入框|名称:'jiesuan{#$TIME2#}'" data_value=0#}<br/>

{#kuaifan vs="1" set="
	<anchor>[确定提现]
	<go href='{#get_link()#}' method='post' accept-charset='utf-8'>
	<postfield name='jiesuan' value='$(jiesuan{#$TIME2#})'/>
	<postfield name='dosubmit' value='1'/>
	</go> </anchor>
"#}
{#form set="按钮|名称:dosubmit,值:确定提现" notvs="1"#}
{#form set="尾" notvs="1"#}
<br/>注意:每笔结算提现不得少于100{#$KUAIFAN.amountname#}。<br/>

{#include file="admin/footer.tpl"#}
{#include file="common/footer.tpl"#}
