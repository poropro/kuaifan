window.hash_li = "";
window.hash_li_a = "";
window.auto_hash = 0
jQuery(function(){
	//高度自适应
	initLayout();
	$(window).resize(function()
	{
		initLayout();
	});
	function initLayout()
	{
		var h1 = document.documentElement.clientHeight - $("#header").outerHeight(true) - $("#info_bar").height();
		var h2 = h1 - $(".headbar").height() - $(".pages_bar").height() - 30;
		$('#admin_left').height(h1);
		$('#admin_right .content').height(h2);
		if ($('#right_iframe')) $('#right_iframe').height(h2);
	}
	//一级菜单切换
	$("#menu ul li:first-child").addClass("first");
	$("#menu ul li:last-child").addClass("last");
	$("[name='menu']>li").click(function(){
		$(this).siblings().removeClass("selected");
        $(this).addClass("selected");
		$("ul.submenu li[class='hide']").hide();
		$("ul.submenu li[id='"+$(this).attr('id')+"']").show();
		window.hash_li = $(this).attr('id');
		if (window.auto_hash == 0) {
			location.hash = "hash=" + window.hash_li;
		}
	});
	//二级菜单展示效果
	$("ul.submenu>li>span").toggle(
		function(){
			$(this).next().css("display","none");
			$(this).addClass("selected");
		},
		function(){
			$(this).next().css("display","");
			$(this).removeClass("selected");
		}
	);
	$("ul.submenu>li").each(function(){
		var j = 1;
		var d = $(this).attr("id");
		$(this).find("ul>li>a").each(function(){
			$(this).attr('data-j', j);
			j++;
		});
	});
	$("ul.submenu>li>ul>li>a").click(function(){
		var obj = $(this).parent('li');
		obj.siblings().removeClass("selected");
		obj.addClass("selected");
		if ($(this).attr('id') == 'main'){
			$('#border_table_org').show();
			$('#border_iframe').hide();
		}else{
			$('#border_table_org').hide();
			$('#border_iframe').hide();
			var h = parseInt($('#admin_right .content').css('height'));
			$('#border_iframe').html('<iframe id="right_iframe" src="' + $(this).attr('href') + '" frameborder="false" scrolling="auto" width="100%" height="auto" style="border: none; height: ' + h + 'px;" allowtransparency="true" onload="onframe(this)"></iframe>');
			window.hash_li_a = $(this).attr('data-j');
			if (window.auto_hash == 0) {
				location.hash = "hash=" + window.hash_li + "&num=" + window.hash_li_a;
			}
		}
		return false;
	});
	//文字滚动显示
	$("#tips a:not(:first)").css("display","none");
	var tips_l=$("#tips a:last");
	var tips_f=$("#tips a:first");
	setInterval(function()
	{
		if($("#tips").children().length	!= 1){
			if(tips_l.is(":visible")){
				tips_f.fadeIn(500);
				tips_l.hide()
			}else{
				$("#tips a:visible").addClass("now");
				$("#tips a.now").next().fadeIn(500);
				$("#tips a.now").hide().removeClass("now");
			}
		}
	},3000);
	//搜索
	var sch_val = "输入商铺名称";
	$(".search>input.text").blur(
		function(){
			if($(this).val()==''){
				$(this).val(sch_val);
			}
		}
	).click(
		function(){
			if($(this).val()==sch_val){
				$(this).val('');
			}
		}
	);
	//关闭侧边栏
	$("#separator").click(function(){
		document.body.className = (document.body.className == "folden") ? "":"folden";
	});
	//跳转
	var thisURL = document.URL; 
	if (thisURL.indexOf("#hash=") > -1){
		var thisURLArr = thisURL.split("#hash=");
		var thisHash = thisURLArr[1];
		var thisHashArr = thisHash.split("&num=");
		if ($("#menu ul li[id='"+thisHashArr[0]+"']")){
			window.auto_hash = 1;
			$("#menu ul li[id='"+thisHashArr[0]+"']").click();
			$('#border_table_org').hide();
			setTimeout(function () {
				if (!thisHashArr[1]){
					$("ul.submenu>li[id='"+thisHashArr[0]+"']>ul>li>a[data-j='1']").click();
				}else{
					$("ul.submenu>li[id='"+thisHashArr[0]+"']>ul>li>a[data-j='"+thisHashArr[1]+"']").click();
				}
			}, window.minrefreshtime);
			window.auto_hash = 0;
		}
	}
});

function onframe(obj){
	$("#right_iframe").contents().find("body").css('font-size','14px');
	$("#right_iframe").contents().find("a").each(function(){
		if ($(this).attr('target')=='_blank'){
			$(this).attr('target','_self');
		}
	});
	$("#right_iframe").contents().find("#noweb").each(function(){
		$(this).remove();
	});
	/*
	$("#right_iframe").contents().find("textarea").each(function(){
		$(this).css('height','150px');
		$(this).css('width','400px');
	});
	*/
	/*
	$("#right_iframe").contents().find("input").each(function(){
		var rand = Math.floor(Math.random()*9999 + 1);
		$(this).attr("data-input-id",rand);
		if ($(this).attr("onclick")){
			$(this).attr("onclick",$(this).attr("onclick") + ";window.parent.MessageTips('" + rand + "');")
		}else{
			$(this).attr("onclick","window.parent.MessageTips('" + rand + "');")
		}
	});
	*/
	$('#border_iframe').show();
}
function MessageTips(id){
	var obj = $("#right_iframe").contents().find("input[data-input-id='"+id+"']");
	//obj.remove();
}
/**
 * 进行商品筛选
 * @param url string 执行的URL
 * @param callback function 筛选成功后执行的回调函数
 */
function searchGoods(url,callback)
{
	var step = 0;
	art.dialog.open(url,
	{
		"id":"searchGoods",
		"title":"商品筛选",
		"okVal":"执行",
		"button":
		[{
			"name":"后退",
			"callback":function(iframeWin,topWin)
			{
				if(step > 0)
				{
					iframeWin.window.history.go(-1);
					this.size(1,1);
					step--;
				}
				return false;
			}
		}],
		"ok":function(iframeWin,topWin)
		{
			if(step == 0)
			{
				iframeWin.document.forms[0].submit();
				step++;
				return false;
			}
			else if(step == 1)
			{
				var goodsList = $(iframeWin.document).find('input[name="id[]"]:checked');

				//添加选中的商品
				if(goodsList.length == 0)
				{
					alert('请选择要添加的商品');
					return false;
				}
				//执行处理回调
				callback(goodsList);
				return true;
			}
		}
	});
}