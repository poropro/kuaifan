{#include file="common/header.tpl" title="充值记录"#}

[充值记录]<br/>
<a href="{#kuaifan getlink='c|pp'#}&amp;c=index">返回系统管理</a><br/>
------------- <br/>
<a href="{#kuaifan getlink='c|pp'#}&amp;c=jilu">点此刷新列表</a><br/>
{#foreach from=$htmlarr.jilu item=list#}
	充值会员:<a href="{#$admin_indexurl#}&amp;c=huiyuan&amp;a=xiangqing&amp;userid={#$list.userid#}&amp;go_url={#urlencode(get_link('','&'))#}">{#$list.userdb.username#}</a><br/>
	商户订单号:{#$list.orderid#}<br/>
	支付卡序列号:{#$list.cardno#}<br/>
	提交金额:{#$list.amt#}{#$KUAIFAN.amountname#}[手续费:{#$list.amt-$list.amts#}{#$KUAIFAN.amountname#}({#$list.rate#}%)]<br/>
	充值状态:{#$list.condition#}<br/>
	充值时间:{#$list.inputdate|date_format:"%Y-%m-%d %H:%M:%S"#}<br/>
	通知时间:{#if $list.endtime#}{#$list.endtime|date_format:"%Y-%m-%d %H:%M:%S"#}{#else#}未通知{#/if#}<br/>
	通知结果:{#$list.tongzhi#}<br/>
	{#if $list.dingdan#}
		关联订单:<a href="{#$admin_indexurl#}&amp;c=chongzhi&amp;a=xiangqing&amp;id={#$list.dingdan.id#}&amp;go_url={#urlencode(get_link('','&'))#}">{#$list.dingdan.title#}</a><br/>
	{#/if#}
	<a href="{#$list.backurl|goto_url#}" target="_blank">[访问通知地址]</a><br/>-----<br/>
{#foreachelse#}
	没有任何充值记录。<br/>-----<br/>
{#/foreach#}
{#$htmlarr.pagelist#}<br/>


{#include file="admin/footer.tpl"#}
{#include file="common/footer.tpl"#}
