<link type='text/css' rel='stylesheet' href='{#$smarty.const.CSS_PATH#}v3_list.css' />
<link type='text/css' rel='stylesheet' href='{#$smarty.const.CSS_PATH#}v3_warning.css' />

<div class="warcon">
	<div class="war-t">{#$body#}</div>
	{#$datalink = str_replace('<br/>','',$datalink)#}
	<div class="war-l" id="war-l">{#$datalink#}</div>
</div>
<script type="text/javascript"> 
	var datalink = document.getElementById("war-l").innerHTML;
	var arr = datalink.match(/<a.*?href=[\'|\"]([^\"]*?)[\'|\"]>([^\"]*?)<\/a>/ig);
	var links = '';
	for(var i=0;i<arr.length;i++){
		links += arr[i];
	}
	if (links) document.getElementById("war-l").innerHTML = links;
</script>
{#include file="common/footerb.html5.tpl"#}
{#include file="common/footer.tpl"#}