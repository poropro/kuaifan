{#include file="common/header.tpl" title_top="1" title="附件管理"#}

[附件管理中心]<br/>
-------------<br/>
<a href="{#kuaifan getlink='a'#}&amp;a=fujianpeizhi">附件上传配置</a><br/>
<a href="{#kuaifan getlink='replace'#}&amp;replace=1">附件地址替换</a><br/>
{#form set="头|地址:'{#str_replace(':','\:',get_link('pp|key'))#}'" notvs="1"#}
{#form set="输入框|名称:key{#$TIME2#},值:'{#$smarty.request.key#}',宽:12"#}
{#kuaifan vs="1" set="
  <anchor>搜索
  <go href='{#get_link('pp|key')#}' method='post' accept-charset='utf-8'>
  <postfield name='key' value='$(key{#$TIME2#})'/>
  <postfield name='dosubmit' value='1'/>
  </go> </anchor>
"#}

{#form set="按钮|名称:dosubmit,值:搜索" notvs="1"#}
{#form set="尾" notvs="1"#}
<br/>

附件名称/所属内容<br/>
{#kuaifan_neirong_fujian set="列表名:lists,显示数目:15,搜索变量名:key,标题长度:15,填补字符:...,分页显示:1,分页名:pagelist,分页变量名:pp,管理:1"#}
{#foreach from=$lists item=list#}
	{#$list._n#}.{#if $list.field=='thumb'#}<img src="{#$smarty.const.IMG_PATH#}jpg.png" />{#/if#}<a href="{#$list.allurl#}" target="_blank">{#$list.name#}</a>/{#if $list.of#}<a href="{#get_link('pp|key|a|catid|edit')#}&amp;catid={#$list.commentid_.2#}&amp;edit={#$list.commentid_.3#}" target="_blank">{#$list.title#}</a><img src="{#$smarty.const.IMG_PATH#}link.png" />{#else#}{#$list.title#}{#/if#}<br/>
	({#$list.format#}/{#formatBytes($list.size,1)#}){#$list.addtime|date_format:"%Y-%m-%d %H:%M:%S"#}<br/>
{#foreachelse#}
	没有任何附件。<br/>
{#/foreach#}

{#$pagelist#}
<br/>
{#include file="admin/footer.tpl"#}
{#include file="common/footer.tpl"#}
