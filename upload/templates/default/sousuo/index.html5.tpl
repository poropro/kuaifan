{#$__seo_head="
	<link type='text/css' rel='stylesheet' href='{#$smarty.const.CSS_PATH#}v3_list.css' />
	<link type='text/css' rel='stylesheet' href='{#$smarty.const.CSS_PATH#}v3_sousuo.css' />
	<script type='text/javascript' src='{#$smarty.const.JS_PATH#}jquery_1.4.2.js'></script>
	<script type='text/javascript' src='{#$smarty.const.JS_PATH#}jquery.alert.js'></script>
"#}
{#include file="common/header.tpl" title="{#$smarty.request.key#} - 搜索"#}



<div class="sch_new">
	<div class="schWrap">
		<form action="{#get_link('page|key')#}" method='post'>
		<input name="key" type="text" class="key" value="{#$smarty.request.key#}" placeholder="{#$KUAIFAN.site_namej#}搜索">
		<input name="dosubmit" value="1" type="hidden">
		<button type="submit" class="submit" value="Search"><i class="i"></i></button>
		</form>
	</div>
</div>


<div class="nlist">
<ul id="listHtml">
<!-- list start -->
{#kuaifan_sousuo set="列表名:lists,显示数目:10,搜索变量名:key,标题长度:18,填补字符:...,分页显示:1,分页名:pagelist,分页变量名:page"#}
{#foreach from=$lists item=list#}
	<li class="s">
		<p><span>{#$list._n#}</span><a href="{#$list.url#}&amp;key={#$smarty.request.key|urlencode#}">{#key_color($list.title,$smarty.request.key)#}</a></p>
		<p>{#key_color(htmlneirong($list.description,40,$smarty.request.key),$smarty.request.key)#}</p>
		<p class="sj">发布时间:{#$list.adddate|date_format:"%Y-%m-%d %H:%M"#}</p>
	</li>
{#foreachelse#}
	<li class="s n">
		<p>抱歉,没有找到"{#$smarty.request.key#}"相关的文章内容.建议您:</p>
		<p>1.查看输入关键词是否有误;</p>
		<p>2.简化输入关键词,如"北京的人口是由什么构成的"简化为"北京 人口 构成".</p>
	</li>
{#/foreach#}
<!-- list end -->
</ul>
</div>

{#if $lists#}
	<div class="showMore"><a href="javascript:loadListMore()" id="linktxt">查看更多</a></div>
{#/if#}
<div id="back-top" style="display: block;"><a href="#top"><span></span></a></div>


<div class="subn-nav">
</div>


<script>
var total = parseInt("{#$pagelist_info.total#}"), //总数量
	pageNo = parseInt("{#$pagelist_info.nowpage#}"), //当前页
	perpage = parseInt("{#$pagelist_info.perpage#}"), //每页显示
	pageUrl = "{#get_link('page', '&', 0, 0, 1)#}";
</script>
{#literal#} 
<script>
$(document).ready(function(){
	// hide #back-top first
	$("#back-top").hide();
	$(function () {
		$(window).scroll(function () {
		  if ($(this).scrollTop() > 200) { 
			$('#back-top').fadeIn();
		  } else { 
			$('#back-top').fadeOut();
		  }
		});
		// scroll body to 0px on click
		$('#back-top a').click(function () {
		  $('body,html').animate({
			scrollTop: 0
		  }, 800);
		  return false;
		});
	});
});

function loadListMore(){    
	if (total < perpage){
		$.alert('全部加载完毕');
		$('#linktxt').hide();
		return;
	}
	$('#linktxt').html("正在加载，请稍等...");
	if (pageNo < 1) pageNo = 1;
	$.ajax({
		type : "POST",
		url : pageUrl,
		data : {page:++pageNo},
		success:function(datas){
		  if (datas.indexOf('<ul id="listHtml">') > 0){
			var ntotal = pageNo*perpage; if (total < ntotal) ntotal = total;
			var listHtml = get_split(datas, "<!-- list start -->", "<!-- list end -->");
			$('#listHtml').append('<li style="background:#e8e8e8; color:#06F; font-size:x-small; text-align:center; height:14px; line-height:14px; overflow:hidden; padding:0;">第'+pageNo+'页(' + ((pageNo-1)*perpage+1) + '-' + ntotal + ')</li>');
			$('#listHtml').append(listHtml);
			if (total <= pageNo * perpage){
				$.alert('全部加载完毕');
				$('#linktxt').hide();
			}else{
				$('#linktxt').html('查看更多');
			}
		  } else {
			$('#listHtml').html('暂无数据');
			$('#linktxt').hide()
		  }
	   
	   }
	  //errors:
	});
}
function get_split(content, s, e){  
	var index1 = content.indexOf(s) + s.length;
	content = content.substring(index1, content.length);
	var index2 = content.indexOf(e);
	content = content.substring(0, index2);
	return content;
}
</script>
{#/literal#}

{#include file="common/footerb.html5.tpl"#}
{#include file="common/footer.tpl"#}