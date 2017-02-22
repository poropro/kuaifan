{#include file="common/header.tpl" title_top="1" title="友情链接"#}

[友情链接配置]<br/>
-------------<br/>
{#$url = get_link('m|allow|vs|c','','1')#}
<a href="{#$url#}&amp;a=index">返回</a><br/>

{#form set="头" notvs="1"#}
游客申请友链:{#form set="列表框|名称:denglu{#$TIME2#}" list="禁止:0,允许:1" data_value=$peizhi.denglu#}<br/>
出站防刷方式:{#form set="列表框|名称:shuachu{#$TIME2#}" list="cookie:cookie,session:session,'ip(推荐)':ip" data_value=$peizhi.shuachu#}<br/>
进站防刷方式:{#form set="列表框|名称:shuajin{#$TIME2#}" list="cookie:cookie,session:session,'ip(推荐)':ip" data_value=$peizhi.shuajin#}<br/>

{#kuaifan vs="1" set="
  <anchor>保存配置
  <go href='{#get_link()#}' method='post' accept-charset='utf-8'>
  <postfield name='denglu' value='$(denglu{#$TIME2#})'/>
  <postfield name='shuachu' value='$(shuachu{#$TIME2#})'/>
  <postfield name='shuajin' value='$(shuajin{#$TIME2#})'/>
  <postfield name='dosubmit' value='1'/>
  </go> </anchor>
"#}

{#form set="按钮|名称:dosubmit,值:保存配置" notvs="1"#}
{#form set="尾" notvs="1"#}
<br/>

{#include file="admin/footer.tpl"#}
{#include file="common/footer.tpl"#}
