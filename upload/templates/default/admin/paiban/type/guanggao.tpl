广告:{#form set="列表框|名称:'body_title{#$TIME2#}'" list=$guanggaoarr default="{#$value.title#}"#}<br/>
显示数量:{#form set="输入框|名称:'body_cut{#$TIME2#}',宽:10,值:'{#$value.cut#}'" data_value="1"#}<br/>
排序方式:{#form set="列表框|名称:'body_order{#$TIME2#}'" list="默认:0,随机:1,上线时间:startdate,下线时间:enddate,点击数:clicks" default=$value.order#}<br/>
换行间隔:{#form set="列表框|名称:'body_dot{#$TIME2#}'" list="0:0,1:1,2:2,3:3,4:4,5:5,6:6,7:7,8:8,9:9" default=$value.dot#}<br/>

<a href="{#get_link('vs','',1)#}&amp;m=explain&amp;l=paiban&amp;a=guanggao&amp;go_url={#urlencode(get_link('','&'))#}">项目说明&gt;&gt;</a><br/>
