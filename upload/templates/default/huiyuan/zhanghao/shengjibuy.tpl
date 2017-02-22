{#include file="common/header.tpl" title="会员自助升级"#}

<a href="{#kuaifan getlinks='vs|sid'#}&amp;m=huiyuan&amp;c=index">返回会员中心</a><br/>


------------- <br/>
	{#$url = get_link('a|groupid')#}
	<a href="{#$url#}&amp;a=shengji">返回列表</a><br/>.<br/>
  购买时限:<br/>

  {#form set="头" notvs="1"#}
  
  {#form set="输入框|名称:'upgradedate{#$TIME2#}',宽:8"#}{#form set="列表框|名称:'upgradetype{#$TIME2#}'" list="年:y,月:m,日:d"#}<br/>

  {#kuaifan vs="1" set="
    <anchor>提交修改
    <go href='{#get_link()#}' method='post' accept-charset='utf-8'>
    <postfield name='email' value='$(email{#$TIME2#})'/>
    <postfield name='password' value='$(password{#$TIME2#})'/>
    <postfield name='newpassword' value='$(newpassword{#$TIME2#})'/>
    <postfield name='renewpassword' value='$(renewpassword{#$TIME2#})'/>
    <postfield name='dosubmit' value='1'/>
    </go> </anchor>
  "#}
  {#form set="按钮|名称:dosubmit,值:提交修改" notvs="1"#}
  {#form set="尾" notvs="1"#}
  <br/>



{#kuaifan tongji="正在" title="查看自助升级"#}
{#include file="common/footerb.tpl"#}
{#include file="common/footer.tpl"#}
