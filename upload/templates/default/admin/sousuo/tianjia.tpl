{#include file="common/header.tpl" title_top="1" title="添加搜索分类"#}

[添加搜索]<br/>
<a href="{#kuaifan getlink='a'#}">返回</a><br/>
-------------<br/>
{#form set="头" notvs="1"#}

所属模块:<br/>
{#form set="列表框|名称:module{#$TIME2#}" list="内容模块:neirong"#}<br/>
所属模型:<br/>
{#form set="列表框|名称:modelid{#$TIME2#}" list=$moxingarr#}<br/>
类型名称:<br/>
{#form set="输入框|名称:name{#$TIME2#}"#}<br/>
排序:(越小越排前)<br/>
{#form set="输入框|名称:listorder{#$TIME2#}"#}<br/>

描述(后台记忆使用):<br/>
{#form set="输入框|名称:description{#$TIME2#}" vs="1"#}
{#form set="文本框|名称:description{#$TIME2#}" notvs="1"#}<br/>




{#kuaifan vs="1" set="
  <anchor title='提交'>[提交添加]
  <go href='{#get_link()#}' method='post' accept-charset='utf-8'>
  <postfield name='module' value='$(module{#$TIME2#})'/>
  <postfield name='modelid' value='$(modelid{#$TIME2#})'/>
  <postfield name='name' value='$(name{#$TIME2#})'/>
  <postfield name='listorder' value='$(listorder{#$TIME2#})'/>
  <postfield name='description' value='$(description{#$TIME2#})'/>
  <postfield name='dosubmit' value='1'/>
  </go> </anchor>
"#}

{#form set="按钮|名称:dosubmit,值:提交添加" notvs="1"#}
{#form set="尾" notvs="1"#}
<br/>

{#include file="admin/footer.tpl"#}
{#include file="common/footer.tpl"#}
