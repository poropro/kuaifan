{#include file="common/header.tpl" title_top="1" title="数据库工具"#}


[数据库分卷备份]<br/>

	{#form set="头" notvs="1"#}

	每个分卷文件大小:<br/>
	{#form set="输入框|名称:sizelimit{#$TIME2#}" data_value="1024"#}K<br/>


	建表语句格式:<br/>
	{#form set="列表框|名称:sqlcompat{#$TIME2#}" list="'默认':'','MySQL 3.23/4.0.x':'MYSQL40','MySQL 4.1.x/5.x':'MYSQL41'"#}<br/>

	强制字符集:{#form set="列表框|名称:sqlcharset{#$TIME2#}" list="'默认':'0','LATIN1':'latin1','UTF-8':'utf8'" default="0"#}<br/>
	


	{#kuaifan vs="1" set="
	  <anchor title='开始备份数据'>[开始备份数据]
	  <go href='{#get_link()#}' method='post' accept-charset='utf-8'>
	  <postfield name='sizelimit' value='$(sizelimit{#$TIME2#})'/>
	  <postfield name='sqlcompat' value='$(sqlcompat{#$TIME2#})'/>
	  <postfield name='sqlcharset' value='$(sqlcharset{#$TIME2#})'/>
	  <postfield name='dosubmit' value='1'/>
	  </go> </anchor>
	"#}
	
	{#form set="按钮|名称:dosubmit,值:开始备份数据" notvs="1"#}
	{#form set="尾" notvs="1"#}
	<br/><u>备份保存路径:/caches/bakup/default/</u>
	<br/>-------------<br/>
	
	功能:备份-<a href="{#kuaifan getlink='a'#}&amp;a=huanyuan">还原</a>-<a href="{#kuaifan getlink='a'#}&amp;a=youhua">优化</a>-<a href="{#kuaifan getlink='a'#}&amp;a=xiufu">修复</a><br/>

{#include file="admin/footer.tpl"#}
{#include file="common/footer.tpl"#}
