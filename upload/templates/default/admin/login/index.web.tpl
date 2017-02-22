{#$__seo_head="
	<link rel='stylesheet' href='{#$smarty.const.ACSS_PATH#}admin.css' />
	<link rel='stylesheet' type='text/css' href='{#$smarty.const.ACSS_PATH#}default.css' />
	<style>
	.form_table_y {
		margin-top: 0px;
	}
	.form_table_y input.submit {
		margin-top: 5px;
	}
	.login_cont .yanzm input {
		width:120px;
	}
	.login_cont .yanzm img {
		height: 24px;
		vertical-align: bottom;
	}
	</style>
	<script type='text/javascript' src='{#$smarty.const.AJS_PATH#}common.js'></script>
	<script type='text/javascript' charset='UTF-8' src='{#$smarty.const.AJS_PATH#}jquery-1.9.0.min.js'></script>
	<script type='text/javascript' charset='UTF-8' src='{#$smarty.const.AJS_PATH#}jquery-migrate-1.2.1.min.js'></script>
	<script type='text/javascript' charset='UTF-8' src='{#$smarty.const.AJS_PATH#}artDialog.js'></script>
	<script type='text/javascript' charset='UTF-8' src='{#$smarty.const.AJS_PATH#}iframeTools.js'></script>
"#}
{#include file="common/header.html.tpl" title="管理后台登录"#}

<div id="login" class="container">
  <div id="header">
    <div class="logo"> <a href="#"><img src="{#$smarty.const.AIMG_PATH#}logo.gif" width="303" height="43" /></a> </div>
  </div>
  <div id="wrapper" class="clearfix">
    <div class="login_box">
      <div class="login_title">后台管理登录</div>
      <div class="login_cont">
        <form action='{#get_link()#}' method='post'>
          <table class="form_table" id="form_table">
            <col width="90px" />
            <col />
            <tr>
              <th valign="middle">用户名：</th>
              <td><input class="normal" type="text" name="username" alt="请填写用户名" /></td>
            </tr>
            <tr>
              <th valign="middle">密码：</th>
              <td><input class="normal" type="password" name="userpass" alt="请填写密码" /></td>
            </tr>
			{#if $yzmpeizhi.houtai#}
            <tr>
              <th valign="middle">验证码：</th>
              <td class="yanzm"><input class="normal" type="password" name="userpass" alt="请填写验证码" /><img id="yzm_img" src="{#kuaifan getlink='m|c'#}&amp;m=api&amp;c=yanzhengma"/></td>
            </tr>
			{#literal#}<script type="text/javascript">
				addClass(document.getElementById("form_table"), "form_table_y");
				function hasClass(obj, cls) {return obj.className.match(new RegExp('(\s|^)' + cls + '(\s|$)'));}
				function addClass(obj, cls) {if (!this.hasClass(obj, cls)) obj.className += " " + cls;}
				function set_refresh_code(id1,id2){if(document.getElementById(id1)){var temp_src=document.getElementById(id1).src;temp_src=temp_src.replace("&amp;","&");document.getElementById(id2).onclick=function(){if(temp_src.indexOf("?")){document.getElementById(id1).src=temp_src+"&_refresh="+Math.random()}else{document.getElementById(id1).src=temp_src+"?_refresh="+Math.random()};return false#{}}
				set_refresh_code('yzm_img', 'yzm_img');
			</script>{#/literal#}
			{#/if#}
            <tr>
              <th valign="middle"></th>
              <td><input class="submit" type="submit" name="dosubmit" value="登录" />
                <input class="submit" type="reset" value="取消" /></td>
            </tr>
          </table>
        </form>
      </div>
    </div>
  </div>
  <div id="footer">{#include file="admin/copyright.tpl"#}</div>
</div>

{#include file="common/footer.html.tpl"#}
