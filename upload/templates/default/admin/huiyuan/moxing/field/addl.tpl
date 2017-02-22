{#include file="common/header.tpl" title_top="1" title="添加字段"#}

[添加字段-{#$field.title#}]<br/>
<a href="{#kuaifan getlink='fadd|faddl'#}&amp;fadd=1">返回重选</a><br/>
-------------<br/>
{#form set="头" notvs="1"#}
字段名:(纯英文)<br/>
{#form set="输入框|名称:field{#$TIME2#}"#}<br/>
字段别名:(例:手机型号)<br/>
{#form set="输入框|名称:name{#$TIME2#}"#}<br/>
字段提示:(作为输入提示)<br/>
{#form set="输入框|名称:tips{#$TIME2#}"#}<br/>

----------<br/>

{#if $smarty.get.faddl=='text'#}
	类型:单行文本<br/>
	默认值:{#form set="输入框|名称:setting_defaultvalue{#$TIME2#}"#}<br/>
	是否为密码框:{#form set="列表框|名称:setting_ispassword{#$TIME2#}" list="否:0,是:1" default="0"#}
{#elseif $smarty.get.faddl=='textarea'#}
	类型:多行文本<br/>
	默认值:{#form set="输入框|名称:setting_defaultvalue{#$TIME2#}"#}<br/>
	是否允许WML文本:{#form set="列表框|名称:setting_enablehtml{#$TIME2#}" list="否:0,是:1" default="0"#}
{#elseif $smarty.get.faddl=='box'#}
	类型:选项<br/>
	选项列表:(格式:选项名称1|选项值1)<br/>
	{#form set="输入框|名称:setting_options{#$TIME2#},行数:5" data_value="选项名称1|选项值1" vs="1"#}
	{#form set="文本框|名称:setting_options{#$TIME2#},行数:5" data_value="选项名称1|选项值1" notvs="1"#}<br/>
	注明:两个选项之间用“换行符”或者“///”分割<br/>
	默认值:{#form set="输入框|名称:setting_defaultvalue{#$TIME2#}"#}<br/>
	字段类型:{#form set="列表框|名称:setting_fieldtype{#$TIME2#}" list="'字符 VARCHAR':'varchar','整数 TINYINT(3)':'tinyint','整数 SMALLINT(5)':'smallint','整数 MEDIUMINT(8)':'mediumint','整数 INT(10)':'int'" default="varchar"#}<br/>
	输出格式:{#form set="列表框|名称:setting_outputtype{#$TIME2#}" list="输出选项值:0,输出选项名称:1" default="0"#}	
{#elseif $smarty.get.faddl=='number'#}
	类型:数字<br/>
	取值范围:{#form set="输入框|名称:setting_minnumber{#$TIME2#},宽:5,值:1"#}-{#form set="输入框|名称:setting_maxnumber{#$TIME2#},宽:5"#}<br/>
	小数位数:{#form set="列表框|名称:setting_decimaldigits{#$TIME2#}" list="0:0,1:1,2:2,3:3,4:4,5:5" default="0"#}<br/>
	默认值:{#form set="输入框|名称:setting_defaultvalue{#$TIME2#}"#}
{#elseif $smarty.get.faddl=='datetime'#}
	类型:日期和时间<br/>
	时间格式:{#$tempdatetime#}
{#elseif $smarty.get.faddl=='images'#}
	类型:图片上传<br/>
	上传图片类型:{#form set="输入框|名称:setting_upload_allowext{#$TIME2#}" data_value="gif|jpg|jpeg|png|bmp"#}<br/>
	最多上传个数:{#form set="输入框|名称:setting_upload_number{#$TIME2#}" data_value="10"#} 
{#elseif $smarty.get.faddl=='downfile'#}
	类型:文件上传<br/>
	上传文件类型:{#form set="输入框|名称:setting_upload_allowext{#$TIME2#}" data_value="rar|zip|jar|apk|7z"#}<br/>
	最多上传个数:{#form set="输入框|名称:setting_upload_number{#$TIME2#}" data_value="10"#}<br/>
	文件下载方式:{#form set="列表框|名称:setting_downloadtype{#$TIME2#}" list="链接文件地址:0,通过PHP读取:1" default="1"#}
{#/if#}
<br/>
----------<br/>
字符长度取值范围:<br/>
最小值:{#form set="输入框|名称:minlength{#$TIME2#},宽:5"#},
最大值:{#form set="输入框|名称:maxlength{#$TIME2#},宽:5"#}<br/>
系统将在表单提交时检测数据长度范围是否符合要求，如果不想限制长度请留空或填0。<br/>
字段排序:(越小越靠前)<br/>
{#form set="输入框|名称:listorder{#$TIME2#}"#}<br/>



{#kuaifan vs="1" set="
  <anchor title='提交'>[确定添加]
  <go href='{#get_link()#}' method='post' accept-charset='utf-8'>
	<postfield name='issystem' value='$(issystem{#$TIME2#})'/>
	<postfield name='field' value='$(field{#$TIME2#})'/>
	<postfield name='name' value='$(name{#$TIME2#})'/>
	<postfield name='tips' value='$(tips{#$TIME2#})'/>
	
	<postfield name='setting_defaultvalue' value='$(setting_defaultvalue{#$TIME2#})'/>
	<postfield name='setting_ispassword' value='$(setting_ispassword{#$TIME2#})'/>
	<postfield name='setting_enablehtml' value='$(setting_enablehtml{#$TIME2#})'/>
	<postfield name='setting_options' value='$(setting_options{#$TIME2#})'/>
	<postfield name='setting_boxtype' value='$(setting_boxtype{#$TIME2#})'/>
	<postfield name='setting_fieldtype' value='$(setting_fieldtype{#$TIME2#})'/>
	<postfield name='setting_outputtype' value='$(setting_outputtype{#$TIME2#})'/>
	<postfield name='setting_minnumber' value='$(setting_minnumber{#$TIME2#})'/>
	<postfield name='setting_maxnumber' value='$(setting_maxnumber{#$TIME2#})'/>
	<postfield name='setting_decimaldigits' value='$(setting_decimaldigits{#$TIME2#})'/>
	<postfield name='setting_format' value='$(setting_format{#$TIME2#})'/>
	<postfield name='setting_upload_allowext' value='$(setting_upload_allowext{#$TIME2#})'/>
	<postfield name='setting_upload_number' value='$(setting_upload_number{#$TIME2#})'/>
	<postfield name='setting_downloadtype' value='$(setting_downloadtype{#$TIME2#})'/>
	
	<postfield name='minlength' value='$(minlength{#$TIME2#})'/>
	<postfield name='maxlength' value='$(maxlength{#$TIME2#})'/>
	<postfield name='listorder' value='$(listorder{#$TIME2#})'/>
	<postfield name='dosubmit' value='1'/>
  </go> </anchor>
"#}

{#form set="按钮|名称:dosubmit,值:确定添加" notvs="1"#}
{#form set="尾" notvs="1"#}
<br/>


{#include file="admin/footer.tpl"#}
{#include file="common/footer.tpl"#}
