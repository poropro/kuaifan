{#$__seo_head="
	<link type='text/css' rel='stylesheet' href='{#$smarty.const.CSS_PATH#}v3_fabu.css' />
	<script type='text/javascript' src='{#$smarty.const.JS_PATH#}jquery_1.4.2.js'></script>
"#}
{#include file="common/header.html.tpl" title_top="1" title="添加内容"#}

[添加内容]<br/>
<a href="{#kuaifan getlink='add'#}">返回.{#$lanmudb.title#}</a><br/>
-------------<br/>

{#form set="头|enctype:'multipart/form-data'"#}

{#foreach from=$ziduandata item=ziduan#}
	{#kuaifan_nr_form($ziduan.type,$ziduan,"<br/>", 3)#}
{#/foreach#}


{#form set="按钮|名称:dosubmit,值:提交发布"#}
{#form set="尾"#}
<br/>


{#include file="admin/footer.html.tpl"#}
{#include file="common/footer.html.tpl"#}
