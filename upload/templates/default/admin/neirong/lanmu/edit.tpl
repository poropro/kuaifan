{#include file="common/header.tpl" title_top="1" title="修改栏目"#}

[修改栏目]<br/>
<a href="{#kuaifan getlink='edit'#}">返回</a><br/>
<a href="{#kuaifan getlink='edit|del'#}&amp;del={#$lanmudb.id#}">!删除栏目</a><br/>
<a href="{#kuaifan getlink='edit|set'#}&amp;set={#$lanmudb.id#}">投稿/权限/收费</a><br/>
-------------<br/>
{#form set="头" notvs="1"#}

选择模型:<br/>
{#$moxingsel#}<br/>
上级栏目:<br/>
{#$lanmusel#}<br/>
栏目名称:<br/>
{#form set="输入框|名称:title{#$TIME2#}" data_value=$lanmudb.title#}<br/>
英文目录:(不可改)<br/>
{#form set="输入框|名称:letter{#$TIME2#}" data_value=$lanmudb.letter#}<br/>
描述:(后台记忆使用)<br/>
{#form set="输入框|名称:body{#$TIME2#}" vs="1" data_value=$lanmudb.body#}
{#form set="文本框|名称:body{#$TIME2#}" notvs="1" data_value=$lanmudb.body#}<br/>
显示排序:(越小越靠前)<br/>
{#form set="输入框|名称:listorder{#$TIME2#}" data_value=$lanmudb.listorder#}<br/>


.<br/>
SEO标题:<br/>
{#form set="输入框|名称:'setting[meta_title]'" notvs="1" data_value=$lanmudbsetting.meta_title#}
{#form set="输入框|名称:'setting_meta_title{#$TIME2#}'" vs="1" data_value=$lanmudbsetting.meta_title#}
<br/>
SEO关键词:<br/>
{#form set="输入框|名称:'setting[meta_keywords]'" notvs="1" data_value=$lanmudbsetting.meta_keywords#}
{#form set="输入框|名称:'setting_meta_keywords{#$TIME2#}'" vs="1" data_value=$lanmudbsetting.meta_keywords#}
<br/>
SEO页面描述:<br/>
{#form set="文本框|名称:'setting[meta_description]'" notvs="1" data_value=$lanmudbsetting.meta_description#}
{#form set="输入框|名称:'setting_meta_description{#$TIME2#}'" vs="1" data_value=$lanmudbsetting.meta_description#}
<br/>

.<br/>
{#* 
模板风格:<br/>
{#form set="输入框|名称:'setting[default_style]'" notvs="1" data_value=$lanmudbsetting.default_style#}
{#form set="输入框|名称:'setting_default_style{#$TIME2#}'" vs="1" data_value=$lanmudbsetting.default_style#}
<br/>
*#}
栏目首页模板:<br/>
{#form set="列表框|名称:'setting[category_template]'" list=$categoryarr notvs="1" default=$lanmudbsetting.category_template#}
{#form set="列表框|名称:'setting_category_template{#$TIME2#}'" list=$categoryarr vs="1" default=$lanmudbsetting.category_template#}
<br/>
栏目列表页模板:<br/>
{#form set="列表框|名称:'setting[list_template]'" list=$listarr notvs="1" default=$lanmudbsetting.list_template#}
{#form set="列表框|名称:'setting_list_template{#$TIME2#}'" list=$listarr vs="1" default=$lanmudbsetting.list_template#}
<br/>
内容页模板:<br/>
{#form set="列表框|名称:'setting[show_template]'" list=$showarr notvs="1" default=$lanmudbsetting.show_template#}
{#form set="列表框|名称:'setting_show_template{#$TIME2#}'" list=$showarr vs="1" default=$lanmudbsetting.show_template#}
<br/>

{#kuaifan vs="1" set="
  <anchor title='提交'>[提交保存]
  <go href='{#get_link()#}' method='post' accept-charset='utf-8'>
  <postfield name='modelid' value='$(modelid{#$TIME2#})'/>
  <postfield name='parentid' value='$(parentid{#$TIME2#})'/>
  <postfield name='title' value='$(title{#$TIME2#})'/>
  <postfield name='letter' value='$(letter{#$TIME2#})'/>
  <postfield name='body' value='$(body{#$TIME2#})'/>
  <postfield name='listorder' value='$(listorder{#$TIME2#})'/>
  
  <postfield name='setting[default_style]' value='$(setting_default_style{#$TIME2#})'/>
  <postfield name='setting[category_template]' value='$(setting_category_template{#$TIME2#})'/>
  <postfield name='setting[list_template]' value='$(setting_list_template{#$TIME2#})'/>
  <postfield name='setting[show_template]' value='$(setting_show_template{#$TIME2#})'/>
  
  <postfield name='setting[meta_title]' value='$(setting_meta_title{#$TIME2#})'/>
  <postfield name='setting[meta_keywords]' value='$(setting_meta_keywords{#$TIME2#})'/>
  <postfield name='setting[meta_description]' value='$(setting_meta_description{#$TIME2#})'/>
  
  <postfield name='dosubmit' value='1'/>
  </go> </anchor>
"#}

{#form set="按钮|名称:dosubmit,值:提交保存" notvs="1"#}
{#form set="尾" notvs="1"#}
<br/>
注:模板风格信息留空则默认按模型设置的。<br/>


{#include file="admin/footer.tpl"#}
{#include file="common/footer.tpl"#}
