{#include file="common/header.tpl" title="订单管理"#}


<a href="{#kuaifan getlinks='vs|sid'#}&amp;m=huiyuan&amp;c=index">返回会员中心</a><br/>
------------- <br/>
{#form set="头|地址:'{#str_replace(':','\:',get_link('pp|key|status'))#}'" notvs="1"#}
{#form set="输入框|名称:key{#$TIME2#},值:'{#$smarty.request.key#}',宽:12"#}
{#kuaifan vs="1" set="
  <anchor>搜索
  <go href='{#get_link('pp|key')#}' method='post' accept-charset='utf-8'>
  <postfield name='key' value='$(key{#$TIME2#})'/>
  <postfield name='dosubmit' value='1'/>
  </go> </anchor>
"#}
{#form set="按钮|名称:dosubmit,值:搜索" notvs="1"#}
{#form set="尾" notvs="1"#}<br/>

{#kuaifan_dingdan set="列表名:lists,会员ID:{#$huiyuan.userid#},状态:GET[status],搜索变量名:key,显示数目:10,标题长度:15,填补字符:...,分页显示:1,分页名:pagelist,分页变量名:pp,币种:GET[type]"#}

<a href="{#kuaifan getlink='status'#}&amp;status=0">待付</a>|<a href="{#kuaifan getlink='status'#}&amp;status=1">待发</a>|<a href="{#kuaifan getlink='status'#}&amp;status=2">待收</a>|<a href="{#kuaifan getlink='status'#}&amp;status=10">成功</a>|<a href="{#kuaifan getlink='status'#}&amp;status=99">失败</a><br/>

{#if $smarty.get.type=='point'#}
	订单币种:<a href="{#kuaifan getlink='type'#}">{#$KUAIFAN.amountname#}</a>|积分<br/>
{#else#}
	订单币种:{#$KUAIFAN.amountname#}|<a href="{#kuaifan getlink='type'#}&amp;type=point">积分</a><br/>
{#/if#}

{#if $smarty.get.status=='0'#}
	已选类型:未付款<a href="{#kuaifan getlink='status'#}">取消筛选</a><br/>
{#elseif $smarty.get.status=='1'#}
	已选类型:待发货<a href="{#kuaifan getlink='status'#}">取消筛选</a><br/>
{#elseif $smarty.get.status=='2'#}
	已选类型:待收货<a href="{#kuaifan getlink='status'#}">取消筛选</a><br/>
{#elseif $smarty.get.status=='10'#}
	已选类型:交易成功<a href="{#kuaifan getlink='status'#}">取消筛选</a><br/>
{#elseif $smarty.get.status=='99'#}
	已选类型:交易失败<a href="{#kuaifan getlink='status'#}">取消筛选</a><br/>
{#else#}
	我的全部订单<br/>
{#/if#}
{#$url = get_link('id|c')#}
{#foreach from=$lists item=list#}
	{#$list.id#}.<a href="{#$url#}&amp;c=xiangqing&amp;id={#$list.id#}">{#$list.title#}</a><br/>
	时间:{#$list.inputtime|date_format:"%Y-%m-%d %H:%M:%S"#}<br/>
	状态:{#$list.status_cn#}{#if $list.status=='0'#}|<a href="{#$url#}&amp;c=fukuan&amp;id={#$list.id#}">立即付款({#$list.price*$list.num#}{#$list.price_type_cn#})</a>{#elseif $list.status=='2'#}|<a href="{#$url#}&amp;c=shouhuo&amp;id={#$list.id#}">确认收货</a>{#/if#}<br/>
	-----<br/>
{#foreachelse#}
	<u>没有任何订单。</u><br/>
{#/foreach#}

{#$pagelist#}<br/>
提示:点击订单标题可查看订单详情以及操作订单。<br/>




{#include file="common/footerb.tpl"#}
{#include file="common/footer.tpl"#}
