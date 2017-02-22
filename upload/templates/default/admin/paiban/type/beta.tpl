切换版本:<br/>
{#form set="输入框|名称:'body_title{#$TIME2#}'" data_value="{#$value.title#}"#}<br/>
切换名称:<br/>
{#form set="输入框|名称:'body_body{#$TIME2#}'" data_value="{#$value.body#}"#}<br/>
分割符号:<br/>
{#form set="输入框|名称:'body_cut{#$TIME2#}'" data_value="{#$value.cut#}"#}<br/>
当前版本无链接:{#form set="列表框|名称:'body_dot{#$TIME2#}'" list="是:0,否:1" default="{#$value.dot#}"#}<br/>
<a href="{#get_link('vs','',1)#}&amp;m=explain&amp;l=paiban&amp;a=beta&amp;go_url={#urlencode(get_link('','&'))#}">项目说明&gt;&gt;</a><br/>
