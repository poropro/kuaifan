{#include file="common/header.tpl" title_top="1" title="添加广告"#}

[添加广告]<br/>
<a href="{#kuaifan getlink='add'#}">返回</a><br/>
-------------<br/>
{#form set="头" notvs="1"#}

关键词:<br/>
{#form set="输入框|名称:title{#$TIME2#}" data_value=$add.title#}<br/>

关键词替换(≤255个字符):<br/>
{#form set="输入框|名称:url{#$TIME2#}" data_value=$add.url#}<br/>

排序:{#form set="输入框|名称:order{#$TIME2#},宽:3" data_value=$add.order#}<br/>

替换类型:{#form set="列表框|名称:stype{#$TIME2#}" list="原文:'',ubb:ubb,wml:wml,链接:link" default=$add.stype#}<br/>

替换目标:{#form set="列表框|名称:type{#$TIME2#}" list="通用:通用,仅内容:内容,仅评论:评论" default=$add.type#}<br/>

登录替换:{#form set="列表框|名称:islogin{#$TIME2#}" list="通用:0,仅登录:1,仅游客:2" default=$add.islogin#}<br/>

指定替换版本:{#form set="列表框|名称:wap{#$TIME2#}" list="通用:0,简版:1,彩版:2,触屏版:3,平板版:4,电脑版:5" default=$add.wap#}<br/>

{#kuaifan vs="1" set="
  <anchor title='提交'>[提交保存]
  <go href='{#get_link()#}' method='post' accept-charset='utf-8'>
  <postfield name='title' value='$(title{#$TIME2#})'/>
  <postfield name='url' value='$(url{#$TIME2#})'/>
  <postfield name='stype' value='$(stype{#$TIME2#})'/>
  <postfield name='order' value='$(order{#$TIME2#})'/>
  <postfield name='type' value='$(type{#$TIME2#})'/>
  <postfield name='islogin' value='$(islogin{#$TIME2#})'/>
  <postfield name='wap' value='$(wap{#$TIME2#})'/>
  <postfield name='dosubmit' value='1'/>
  </go> </anchor>
"#}

{#form set="按钮|名称:dosubmit,值:提交保存" notvs="1"#}
{#form set="尾" notvs="1"#}
<br/>

{#include file="admin/footer.tpl"#}
{#include file="common/footer.tpl"#}
