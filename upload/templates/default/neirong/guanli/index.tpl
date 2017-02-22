{#include file="common/header.tpl" title="已发布稿件"#}


<a href="{#kuaifan getlinks='vs|sid'#}&amp;m=huiyuan&amp;c=index">返回会员中心</a><br/>
------------- <br/>
<a href="{#kuaifan getlink='a|pp|key|status'#}&amp;a=fabu">在线投稿</a><br/>
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

{#kuaifan_neirong_fabu set="列表名:lists,会员名:{#$username#},状态:GET[status],栏目:GET[catid],搜索变量名:key,显示数目:10,标题长度:15,填补字符:...,分页显示:1,分页名:pagelist,分页变量名:pp"#}

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
状态|标题(发布时间)<br/>
{#foreach from=$lists item=list#}
	{#$list.status_txt#}|[<a href="{#$list.catid_url#}">{#$list.catid_cn#}</a>]{#if $list.bjurl#}<a href="{#$list.bjurl#}">编辑</a>.{#/if#}{#if $list.url#}<a href="{#$list.url#}">{#$list.title#}</a>{#else#}{#$list.title#}{#/if#}({#$list.inputtime|date_format:"%Y-%m-%d"#})<br/>
{#foreachelse#}
	<u>没有任何投稿。</u><br/>
{#/foreach#}

{#$pagelist#}<br/>

栏目分类:<br/>
<select name="catid{#$TIME2#}" value="{#$smarty.get.catid#}"><option value="0">全部显示</option>{#$categorys#}</select>
{#kuaifan vs="1" set="
  <anchor>筛选
  <go href='{#get_link('catid')#}' method='post' accept-charset='utf-8'>
  <postfield name='catid' value='$(catid{#$TIME2#})'/>
  <postfield name='dosubmit' value='1'/>
  </go> </anchor>
"#}
<br/>



{#kuaifan tongji="查看自己" title="已发布的稿件"#}
{#include file="common/footerb.tpl"#}
{#include file="common/footer.tpl"#}
