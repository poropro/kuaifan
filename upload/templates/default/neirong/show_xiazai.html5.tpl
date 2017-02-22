{#$__seo_head="
	<link type='text/css' rel='stylesheet' href='{#$smarty.const.CSS_PATH#}v3_list.css' />
	<link type='text/css' rel='stylesheet' href='{#$smarty.const.CSS_PATH#}v3_xiazai.css' />
	<link type='text/css' rel='stylesheet' href='{#$smarty.const.CSS_PATH#}v3_bqbox.css' />
	<script type='text/javascript' src='{#$smarty.const.JS_PATH#}jquery_1.4.2.js'></script>
	<script type='text/javascript' src='{#$smarty.const.JS_PATH#}jquery.cookie.js'></script>
	<script type='text/javascript' src='{#$smarty.const.JS_PATH#}jquery.alert.js'></script>
	<script type='text/javascript' src='{#$smarty.const.JS_PATH#}bqbox.js'></script>
"#}
{#include file="common/header.tpl" title={#$SEO.title#} seo={#$SEO#}#}

<div id="text_set" style="display:none" class="text_set">
    <div class="text_table">
        <span id="fontSizeSmall">A-</span>
        <span id="fontSizeNormal">A</span>
        <span id="fontSizeBig">A+</span> 
		<span> </span>
    </div>
</div>


<div class="sub_nav">
	<div class="sub_nav_l">{#get_pos($M.id)#}</div>    
	<div class="sub_nav_r"><img onclick="clickme()" src="{#$smarty.const.IMG_PATH#}shezhi.png" width="21" height="21"></div>
	<div style="clear:both;"></div>
</div>


<div class="title_text">
  <h3>{#$V.title#}<span>({#nocache#}{#$V.read#}{#/nocache#})</span></h3>
  <p>
	{#$V.inputtime|date_format:"%Y-%m-%d %H:%M"#}
	{#if !$V.sysadd#}
		作者: <a href="{#kuaifan getlinks='sid|vs'#}&amp;m=huiyuan&amp;c=ziliao&amp;username={#$V.username#}" class="author">{#$V.username|colorname#}</a>
	{#/if#}
    <a href="#commentCount" class="commentCount">{#$V.reply#}</a>
  </p>
</div>

<div class="main_text" id="mainText">
	{#$F=wenjian('jietu')#}
	{#if $F#}	
		<div class="jietu">
			<div id='slider' class='swipe'>
				<div class='swipe-wrap'>
					{#foreach from=$F.list item=list#}
						<figure>
							<div class='wrap'>
								<div class='image'><img data-src="{#$list.allurl#}"/></div>
							</div>
						</figure>
					{#/foreach#}				
				</div>
			</div>
			<nav>
				<ul id='position'></ul>
			</nav>
		</div>
		<script src='{#$smarty.const.JS_PATH#}xiazai.js'></script>
		<script>
		  var sliders = document.getElementById('slider');
		  var positions = document.getElementById('position');
		  var slidersnum = sliders.children[0].children.length;
		  for (i=0; i<slidersnum; i++) {
			positions.innerHTML = positions.innerHTML + "<li data-num='"+i+"'></li>";
		  }
		  $("ul#position li").click(function(){
			  if ($(this).attr("data-num")) slider.slide(parseInt($(this).attr("data-num")));
		  });
		  var slider = Swipe(sliders, {
			auto : 0,
			continuous : true,
			callback : function(pos) {
			  var i = bullets.length;
			  while (i--) {
				bullets[i].className = ' ';
			  }
			  bullets[pos].className = 'on';
			}
		  });
		  var bullets = positions.getElementsByTagName('li');
		  bullets[0].className = 'on';
		</script>
	{#else#}
		<div class="thumb">{#wap_img($V.thumb[0],320,0)#}</div>
	{#/if#}	


	[应用介绍]<br/>
	{#implode("<br/>",$contents)#}
	
	{#$F=wenjian('downfile')#}
	{#if $F#}
		<div class="fj_list">
			<div class="ft">附件列表({#$F.count#}个)：</div>
			{#foreach from=$F.list item=list#}
				<div class="fn">
					<p class="down-name">{#$list.name#}</p>
					{#if $list.body#}<p class="down-body">{#$list.body#}</p>{#/if#}
					<span class="down-size">{#$list.size#}</span>
					<span class="down-btn">下载</span>
					<a class="down-link" href="{#$list.downurl#}"></a>
				</div>
			{#/foreach#}
		</div>
	{#/if#}
</div>

	
{#if $Q#}
	<div class="xq_news">
		{#foreach from=$Q item=list#}
			{#if $list.n%5 == 0#}</div><div class="xq_news">{#/if#}
			<a href="javascript:xqnew('{#kuaifan getlink='xinqing'#}&amp;xinqing={#$list.k#}','{#$list.n#}');"><img src="{#$smarty.const.IMG_PATH#}{#$list.pic#}" alt="{#$list.name#}"/>(<span id="xq{#$list.n#}">{#$list.num#}</span>)</a>
		{#/foreach#}
	</div>
{#/if#}


<div class="text_comment" id="commentText">
  <div class="com_num"><span>跟帖</span>
  <a class="commentCount" id="commentCount">{#$V.reply#}</a>
  <span style="float:right; font-size:12px; font-weight:normal; color:#8e8e8e;">文明用语，文明上网</span> </div>
  <div class="form_text form_bq">
    <form action="{#get_link()#}" method="post" onSubmit="return beforeSubmit(this);">
      <textarea class="textarea" name="pl" cols="" rows="" id="commentTextarea">我来评论...</textarea>
      <input class="ipt_text_submit" name="dosubmit" type="submit" id="button" value="提交">
	  {#if $M.setting.pinglun_format_num#}
	  <a href="{#kuaifan getlink='c|a'#}&amp;c=pinglun&amp;a=upfile" class="ipt_text_submit fujian">附件</a>
	  {#/if#}
	  <div id="bqico" class="bqico" onClick="bqico('commentTextarea',this.id);"></div>
	  <input type="hidden" name="go_url" value="{#get_url()|urlencode#}" />
    </form>
  </div>
  <div class="com_title">
    <h3 class="jc_comment"><a href="{#kuaifan getlink='shoucang'#}&amp;shoucang=1">添加到收藏夹</a>精彩评论</h3>
    <ul class="com_list" id="commentList">
		{#kuaifan_neirong_pinglun set="列表名:lists,显示数目:3,状态:1,标题长度:500" where="commentid='neirong_{#$V.catid#}_{#$V.id#}_{#$KUAIFAN.site#}'"#}
		{#foreach from=$lists item=list#}
			<li>
				<div class="phone_pp clearfix">
				  <p>{#if $list.userid>0#}<a href="{#kuaifan getlinks='vs|sid'#}&amp;m=huiyuan&amp;c=ziliao&amp;userid={#$list.userid#}">{#$list.username|colorname#}</a>{#else#}{#$list.username#}{#/if#}<br>
					{#$list.creat_at|date_format:"%Y-%m-%d %H:%M:%S"#}</p>
				  <p><a name="upport" data="{#$list.id#}" id="up{#$list.id#}" style="cursor:pointer;">{#$list.support#}</a></p>
				</div>
				<div class="content_text">
					<span>{#$list.content#}</span>
					{#if $M.setting.pinglun_guest_del && $list.userid>0 && ($list.userid==$smarty.const.US_USERID || in_array($smarty.const.US_USERID,$bbs_bzarr))#}
						<a href="{#kuaifan getlink='c'#}&amp;c=huifu&amp;del=1&amp;rid={#$list.id#}">[删除]</a>
					{#/if#}
				</div>
			</li>
		{#foreachelse#}
			<li>没有任何评论。</li>
		{#/foreach#}
    </ul>
  </div>
  <div class="all_com"> <a href="{#kf_url('neirongreply')#}">全部跟帖</a> </div>
</div>


<div class="sp-jctu">
	<h3><span> 活跃推荐 </span></h3>
	<ul>
	{#kuaifan_neirong set="列表名:lists,显示数目:8,模型:{#$M.module#},分类:{#$M.id#},状态:1,标题长度:30,填补字符:...,排序:readtime DESC" where="id!={#$V.id#}"#}
	{#foreach from=$lists item=list#}
		<li><p><a href="{#$list.url#}">{#$list.title#}</a></p></li>
	{#foreachelse#}
		<li><p>暂无任何推荐</p></li>
	{#/foreach#}
	</ul>
</div>
	  

<div class="subn-nav">
	{#get_pos($M.id)#}
</div>

<div id="back-top" style="display: block;"><a href="#top"><span></span></a></div>


<script>
var upportUrl = "{#get_link('upport', '&', 0, 0, 1)#}";
</script>
{#literal#} 
<script>
$(document).ready(function(){
	//字体设置部分
	$('#fontSizeSmall').click(function(){
		var fs = parseInt($('#mainText').css('font-size')); if (fs < 12) fs = 12;
		$('#mainText').css('font-size', (fs - 2) + 'px');
		$.cookie('mainTextfontsize', fs - 2)
	});

	$('#fontSizeNormal').click(function(){
		$('#mainText').css('font-size', '18px');
		$.cookie('mainTextfontsize', 18)
	});

	$('#fontSizeBig').click(function(){
		var fs = parseInt($('#mainText').css('font-size')); if (fs < 12) fs = 12;
		$('#mainText').css('font-size', (fs + 2) + 'px');
		$.cookie('mainTextfontsize', fs + 2)
	});
	var mainTextfontsize = parseInt($.cookie('mainTextfontsize')); 
	if(!isNaN(mainTextfontsize)){
		$('#mainText').css('font-size', mainTextfontsize + 'px');
	}

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
	$("a[name=upport]").each(function(){
		$(this).click(function(){ 
			upport($(this).attr("data"));
		});
	});
	$('#commentTextarea').focus(function(){
		$(this).html('');
		$(this).css('color','#3c3c3c');
		//$(this).css('height', '80px');
	});
	$('#commentTextarea').blur(function(){
		//$('#commentTextarea').css('height', '38px');
	});
});

function beforeSubmit(form){
	if (form.pl.value=='' || form.pl.value=='我来评论...'){
		$.alert('请输入要评论的内容');
		form.pl.focus();
		return false;
	}
	return true;
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

function upport(id){
	$.ajax({
		type : "GET",
		url : upportUrl + "&upport=" + id,
		success:function(datas){
		  if (datas.indexOf('ok:') > 0){
			$('#up'+id).text(Number($('#up'+id).text()) + 1);
		  }
		  if (datas.indexOf('#to:') > 0){
			$.alert("你已经支持过了！");
		  }
	   }
	  //errors:
	});
}
function clickme(){
	var text_set = document.getElementById("text_set");
	if(text_set.style.display =="none"){
		text_set.style.display ="block"; 
	}else{
		text_set.style.display ="none";
	}
}
</script>
{#/literal#}

{#kuaifan tongji="阅读" get=$getarr#}
{#include file="common/footerb.html5.tpl"#}
{#include file="common/footer.tpl"#}