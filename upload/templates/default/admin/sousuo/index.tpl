{#include file="common/header.tpl" title_top="1" title="重建索引"#}

[重建索引]<br/>
-------------<br/>
{#form set="头" notvs="1"#}
重建索引将会清空原有的所有的索引内容。<br/>
每轮更新:(条)<br/>
{#form set="输入框|名称:pagesize{#$TIME2#},值:100"#}<br/>


{#kuaifan vs="1" set="
  <anchor title='提交'>[确认重建索引]
  <go href='{#get_link()#}' method='post' accept-charset='utf-8'>
  <postfield name='pagesize' value='$(pagesize{#$TIME2#})'/>
  <postfield name='dosubmit' value='1'/>
  </go> </anchor>
"#}

{#form set="按钮|名称:dosubmit,值:确认重建索引" notvs="1"#}
{#form set="尾" notvs="1"#}
<br/>
注意: 重建索引期间会比较占用服务器资源。<br/>

{#include file="admin/footer.tpl"#}
{#include file="common/footer.tpl"#}
