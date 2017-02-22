{#$__seo_head="
	<link type='text/css' rel='stylesheet' href='{#$smarty.const.CSS_PATH#}v3_list.css' />
	<link type='text/css' rel='stylesheet' href='{#$smarty.const.CSS_PATH#}v3_sousuo.css' />
	<script type='text/javascript' src='{#$smarty.const.JS_PATH#}jquery_1.4.2.js'></script>
	<script type='text/javascript' src='{#$smarty.const.JS_PATH#}jquery.alert.js'></script>
"#}
{#include file="common/header.tpl" title="搜索"#}



<div class="sch_new">
	<div class="schWrap">
		<form action="{#get_link('page|key|c')#}" method='post'>
		<input name="key" type="text" class="key" value="{#$smarty.request.key#}" placeholder="输入搜索关键词">
		<input name="dosubmit" value="1" type="hidden">
		<button type="submit" class="submit" value="Search"><i class="i"></i></button>
		</form>
	</div>
</div>



{#include file="common/footerb.html5.tpl"#}
{#include file="common/footer.tpl"#}