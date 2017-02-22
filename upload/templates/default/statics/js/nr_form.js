function formaffix(field, type, fname, fnum){
	var divid = "affix-"+field;
	var divnum = $('#'+divid+' div').length + 1;
	if (divnum > fnum && fnum > 0) {
		alert("最多允许上传数量"+fnum+"个");
		return;
	}
	var chknum = divnum?divnum%2?" odd":" even":"";
	var divhtml = '<div class="affixbox'+chknum+'" id="abox-'+divnum+'">';
	divhtml+= '<span class="affixnum">'+divnum+'.</span>';
	divhtml+= '<em onclick="affixdel(this, \''+field+'\')" title="删除"></em>';
	if (type == 'img') {
		divhtml+= '<input type="file" name="'+field+'_'+divnum+'" accept="image/*" onchange="affixImg(this,\''+type+'\',\''+field+'\')"/>';
		divhtml+= '<i class="affixp">';
		divhtml+= '<p class="text"><textarea name="'+field+'_'+divnum+'" placeholder="'+fname+'说明"></textarea></p>';
		divhtml+= '<p class="aimg" onclick="affixbig(this);"></p>';
		divhtml+= '</i>';
	}else{
		divhtml+= '<input type="file" name="'+field+'_'+divnum+'" onchange="affixImg(this,\''+type+'\',\''+field+'\')"/>';
		divhtml+= '<p class="text"><textarea name="'+field+'_'+divnum+'" placeholder="'+fname+'说明"></textarea></p>';
	}
	divhtml+= '</div>';
	$('#'+divid).show();
	$('#'+divid).append(divhtml);
	$('input[name='+field+'_'+divnum+']').click();
}
function affixdel(obj, field) {
	$(obj.parentNode).remove();
	var divid = "affix-"+field;
	var divnum = 1;
	$('#'+divid+' div').each(function(){
		$(this).removeClass("odd");
		$(this).removeClass("even");
		$(this).addClass(divnum?divnum%2?" odd":" even":"");
		$(this).attr('id', 'abox-'+divnum);
		$(this).find("span").html(divnum+'.');
		$(this).find("input").attr('name', field+'_'+divnum);
		$(this).find("textarea").attr('name', field+'_'+divnum);
		divnum++;
	});
	if (divnum == 1) {
		$('#'+divid).hide();
	}
}
window.URL = window.URL || window.webkitURL;
function affixImg(obj, type, field) {
	var files = obj.files;
	if ($('#affix_max_size')) {
		var affix_max_size = parseInt($('#affix_max_size').html());
		if (affix_max_size*1024 < files[0].size) {
			alert("单个文件最大不可超过"+affix_max_size+"KB"); $(obj).val(""); return;
		}
	}
	if ($('#allowext-'+field)) {
		var affix_allowext = $('#allowext-'+field).html();
		affix_allowext = affix_allowext.replace(/\//ig, "|");
		affix_allowext = affix_allowext.replace(/\,/ig, "|");
		affix_allowext = affix_allowext.replace(/，/ig, "|");
		if (affix_allowext){
			var _affix_allowext = new RegExp("("+affix_allowext+")$", "i"); 
			if (!_affix_allowext.test(files[0].name)) {
				alert("格式错误，支持上传的格式有:"+affix_allowext); $(obj).val(""); return;
			}
		}
	}
	if (type == 'file') return;
	var img = new Image();
	if(window.URL){
		//File API
		  //alert(files[0].name + "," + files[0].size + " bytes");
		  img.src = window.URL.createObjectURL(files[0]); //创建一个object URL，并不是你的本地路径
		  //img.width = 200;
		  img.onload = function(e) {
			window.URL.revokeObjectURL(this.src); //图片加载后，释放object URL
			affixImgCss(obj, img);
		  }
	}else if(window.FileReader){
		//opera不支持createObjectURL/revokeObjectURL方法。我们用FileReader对象来处理
		var reader = new FileReader();
		reader.readAsDataURL(files[0]);
		reader.onload = function(e){
			//alert(files[0].name + "," +e.total + " bytes");
			img.src = this.result;
			//img.width = 200;
		    $(obj.parentNode).children('.aimg').html(img);
		}
	}else{
		//ie
		obj.select();
		obj.blur();
		var nfile = document.selection.createRange().text;
		document.selection.empty();
		img.src = nfile;
		//img.width = 200;
		img.onload=function(){
			//alert(nfile+","+img.fileSize + " bytes");
			$(obj.parentNode).children('.aimg').html(img);
		}
	}
}
function affixImgCss(obj, img) {
	$(obj.parentNode).children('.affixp').addClass('affixps');
	$(obj.parentNode).children('.affixp').children('.aimg').html(img);
}
function affixbig(obj) {
}