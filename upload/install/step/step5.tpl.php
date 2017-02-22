<?php include KF_ROOT_PATH.'install/step/header.tpl.php';?>

<div id="body" style="padding: 16px;">
	<div style="width: 700px; margin: auto;">
		<h1>6. 安装详细过程</h1>
		<div class="div">
			<div class="header">安装过程:</div>
			<div class="body">
				<div id="installmessage" style="line-height: 25px;padding: 0 5px;">正在准备安装 ...<br /></div>
				<div id="hiddenop"></div>
			</div>
		</div>
	</div>

	<p style="text-align: center;">
		<input type="button" value=" 上一步" onClick="history.back();"/>
		<input type="button" id="finish" value=" 安装中.."/>
	</p>
	<div style="display:none;width:700px;margin:10px auto 0px auto;" id="sqlerrdiv">
		<a name="sqlerra"></a>
		<h2 class="box">如果可以请您将以下错误信息反馈给我们：342210020@qq.com</h2>
		<textarea name="content" style="width:100%;height:80px;padding:10px;width:680px;line-height:18px;" id="sqlerrtext"></textarea>
	</div>
</div>


<form id="install" action="install.php?" method="post">
	<input type="hidden" name="module" id="module" value="<?php echo $module?>" />
	<input type="hidden" name="testdata" id="testdata" value="<?php echo $testdata?>" />
	<input type="hidden" id="selectmod" name="selectmod" value="<?php echo $selectmod?>" />
	<input type="hidden" name="step" value="6">
</form>



<div id="footer"> Powered by KuaiFan (c) 2008-2015 </div>


<script language="JavaScript">
<!--
$().ready(function() {
reloads();
})
var n = 0;
var setting =  new Array();
setting['admin'] = '后台管理主模块安装成功......';
setting['neirong'] = '内容模块安装成功......';
setting['expand'] = '扩展功能安装成功......';
setting['huiyuan'] = '会员模块安装成功......';
setting['guanliyuan'] = '管理员模块安装成功......';
setting['paiban'] = '排版模块安装成功......';
setting['peizhi'] = '配置模块安装成功......';
setting['pinglun'] = '评论模块安装成功......';
setting['tongji'] = '统计模块安装成功......';
setting['xitongrizhi'] = '系统日志模块安装成功......';
setting['youxiang'] = '邮箱模块安装成功......';
setting['sqlnum'] = '';

var dbhost = '<?php echo $dbhost?>';
var dbuser = '<?php echo $dbuser?>';
var dbpass = '<?php echo $dbpass?>';
var dbname = '<?php echo $dbname?>';
var pre = '<?php echo $pre?>';
var dbcharset = '<?php echo $dbcharset?>';
var pconnect = '<?php echo $pconnect?>';
var username = '<?php echo $username?>';
var password = '<?php echo $password?>';
var email = '<?php echo $email?>';
var ftp_user = '<?php echo $dbuser?>';
var password_key = '<?php echo $password_key?>';
function reloads() {
	var module = "admin,neirong,expand,huiyuan,guanliyuan,paiban,peizhi,pinglun,tongji,xitongrizhi,youxiang,sqlnum";
	m_d = module.split(',');
	$.ajax({
		   type: "POST",
		   url: 'install.php',
		   data: "step=installmodule&module="+m_d[n]+"&dbhost="+dbhost+"&dbuser="+dbuser+"&dbpass="+dbpass+"&dbname="+dbname+"&pre="+pre+"&dbcharset="+dbcharset+"&pconnect="+pconnect+"&username="+username+"&password="+password+"&email="+email+"&ftp_user="+ftp_user+"&password_key="+password_key+"&t="+Math.random()*5,
		   success: function(msg){
			   if(msg==1) {
				   alert('指定的数据库不存在，系统也无法创建，请先通过其他方式建立好数据库！');
			   } else if(msg==2) {
				   $('#installmessage').append("<font color='#ff0000'>"+m_d[n]+"/install/main/kuaifan_db.sql 数据库文件不存在</font>");
			   } else if(msg.length>20) {
				   $('#installmessage').append("<font color='#ff0000'>错误信息：</font>"+msg);
			   } else {
				   $('#installmessage').append(setting[m_d[n]] + msg + "<img src='images/correct.gif' /><br>");				   
					n++;
					if(n < m_d.length) {
						reloads();
					} else {
						var testdata = $('#testdata').val();
						if(testdata == 1) {
							$('#hiddenop').load('install.php?step=testdata&sid='+Math.random()*5);
							$('#installmessage').append("<font color='green'>测试数据安装完成</font><br>");
						}else{
							$('#hiddenop').load('install.php?step=deltestdata&sid='+Math.random()*5);
							$('#installmessage').append("");
						}
						$('#hiddenop').load('install.php?step=cache_all&sid='+Math.random()*5);						
						$('#installmessage').append("<font color='green'>缓存更新成功</font><br>");
						$('#installmessage').append("<font color='green'>恭喜您，网站安装完成！</font>");
						$('#finish').val('安装完成');
						//$('#finish').attr('onClick','window.location=\'install.php?step=6\';');
						setTimeout("$('#install').submit();",1000); 						
					}
					document.getElementById('installmessage').scrollTop = document.getElementById('installmessage').scrollHeight;
			   }	
		}	
		});
}
//-->
</script>
</body>
</html>
