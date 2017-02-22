{#include file="common/header.tpl" title_top="1" title="网站管理后台"#}

{#$smarty.now|date_format:"%Y-%m-%d %H:%M:%S"#}<br/>
{#$admintop#}


〓基本设置〓 <br/>
<a href="{#$admin_indexurl#}&amp;c=paiban">排版设计</a>|<a href="{#$admin_indexurl#}&amp;c=peizhi">网站配置</a> <br/>
<a href="{#$admin_indexurl#}&amp;c=anquan">安全配置</a>|<a href="{#$admin_indexurl#}&amp;c=youxiang">邮箱配置</a> <br/>
<a href="{#$admin_indexurl#}&amp;c=guanli">管理帐号</a>|<a href="{#kuaifan getlinks='vs'#}&amp;m=explain&amp;go_url={#urlencode(get_link('','&'))#}">帮助手册</a> <br/>

〓模块管理〓 <br/>
{#if $mokuaival#}
	{#$mokuaival#}
{#else#}
	没有任何模块
{#/if#}
<br/>

〓内容模块〓 <br/>
<a href="{#$admin_indexurl#}&amp;c=neirong">内容管理</a>|<a href="{#$admin_indexurl#}&amp;c=neirong&amp;a=fujian">附件管理</a> <br/>
<a href="{#$admin_indexurl#}&amp;c=neirong&amp;a=lanmu">栏目管理</a>|<a href="{#$admin_indexurl#}&amp;c=neirong&amp;a=moxing">模型管理</a> <br/>
<a href="{#$admin_indexurl#}&amp;c=neirong&amp;a=pinglun">评论管理</a>|<a href="{#$admin_indexurl#}&amp;c=neirong&amp;a=qingli">清理数据</a> <br/>
<a href="{#$admin_indexurl#}&amp;c=neirong&amp;a=guanggao&amp;l=nr">内容广告</a>|<a href="{#$admin_indexurl#}&amp;c=neirong&amp;a=guanggao&amp;l=pl">评论广告</a>  <br/>

〓会员管理〓 <br/>
<a href="{#$admin_indexurl#}&amp;c=huiyuan">会员管理</a>|<a href="{#$admin_indexurl#}&amp;c=huiyuan&amp;a=shenhe">审核会员</a> <br/>
<a href="{#$admin_indexurl#}&amp;c=huiyuan&amp;a=peizhi">会员配置</a>|<a href="{#$admin_indexurl#}&amp;c=huiyuan&amp;a=fenzu">会员分组</a> <br/>
<a href="{#$admin_indexurl#}&amp;c=huiyuan&amp;a=xunzhang">会员勋章</a>|<a href="{#$admin_indexurl#}&amp;c=huiyuan&amp;a=moxing">会员模型</a> <br/>

〓扩展功能〓 <br/>
<a href="{#$admin_indexurl#}&amp;c=shengji">在线升级</a>|<a href="{#$admin_indexurl#}&amp;c=mumachasha">木马查杀</a> <br/>
<a href="{#$admin_indexurl#}&amp;c=zhongduan">终端信息</a>|<a href="{#$admin_indexurl#}&amp;c=adminrizhi">后台日志</a> <br/>
<a href="{#$admin_indexurl#}&amp;c=jianfan">简繁互换</a>|<a href="{#$admin_indexurl#}&amp;c=chakanhtml">查看源码</a> <br/>
<a href="{#$admin_indexurl#}&amp;c=huancun">更新缓存</a>|<a href="{#$admin_indexurl#}&amp;c=tongji">统计功能</a> <br/>
<a href="{#$admin_indexurl#}&amp;c=shujuku">备份数据</a>|<a href="{#$admin_indexurl#}&amp;c=shujuku&amp;a=huanyuan">还原数据</a> <br/>
<a href="{#$admin_indexurl#}&amp;c=yingyong">应用插件</a>|<a href="{#$admin_indexurl#}&amp;c=ucenter">UCenter</a> <br/>

〓后台退出〓 <br/>
<a href="{#$admin_indexurl#}&amp;c=out">安全退出</a>|<a href="{#kuaifan getlinks='vs'#}&amp;m=index" target="_blank">网站首页</a> <br/>
-----------------

<br/>
{#if $VS=='1'#}
	切换:WML.<a href="{#kuaifan getlink='vs'#}&amp;vs=2">HTML</a><br/>
{#else#}
	<div style="background: #e97e00;border-top: 0px solid #ba5c18;color: #FC0;">
	切换: <a href="{#kuaifan getlink='vs'#}&amp;vs=1">WML</a>.HTML
	</div>
{#/if#}
{#kuaifan notvs="1" set='<div style="padding: 3px 5px 3px;background: #e4e4e4;font-size: 18px;">'#}
	©快范网络组荣誉出品
{#kuaifan notvs="1" set='</div>'#}


{#include file="common/footer.tpl"#}

