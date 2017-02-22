{#include file="common/header.tpl" title_top="1" title="回复心情"#}


{#form set="头" notvs="1"#}
[心情配置]<br/>

{#foreach from=$xinqingarr item=list key=name#}
	-----<br/>
	可用:{#form set="列表框|名称:xq_a_{#$name#}{#$TIME2#}" list="否:0,是:1" data_value=$list.use#}<br/>
	名称:{#form set="输入框|名称:xq_b_{#$name#}{#$TIME2#}" data_value=$list.name#}<br/>
	图片:{#form set="输入框|名称:xq_c_{#$name#}{#$TIME2#}" data_value=$list.pic#}{#if $list.pic#}<img src="{#$smarty.const.IMG_PATH#}{#$list.pic#}"/>{#/if#}<br/>
{#/foreach#}

{#if $VS==1#}
  <anchor title="提交">[提交修改]
  <go href="{#get_link()#}" method="post" accept-charset="utf-8">
  {#foreach from=$xinqingarr item=list key=name#}
	<postfield name="xq_a_{#$name#}" value="$(xq_a_{#$name#}{#$TIME2#})"/>
	<postfield name="xq_b_{#$name#}" value="$(xq_b_{#$name#}{#$TIME2#})"/>
	<postfield name="xq_c_{#$name#}" value="$(xq_c_{#$name#}{#$TIME2#})"/>
  {#/foreach#}
  <postfield name="dosubmit" value="1"/>
  </go> </anchor>
{#/if#}
{#form set="按钮|名称:dosubmit,值:提交修改" notvs="1"#}
{#form set="尾" notvs="1"#}

<br/>

{#include file="admin/footer.tpl"#}
{#include file="common/footer.tpl"#}
