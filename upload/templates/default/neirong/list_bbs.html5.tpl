{#$__seo_head="
	<link type='text/css' rel='stylesheet' href='{#$smarty.const.CSS_PATH#}v3_list.css' />
	<link type='text/css' rel='stylesheet' href='{#$smarty.const.CSS_PATH#}v3_bbs.css' />
	<script type='text/javascript' src='{#$smarty.const.JS_PATH#}jquery_1.4.2.js'></script>
	<script type='text/javascript' src='{#$smarty.const.JS_PATH#}jquery.alert.js'></script>
"#}
{#include file="common/header.tpl" title={#$SEO.title#} seo={#$SEO#}#}


<div class="text-nav">
<a class="textn-left" href="{#get_pos_url($M.id)#}"></a>
{#$M.title#}
<a class="textn-right" href="{#kf_url('index')#}"></a>
</div>


<div class="nav-time" id="top">
{#get_pos($M.id)#}
</div>

<div class="menu">
	<a href="{#kuaifan getlinks='vs|sid|m'#}&amp;c=guanli&amp;a=fabu&amp;catid={#$M.id#}"><b>发帖</b></a>|{#$M.type_head#}<br/>
</div>


<div class="key">
{#form set="头|地址:'{#str_replace(':','\:',get_link('page|key'))#}'" notvs="1"#}
{#form set="输入框|名称:key{#$TIME2#},值:'{#$smarty.request.key#}',宽:12"#}
{#kuaifan vs="1" set="
  <anchor>搜索
  <go href='{#get_link('page|key')#}' method='post' accept-charset='utf-8'>
  <postfield name='key' value='$(key{#$TIME2#})'/>
  <postfield name='dosubmit' value='1'/>
  </go> </anchor>
"#}
{#form set="按钮|名称:dosubmit,值:搜索" notvs="1"#}
{#form set="尾" notvs="1"#}
</div>



<div class="nlist">
<ul id="listHtml">
<!-- list start -->
{#kuaifan_neirong set="列表名:lists,显示数目:20,模型:{#$M.module#},论坛模型:1,分类:{#$M.id#},状态:1,搜索变量名:key,标题长度:15,填补字符:...,分页显示:1,分页名:pagelist,分页变量名:page,论坛排序:GET[type]"#}
{#foreach from=$lists item=list#}
	<li class="bbs">
		<div class="tit"><span>{#$list._n#}.{#if $list.dingzhi#}<b>[顶]</b>{#/if#}{#if $list.jinghua#}<b>[精]</b>{#/if#}<b>{#$list.subtitle|subt:"[":"]"#}</b></span><a href="{#$list.url#}">{#$list.title#}</a></div>
		<div class="aut"><em>{#$list.reply#}回/{#$list.read#}阅</em>{#$list.username|colorname#}</div>
	</li>
{#foreachelse#}
	<li>此栏目尚未发布内容。</li>
{#/foreach#}
<!-- list end -->
</ul>
</div>

<div class="showMore"><a href="javascript:loadListMore()" id="linktxt">查看更多▼</a></div>

<div class="bbs-more">
	<div>第{#$pagelist_info.nowpage#}/{#$pagelist_info.totalpage#}页 共{#$pagelist_info.total#}贴</div>
</div>

<form name="frm_page" method="post" action="{#$pagelist_info.url.now#}">
	<div class="bbs-fenye">
		<div class="fenye_wrap">
			{#if $pagelist_info.totalpage>$pagelist_info.nowpage#}
				<a href="{#$pagelist_info.url.next#}">下一页</a>
			{#else#}
				<span class="no">下一页</span>
			{#/if#}
			<input name="page" value="{#$pagelist_info.nowpage#}" type="text" class="inp">
			<input name="submit_jump" value="跳转" type="submit" class="sub">
			{#if $pagelist_info.nowpage>1#}
				{#$nextpagearr.page = $pagelist_info.nowpage-1#}
				<a href="{#$pagelist_info.url.pre#}">上一页</a>
			{#else#}
				<span class="no">上一页</span>
			{#/if#}
			
		</div>
	</div>
</form>


<div class="subn-nav">
{#get_pos($M.id)#}
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
	if($('#linktxt').attr('class') == 'no') return;
	if (total < perpage){
		$.alert('全部加载完毕');
		$('#linktxt').addClass('no');
		return;
	}
	$('#linktxt').html("加载中，请稍等...");
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
				$('#linktxt').addClass('no');
			}else{
				$('#linktxt').html('查看更多▼');
			}
		  } else {
			$('#listHtml').html('暂无数据');
			$('#linktxt').addClass('no');
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

{#kuaifan tongji="查看" get=$getarr#}
{#include file="common/footerb.html5.tpl"#}
{#include file="common/footer.tpl"#}