{#include file="common/header.tpl" title_top="1" title="修改搜索分类"#}

[修改搜索]<br/>
<a href="{#kuaifan getlink='a|id'#}">返回</a><br/>
<a href="{#kuaifan getlink='a'#}&amp;a=shanchu">!删除此分类</a><br/>
-------------<br/>
{#form set="头" notvs="1"#}

所属模块:<br/>
{#form set="列表框|名称:module{#$TIME2#}" list="内容模块:neirong" data_value="{#$xiugai.module#}"#}<br/>
所属模型:<br/>
{#form set="列表框|名称:modelid{#$TIME2#}" list=$moxingarr data_value="{#$xiugai.modelid#}"#}<br/>
类型名称:<br/>
{#form set="输入框|名称:name{#$TIME2#}" data_value="{#$xiugai.name#}"#}<br/>
排序:(越小越排前)<br/>
{#form set="输入框|名称:listorder{#$TIME2#}" data_value="{#$xiugai.listorder#}"#}<br/>

描述(后台记忆使用):<br/>
{#form set="输入框|名称:description{#$TIME2#}" vs="1" data_value="{#$xiugai.description#}"#}
{#form set="文本框|名称:description{#$TIME2#}" notvs="1" data_value="{#$xiugai.description#}"#}<br/>




{#kuaifan vs="1" set="
  <anchor title='提交'>[提交保存]
  <go href='{#get_link()#}' method='post' accept-charset='utf-8'>
  <postfield name='module' value='$(module{#$TIME2#})'/>
  <postfield name='modelid' value='$(modelid{#$TIME2#})'/>
  <postfield name='name' value='$(name{#$TIME2#})'/>
  <postfield name='listorder' value='$(listorder{#$TIME2#})'/>
  <postfield name='description' value='$(description{#$TIME2#})'/>
  <postfield name='dosubmit' value='1'/>
  </go> </anchor>
"#}

{#form set="按钮|名称:dosubmit,值:提交保存" notvs="1"#}
{#form set="尾" notvs="1"#}
<br/>

{#include file="admin/footer.tpl"#}
{#include file="common/footer.tpl"#}
