{#$__seo_head="
	<link type='text/css' rel='stylesheet' href='{#$smarty.const.CSS_PATH#}v3_pinglun.css' />
	<link type='text/css' rel='stylesheet' href='{#$smarty.const.CSS_PATH#}v3_bqbox.css' />
	<script type='text/javascript' src='{#$smarty.const.JS_PATH#}jquery_1.4.2.js'></script>
	<script type='text/javascript' src='{#$smarty.const.JS_PATH#}jquery.alert.js'></script>
	<script type='text/javascript' src='{#$smarty.const.JS_PATH#}bqbox.js'></script>
"#}
{#include file="common/header.tpl" title={#$SEO.title#} seo={#$SEO#}#}

<div class="text-nav">
<a class="textn-left" href="{#$V.url#}"></a>
跟帖
<a class="textn-right" href="{#kf_url('index')#}"></a>
</div>

<div class="bg_color"><div class="text_comment">

<h3 class="title-name">{#$V.title#}</h3>

<div class="com_num">
  <a class="gt-num" id="gt-num" href="#pinglun">{#$V.reply#}</a>
  <a class="ks-gt" href="#pinglun">快速跟帖</a>
  <a class="ks-gt g2" href="{#kuaifan getlink='c'#}&amp;c=pingluntop">评论排行</a>&nbsp;
</div>

<div id="tb_" class="tb_">
  <ul>
	{#if $smarty.get.support#}
		<li id="tb_1" class="normaltab"><a href="{#get_link('support')#}">最新评论</a></li>
		<li id="tb_2" class="hovertab">最热评论</li>
	{#else#}
		<li id="tb_1" class="hovertab">最新评论</li>
		<li id="tb_2" class="normaltab"><a href="{#get_link('support')#}&amp;support=support">最热评论</a></li>
	{#/if#}   
  </ul>
</div>



{#kuaifan_neirong_pinglun set="列表名:lists,显示数目:15,状态:1,排序:GET[support],标题长度:500,分页显示:1,分页名:pagelist,分页变量名:page,原文标题:title" where="commentid='neirong_{#$V.catid#}_{#$V.id#}_{#$KUAIFAN.site#}'"#}





<div class="ctt">
  <div class="dis" id="tbc_01">
    <div class="com_title">
      <ul class="com_list" id="com_list_last">
		<!-- list start -->
		{#foreach from=$lists item=list#}
        <li>
          <div class="phone_pp clearfix">
			<div class="louceng">
			  <div class="avatar"><img src="{#kuaifan touxiang=$list.userid size='微'#}?t={#$TIME2#}"/></div>
			  <div class="info">
				<div class="zz">
					{#if $list.userid>0#}<a href="{#kuaifan getlinks='vs|sid'#}&amp;m=huiyuan&amp;c=ziliao&amp;userid={#$list.userid#}">{#$list.username|colorname#}</a>{#else#}{#$list.username#}{#/if#}
				</div>
				<div class="tt">
				{#$list.creat_at|date_format:"%Y-%m-%d %H:%M:%S"#}
				</div>
			  </div>
			</div>
          </div>
          <div class="content_text">
			{#$list.content#}
			{#if $type.0=="xuan" && !$type.1.3 && $smarty.const.US_USERNAME == $V.username#}
				<a href="{#kuaifan getlink='c'#}&amp;c=zuijia&amp;rid={#$list.id#}">(设为最佳回复)</a>
			{#/if#}
			{#if $M.setting.pinglun_guest_del && $list.userid>0 && ($list.userid==$smarty.const.US_USERID || in_array($smarty.const.US_USERID,$bbs_bzarr))#}
				<a href="{#kuaifan getlink='c'#}&amp;c=huifu&amp;del=1&amp;rid={#$list.id#}">[删除]</a>
			{#/if#}
		  </div>
          <div class="good-msg"><a href="javascript:void(0);">
            <input type="button" class="kedian" id="up{#$list.id#}" value="{#$list.support#}" onclick="upport('{#$list.id#}');">
            </a><a onclick="popup_show('{#$list.id#}');" href="javascript:;">回复</a></div>
          <div class="sample_popup" id="popup_{#$list.id#}" style="display: none;">
            <div class="menu_form_header"><div class="menu_form_exit" onclick="popup_show('{#$list.id#}');"></div></div>
            <div class="form_text form_ksbq">
              <form action="{#kuaifan getlink='c'#}&amp;c=huifu&amp;rid={#$list.id#}" method="post" onSubmit="return beforeSubmit(this);">
                <textarea class="textarea" name="pl" id="ks-{#$list.id#}" cols="" rows="" onfocus="chekReplyContent(this,'我来评论...')">我来评论...</textarea>
                <input class="ipt_text_submit" name="dosubmit" type="submit" value="提交">
				<div id="bqico-{#$list.id#}" class="bqico" onClick="bqico('ks-{#$list.id#}',this.id);"></div>
			    <input type="hidden" name="go_url" value="{#get_url()|urlencode#}" />
              </form>
            </div>
          </div>
        </li>
		{#/foreach#}
		<!-- list end -->
      </ul>
    </div>
  </div>
</div>

<div class="all_com" id="more_list">
  <a href="javascript:loadListMore()" id="linktxt">显示更多</a>
</div>


<div><span style="float:right; font-size:12px; font-weight:normal; color:#8e8e8e;">文明用语，文明上网</span></div>

<form action="{#get_link()#}" method="post" id="pinglun" onSubmit="return beforeSubmit(this);">
  <div class="taks-gt form_plbq">
    <textarea class="textarea2" name="pl" cols="" rows="" id="getfocus" onfocus="chekReplyContent(this,'我来评论...')">我来评论...</textarea>
    <p>
	  <input class="ipt_text_submit2" name="dosubmit" type="submit" value="提交">
      {#if $M.setting.pinglun_format_num#}<a href="{#kuaifan getlink='c|a'#}&amp;c=pinglun&amp;a=upfile" class="ipt_text_submit2" style="height:32px;line-height:32px;margin-right:8px;text-align:center;color:#767676;width:68px;">附件回复</a>{#/if#}
    </p>
	<div id="bqico" class="bqico" onClick="bqico('getfocus',this.id);"></div>
  </div>
  <input type="hidden" name="go_url" value="{#get_url()|urlencode#}" />
</form>

		
</div></div>
		


<div class="subn-nav">
	{#get_pos($M.id)#}
</div>

<div id="back-top" style="display: block;"><a href="#top"><span></span></a></div>


<script>
var total = parseInt("{#$pagelist_info.total#}"), //总数量
	pageNo = parseInt("{#$pagelist_info.nowpage#}"), //当前页
	perpage = parseInt("{#$pagelist_info.perpage#}"), //每页显示
	pageUrl = "{#get_link('page', '&', 0, 0, 1)#}";
var upportUrl = "{#get_link('upport', '&', 0, 0, 1)#}";
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
		  if (datas.indexOf('<ul class="com_list" id="com_list_last">') > 0){
			var ntotal = pageNo*perpage; if (total < ntotal) ntotal = total;
			var listHtml = get_split(datas, "<!-- list start -->", "<!-- list end -->");
			$('#com_list_last').append('<li style="background:#e8e8e8; color:#06F; font-size:x-small; text-align:center; height:14px; line-height:14px; overflow:hidden; padding:0;">第'+pageNo+'页(' + ((pageNo-1)*perpage+1) + '-' + ntotal + ')</li>');
			$('#com_list_last').append(listHtml);
			if (total <= pageNo * perpage){
				$.alert('全部加载完毕');
				$('#linktxt').hide();
			}else{
				$('#linktxt').html('显示更多');
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
function beforeSubmit(form){
	if (form.pl.value=='' || form.pl.value=='我来评论...'){
		$.alert('请输入要评论的内容');
		form.pl.focus();
		chekReplyContent(form.pl,'我来评论...');
		return false;
	}
	return true;
}
function popup_show(id){
	var element = document.getElementById('popup_'+id);
	if(element.style.display =="none"){
		element.style.display = "block";
	} else {
		element.style.display = "none";
	}
}
function chekReplyContent(obj,text){
	if (text == obj.value){
		obj.value = "";
		obj.style.color = "#3c3c3c";
	}
}
function upport(id){
	$.ajax({
		type : "GET",
		url : upportUrl + "&upport=" + id,
		success:function(datas){
		  if (datas.indexOf('ok:') > 0){
			$('#up'+id).val(Number($('#up'+id).val()) + 1);
		  }
		  if (datas.indexOf('#to:') > 0){
			$.alert("你已经支持过了！");
		  }
	   }
	  //errors:
	});
}
</script>
{#/literal#}

{#include file="common/footerb.html5.tpl"#}
{#include file="common/footer.tpl"#}