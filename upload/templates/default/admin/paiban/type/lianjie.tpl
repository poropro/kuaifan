友链分类:{#form set="列表框|名称:'body_title{#$TIME2#}'" list=$lianjiearr default="{#$value.title#}"#}<br/>
显示数量:{#form set="输入框|名称:'body_cut{#$TIME2#}',宽:10,值:'{#$value.cut#}'" data_value="1"#}<br/>
显示名称:{#form set="列表框|名称:'body_body{#$TIME2#}'" list="显示简称:0,显示全称:1" default=$value.body#}<br/>
排序方式:{#form set="列表框|名称:'body_order{#$TIME2#}'" list="默认:0,随机:1,访问次数降序:read,访问时间降序:readtime,来访次数降序:from,来访时间降序:fromtime,支持降序:zhichi,不支持降序:buzhichi" default=$value.order#}<br/>
换行间隔:{#form set="列表框|名称:'body_dot{#$TIME2#}'" list="0:0,1:1,2:2,3:3,4:4,5:5,6:6,7:7,8:8,9:9" default=$value.dot#}<br/>
间隔符号:{#form set="输入框|名称:'body_link{#$TIME2#}'" data_value="{#$value.link#}"#}<br/>

<a href="{#get_link('vs','',1)#}&amp;m=explain&amp;l=paiban&amp;a=lianjie&amp;go_url={#urlencode(get_link('','&'))#}">项目说明&gt;&gt;</a><br/>
