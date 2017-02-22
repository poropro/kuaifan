{#$__seo_head="
	<link type='text/css' rel='stylesheet' href='{#$smarty.const.CSS_PATH#}v3_list.css' />
	<script type='text/javascript' src='{#$smarty.const.JS_PATH#}jquery_1.4.2.js'></script>
	<script type='text/javascript' src='{#$smarty.const.JS_PATH#}jquery.alert.js'></script>
"#}
{#include file="common/header.tpl" title="评论排行榜"#}


<div class="text-nav">
<a class="textn-left" href="javascript:onclick=history.go(-1)"></a>
评论排行榜
<a class="textn-right" href="{#kf_url('index')#}"></a>
</div>


<div class="nav-time" id="top">
{#if $smarty.get.total#}
	<a href="{#get_link('total')#}">评论最多</a> | 最新评论
	{#$desc = ''#}
{#else#}
	评论最多 | <a href="{#get_link('total')#}&amp;total=1">最新评论</a>
	{#$desc = 'total DESC'#}
{#/if#}
</div>



<div class="nlist">
<ul id="listHtml">
<!-- list start -->
{#kuaifan_pinglun set="列表名:lists,显示数目:15,最多数目:150,排序:$desc,标题长度:15,填补字符:...,分页显示:1,分页名:pagelist,分页变量名:page"#}
{#foreach from=$lists item=list#}
	<li class="pl"><span class="right"><a href="{#$list.plurl#}">{#$list.total#}</a></span><span>{#$list._n#}</span><a href="{#$list.url#}">{#$list.title#}</a></li>
{#foreachelse#}
	<li>没有任何评论的内容。</li>
{#/foreach#}
<!-- list end -->
</ul>
</div>

<div class="showMore"><a href="javascript:loadListMore()" id="linktxt">查看更多</a></div>

<div class="subn-nav">
</div>

<div id="back-top" style="display: block;"><a href="#top"><span></span></a></div>

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


{#kuaifan tongji="查看"#}
{#include file="common/footerb.html5.tpl"#}
{#include file="common/footer.tpl"#}