/*
 *  clicktag.js - tracking click event(s)  
 *
 *  copyright(c) activecore,Inc. 2005-2013
 * 
 *  This software is licensed by activecore, Inc.
 *  You CAN NOT use this file except in compliance with the License.
 *
 *  Product  : common for activecore products
 *  Version  : 5.0 
 *  Rev      : 1.0
 *  Last Update: 2013/09/26
 *  S/N      : acct111102013
 *
 *  Modification History
 *  ---------- ----- -----------------------------------------------------
 *  2013-01-17 REV01 click count for safari
 */
var _gif="tracer21.a-cast.jp/actag";
var _click_type = "";
function _void() { 
    return; 
}
function _acGetCookie(cn) {
   var get_data = document.cookie;
   var cv = new Array();
   var gd = get_data.split(";");
   var i;
   for (i = 0; i < gd.length; i++) { 
      var a = gd[i].split("=");
      a[0] = a[0].replace(" ","");
      cv[a[0]] = a[1];
   }
   if (cv[cn]) return cv[cn];
   else return "";
}
function _acSetCookie(cn,val) {
   document.cookie = cn + "=" + val + "; path=/; expires=Thu, 1-Jan-2030 00:00:00 GMT;";
}
function _encodeURL(str){
    var s0, i, s, u;
    s0 = "";                
    for (i = 0; i < str.length; i++){  
        s = str.charAt(i);
        u = str.charCodeAt(i);          
        if (s == " "){s0 += "+";}    
        else {
            if ( u == 0x2a || u == 0x2d || u == 0x2e || u == 0x5f || ((u >= 0x30) && (u <= 0x39)) || ((u >= 0x41) && (u <= 0x5a)) || ((u >= 0x61) && (u <= 0x7a))){   
                s0 = s0 + s;            
            }
            else {                  
                if ((u >= 0x0) && (u <= 0x7f)){     
                    s = "0"+u.toString(16);
                    s0 += "%"+ s.substr(s.length-2);
                }
                else if (u > 0x1fffff){     
                    s0 += "%" + (oxf0 + ((u & 0x1c0000) >> 18)).toString(16);
                    s0 += "%" + (0x80 + ((u & 0x3f000) >> 12)).toString(16);
                    s0 += "%" + (0x80 + ((u & 0xfc0) >> 6)).toString(16);
                    s0 += "%" + (0x80 + (u & 0x3f)).toString(16);
                }
                else if (u > 0x7ff){        
                    s0 += "%" + (0xe0 + ((u & 0xf000) >> 12)).toString(16);
                    s0 += "%" + (0x80 + ((u & 0xfc0) >> 6)).toString(16);
                    s0 += "%" + (0x80 + (u & 0x3f)).toString(16);
                }
                else {                     
                    s0 += "%" + (0xc0 + ((u & 0x7c0) >> 6)).toString(16);
                    s0 += "%" + (0x80 + (u & 0x3f)).toString(16);
                }
            }
        }
    }
    return s0;
}
function _clickTag(_cid, _page, _title, _item, _info, _cseg) {
    var now = new Date();
    var x = Math.round(Math.random() * (now.getTime() / 2147483647)) + now.getTime();
    var _proto=location.protocol;
    if (_proto != "http:" && _proto != "https:") {
        return;
    }
    var id;
    if(!navigator.cookieEnabled){    
        id = 'N/A';
    } else {
        id = _acGetCookie('ac');
        if (id == "") {
            id = 'N/A';
        }
    } 
    if (id != 'N/A') { 
        _acSetCookie('ac', id); 
    } else {
        _acSetCookie('ac', x);
        id = x; 
    }
    var url = window.location;
    var str = new String(url);
    var point = str.indexOf("#",0);
    if (point != -1) {
        url = str.substring(0, point);
    }
    if (_page != undefined && _page != '') {
        // page option setting here!
        if (_page.charAt(0) != "/") {
            _page = "/" + _page;
        }
        url = _proto+"//"+location.hostname+_page;
    }
    var ref = document.referrer;
    var ua = navigator.userAgent;
    var title = document.title;
    var item_param = '';
    var info_param = '';
    if (_title != undefined && _title != '') {
        title = _title;
    }
    if (url == "") url = 'N/A';
    if (ref == "") ref = 'N/A';
        if (ua == "") { ua = 'N/A'; } else { ua = _encodeURL(ua); }
    if (title == "") title = 'N/A';
    if (_item != undefined && _item != "") {
        item_param = '||||info=ac_item_no=' + _item;
    }
    if (_info != undefined && _info != "") {
        if (item_param == '') {
            item_param = '||||info=' + _info;
        } else {
            item_param += '|' + _info;
        }
    }
    if (_cseg != undefined && _cseg != '') {
        item_param += '||||cseg={' + _cseg + '}';
    }

    var _path=_proto + '//' + _gif + '?' + _cid + '*' + '0' + '*' + now.getTime() 
    + '*'+ url + '*' + ref + '*' + ua + '*' + id + '*' + _encodeURL(title) + item_param; 
    var _img=new Image(1,1);
    _img.src=_path;
    _img.onload=function() {_void();}

    if (_click_type != "t") {
        return;
    }

    if (navigator.userAgent.indexOf("Safari") >  0) {
        doHttpRead(_proto+"//"+location.hostname+"/robots.txt?"+(new Date()).getTime());
        return;
    }

    if (navigator.userAgent.indexOf("MSIE") < 0  && navigator.userAgent.indexOf("Safari") < 0 ) {
                var _stopTime = (new Date()).getTime() + 5000; 
        while (_img.complete == false) {
            doHttpRead(_proto+"//"+location.hostname+"/robots.txt?"+(new Date()).getTime());
            var _curTime = (new Date()).getTime() ;
                if ( _stopTime <= _curTime ) break ;
        }
    }

}
function _flashTag(_cid, _page) {
        _click_type = "f";
    _clickTag(_cid, _page, _page);
}


