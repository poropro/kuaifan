{#$__seo_head="
	<link type='text/css' rel='stylesheet' href='{#$smarty.const.CSS_PATH#}v2_list.css' />
"#}
{#include file="common/header.tpl" title="回复评论"#}

<div class="daohang" id="top">
{#get_pos($M.id)#}
</div>

<div class="pltit"><a href="{#$V.url#}">{#$V.title#}</a></div>


<div class="review">
<font color="#666666">回复该评论：</font><br/>
{#strip_tags($P.content,"<br/>")#}<br/>

{#form set="头" notvs="1"#}
{#form set="文本框|名称:pl{#$TIME2#}"#}<br/>
{#kuaifan vs="1" set="
	  <anchor>回复发言
	  <go href='{#get_link()#}' method='post' accept-charset='utf-8'>
      <postfield name='pl' value='$(pl{#$TIME2#})'/>
	  <postfield name='dosubmit' value='1'/>
	  </go> </anchor>
	"#}
{#form set="按钮|名称:dosubmit,值:回复发言" notvs="1"#}
{#form set="尾" notvs="1"#}
</div>


<div class="pnpage">
<a href="{#kf_url('neirongreply')#}">返回评论列表</a>
</div>

{#include file="common/footerb.html.tpl"#}
{#include file="common/footer.tpl"#}