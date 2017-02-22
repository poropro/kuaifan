{#$__seo_head="
	<link rel='stylesheet' href='{#$smarty.const.ACSS_PATH#}admin.css' />
	<link rel='stylesheet' type='text/css' href='{#$smarty.const.ACSS_PATH#}default.css' />
	<link rel='stylesheet' type='text/css' href='{#$smarty.const.ACSS_PATH#}style.css' />
	<script type='text/javascript' src='{#$smarty.const.AJS_PATH#}common.js'></script>
	<script type='text/javascript' charset='UTF-8' src='{#$smarty.const.AJS_PATH#}jquery-1.9.0.min.js'></script>
	<script type='text/javascript' charset='UTF-8' src='{#$smarty.const.AJS_PATH#}jquery-migrate-1.2.1.min.js'></script>
	<script type='text/javascript' charset='UTF-8' src='{#$smarty.const.AJS_PATH#}artDialog.js'></script>
	<script type='text/javascript' charset='UTF-8' src='{#$smarty.const.AJS_PATH#}iframeTools.js'></script>
	<script type='text/javascript' charset='UTF-8' src='{#$smarty.const.AJS_PATH#}form.js'></script>
	<script type='text/javascript' charset='UTF-8' src='{#$smarty.const.AJS_PATH#}validate.js'></script>
	<script type='text/javascript' charset='UTF-8' src='{#$smarty.const.AJS_PATH#}artTemplate.js'></script>
	<script type='text/javascript' charset='UTF-8' src='{#$smarty.const.AJS_PATH#}artTemplate-plugin.js'></script>
	<script type='text/javascript' src='{#$smarty.const.AJS_PATH#}common.js'></script>
	<script type='text/javascript' src='{#$smarty.const.AJS_PATH#}admin.js'></script>
	<script type='text/javascript' src='{#$smarty.const.AJS_PATH#}menu.js'></script>
	<script>
	$(document).ready(function () {
		if(self != top){
			$('#header').hide();
			$('#info_bar').hide();
			$('#admin_left').hide();
			$('#separator').hide();
			$('#admin_right').css('margin','0');
			$('#admin_right').css('padding','0');
		}
	}); 
	window.minrefreshtime = {#intval($KUAIFAN.minrefreshtime)#};
	</script>
"#}
{#include file="common/header.html.tpl" title="网站管理后台"#}

<div class="container">
  <div id="header">
    <div class="logo"> <a href=""><img src="{#$smarty.const.AIMG_PATH#}logo.gif" width="303" height="43"></a> </div>
    <div id="menu">
      <ul name="menu">
        <li class="selected" id="main"><a href="javascript:;">系统</a></li>
        <li id="shezhi"><a href="javascript:;">设置</a></li>
        <li id="mokuai"><a href="javascript:;">模块</a></li>
        <li id="neirong"><a href="javascript:;">内容</a></li>
        <li id="huiyuan"><a href="javascript:;">会员</a></li>
        <li id="kuozhan"><a href="javascript:;">扩展</a></li>
      </ul>
    </div>
    <p>
		<a href="{#$admin_indexurl#}&amp;c=out">退出管理</a>
		<a href="{#kuaifan getlinks='vs'#}&amp;m=index" target="_blank">网站首页</a>
		<span>您好<label class="bold">{#$admin_val.name#}</label>，当前身份<label class="bold">{#$admin_val.rank#}</label>。</span>
	</p>
  </div>
  <div id="info_bar">
    <span class="nav_sec"> </span> </div>
  <div id="admin_left" style="height: 609px;">
    <ul class="submenu">
		<li id="main" class="hide" style="display:block;">
			<span>我的面板</span>
			<ul>
				<li class="selected"><a href="{#$admin_indexurl#}" id="main">系统信息</a></li>
				<li><a href="{#$admin_indexurl#}&amp;c=guanli&amp;id={#$admin_val.id#}">修改后台密码</a></li>
			</ul>
		</li>

		<li id="shezhi" class="hide">
			<span>基本设置</span>
			<ul>
				<li><a href="{#$admin_indexurl#}&amp;c=paiban">排版设计</a></li>
				<li><a href="{#$admin_indexurl#}&amp;c=peizhi">网站配置</a></li>
				<li><a href="{#$admin_indexurl#}&amp;c=anquan">安全配置</a></li>
				<li><a href="{#$admin_indexurl#}&amp;c=youxiang">邮箱配置</a></li>
				<li><a href="{#$admin_indexurl#}&amp;c=guanli">管理帐号</a></li>
				<li><a href="{#kuaifan getlinks='vs'#}&amp;m=explain&amp;go_url={#urlencode(get_link('','&'))#}">帮助手册</a></li>
			</ul>
		</li>
		
		
		<li id="mokuai" class="hide">
			<span>模块管理</span>
			<ul>
				{#foreach from=$mokuaiarr item=list#}
					{#if $list.url#}
						<li><a href="{#$admin_indexurl#}{#$list.url#}">{#$list.name#}</a></li>
					{#else#}
						<li class="ing"><a href="javascript:;">{#$list.name#}(开发中)</a></li>
					{#/if#}
				{#/foreach#}
			</ul>
		</li>
		
		<li id="neirong" class="hide">
			<span>内容模块</span>
			<ul>
				<li><a href="{#$admin_indexurl#}&amp;c=neirong">内容管理</a></li>
				<li><a href="{#$admin_indexurl#}&amp;c=neirong&amp;a=fujian">附件管理</a></li>
				<li><a href="{#$admin_indexurl#}&amp;c=neirong&amp;a=lanmu">栏目管理</a></li>
				<li><a href="{#$admin_indexurl#}&amp;c=neirong&amp;a=moxing">模型管理</a></li>
				<li><a href="{#$admin_indexurl#}&amp;c=neirong&amp;a=pinglun">评论管理</a></li>
				<li><a href="{#$admin_indexurl#}&amp;c=neirong&amp;a=qingli">清理数据</a></li>
				<li><a href="{#$admin_indexurl#}&amp;c=neirong&amp;a=guanggao&amp;l=nr">内容广告</a></li>
				<li><a href="{#$admin_indexurl#}&amp;c=neirong&amp;a=guanggao&amp;l=pl">评论广告</a></li>
			</ul>
		</li>

		<li id="huiyuan" class="hide">
			<span>会员管理</span>
			<ul>
				<li><a href="{#$admin_indexurl#}&amp;c=huiyuan">会员管理</a></li>
				<li><a href="{#$admin_indexurl#}&amp;c=huiyuan&amp;a=shenhe">审核会员</a></li>
				<li><a href="{#$admin_indexurl#}&amp;c=huiyuan&amp;a=peizhi">会员配置</a></li>
				<li><a href="{#$admin_indexurl#}&amp;c=huiyuan&amp;a=fenzu">会员分组</a></li>
				<li><a href="{#$admin_indexurl#}&amp;c=huiyuan&amp;a=xunzhang">会员勋章</a></li>
				<li><a href="{#$admin_indexurl#}&amp;c=huiyuan&amp;a=moxing">管理会员模型</a></li>
			</ul>
		</li>

		<li id="kuozhan" class="hide">
			<span>扩展功能</span>
			<ul>
				<li><a href="{#$admin_indexurl#}&amp;c=shengji">在线升级</a></li>
				<li><a href="{#$admin_indexurl#}&amp;c=mumachasha">木马查杀</a></li>
				<li><a href="{#$admin_indexurl#}&amp;c=zhongduan">终端信息</a></li>
				<li><a href="{#$admin_indexurl#}&amp;c=adminrizhi">后台日志</a></li>
				<li><a href="{#$admin_indexurl#}&amp;c=jianfan">简繁互换</a></li>
				<li><a href="{#$admin_indexurl#}&amp;c=chakanhtml">查看源码</a></li>
				<li><a href="{#$admin_indexurl#}&amp;c=huancun">更新缓存</a></li>
				<li><a href="{#$admin_indexurl#}&amp;c=tongji">统计功能</a></li>
				<li><a href="{#$admin_indexurl#}&amp;c=shujuku">备份数据</a></li>
				<li><a href="{#$admin_indexurl#}&amp;c=shujuku&amp;a=huanyuan">还原数据</a></li>
				<li><a href="{#$admin_indexurl#}&amp;c=yingyong">应用插件</a></li>
				<li><a href="{#$admin_indexurl#}&amp;c=ucenter">UCenter</a></li>
			</ul>
		</li>

	  </li>
    </ul>
    <div id="copyright"></div>
  </div>
  <div id="admin_right">
    <div class="content_box" style="border:none">
      <div class="content" style="height: 579px;">
        <table width="31%" cellspacing="0" cellpadding="5" id="border_table_org" class="border_table_org" style="float:left">
          <thead>
            <tr><th>我的个人信息</th></tr>
          </thead>
          <tbody>
            <tr>
              <td><table class="list_table2" width="100%">
                  <colgroup><col width="80px"><col></colgroup>
                  <tbody>
                    <tr>
                      <th>用户名：</th>
                      <td>{#$admin_val.name#}</td>
                    </tr>
                    <tr>
                      <th>所属角色：</th>
                      <td>{#$admin_val.rank#}</td>
                    </tr>
                    <tr>
                      <th>上次登录时间：</th>
                      <td>{#$admin_val.lasttime|date_format:"%Y-%m-%d %H:%M:%S"#}</td>
                    </tr>
                    <tr>
                      <th>上次登录IP：</th>
                      <td>{#$admin_val.lastip#}</td>
                    </tr>
                    <tr>
                      <th>本次登录时间：</th>
                      <td>{#$admin_val.nowtime|date_format:"%Y-%m-%d %H:%M:%S"#}</td>
                    </tr>
                    <tr>
                      <th>本次登录IP：</th>
                      <td>{#$admin_val.nowip#}</td>
                    </tr>
                  </tbody>
                </table></td>
            </tr>
          </tbody>
        </table>
        <span id="border_iframe"></span>
      </div>
    </div>
  </div>
  <div id="separator"></div>
</div>

{#include file="common/footer.html.tpl"#}

