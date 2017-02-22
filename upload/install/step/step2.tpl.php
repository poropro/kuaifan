<?php include KF_ROOT_PATH.'install/step/header.tpl.php';?>
<div id="body" style="padding: 16px;">
<style type="text/css">
#body div.ok, #body div.warn, #body div.error{border: 0px; background: none; margin: 0px; padding: 0px;}
.list .table td {padding:0px; padding-left: 4px;}
.b { font-weight:bold;}
</style>
	<div style="width: 700px; margin: auto;">
		<h1>2. KuaiFan CMS <?php echo $steps[$step];?></h1>
		<div class="list">
			<table align="center" class="table">
				<tr class="header">
					<td width="150">检查项目</td>
					<td width="180">系统建议</td>
					<td>当前环境</td>
					<td  width="120" style="text-align: center;">功能影响</td>
				</tr>
				<tr>
					<td>操作系统</td>
					<td>Windows_NT/Linux/Freebsd</td>
					<td class="b"><?php echo php_uname();?></td>
					<td><div class="ok">✔通过</div></td>
				</tr>
				<tr>
					<td>WEB服务器</td>
					<td>Apache/Nginx/IIS</td>
					<td class="b"><?php echo $_SERVER['SERVER_SOFTWARE'];?></td>
					<td><div class="ok">✔通过</div></td>
				</tr>
				<tr>
					<td>PHP版本</td>
					<td>PHP5.2.0及以上</td>
					<td class="b">PHP <?php echo phpversion();?></td>
					<td><?php if(phpversion() >= '5.2.0'){ ?>
						<div class="ok">✔通过</div>
						<?php }else{ ?>
						<img src="images/error.gif" alt="无法安装"/>
						<?php }?></td>
				</tr>
				<tr>
					<td>MYSQL扩展</td>
					<td>必须开启</td>
					<td class="b"><?php if(extension_loaded('mysql')){ ?>
						√
						<?php }else{ ?>
						×
						<?php }?></td>
					<td><?php if(extension_loaded('mysql')){ ?>
						<div class="ok">✔通过</div>
						<?php }else{ ?>
						<img src="images/error.gif" alt="无法安装"/>
						<?php }?></td>
				</tr>
				<tr>
					<td>ICONV/MB_STRING扩展</td>
					<td>必须开启</td>
					<td class="b"><?php if(extension_loaded('iconv') || extension_loaded('mbstring')){ ?>
						√
						<?php }else{ ?>
						×
						<?php }?></td>
					<td><?php if(extension_loaded('iconv') || extension_loaded('mbstring')){ ?>
						<div class="ok">✔通过</div>
						<?php }else{ ?>
						<img src="images/error.gif" alt="字符集转换效率低"/>
						<?php }?></td>
				</tr>
				<tr>
					<td>JSON扩展</td>
					<td>必须开启</td>
					<td class="b"><?php if($PHP_JSON){ ?>
						√
						<?php }else{ ?>
						×
						<?php }?></td>
					<td><?php if($PHP_JSON){ ?>
						<div class="ok">✔通过</div>
						<?php }else{ ?>
						<a href="http://pecl.php.net/package/json" target="_blank"><img src="images/error.gif" alt="不支持json,点击安装PECL扩展"/></a>
						<?php }?></td>
				</tr>
				<tr>
					<td>GD扩展</td>
					<td>建议开启</td>
					<td class="b"><?php if($PHP_GD){ ?>
						√(支持 <?php echo $PHP_GD;?>)
						<?php }else{ ?>
						×
						<?php }?></td>
					<td><?php if($PHP_GD){ ?>
						<div class="ok">✔通过</div>
						<?php }else{ ?>
						<img src="images/error.gif" alt="不支持缩略图和水印"/>
						<?php }?>
					</td>
				</tr>
				<tr>
					<td>ZLIB扩展</td>
					<td>建议开启</td>
					<td class="b"><?php if(extension_loaded('zlib')){ ?>
						√
						<?php }else{ ?>
						×
						<?php }?></td>
					<td><?php if(extension_loaded('zlib')){ ?>
						<div class="ok">✔通过</div>
						<?php }else{ ?>
						<img src="images/error.gif" alt="不支持Gzip功能"/>
						<?php }?>
					</td>
				</tr>
				<tr>
					<td>FTP扩展</td>
					<td>建议开启</td>
					<td class="b"><?php if(extension_loaded('ftp')){ ?>
						√
						<?php }else{ ?>
						×
						<?php }?></td>
					<td><?php if(extension_loaded('ftp')){ ?>
						<div class="ok">✔通过</div>
						<?php }elseif(ISUNIX){ ?>
						<img src="images/error.gif" alt="不支持FTP形式文件传送"/>
						<?php }?>
					</td>
				</tr>
				<tr>
					<td>allow_url_fopen</td>
					<td>建议打开</td>
					<td class="b"><?php if(ini_get('allow_url_fopen')){ ?>
						√
						<?php }else{ ?>
						×
						<?php }?></td>
					<td><?php if(ini_get('allow_url_fopen')){ ?>
						<div class="ok">✔通过</div>
						<?php }else{ ?>
						<img src="images/error.gif" alt="不支持保存远程图片"/>
						<?php }?>
					</td>
				</tr>
				<tr>
					<td>fsockopen</td>
					<td>建议打开</td>
					<td class="b"><?php if(function_exists('fsockopen')){ ?>
						√
						<?php }else{ ?>
						×
						<?php }?></td>
					<td><?php if($PHP_FSOCKOPEN=='1'){ ?>
						<div class="ok">✔通过</div>
						<?php }else{ ?>
						<img src="images/error.gif" alt="不支持fsockopen函数"/>
						<?php }?>
					</td>
				</tr>
				<tr>
					<td>DNS解析</td>
					<td>建议设置正确</td>
					<td class="b"><?php if($PHP_DNS){ ?>
						√
						<?php }else{ ?>
						×
						<?php }?></td>
					<td><?php if($PHP_DNS){ ?>
						<div class="ok">✔通过</div>
						<?php }else{ ?>
						<img src="images/error.gif" alt="不支持采集和保存远程图片"/>
						<?php }?>
					</td>
				</tr>
			</table>
		</div>
		<?php if($is_right) { ?>
			<div class="ok" style="text-align: center;">✔ 通过检测，KuaiFan CMS 可以正常运行在该环境下。 </div>
			<p style="text-align: center;">
				<input type="button" value=" 上一步" onClick="history.back();"/>
				<input type="button" value=" 下一步" onClick="window.location='install.php?step=3';"/>
			</p>
		<?php }else{ ?>
			<div class="error" style="text-align: center;">当前配置不满足KuaiFanCMS安装需求，无法继续安装。 </div>
			<p style="text-align: center;">
				<input type="button" value=" 上一步" onClick="history.back();"/>
			</p>
		<?php }?>
	</div>
</div>
<div id="footer"> Powered by KuaiFan (c) 2008-2015 </div>

</body>
</html>