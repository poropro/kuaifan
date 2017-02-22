{#include file="common/header.html.tpl" title_top="1" title="添加表情"#}

<a href="{#kuaifan getlink='a'#}">返回列表</a><br/>
-------------<br/>

	{#form set="头|enctype:'multipart/form-data'"#}

	分类:{#form set="列表框|名称:catid" list=$fenleiarr#}<br/>
	代号:{#form set="输入框|名称:em,宽:8"#}<br/>
	排序:{#form set="输入框|名称:listorder,宽:8" data_value="0"#}(越大越靠前)<br/>
	隐藏:{#form set="列表框|名称:is" list="否:0,是:1" data_value="0"#}<br/>
	表情:{#form set="文件|名称:file"#}<br/>
	
	{#form set="按钮|名称:dosubmit,值:提交保存"#}
	{#form set="尾"#}
	<br/>
	注意:代号建议使用英文或数字,请勿填特殊字符。
	<br/>

{#include file="admin/footer.html.tpl"#}
{#include file="common/footer.html.tpl"#}
