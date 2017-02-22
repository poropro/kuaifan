显示数量:{#form set="输入框|名称:'body_body{#$TIME2#}',宽:10,值:'{#$value.body#}'" data_value="5"#}<br/>
截取标题:{#form set="输入框|名称:'body_cut{#$TIME2#}',宽:10,值:'{#$value.cut#}'" data_value="0"#}<br/>
填补字符:{#form set="输入框|名称:'body_dot{#$TIME2#}',宽:10,值:'{#$value.dot#}'" data_value="..."#}<br/>
调用类型:{#form set="列表框|名称:'body_asc{#$TIME2#}',值:'{#$value.asc#}'" default="0" list="所有:'0',仅会员:'1',仅游客:'2'"#}<br/>
自写模板:<br/>
{#form set="文本框|名称:'body_template{#$TIME2#}',style:'width\:98%; height\:120px;'" data_value="{#$value.template#}" notvs="1"#}
{#form set="输入框|名称:'body_template{#$TIME2#}'" data_value="{#$value.template#}" vs="1"#}<br/>
<a href="{#get_link('vs','',1)#}&amp;m=explain&amp;l=paiban&amp;a=dongtai&amp;go_url={#urlencode(get_link('','&'))#}">项目说明&gt;&gt;</a><br/>