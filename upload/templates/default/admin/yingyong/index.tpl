{#$__seo_head="
	<script type='text/javascript' src='{#$smarty.const.JS_PATH#}jquery_1.4.2.js'></script>
	<script type='text/javascript' src='{#$smarty.const.JS_PATH#}jquery.alert.js'></script>
"#}
{#include file="common/header.html5.tpl" title_top="1" title="应用插件"#}

[应用插件<a href="{#$admin_indexurl#}&amp;c=yingyong&amp;a=tianjia">添加</a>]<br/>


<input type="button" value="一键查新版本" id="yijianup"/><br/>

{#kuaifan_pc set="列表名:lists,显示数目:1000,数据表:yingyong,排序:intime DESC"#}
{#$_appurl = get_link("allow|vs","",1)#}
{#foreach from=$lists item=list#}
	-------------<br/>
	{#$_setting = string2array($list.setting)#}
	{#if $_setting.dataurl#}
		{#$list._n#}.<a href="{#$_setting.dataurl|appurl#}">{#$list.title#}</a>({#$list.v#})
	{#else#}
        {#$list._n#}.<a href="{#$_appurl#}&amp;m={#$list.name#}&amp;c=index">{#$list.title#}</a>({#$list.v#})
	{#/if#}
	<br/>
	<a id="{#$list.name#}" href="{#$admin_indexurl#}&amp;c=yingyong&amp;a=info&amp;app={#$list.name#}&amp;islocal={#$_setting.local#}">更新</a>.<a href="{#$admin_indexurl#}&amp;c=yingyong&amp;a=del&amp;app={#$list.name#}">删除</a>
	<br/>
{#foreachelse#}
	没有<a href="{#$admin_indexurl#}&amp;c=yingyong&amp;a=tianjia">安装</a>任何应用插件。<br/>
{#/foreach#}


<script type="text/javascript">
    $(function(){
        $("#yijianup").click(function(){
            $.alert("正在检测...",0,1);
            {#$upurl = str_replace('&amp;','&',$admin_indexurl)#}
            setTimeout(function(){
                $.ajax({
                    type: "GET",
                    url: "{#$upurl#}&c=yingyong&a=getup",
                    dataType: "json",
                    success: function (data) {
                        $.alert(0);
                        $.each(data, function(idx, obj) {
                            if ($("a#"+idx)) {
                                $("a#"+idx).css("color","#ff0000").text("更新("+obj.versiondate+")");
                            }
                        });
                    },error: function (data) {
                        $.alert(0);
                    }
                });
            },{#$KUAIFAN.minrefreshtime#});
        });
    });
</script>

{#include file="admin/footer.html.tpl"#}
{#include file="common/footer.html.tpl"#}
