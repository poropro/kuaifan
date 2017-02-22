{#$__seo_head="
	<style type='text/css'>
	html {#
		background:#000000;
	#}
	</style>
	<link type='text/css' rel='stylesheet' href='{#$smarty.const.CSS_PATH#}v3_tu.css' />
	<link type='text/css' rel='stylesheet' href='{#$smarty.const.CSS_PATH#}v3_bqbox.css' />
	<script type='text/javascript' src='{#$smarty.const.JS_PATH#}jquery_1.4.2.js'></script>
	<script type='text/javascript' src='{#$smarty.const.JS_PATH#}jquery.alert.js'></script>
	<script type='text/javascript' src='{#$smarty.const.JS_PATH#}bqbox.js'></script>
"#}
{#include file="common/header.tpl" title={#$SEO.title#} seo={#$SEO#}#}

<header class="top bg anim">
  <nav>
	<button type="button" class="home icon" data-url="{#kf_url('index')#}"><em>返回首页</em></button>
	<button class="info icon" id="xq_btn"><em>操作</em></button>
	<span>浏览 {#nocache#}{#$V.read#}{#/nocache#}</span>
	<button class="back icon" data-url="{#get_catid_url($M.id)#}"><em>返回列表</em></button>
	<button class="share icon" id="share_btn"><em>分享</em></button>
  </nav>
</header>

<!-- 幻灯片列表 注意div直接不要换行和有空格 begin-->
<section class="pic_box" id="pic_box">
	<div class="pic_slider" id="pic_slider">
		{#$F=wenjian('downfile')#}
		{#foreach from=$F.list item=list#}<div data-src="{#$list.downurl#}"></div>{#foreachelse#}<div data-src="{#$V.thumb.0#}"></div>{#/foreach#}
	</div>
</section>
<!-- 幻灯片列表 end-->
<section class="content_box anim" id="content_box">
	<header>
		<h1>{#$V.title#}</h1>
		<span id="content_index">[1/{#$F.count#}]</span>
	</header>
	<p id="content_info"></p>
</section>

<section class="comment_box mini_comment" id="comment_box">
	<!-- <div class="comment_shadow"></div> -->
	<form action="{#get_link()#}" method="post" onSubmit="return plSubmit(this);">
		<input name="dosubmit" value="1" type="hidden">
		<div class="comment_inner bg form_imgbq">
			<div class="comment_toolbar">
				<button type="submit" class="comment_submit" id="comment_submit"><i class="icon icon34"></i><strong>发送</strong></button>
				<button type="button" class="comment_close" id="comment_close"><i class="icon icon34"></i><strong>关闭</strong></button>
				<h2>评论</h2>
			</div>
			<div class="comment_count" data-target="blank" data-url="{#kf_url('neirongreply')#}">
				<i class="icon icon24"></i><strong id="comment_count">{#$V.reply#}</strong>
			</div>
			<div class="comment_input_box">
				<textarea class="comment_input" id="comment_input" name="pl" placeholder="输入评论"></textarea>
				<input type="hidden" name="go_url" value="{#get_url()|urlencode#}" />
			</div>
			<div id="bqico" class="bqico" onClick="bqico('comment_input',this.id);"></div>
		</div>
	</form>
</section> 

<section class="share_box share_box_hide anim" id="share_box">
	<button class="share-btn1 icon" data-target="blank" data-url="http://v.t.sina.com.cn/share/share.php?title={#$V.title|urlencode#}&amp;url={#get_url()|urlencode#}"><em>新浪微博</em></button>
	<button class="share-btn2 icon" data-target="blank" data-url="http://share.v.t.qq.com/index.php?c=share&a=index&url={#get_url()|urlencode#}&title={#$V.title|urlencode#}"><em>腾讯微博</em></button>
	<button class="share-btn3 icon" data-target="blank" data-url="http://go.10086.cn/ishare.do?m=t&u={#get_url()|urlencode#}&t={#$V.title|urlencode#}"><em>爱分享</em></button>
</section>

<section class="xq_box anim" id="xq_box">
{#if $Q#}
	<div class="xq_news">
		{#foreach from=$Q item=list#}
			{#if $list.n%5 == 0#}</div><div class="xq_news">{#/if#}
			<a href="javascript:xqnew('{#kuaifan getlink='xinqing'#}&amp;xinqing={#$list.k#}','{#$list.n#}');"><img src="{#$smarty.const.IMG_PATH#}{#$list.pic#}" alt="{#$list.name#}"/>(<span id="xq{#$list.n#}">{#$list.num#}</span>)</a>
		{#/foreach#}
	</div>
	<div class="down-pic" id="down-pic">下载原图</div>
{#/if#}
</section>


<div id="log"></div>
<div id="assist_view_port" class="assist"></div>
<div id="assist_hide_bar" class="assist"></div>
{#literal#}
<script type="text/javascript">
function plSubmit(form){
	if (form.pl.value=='' || form.pl.value=='我来评论...'){
		$.alert('请输入要评论的内容');
		form.pl.focus();
	}else{
		$.ajax({
			type : "POST",
			url : form.action,
			data : {pl:$("#comment_input").val(),dosubmit:1},
			success:function(datas){
			  if (datas.indexOf('#ok:') > 0){
				$('#comment_count').text(Number($('#comment_count').text()) + 1);
				$('#comment_box').addClass("mini_comment");
				$('#comment_box').removeClass("full_comment");
				$("#comment_input").val("");
				$.alert("评论成功！");
			  }
			  if (datas.indexOf('#ok1:') > 0){
				$.alert("评论成功，等待审核后显示！");
			  }
		   }
		  //errors:
		});
	}
	return false;
}
function xqnew(url,id){
	$.ajax({
		type : "GET",
		url : url,
		success:function(datas){
		  if (datas.indexOf('#ok:') > 0){
			$('#xq'+id).text(Number($('#xq'+id).text()) + 1);
		  }
		  if (datas.indexOf('#to:') > 0){
			$.alert("你已经表达过心情了，保持平常心有益身体健康！");
		  }
	   }
	  //errors:
	});
}
</script>
{#/literal#}
<script type="text/javascript">
	// 文章内容
	{#$descdata = implode("[page]", $contents)#}
	{#$descdata = ubb_neirong($descdata)#}
	{#$descdata = str_replace(array("<br/>", "\r\n", "\r", "\n"), array("[br]", ""), $descdata)#}
	{#$descdata = htmlspecialchars($descdata)#}	
	{#$descdata = str_replace("[br]", "<br/>", $descdata)#}	
	window.descData = "{#$descdata#}";
	// 上一个图集的连接地址，如果没有，就不生成
	var prevLocation = '{#shangpian("&")#}';
	// 下一个图集的连接地址，如果没有，就不要生成
	var nextLocation = '{#xiapian("&")#}';
</script>    
<script type="text/javascript" src="{#$smarty.const.JS_PATH#}tu_fm.js"></script>
<script type="text/javascript" src="{#$smarty.const.JS_PATH#}tu_ppt.js"></script> 

{#kuaifan tongji="阅读" get=$getarr#}
{#include file="common/footer.tpl"#}