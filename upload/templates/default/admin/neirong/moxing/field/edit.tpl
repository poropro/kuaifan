{#include file="common/header.tpl" title_top="1" title="修改字段"#}

[修改字段-{#$field.title#}]<br/>
<a href="{#kuaifan getlink='fedit'#}">返回列表</a><br/>
-------------<br/>
{#form set="头" notvs="1"#}
存放位置:<br/>
{#if $ziduan.issystem=='1'#}
	{#form set="列表框|名称:issystem{#$TIME2#}" list="主表字段:1" default="1"#}<br/>
{#else#}
	{#form set="列表框|名称:issystem{#$TIME2#}" list="副表字段:0" default="0"#}<br/>
{#/if#}
字段名:(不可改)<br/>
{#form set="输入框|名称:field{#$TIME2#}" data_value=$ziduan.field#}<br/>
字段别名:(例:文章标题)<br/>
{#form set="输入框|名称:name{#$TIME2#}" data_value=$ziduan.name#}<br/>
字段提示:(作为输入提示)<br/>
{#form set="输入框|名称:tips{#$TIME2#}" data_value=$ziduan.tips#}<br/>

----------<br/>

{#if $ziduan.del=='0' || $ziduan.type=='downfile'#}
	{#if $ziduan.type=='text'#}
		类型:单行文本<br/>
		默认值:{#form set="输入框|名称:setting_defaultvalue{#$TIME2#}" data_value=$setting.defaultvalue#}<br/>
		是否为密码框:{#form set="列表框|名称:setting_ispassword{#$TIME2#}" list="否:0,是:1" default="0" data_value=$setting.ispassword#}
	{#elseif $ziduan.type=='textarea'#}
		类型:多行文本<br/>
		默认值:{#form set="输入框|名称:setting_defaultvalue{#$TIME2#}" data_value=$setting.defaultvalue#}<br/>
		是否允许WML文本:{#form set="列表框|名称:setting_enablehtml{#$TIME2#}" list="否:0,是:1" default="0" data_value=$setting.enablehtml#}
	{#elseif $ziduan.type=='box'#}
		类型:选项<br/>
		选项列表:(格式:选项名称1|选项值1)<br/>
		{#form set="输入框|名称:setting_options{#$TIME2#},行数:5" vs="1" data_value=$setting.options_vs1#}
		{#form set="文本框|名称:setting_options{#$TIME2#},行数:5" notvs="1" data_value=$setting.options#}<br/>
		注明:两个选项之间用“换行符”或者“///”分割<br/>
		默认值:{#form set="输入框|名称:setting_defaultvalue{#$TIME2#}" data_value=$setting.defaultvalue#}<br/>
		选项类型:{#form set="列表框|名称:setting_boxtype{#$TIME2#}" list="单选:0,多选:1" default="0" data_value=$setting.boxtype#}<br/>
		{#if $setting.fieldtype=='varchar'#}
			字段类型:{#form set="列表框|名称:setting_fieldtype{#$TIME2#}" list="'字符 VARCHAR':'varchar'" default="varchar"#}<br/>
		{#/if#}
		{#if $setting.fieldtype=='tinyint'#}
			字段类型:{#form set="列表框|名称:setting_fieldtype{#$TIME2#}" list="'整数 TINYINT(3)':'tinyint'" default="tinyint"#}<br/>
		{#/if#}
		{#if $setting.fieldtype=='smallint'#}
			字段类型:{#form set="列表框|名称:setting_fieldtype{#$TIME2#}" list="'整数 SMALLINT(5)':'smallint'" default="smallint"#}<br/>
		{#/if#}
		{#if $setting.fieldtype=='mediumint'#}
			字段类型:{#form set="列表框|名称:setting_fieldtype{#$TIME2#}" list="'整数 MEDIUMINT(8)':'mediumint'" default="mediumint"#}<br/>
		{#/if#}
		{#if $setting.fieldtype=='int'#}
			字段类型:{#form set="列表框|名称:setting_fieldtype{#$TIME2#}" list="'整数 INT(10)':'int'" default="int"#}<br/>
		{#/if#}
		输出格式:{#form set="列表框|名称:setting_outputtype{#$TIME2#}" list="输出选项值:0,输出选项名称:1" default="0" data_value=$setting.outputtype#}
	{#elseif $ziduan.type=='number'#}
		类型:数字<br/>
		取值范围:{#form set="输入框|名称:setting_minnumber{#$TIME2#},宽:5,值:1" data_value=$setting.minnumber#}-{#form set="输入框|名称:setting_maxnumber{#$TIME2#},宽:5" data_value=$setting.maxnumber#}<br/>
		小数位数:{#form set="列表框|名称:setting_decimaldigits{#$TIME2#}" list="0:0,1:1,2:2,3:3,4:4,5:5" default="0" data_value=$setting.decimaldigits#}<br/>
		默认值:{#form set="输入框|名称:setting_defaultvalue{#$TIME2#}" data_value=$setting.defaultvalue#}
	{#elseif $ziduan.type=='datetime'#}
		类型:日期和时间<br/>
		时间格式:{#$setting.format#}
	{#elseif $ziduan.type=='images'#}
		类型:图片上传<br/>
		上传图片类型:{#form set="输入框|名称:setting_upload_allowext{#$TIME2#}" data_value="gif|jpg|jpeg|png|bmp" data_value=$setting.upload_allowext#}<br/>
		最多上传个数:{#form set="输入框|名称:setting_upload_number{#$TIME2#}" data_value="10" data_value=$setting.upload_number#} <br/>
		无缩略图时第一张图片作为:{#form set="列表框|名称:setting_uponethumb{#$TIME2#}" list="否:0,是:1" default=$setting.uponethumb#} <br/>
		目录储存模式:{#form set="列表框|名称:setting_pathlist{#$TIME2#}" list="否:0,是:1" default="0" data_value=$setting.pathlist#}
		(<a href="{#kuaifan getlinks='vs'#}&amp;m=explain&amp;l=wenjian&amp;a=mulu&amp;go_url={#urlencode(get_link('','&'))#}">说明</a>)
		{#if $setting.pathlist#}
			<br/>储存位置: {#$KUAIFAN.site_dir#}uploadfiles/content/栏目ID/内容ID/
		{#/if#}
	{#elseif $ziduan.type=='downfile'#}
		类型:文件上传<br/>
		上传文件类型:{#form set="输入框|名称:setting_upload_allowext{#$TIME2#}" data_value="rar|zip|jar|apk|7z" data_value=$setting.upload_allowext#}<br/>
		最多上传个数:{#form set="输入框|名称:setting_upload_number{#$TIME2#}" data_value="10" data_value=$setting.upload_number#}<br/>
		文件下载方式:{#form set="列表框|名称:setting_downloadtype{#$TIME2#}" list="链接文件地址:0,通过PHP读取:1" default=$setting.downloadtype#}<br/>
		无缩略图时第一张图片作为:{#form set="列表框|名称:setting_uponethumb{#$TIME2#}" list="否:0,是:1" default=$setting.uponethumb#}<br/>
		目录储存模式:{#form set="列表框|名称:setting_pathlist{#$TIME2#}" list="否:0,是:1" default="0" data_value=$setting.pathlist#}
		(<a href="{#kuaifan getlinks='vs'#}&amp;m=explain&amp;l=wenjian&amp;a=mulu&amp;go_url={#urlencode(get_link('','&'))#}">说明</a>)
		{#if $setting.pathlist#}
			<br/>储存位置: {#$KUAIFAN.site_dir#}uploadfiles/content/栏目ID/内容ID/
		{#/if#}
	{#elseif $ziduan.type=='wanneng'#}
		类型:万能字段<br/>
		字段表单:<br/>
		{#form set="输入框|名称:setting_formtext{#$TIME2#}" data_value=$setting.formtext vs="1"#}
		{#form set="文本框|名称:setting_formtext{#$TIME2#}" data_value=$setting.formtext notvs="1"#}<br/>
		{#literal#}
			<u>
				1.别名、提示无效,请直接表单里<br/>
				2.支持HTML、{#wap=x#}{#/wap#}标签<br/>
				3.例如:&lt;input type=&quot;text&quot; name=&quot;{#字段名#}&quot; value=&quot;{#默认值#}&quot;/&gt;<br/>
			</u>
		{#/literal#}
		{#if $setting.fieldtype=='varchar'#}
			字段类型:{#form set="列表框|名称:setting_fieldtype{#$TIME2#}" list="'字符 VARCHAR':'varchar'" default="varchar"#}
		{#/if#}
		{#if $setting.fieldtype=='tinyint'#}
			字段类型:{#form set="列表框|名称:setting_fieldtype{#$TIME2#}" list="'整数 TINYINT(3)':'tinyint'" default="tinyint"#}
		{#/if#}
		{#if $setting.fieldtype=='smallint'#}
			字段类型:{#form set="列表框|名称:setting_fieldtype{#$TIME2#}" list="'整数 SMALLINT(5)':'smallint'" default="smallint"#}
		{#/if#}
		{#if $setting.fieldtype=='mediumint'#}
			字段类型:{#form set="列表框|名称:setting_fieldtype{#$TIME2#}" list="'整数 MEDIUMINT(8)':'mediumint'" default="mediumint"#}
		{#/if#}
		{#if $setting.fieldtype=='int'#}
			字段类型:{#form set="列表框|名称:setting_fieldtype{#$TIME2#}" list="'整数 INT(10)':'int'" default="int"#}
		{#/if#}
		{#if $setting.fieldtype=='text'#}
			字段类型:{#form set="列表框|名称:setting_fieldtype{#$TIME2#}" list="'文本 TEXT':'text'" default="text"#}
		{#/if#}<br/>
		默认值:{#form set="输入框|名称:setting_defaultvalue{#$TIME2#}" data_value=$setting.defaultvalue#}<br/>
		换行替换br:{#form set="列表框|名称:setting_newline2br{#$TIME2#}" list="替换:0,不替换:1" default=$setting.newline2br#}
	{#/if#}
	<br/>
	----------<br/>
{#/if#}
{#if $ziduan.field=='content'#}
	使用标签:{#form set="列表框|名称:setting_nrbiaoqian{#$TIME2#}" list="不使用:0,ubb:ubb,wml:wml,htmlspecialchars:htmlspecialchars,strip_tags:strip_tags" default=$setting.nrbiaoqian#}<br/>
	换行替换br:{#form set="列表框|名称:setting_newline2br{#$TIME2#}" list="替换:0,不替换:1" default=$setting.newline2br#}<br/>
	输入框大小:{#form set="输入框|名称:setting_input_kuan{#$TIME2#},宽:3" data_value="0" data_value=$setting.input_kuan#}宽 x
	{#form set="输入框|名称:setting_input_gao{#$TIME2#},宽:3" data_value="0" data_value=$setting.input_gao#}高<br/>
	内容图片自动缩放:{#form set="输入框|名称:setting_tu_kuan{#$TIME2#},宽:3" data_value="0" data_value=$setting.tu_kuan#}宽 x
	{#form set="输入框|名称:setting_tu_gao{#$TIME2#},宽:3" data_value="0" data_value=$setting.tu_gao#}高<br/>
	----------<br/>
{#elseif $ziduan.type=='textarea'#}
	{#if $ziduan.issystem=='0'#}
		换行替换br:{#form set="列表框|名称:setting_newline2br{#$TIME2#}" list="替换:0,不替换:1" default=$setting.newline2br#}<br/>
	{#/if#}
	输入框大小:{#form set="输入框|名称:setting_input_kuan{#$TIME2#},宽:3" data_value="0" data_value=$setting.input_kuan#}宽 x
	{#form set="输入框|名称:setting_input_gao{#$TIME2#},宽:3" data_value="0" data_value=$setting.input_gao#}高<br/>
	----------<br/>
{#elseif $ziduan.type=='pages'#}
	默认值:{#form set="输入框|名称:setting_defaultvalue{#$TIME2#}" data_value=$setting.defaultvalue#}<br/>
	----------<br/>
{#/if#}


字符长度取值范围:<br/>
最小值:{#form set="输入框|名称:minlength{#$TIME2#},宽:5" data_value=$ziduan.minlength#},
最大值:{#form set="输入框|名称:maxlength{#$TIME2#},宽:5" data_value=$ziduan.maxlength#}<br/>
系统将在表单提交时检测数据长度范围是否符合要求，如果不想限制长度请留空或填0。<br/>
字段排序:(越小越靠前)<br/>
{#form set="输入框|名称:listorder{#$TIME2#}" data_value=$ziduan.listorder#}<br/>
在前台投稿中显示:{#form set="列表框|名称:isadd{#$TIME2#}" list="是:1,否:0" default=$ziduan.isadd#}	<br/>



{#kuaifan vs="1" set="
  <anchor title='提交'>[确定修改]
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
	<postfield name='setting_pathlist' value='$(setting_pathlist{#$TIME2#})'/>
	<postfield name='setting_uponethumb' value='$(setting_uponethumb{#$TIME2#})'/>
	<postfield name='setting_nrbiaoqian' value='$(setting_nrbiaoqian{#$TIME2#})'/>
	<postfield name='setting_newline2br' value='$(setting_newline2br{#$TIME2#})'/>
	<postfield name='setting_tu_kuan' value='$(setting_tu_kuan{#$TIME2#})'/>
	<postfield name='setting_tu_gao' value='$(setting_tu_gao{#$TIME2#})'/>
	<postfield name='setting_input_kuan' value='$(setting_input_kuan{#$TIME2#})'/>
	<postfield name='setting_input_gao' value='$(setting_input_gao{#$TIME2#})'/>
	
	<postfield name='minlength' value='$(minlength{#$TIME2#})'/>
	<postfield name='maxlength' value='$(maxlength{#$TIME2#})'/>
	<postfield name='listorder' value='$(listorder{#$TIME2#})'/>
	<postfield name='isadd' value='$(isadd{#$TIME2#})'/>
	<postfield name='dosubmit' value='1'/>
  </go> </anchor>
"#}

{#form set="按钮|名称:dosubmit,值:确定修改" notvs="1"#}
{#form set="尾" notvs="1"#}
<br/>


{#include file="admin/footer.tpl"#}
{#include file="common/footer.tpl"#}
