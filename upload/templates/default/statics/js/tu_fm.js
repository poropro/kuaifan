
var F = (function (F, win, doc, undefined) {

  "use strict";

  var TOUCH_START = 'ontouchstart' in win ? 'touchstart' : 'mousedown';
  var TOUCH_MOVE = 'ontouchmove' in win ? 'touchmove' : 'mousemove';
  var TOUCH_END = 'ontouchend' in win ? 'touchend' : 'mouseup';

  var device = (function () {
    var ua = navigator.userAgent;
    var android = ua.match(/(Android)\s+([\d.]+)/);
    var ipad = ua.match(/(iPad).*OS\s([\d_]+)/);
    var iphone = ua.match(/(iPhone\sOS)\s([\d_]+)/);
    var wphone = ua.match(/Windows Phone/);
    
    if (ipad || iphone) {
      return "ios";
    } else if (android) {
      return "android";
    } else if (wphone) {
      return "wphone";
    }
    return "other";

  }());

  var elementStyle = document.createElement("div").style;
  
  var vendor = (function () {
    var lists = ['t', 'webkitT', 'MozT', 'msT', 'OT'];
    var transform;

    for (var i = 0, iLen = lists.length; i < iLen; i++) {
      transform = lists[i] + 'ransform';
      
      if (transform in elementStyle) {
        return lists[i].substr(0, lists[i].length - 1);
      }
    }

    return false;
  }());
  
  var getStylePre = function () {
    return vendor;
  };

  var prefixStyle = function (style) {
    if (vendor === false) {
      return false;
    }
    if (vendor === '') {
      return style;
    }
    return vendor + style.charAt(0).toUpperCase() + style.substr(1);
  };
  
  var addEvent = function (dom, type, callback) {
    dom.addEventListener(type, callback, false);
  };

  var query = function (selects) {
    return doc.querySelectorAll(selects);
  };

  var one = function (id) {
    return doc.getElementById(id);
  };
  
  var hasClass = function (elm, className) {
    var re = new RegExp("(^|\\s)" + className + "(\\s|$)");
    return re.test(elm.className);
  };
  
  var addClass = function (elm, className) {
    if (!hasClass(elm, className)) {
      elm.className = elm.className + " " + className;
    }
  };
  
  var removeClass = function (elm, className) {
    var re = new RegExp("(^|\\s)" + className + "(\\s|$)", 'g');
    if (hasClass(elm, className)) {
      elm.className = elm.className.replace(re, ' ');
    }
  };
  
  var tap = function (elm, callback, opts) {
    opts = opts || {};
    var duration = opts.duration || 0;
    var fixDistance = opts.fixDistance || 10;
    var time;
    var pageX;
    var pageY;

    addEvent(elm, TOUCH_START, function (e) {
      time = new Date().valueOf();
      pageX = getEventPageXY(e, "x");
      pageY = getEventPageXY(e, "y");
    });

    addEvent(elm, TOUCH_END, function (e) {
      var result = true;
      var endTime = new Date().valueOf();
      var endPageX = getEventPageXY(e, "x", true);
      var endPageY = getEventPageXY(e, "y", true);
      
      if (endTime - time > duration &&
          Math.abs(endPageX - pageX) < fixDistance &&
          Math.abs(endPageY - pageY) < fixDistance) {
        result = callback.call(elm, e);
      }

      if (result === false) {
        e.preventDefault();
        e.stopPropagation();
      }

    });
  };

  var getEventPageXY = function (e, xy, isEnd) {
    e = e || window.event;
    xy = xy === "x" ? "pageX" : "pageY";
    isEnd = typeof isEnd === "undefined" ? false : true;
    var useTouch = 'ontouchstart' in win;

    if (!useTouch) {
      return e[xy];
    } else if (isEnd) {
      return e.changedTouches[0][xy];
    } else {
      return e.touches[0][xy];
    }

  };

  var swip = function (elm, startCallback, movCallback, endCallback, transitionCallback, opts) {
    opts = opts || {};
    var canMove;
    var beginX;
    var beginY;
    var endX;

    addEvent(elm, TOUCH_START, function (e) {
      canMove = "uncertain";
      beginX = getEventPageXY(e, "x");
      beginY = getEventPageXY(e, "y");
      startCallback.call(elm, beginX, beginY);
    });
    
    addEvent(elm, TOUCH_MOVE, function (e) {
      var movX = getEventPageXY(e, "x");
      var movY = getEventPageXY(e, "y");

      if (canMove === "uncertain") {
        if (Math.abs(movX - beginX) > Math.abs(movY - beginY)) {
          canMove = "movable";
        } else {
          canMove = "unmoveable";
        }
      }
      
      if (canMove === "unmoveable") {
        return;
      }
      
      var result = movCallback.call(elm, beginX, movX);

      if (result === false) {
        e.preventDefault();
        e.stopPropagation();
      }

    });
    
    addEvent(elm, TOUCH_END, function (e) {
      endX = getEventPageXY(e, "x", true);
      if (canMove === "unmoveable") {
        return;
      }

      endCallback.call(elm, beginX, endX);
      canMove = "unmoveable";
      
    });

    addEvent(elm, getStylePre() + "TransitionEnd", function () {
      transitionCallback.call(elm, beginX, endX);
    });
  };

  var toggle = function (elm) {
    var fnCount = arguments.length - 1;
    var args = arguments;
    var currentIndex = 1;
    tap(elm, function (e) {
      args[currentIndex].call(elm, e);
      currentIndex++;
      if (currentIndex > fnCount) {
        currentIndex = 1;
      }
    });
  };
  
  var timestampIndex = 0;
  var jsonp = function (options) {

    var cache = typeof options.cache !== "undefined" ? cache : false;
    var url = options.url;
    var success = options.success;
    var data = [];
    var scope = options.scope || win;
    var callback;
    if (typeof options.data === "object") {
      for (var k in options.data) {
        data.push(k + "=" + encodeURIComponent(options.data[k]));
      }
    }

    if (typeof options.callback === "string" && options.callback !== "") {
      callback = options.callback;
    } else {
      callback = "f" + new Date().valueOf().toString(16) + timestampIndex;
      timestampIndex++;
    }

    data.push("callback=" + callback);

    if (cache === false) {
      data.push("_=" + new Date().valueOf() + timestampIndex);
      timestampIndex++;
    }
    if (url.indexOf("?") < 0) {
      url = url + "?" + data.join("&");
    } else {
      url = url + "&" + data.join("&");
    }

    var insertScript = doc.createElement("script");
    insertScript.src = url;

    win[callback] = function () {
      success.apply(scope, [].slice.apply(arguments).concat("success", options));
    };

    insertScript.onload = insertScript.onreadystatechange = function () {
      if (!this.readyState || this.readyState === 'loaded' || this.readyState === 'complete') {
        insertScript.onload = insertScript.onreadystatechange = null;
        insertScript.parentNode.removeChild(insertScript);
      }
    };

    var oScript = doc.getElementsByTagName("script")[0];
    oScript.parentNode.insertBefore(insertScript, oScript);

  };
  var loadScript = function (options) {

    var cache = typeof options.cache !== "undefined" ? cache : false;
    var url = options.url;
    var success = options.success;
    var data = [];
    var scope = options.scope || win;

    if (typeof options.data === "object") {
      for (var k in options.data) {
        data.push(k + "=" + encodeURIComponent(options.data[k]));
      }
    }
    if (cache === false) {
      data.push("_=" + new Date().valueOf() + timestampIndex);
      timestampIndex++;
    }
    if (url.indexOf("?") < 0) {
      url = url + "?" + data.join("&");
    } else {
      url = url + "&" + data.join("&");
    }

    var insertScript = doc.createElement("script");
    insertScript.setAttribute('type', 'text/javascript');
    insertScript.setAttribute('src', url);
    insertScript.setAttribute('async', true);

    insertScript.onload = insertScript.onreadystatechange = function () {
      if (!this.readyState || this.readyState === 'loaded' || this.readyState === 'complete') {
        if (typeof success === "function") {
          success.apply(scope, ["", "success", options]);
        }
        insertScript.onload = insertScript.onreadystatechange = null;
        insertScript.parentNode.removeChild(insertScript);
      }
    };

    var oScript = doc.getElementsByTagName("script")[0];
    oScript.parentNode.insertBefore(insertScript, oScript);
  };

  F.m = F.m || {};
  F.m.device = device;
  F.m.device = device;
  F.m.prefixStyle = prefixStyle;
  F.m.query = query;
  F.m.one = one;
  F.m.hasClass = hasClass;
  F.m.addClass = addClass;
  F.m.removeClass = removeClass;
  F.m.addEvent = addEvent;
  F.m.tap = tap;
  F.m.swip = swip;
  F.m.toggle = toggle;
  F.m.jsonp = jsonp;
  F.m.loadScript = loadScript;
  F.m.getStylePre = getStylePre;
  
  return F;

} ((F || {}), window, document));
