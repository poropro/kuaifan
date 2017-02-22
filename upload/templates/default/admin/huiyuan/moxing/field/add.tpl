{#include file="common/header.tpl" title_top="1" title="添加字段"#}

<a href="{#kuaifan getlink='fadd'#}">返回列表</a><br/>
[选择字段类型]<br/>
-------------<br/>
<a href="{#kuaifan getlink='fadd'#}&amp;faddl=text">单行文本</a>:最长255个字符 <br/>
<a href="{#kuaifan getlink='fadd'#}&amp;faddl=textarea">多行文本</a>:支持wml文本 <br/>
<a href="{#kuaifan getlink='fadd'#}&amp;faddl=box">选项</a>:下拉型列表框 <br/>
<a href="{#kuaifan getlink='fadd'#}&amp;faddl=number">数字</a>:支持小数点 <br/>
<a href="{#kuaifan getlink='fadd'#}&amp;faddl=datetime">日期和时间</a>:可选时间格式 <br/>


{#include file="admin/footer.tpl"#}
{#include file="common/footer.tpl"#}
