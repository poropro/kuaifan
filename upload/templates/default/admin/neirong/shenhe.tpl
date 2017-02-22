{#include file="common/header.tpl" title_top="1" title="审核内容"#}

<a href="{#kuaifan getlink='a|pp|key|status'#}">返回内容管理</a><br/>
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

{#kuaifan_neirong_fabu set="列表名:lists,状态:GET[status],搜索变量名:key,显示数目:10,标题长度:15,填补字符:...,分页显示:1,分页名:pagelist,分页变量名:pp"#}

<a href="{#kuaifan getlink='status'#}&amp;status=1">通过</a>|<a href="{#kuaifan getlink='status'#}&amp;status=0">退稿</a>|<a href="{#kuaifan getlink='status'#}&amp;status=98">草稿</a>|<a href="{#kuaifan getlink='status'#}&amp;status=99">审核中</a><br/>
{#if $smarty.get.status=='1'#}
	已选类型:通过<a href="{#kuaifan getlink='status'#}">取消筛选</a><br/>
{#elseif $smarty.get.status=='98'#}
	已选类型:草稿<a href="{#kuaifan getlink='status'#}">取消筛选</a><br/>
{#elseif $smarty.get.status=='99'#}
	已选类型:审核中<a href="{#kuaifan getlink='status'#}">取消筛选</a><br/>
{#elseif $smarty.get.status=='0'#}
	已选类型:退稿<a href="{#kuaifan getlink='status'#}">取消筛选</a><br/>
{#/if#}
ID.状态|标题(发布时间)<br/>
{#foreach from=$lists item=list#}
	{#$list.id#}.{#$list.status_txt#}|<a href="{#$admin_indexurl#}&amp;c=neirong&amp;catid={#$list.catid#}&amp;edit={#$list.id#}">{#$list.title#}</a>({#$list.inputtime|date_format:"%Y-%m-%d %H:%M:%S"#})<br/>
	操作:<a href="{#kuaifan getlink='mode|checkid'#}&amp;mode=1&amp;checkid={#$list.checkid#}">通过</a>|<a href="{#kuaifan getlink='mode|checkid'#}&amp;mode=0&amp;checkid={#$list.checkid#}">退稿</a>|<a href="{#kuaifan getlink='mode|checkid'#}&amp;mode=98&amp;checkid={#$list.checkid#}">草稿</a>|<a href="{#kuaifan getlink='mode|checkid'#}&amp;mode=99&amp;checkid={#$list.checkid#}">审核中</a><br/>-----<br/>
{#foreachelse#}
	<u>没有任何投稿。</u><br/>
{#/foreach#}

{#$pagelist#}<br/>
提示:审核中、退稿或草稿的状态可以点击标题重新修改提交或删除。<br/>


{#include file="admin/footer.tpl"#}
{#include file="common/footer.tpl"#}
