{#include file="common/header.tpl" title_top="1" title="附件配置"#}


	{#form set="头|enctype:'multipart/form-data'"#}

	允许上传附件大小:(服务器最大限制:{#$system_info.max_filesize#})<br/>
	{#form set="输入框|名称:upload_maxsize{#$TIME2#},宽:5" data_value=$peizhi.upload_maxsize#}KB <br/>

	GD库功能检测:(如果不支持则无法处理图片)<br/>
	{#$system_info.gd#} <br/>
	
	---------<br/>
	
	是否开启图片水印:<br/>
	{#form set="列表框|名称:watermark_enable{#$TIME2#}" list="不启用:0,启用:1" default=$peizhi.watermark_enable#}<br/>

	水印添加条件:(达到此条件才添加水印)<br/>
	宽{#form set="输入框|名称:watermark_minwidth{#$TIME2#},宽:5" data_value=$peizhi.watermark_minwidth#}X 高{#form set="输入框|名称:watermark_minheight{#$TIME2#},宽:5" data_value=$peizhi.watermark_minheight#}PX<br/>

	水印图片:{#if $peizhi.watermark_img#}<a href="{#$peizhi.watermark_img#}">{#$peizhi.watermark_img#}</a>{#/if#}<br/>
	{#form set="文件|名称:watermark_img{#$TIME2#}"#}<br/>

	水印透明度:(请设置为0-100之间的数字,0代表完全透明,100代表不透明)<br/>
	{#form set="输入框|名称:watermark_pct{#$TIME2#}" data_value=$peizhi.watermark_pct#}<br/>
	
	JPEG 水印质量:(水印质量请设置为0-100之间的数字,决定 jpg 格式图片的质量)<br/>
	{#form set="输入框|名称:watermark_quality{#$TIME2#}" data_value=$peizhi.watermark_quality#}<br/>
	
	
	水印位置:<br/>
	{#form set="列表框|名称:watermark_pos{#$TIME2#}" list=$system_info.watermark_pos default=$peizhi.watermark_pos#}<br/>

	---------<br/>

	图片文件进行缩放:<br/>
	宽{#form set="输入框|名称:thumb_width{#$TIME2#},宽:5" data_value=$peizhi.thumb_width#}X 高{#form set="输入框|名称:thumb_height{#$TIME2#},宽:5" data_value=$peizhi.thumb_height#}PX<br/>
	(留空或填0不缩放，此缩放功能保持原图的比例。)<br/>


	{#kuaifan vs="1" set="
	  <anchor title='提交'>[提交修改]
	  <go href='{#get_link()#}' method='post' accept-charset='utf-8'>
	  <postfield name='upload_maxsize' value='$(upload_maxsize{#$TIME2#})'/>
	  <postfield name='watermark_enable' value='$(watermark_enable{#$TIME2#})'/>
	  <postfield name='watermark_minwidth' value='$(watermark_minwidth{#$TIME2#})'/>
	  <postfield name='watermark_img' value='$(watermark_img{#$TIME2#})'/>
	  <postfield name='watermark_pct' value='$(watermark_pct{#$TIME2#})'/>
	  <postfield name='watermark_quality' value='$(watermark_quality{#$TIME2#})'/>
	  <postfield name='watermark_pos' value='$(watermark_pos{#$TIME2#})'/>
	  <postfield name='thumb_width' value='$(thumb_width{#$TIME2#})'/>
	  <postfield name='thumb_height' value='$(thumb_height{#$TIME2#})'/>
	  <postfield name='dosubmit' value='1'/>
	  </go> </anchor>
	"#}
	
	{#form set="按钮|名称:dosubmit,值:提交修改" notvs="1"#}
	{#form set="尾" notvs="1"#}
	<br/>
	

{#include file="admin/footer.tpl"#}
{#include file="common/footer.tpl"#}
