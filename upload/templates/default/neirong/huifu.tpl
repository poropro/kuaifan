{#include file="common/header.tpl" title="回复评论"#}

{#get_pos($M.id)#}
<br/>

返回:<a href="{#$V.url#}">{#$V.title#}</a>
<br/>
回复该评论：<br/>
{#strip_tags($P.content,"<br/>")#}<br/>

{#form set="头" notvs="1"#}
{#form set="输入框|名称:pl{#$TIME2#}"#}<br/>
{#kuaifan vs="1" set="
	  <anchor>发言
	  <go href='{#get_link()#}' method='post' accept-charset='utf-8'>
      <postfield name='pl' value='$(pl{#$TIME2#})'/>
	  <postfield name='dosubmit' value='1'/>
	  </go> </anchor>
	"#}
{#form set="按钮|名称:dosubmit,值:发言" notvs="1"#}
{#form set="尾" notvs="1"#}<br/>


<a href="{#kf_url('neirongreply')#}">返回评论列表</a>
<br/>

{#include file="common/footerb.tpl"#}
{#include file="common/footer.tpl"#}