function ac_tracer(_cid, _page, _item, _info, _cseg) {
    _click_type = "t";
    _clickTag(_cid, _page, _page, _item, _info, _cseg);
}

//v50-001
function ac_tracer5(_cid, _page, _linkUrl, _item, _info, _cseg) {
    _click_type = "t";
    return _clickTag5(_cid, _page, _linkUrl, _item, _info, _cseg);
}

function ac_tracer_attr(_cid, _page, _param, _info, _linkUrl) {
    _click_type = "t";
    var now = new Date();
    var x = Math.round(Math.random() * (now.getTime() / 2147483647)) + now.getTime();
    var _proto=location.protocol;
    if (_proto != "http:" && _proto != "https:") {
        return;
    }
    var id;
    if(!navigator.cookieEnabled){    
        id = 'N/A';
    } else {
        id = _acGetCookie('ac');
        if (id == "") {
            id = 'N/A';
        }
    } 
    if (id != 'N/A') { 
        _acSetCookie('ac', id); 
    } else {
        _acSetCookie('ac', x);
        id = x; 
    }
    var url = window.location;
    var str = new String(url);
    var point = str.indexOf("#",0);
    if (point != -1) {
        url = str.substring(0, point);
    }

    var ref = document.referrer;
    var ua = navigator.userAgent;
    var title = document.title;
    if (url == "") url = 'N/A';
    if (ref == "") ref = 'N/A';
        if (ua == "") { ua = 'N/A'; } else { ua = _encodeURL(ua); }
    if (title == "") title = 'N/A';
    
    var _path=_proto + '//' + _gif + '?' + _cid + '*' + '0' + '*' + now.getTime() 
    + '*'+ _page + '*' + ref + '*' + ua + '*' + id + '*' + _encodeURL(title) + '||||info=' + _info + '||||param={' + _param + '}'; 

    var _img=new Image(1,1);
    _img.src=_path;

    var click_interval=500;

    var _isError = false;
    var _isOnload = false;       
    _img.onerror = function(){ 
        _isError = true; 
    };
    _img.onload = function(){ 
       _isOnload = true; 
    };
    if (_img.complete == true) {
       location.href = _linkUrl;
       return;
    }
    var _timerId = setInterval( function(){
       if (_img.complete == true || _isOnload == true || _isError == true) {
          clearInterval(_timerId);
          location.href = _linkUrl;
       }
    }, click_interval);

    if (_click_type != "t") {
        return;
    }
}

