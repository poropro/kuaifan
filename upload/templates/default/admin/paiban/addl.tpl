{#include file="common/header.tpl" title_top="1" title="排版设计中心"#}


<a href="{#kuaifan getlink='a|l'#}&amp;a=add">返回重选</a><br/>

[添加-{#ltype($smarty.get.l)#}]<br/>

-------------<br/>
{#form set="头" notvs="1"#}

项目名称:(仅作为后台记录)<br/>{#form set="输入框|名称:'title{#$TIME2#}'"#}<br/>
-------------<br/>

{#include file="admin/paiban/type/{#$smarty.get.l#}.tpl"#}
-------------<br/>

内容前面:(支持ubb,如:[br]自动换行)<br/>{#form set="输入框|名称:'qianmian{#$TIME2#}',最多字符:300"#}<br/>
内容后面:(支持ubb,如:[br]自动换行)<br/>{#form set="输入框|名称:'houmian{#$TIME2#}',最多字符:300"#}<br/>
项目排序:(越大越靠前)<br/>{#form set="输入框|名称:'order{#$TIME2#}'" data_value=$paibanorder#}<br/>
显示版本:(<a href="{#get_link('vs','',1)#}&amp;m=explain&amp;l=paiban&amp;a=wap&amp;go_url={#urlencode(get_link('','&'))#}">说明</a>)<br/>
{#form set="输入框|名称:'wap{#$TIME2#}',最多字符:100,值:0"#}<br/>
登录可见:{#form set="列表框|名称:'islogin{#$TIME2#}'" list="默认:0,仅登录:1,仅游客:2" default="0"#}<br/>
是否显示:{#form set="列表框|名称:'hide{#$TIME2#}'" list="是:0,否:1" default="0"#}<br/>
缓存时长:{#form set="输入框|名称:'nocache{#$TIME2#}',宽:5" data_value="0"#}分(<a href="{#get_link('vs','',1)#}&amp;m=explain&amp;l=paiban&amp;a=nocache&amp;go_url={#urlencode(get_link('','&'))#}">说明</a>)<br/>

{#kuaifan vs="1" set="
  <anchor title='提交'>[提交添加]
  <go href='{#get_link()#}' method='post' accept-charset='utf-8'>
  <postfield name='body_body' value='$(body_body{#$TIME2#})'/>
  <postfield name='body_title' value='$(body_title{#$TIME2#})'/>
  <postfield name='body_picurl' value='$(body_picurl{#$TIME2#})'/>
  <postfield name='body_link' value='$(body_link{#$TIME2#})'/>
  <postfield name='body_picurlarr' value='$(body_picurlarr{#$TIME2#})'/>
  <postfield name='body_keywords' value='$(body_keywords{#$TIME2#})'/>
  <postfield name='body_description' value='$(body_description{#$TIME2#})'/>
  
  <postfield name='body_cut' value='$(body_cut{#$TIME2#})'/>
  <postfield name='body_dot' value='$(body_dot{#$TIME2#})'/>
  <postfield name='body_order' value='$(body_order{#$TIME2#})'/>
  <postfield name='body_asc' value='$(body_asc{#$TIME2#})'/>
  <postfield name='body_select' value='$(body_select{#$TIME2#})'/>
  <postfield name='body_template' value='$(body_template{#$TIME2#})'/>
  
  <postfield name='title' value='$(title{#$TIME2#})'/>
  <postfield name='qianmian' value='$(qianmian{#$TIME2#})'/>
  <postfield name='houmian' value='$(houmian{#$TIME2#})'/>
  <postfield name='order' value='$(order{#$TIME2#})'/>
  <postfield name='hide' value='$(hide{#$TIME2#})'/>
  <postfield name='wap' value='$(wap{#$TIME2#})'/>
  <postfield name='nocache' value='$(nocache{#$TIME2#})'/>
  <postfield name='islogin' value='$(islogin{#$TIME2#})'/>
  <postfield name='dosubmit' value='1'/>
  </go> </anchor>
"#}

{#form set="按钮|名称:dosubmit,值:提交添加" notvs="1"#}
{#form set="尾" notvs="1"#}
<br/>



{#include file="admin/footer.tpl"#}
{#include file="common/footer.tpl"#}