/**
 *
 * @param e 提示内容，===0则关闭提示
 * @param t 自动关闭时间，默认2000，===0则不自动关闭
 * @param p 点击关闭，赋值则不关闭
 * @param but 赋值显示按钮名称
 */
jQuery.alert = function(e, t, p, but, bk) {
    $("div.jQuery-ui-alert").remove();
    $("div.jQuery-ui-alert-back").remove();
    if (e === 0) return;
    var m = Math.round(Math.random() * 10000);
    if (but) e+= but;
    var n = '<div class="jQuery-ui-alert" style="display:hidden;position:fixed;top:0;left:0;padding:15px 10px;min-width:100px;opacity:1;min-height:25px;text-align:center;color:#fff;display:block;z-index:2147483647;border-radius:3px;background-color: rgba(51,51,51,.9); opacity:1;font-size:14px; line-height:22px;" id="jQuery-ui-alert-' + m + '" >' + e + '</div>' +
        '<div class="jQuery-ui-alert-back" style="display:none;z-index:2147483646" id="jQuery-ui-alert-back-' + m + '"></div>';
    $("body").append(n);
    var nobjbg = $('#jQuery-ui-alert-back-' + m);
    nobjbg.css({
        "width":"100%",
        "height":$(document).height(),
        "position":"absolute",
        "top":"0px",
        "left":"0px",
        "background-color":"#cccccc",
        "opacity":"0.6"
    });
    if (!bk) nobjbg.show();
    var nobj = $('#jQuery-ui-alert-' + m);
    if (!p)	nobj.click(function(){ nobj.fadeOut(); nobjbg.hide(); });
    if (!p)	nobjbg.click(function(){ nobj.fadeOut(); nobjbg.hide(); });
    var i = $(window).width(),
        s = $(window).height(),
        o = nobj.width()+20,
        u = nobj.height(),
        l = (i - o) / 2;
    i > o && nobj.css("left", parseInt(l)),
        i > o && nobj.css("right", parseInt(l)),
        s > u && nobj.css("top", (s - u) / 2 - 20),
        l < 5 && nobj.css("margin", "0 5px"),
        nobj.show();
    if (t === 0) return;
    setTimeout(function() { nobj.fadeOut(); nobjbg.hide(); }, t || 2000)
};
jQuery.alertk = function(e, t, p, but) {
    $.alert(e, t, p, but, 1)
}
jQuery.alertb = function(e, but, diy) {
    if (!but) but = "确定";
    var _click =  (!diy)?'onclick="$.alert(0)" ':'';
    $.alert(e, 0, 1, '<div '+_click+'style="text-align:center;padding:5px;border-top:1px solid #ECECEC;margin:15px -10px -15px;">'+but+'</div>')
}