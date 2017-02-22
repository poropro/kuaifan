{#include file="common/header.tpl" title_top="1" title="网站管理员"#}

	{#if $guanliid.title == '0'#}
		[网站管理员]<br/>
		{#foreach from=$guanliyuan item=list#}
			ID{#$list.id#}.<a href="{#kuaifan getlink='a|id'#}&amp;id={#$list.id#}">[{#$list.rank#}]{#$list.name#}</a>.<a href="{#kuaifan getlinks='m|allow|vs'#}&amp;c=adminrizhi&amp;name={#$list.name#}">日志</a><br/>
		{#foreachelse#}
			没有任何管理员<br/>
		{#/foreach#}
		-------------<br/>
	{#/if#}


	{#form set="头" notvs="1"#}
	{#if $guanliid.title == '1'#}
		<a href="{#kuaifan getlink='id'#}">返回添加</a><br/>
		<a href="{#kuaifan getlink='del'#}&amp;del=1">!删除此帐号</a><br/>
		<b>[修改管理员]</b><br/>
	{#else#}
		[添加管理员]<br/>
	{#/if#}
	帐号:{#form set="输入框|名称:name{#$TIME2#}" data_value=$guanliid.name#}<br/>
	密码:{#form set="密码框|名称:pass{#$TIME2#}"#}<br/>
	头衔:{#form set="输入框|名称:rank{#$TIME2#}" data_value=$guanliid.rank#}<br/>
	{#kuaifan vs="1" set="
	  <anchor title='提交'>[提交保存]
	  <go href='{#get_link()#}' method='post' accept-charset='utf-8'>
	  <postfield name='name' value='$(name{#$TIME2#})'/>
	  <postfield name='pass' value='$(pass{#$TIME2#})'/>
	  <postfield name='rank' value='$(rank{#$TIME2#})'/>
	  <postfield name='dosubmit' value='1'/>
	  </go> </anchor>
	"#}
	
	{#form set="按钮|名称:dosubmit,值:提交保存" notvs="1"#}
	{#form set="尾" notvs="1"#}
	<br/>提示:修改管理员时密码留空则不修改。
	<br/>

{#include file="admin/footer.tpl"#}
{#include file="common/footer.tpl"#}
