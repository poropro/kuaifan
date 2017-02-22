{#include file="common/header.tpl" title_top="1" title="新建文件"#}

<a href="{#kuaifan getlink='a|f'#}&amp;a=index&amp;f={#urlencode($smarty.get.f)#}">返回列表</a><br/>
-------------<br/>
{#form set="头" notvs="1"#}
{#if $smarty.post.dosubmit#}
<b>请输入名称！</b><br/>
{#/if#}
名称:{#form set="输入框|名称:newfile{#$TIME2#}"#}<br/>

{#kuaifan vs="1" set="
  <anchor>确定新建
  <go href='{#get_link()#}' method='post' accept-charset='utf-8'>
  <postfield name='newfile' value='$(newfile{#$TIME2#})'/>
  <postfield name='dosubmit' value='1'/>
  </go> </anchor>
"#}
{#form set="按钮|名称:dosubmit,值:确定新建" notvs="1"#}
{#form set="尾" notvs="1"#}
<br/>
1.请输入名称，无需输入扩展名。<br/>
2.名称只能为数字、字母、点、下划线。<br/>
3.本功能只能创建不可删除，建议通过FTP工具操作。<br/>
4.当前目录FTP地址{#$f#}。<br/>

{#include file="admin/footer.tpl"#}
{#include file="common/footer.tpl"#}