function createXMLHttpRequest() {
    if (window.XMLHttpRequest) {
        return new XMLHttpRequest();
    }
    try {
        return new ActiveXObject("MSXML2.XMLHTTP");
    } catch(e) {
        try {
            return new ActiveXObject("Microsoft.XMLHTTP");
        } catch(f) {
            return null;
        }
    }
}

function URLReader(url) {
    this.url = url;
    this.complete = false;
    this.req = createXMLHttpRequest();
    if (this.req == null) {
        // throw new Error("XMLHTTPRequest error");
        return null;
    }
    this.req.open("GET", this.url, false);  
}

URLReader.prototype.read = function() {
    this.req.send("");
    if (this.req.status == 200 && this.req.readyState== 4) {
        this.complete = true;
    } else {
        this.complete = false;
        // throw new Error("connect error");
    }
}

function doHttpRead(url) {
    try {
        var urlReader = new URLReader(url);
        if (urlReader == null) {
            return false;
        }
        urlReader.read();
    } catch (e) {
        // alert("connect error: " + url);
        return false;
    }
    return true;
}

//REV01
function _clickTag5(_cid, _page, _linkUrl, _item, _info, _cseg) {
    var now = new Date();
    var x = Math.round(Math.random() * (now.getTime() / 2147483647)) + now.getTime();
    var _proto=location.protocol;
    if (_proto != "http:" && _proto != "https:") {
        return;
    }
    var id;
    if(!navigator.cookieEnabled){    
        id = 'N/A';
    } else {
        id = _acGetCookie('ac');
        if (id == "") {
            id = 'N/A';
        }
    } 
    if (id != 'N/A') { 
        _acSetCookie('ac', id); 
    } else {
        _acSetCookie('ac', x);
        id = x; 
    }
    var _title = '';
    var url = window.location;
    var str = new String(url);
    var point = str.indexOf("#",0);
    if (point != -1) {
        url = str.substring(0, point);
    }
    if (_page != undefined && _page != '') {
        // page option setting here!
        if (_page.charAt(0) != "/") {
            _page = "/" + _page;
        }
        _title = _page;
        url = _proto+"//"+location.hostname+_page;
    }
    var ref = document.referrer;
    var ua = navigator.userAgent;
    var title = document.title;
    if (_title != undefined && _title != '') {
        title = _title;
    }
    var item_param = '';
    var info_param = '';
    if (title == undefined || title == "") {
        title = 'N/A';
    }
    if (url == "") url = 'N/A';
    if (ref == "") ref = 'N/A';
        if (ua == "") { ua = 'N/A'; } else { ua = _encodeURL(ua); }
    if (_item != undefined && _item != "") {
        item_param = '||||info=ac_item_no=' + _item;
    }
    if (_info != undefined && _info != "") {
        if (item_param == '') {
            item_param = '||||info=' + _info;
        } else {
            item_param += '|' + _info;
        }
    }
    if (_cseg != undefined && _cseg != '') {
        item_param += '||||cseg={' + _cseg + '}';
    }
    
    var _path=_proto + '//' + _gif + '?' + _cid + '*' + '0' + '*' + now.getTime() 
    + '*'+ url + '*' + ref + '*' + ua + '*' + id + '*' + _encodeURL(title) + item_param; 
    var _img=new Image();
    _img.src=_path;

	var _link =null;
	if(null != _linkUrl && 'undefined' != _linkUrl && '' != _linkUrl) {
		_link = _linkUrl;
	}
	var click_interval = 200;
	var _isError = false;
	var _isOnload = false;
	_img.onerror = function(){ 
		_isError = true; 
	};
	_img.onload = function(){ 
		_isOnload = true; 
	 };
	 if (_img.complete == true) {
		if(null != _link) {
			//location.href = _link;
			location = _link;
			return;
		}
		return;
	}
	var _timerId = setInterval(function(){
		if (_img.complete == true || _isOnload == true || _isError == true) {
			clearInterval(_timerId);
			if(null != _link) {
				//location.href = _link;
				location = _link;
				return;
			}
		}
	 },click_interval);
	 
	return;
	 
}
