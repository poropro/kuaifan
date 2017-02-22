{#include file="common/header.tpl" title_top="1" title="添加模型"#}

[添加模型]<br/>
<a href="{#kuaifan getlink='add'#}">返回</a><br/>
-------------<br/>
{#form set="头" notvs="1"#}

模型名称:<br/>
{#form set="输入框|名称:title{#$TIME2#}"#}<br/>
模型表键名:<br/>
{#form set="输入框|名称:tablename{#$TIME2#}"#}<br/>
描述(后台记忆使用):<br/>
{#form set="输入框|名称:body{#$TIME2#}" vs="1"#}
{#form set="文本框|名称:body{#$TIME2#}" notvs="1"#}<br/>




{#kuaifan vs="1" set="
  <anchor title='提交'>[提交保存]
  <go href='{#get_link()#}' method='post' accept-charset='utf-8'>
  <postfield name='title' value='$(title{#$TIME2#})'/>
  <postfield name='tablename' value='$(tablename{#$TIME2#})'/>
  <postfield name='body' value='$(body{#$TIME2#})'/>
  <postfield name='dosubmit' value='1'/>
  </go> </anchor>
"#}

{#form set="按钮|名称:dosubmit,值:提交保存" notvs="1"#}
{#form set="尾" notvs="1"#}
<br/>

{#include file="admin/footer.tpl"#}
{#include file="common/footer.tpl"#}
