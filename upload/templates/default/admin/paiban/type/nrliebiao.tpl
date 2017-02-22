绑定栏目:{#$lanmusel#}<br/>
显示数量:{#form set="输入框|名称:'body_body{#$TIME2#}',宽:10,值:'{#$value.body#}'" data_value="5"#}<br/>
截取标题:{#form set="输入框|名称:'body_cut{#$TIME2#}',宽:10,值:'{#$value.cut#}'" data_value="0"#}<br/>
填补字符:{#form set="输入框|名称:'body_dot{#$TIME2#}',宽:10,值:'{#$value.dot#}'" data_value="..."#}<br/>
排序方式:{#form set="列表框|名称:'body_order{#$TIME2#}',值:'{#$value.order#}'" default="id" list="默认:'id',最新:'inputtime',动态:'readtime',回复:'replytime',人气:'read',随机:'rand'"#}
{#form set="列表框|名称:'body_asc{#$TIME2#}',值:'{#$value.asc#}'" default="desc" list="降序:'desc',升序:'asc'"#}<br/>
内容筛选:{#form set="列表框|名称:'body_select{#$TIME2#}',值:'{#$value.select#}'" list="不限制:'',有缩略图:'suo',有图片:'tu',有附件:'fu',有摘要:'zhai',精华贴:'jing',置顶贴:'ding',隐藏贴:'yin',派币贴:'pai',悬赏贴:'shang',收费贴:'mai',会员发布:'isuser',后台发布:'isadmin'"#}<br/>
自写模板:<br/>
{#form set="文本框|名称:'body_template{#$TIME2#}',style:'width\:98%; height\:120px;'" data_value="{#$value.template#}" notvs="1"#}
{#form set="输入框|名称:'body_template{#$TIME2#}'" data_value="{#$value.template#}" vs="1"#}<br/>
<a href="{#get_link('vs','',1)#}&amp;m=explain&amp;l=paiban&amp;a=nrliebiao&amp;go_url={#urlencode(get_link('','&'))#}">项目说明&gt;&gt;</a><br/>