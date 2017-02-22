function bqico(inpid,icoid){
	if ($('#bqbox-'+icoid).length == 0){
		$("#"+icoid).after('<div class="bqbox" id="bqbox-'+icoid+'"></div>');
		$("#"+icoid).after('<div class="bqbox-bg" id="bqbox-bg-'+icoid+'" onclick="bqboxbg(\''+icoid+'\')"></div>');
	}
	var boxobj = $('#bqbox-'+icoid);
	var boxbgobj = $('#bqbox-bg-'+icoid);
	if (boxobj.attr('data-load') != 'load'){
		boxobj.html('<div class="top"></div><div class="box" id="data-box-img"><span class="bqing">表情加载中...</span></div><div class="clear"></div>');
	}
	if (boxobj.css('display') == 'none') {
		if (boxobj.attr('data-load') != 'load'){
			$.getJSON("index.php?m=biaoqing&c=list",function(result){
				boxobj.children("#data-box-img").children(".bqing").hide();
				var j = 0;
				$.each(result, function(i, field){
					boxobj.children("#data-box-img").append("<img src=\""+field+"\" alt=\""+i+"\" onclick=\"inputbq('"+i+"','"+inpid+"');\"/>");
					j++;
				});
				if (j == 0) {
					boxobj.children("#data-box-img").html('<span class="bqing">没有任何表情</span>');
				}
				boxobj.attr('data-load', 'load');
			});
		}
		boxobj.show();
		boxbgobj.show();
	} else {
		boxobj.hide();
		boxbgobj.hide();
	}
}
function inputbq(str,inpid){
	var inpidobj = document.getElementById(inpid);
	inpidobj.setAttribute("disabled",true);
	if (inpidobj.value == "我来评论...") inpidobj.value = "";
	inpidobj.value += "[/"+str+"]"; 
	//insertAtCursor(inpidobj, "[/"+str+"]")
	inpidobj.removeAttribute("disabled");
}
function bqboxbg(objid){
	$('#bqbox-'+objid).hide();
	$('#bqbox-bg-'+objid).hide();
}
function insertAtCursor(myField, myValue) { 
     //IE support
     if (document.selection) { 
         myField.focus(); 
         sel = document.selection.createRange(); 
         sel.text = myValue; 
         sel.select(); 
     } 
     //MOZILLA/NETSCAPE support 
     else if (myField.selectionStart || myField.selectionStart == '0') { 
         var startPos = myField.selectionStart; 
         var endPos = myField.selectionEnd; 
         // save scrollTop before insert 
         var restoreTop = myField.scrollTop; 
         myField.value = myField.value.substring(0, startPos) + myValue + myField.value.substring(endPos, myField.value.length); 
         if (restoreTop > 0) myField.scrollTop = restoreTop; 
         myField.focus(); 
         myField.selectionStart = startPos + myValue.length; 
         myField.selectionEnd = startPos + myValue.length; 
     } else { 
         myField.value += myValue; 
         myField.focus(); 
     } 
} 