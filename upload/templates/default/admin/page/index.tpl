{#include file="common/header.tpl" title_top="1" title="页面伪静态"#}


	{#form set="头" notvs="1"#}

	页面:网站首页(index)<br/>
	标记:无<br/>
	格式:{#form set="输入框|名称:'KF_index_rewrite{#$TIME2#}'" data_value=$KF_index.rewrite#}<br/>
	可用(简版):{#form set="列表框|名称:'KF_index_url-1{#$TIME2#}'" list="否:0,是:1" default=$KF_index['url-1']#}
	(彩版):{#form set="列表框|名称:'KF_index_url-2{#$TIME2#}'" list="否:0,是:1" default=$KF_index['url-2']#}
	(触屏版):{#form set="列表框|名称:'KF_index_url-3{#$TIME2#}'" list="否:0,是:1" default=$KF_index['url-3']#}
	(平板版):{#form set="列表框|名称:'KF_index_url-4{#$TIME2#}'" list="否:0,是:1" default=$KF_index['url-4']#}
	(电脑版):{#form set="列表框|名称:'KF_index_url-5{#$TIME2#}'" list="否:0,是:1" default=$KF_index['url-5']#}<br/>
	-----<br/>
	页面:新建页面(paibanpage)<br/>
	标记:($id)<br/>
	格式:{#form set="输入框|名称:'KF_paibanpage_rewrite{#$TIME2#}'" data_value=$KF_paibanpage.rewrite#}<br/>
	可用(简版):{#form set="列表框|名称:'KF_paibanpage_url-1{#$TIME2#}'" list="否:0,是:1" default=$KF_paibanpage['url-1']#}
	(彩版):{#form set="列表框|名称:'KF_paibanpage_url-2{#$TIME2#}'" list="否:0,是:1" default=$KF_paibanpage['url-2']#}
	(触屏版):{#form set="列表框|名称:'KF_paibanpage_url-3{#$TIME2#}'" list="否:0,是:1" default=$KF_paibanpage['url-3']#}
	(平板版):{#form set="列表框|名称:'KF_paibanpage_url-4{#$TIME2#}'" list="否:0,是:1" default=$KF_paibanpage['url-4']#}
	(电脑版):{#form set="列表框|名称:'KF_paibanpage_url-5{#$TIME2#}'" list="否:0,是:1" default=$KF_paibanpage['url-5']#}<br/>
	-----<br/>
	页面:内容列表(neironglist)<br/>
	标记:($catid),($page|1)<br/>
	格式:{#form set="输入框|名称:'KF_neironglist_rewrite{#$TIME2#}'" data_value=$KF_neironglist.rewrite#}<br/>
	可用(简版):{#form set="列表框|名称:'KF_neironglist_url-1{#$TIME2#}'" list="否:0,是:1" default=$KF_neironglist['url-1']#}
	(彩版):{#form set="列表框|名称:'KF_neironglist_url-2{#$TIME2#}'" list="否:0,是:1" default=$KF_neironglist['url-2']#}
	(触屏版):{#form set="列表框|名称:'KF_neironglist_url-3{#$TIME2#}'" list="否:0,是:1" default=$KF_neironglist['url-3']#}
	(平板版):{#form set="列表框|名称:'KF_neironglist_url-4{#$TIME2#}'" list="否:0,是:1" default=$KF_neironglist['url-4']#}
	(电脑版):{#form set="列表框|名称:'KF_neironglist_url-5{#$TIME2#}'" list="否:0,是:1" default=$KF_neironglist['url-5']#}<br/>
	-----<br/>
	页面:内容详情(neirongshow)<br/>
	标记:($catid),($id),($p|1)<br/>
	格式:{#form set="输入框|名称:'KF_neirongshow_rewrite{#$TIME2#}'" data_value=$KF_neirongshow.rewrite#}<br/>
	可用(简版):{#form set="列表框|名称:'KF_neirongshow_url-1{#$TIME2#}'" list="否:0,是:1" default=$KF_neirongshow['url-1']#}
	(彩版):{#form set="列表框|名称:'KF_neirongshow_url-2{#$TIME2#}'" list="否:0,是:1" default=$KF_neirongshow['url-2']#}
	(触屏版):{#form set="列表框|名称:'KF_neirongshow_url-3{#$TIME2#}'" list="否:0,是:1" default=$KF_neirongshow['url-3']#}
	(平板版):{#form set="列表框|名称:'KF_neirongshow_url-4{#$TIME2#}'" list="否:0,是:1" default=$KF_neirongshow['url-4']#}
	(电脑版):{#form set="列表框|名称:'KF_neirongshow_url-5{#$TIME2#}'" list="否:0,是:1" default=$KF_neirongshow['url-5']#}<br/>
	-----<br/>
	页面:评论列表(neirongreply)<br/>
	标记:($catid),($id),($page|1)<br/>
	格式:{#form set="输入框|名称:'KF_neirongreply_rewrite{#$TIME2#}'" data_value=$KF_neirongreply.rewrite#}<br/>
	可用(简版):{#form set="列表框|名称:'KF_neirongreply_url-1{#$TIME2#}'" list="否:0,是:1" default=$KF_neirongreply['url-1']#}
	(彩版):{#form set="列表框|名称:'KF_neirongreply_url-2{#$TIME2#}'" list="否:0,是:1" default=$KF_neirongreply['url-2']#}
	(触屏版):{#form set="列表框|名称:'KF_neirongreply_url-3{#$TIME2#}'" list="否:0,是:1" default=$KF_neirongreply['url-3']#}
	(平板版):{#form set="列表框|名称:'KF_neirongreply_url-4{#$TIME2#}'" list="否:0,是:1" default=$KF_neirongreply['url-4']#}
	(电脑版):{#form set="列表框|名称:'KF_neirongreply_url-5{#$TIME2#}'" list="否:0,是:1" default=$KF_neirongreply['url-5']#}<br/>



	{#kuaifan vs="1" set="
	  <anchor title='提交'>[提交修改]
	  <go href='{#get_link()#}' method='post' accept-charset='utf-8'>
	  <postfield name='site_name' value='$(site_name{#$TIME2#})'/>
	  <postfield name='site_namej' value='$(site_namej{#$TIME2#})'/>
	  <postfield name='index_title' value='$(index_title{#$TIME2#})'/>
	  <postfield name='index_keywords' value='$(index_keywords{#$TIME2#})'/>
	  <postfield name='index_description' value='$(index_description{#$TIME2#})'/>
	  <postfield name='site_domain' value='$(site_domain{#$TIME2#})'/>
	  <postfield name='site_dir' value='$(site_dir{#$TIME2#})'/>
	  <postfield name='inwebway' value='$(inwebway{#$TIME2#})'/>
	  <postfield name='vs' value='$(vs{#$TIME2#})'/>
	  <postfield name='template_dir' value='$(template_dir{#$TIME2#})'/>
	  <postfield name='template_lifetime' value='$(template_lifetime{#$TIME2#})'/>
	  <postfield name='lonline' value='$(lonline{#$TIME2#})'/>
	  <postfield name='regtype' value='$(regtype{#$TIME2#})'/>
	  <postfield name='smsregtxt' value='$(smsregtxt{#$TIME2#})'/>
	  <postfield name='fullmoney' value='$(fullmoney{#$TIME2#})'/>
	  <postfield name='fullmerid' value='$(fullmerid{#$TIME2#})'/>
	  <postfield name='fullkeyvalue' value='$(fullkeyvalue{#$TIME2#})'/>
	  <postfield name='isclose' value='$(isclose{#$TIME2#})'/>
	  <postfield name='close_reason' value='$(close_reason{#$TIME2#})'/>
	  <postfield name='closereg' value='$(closereg{#$TIME2#})'/>
	  <postfield name='dosubmit' value='1'/>
	  </go> </anchor>
	"#}
	
	{#form set="按钮|名称:dosubmit,值:提交修改" notvs="1"#}
	{#form set="尾" notvs="1"#}
	<br/>
	备注:伪静态相关教程请至快范官网查阅。
	<br/>

{#include file="admin/footer.tpl"#}
{#include file="common/footer.tpl"#}
