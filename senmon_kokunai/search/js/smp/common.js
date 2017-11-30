//金額にカンマ付与
function addFigure(str) {
	var num = new String(str).replace(/,/g, "");
	while(num != (num = num.replace(/^(-?\d+)(\d{3})/, "$1,$2")));
	return num;
}
//金額配列
var price_t = [0,1000,2000,3000,4000,5000,6000,7000,8000,9000,10000,20000,30000,40000,50000,60000,70000,80000,90000,100000,110000,120000,130000,140000,150000,200000,250000,300000,350000,400000,450000,
500000,600000,700000,800000,900000,1000000];
//お宿用
var price_oyado_t = [0,5000,6000,7000,8000,9000,10000,11000,12000,14000,16000,18000,20000,30000,40000,50000,100000];

//連想配列の値からキーを取得
function getKey(value) {
	var returnKey = [];
	for(var key in price_t){
		if (price_t[key] == value) {
			returnKey.push(key);
		}
	}
	return returnKey;
}
//連想配列の値からキーを取得。最大値はkeyを+1する
function getKeyMax(value) {
	var returnKey = [];
	for(var key in price_t){
		if (price_t[key] == value) {
			
			if(key != 36)
			{
				key++;
			}
			
			returnKey.push(key);
		}
	}
	return returnKey;
}

//金額のヨリマデ作成
//function makeYoriMade(min,max,str="円",NgStr=0){
function makeYoriMade(min,max){
	
	var ret = '';
	var NgStr = 0;
	var str = '円';
	if( typeof(min) == 'undefined' && typeof(max) == 'undefined' || 
		min == '' && max == '' ){
		ret = '未設定';
		return ret;
	}
	if( NumberCheck(min) && NumberCheck(max) ){
		//同じ場合
		if(min == max){
			if(min == 0 || min == ''){
				ret = NgStr;
			}
			else{	//単一
				ret = addFigure(min) + str;
				if(min >= 1000000){
					ret += '以上'; 
				}
			}
		}
		else{
			if(min == ''){
				min = NgStr;
			}
			ret = addFigure(min) + '〜' + addFigure(max) + str;
			if(max >= 1000000){
				ret += '以上'; 
			}
			
			if(max == '')
			{
				ret = addFigure(min) + '〜上限なし';
			}
			
		}
	}
	return ret;
}
//旅行日数文言作成
function makeKikan(min,max){
	var ret = '';
	
	if( typeof(min) == 'undefined' && typeof(max) == 'undefined' || 
		min == '' && max == '' ){
		ret = '未設定';
		return ret;
	}
	if(max == "")
	{
		ret = min + '日間以上';
	}
	else if( NumberCheck(min) && NumberCheck(max) ){
		if(min == max){
			if(min == 1){
				ret +='日帰り';
			}else{
				ret +=min + '日間';
			}
		}else{
			ret += min + '〜'+ max +'日間';
		}
	}
	
	return ret;
}
//最小値を返却
function getMin(obj){
	var cnt = 0;
	for(i in obj){
		var val = parseInt(obj[i],10);
		if(cnt == 0) {
			var num = val;
		}else{
			num = (num > val) ? val : num;
		}
		cnt++;
	}
	return num;
}

//最大値を返却
function getMax(obj){
	var cnt = 0;
	for(i in obj){
		var val = parseInt(obj[i],10);
		if(cnt == 0){
			var num = val;
		}else{
			num = (num < val) ? val : num;
		}
		cnt++;
	}
	return num;
}

//■日付編集
function makeDepDate(date){
	var dateVal = date;
	var ret = '';
	
	if(dateVal){
		if(dateVal.indexOf('/') != -1){
			var DateAry = dateVal.split('/');
			//月指定
			if(typeof(DateAry[2]) == 'undefined' || DateAry[2] == ''){
				dateVal =  DateAry[0] + ( '0' + DateAry[1] ).slice(-2);
			}
			//日付
			else{
				dateVal = DateAry[0] + ("0" + DateAry[1]).slice(-2) + ("0" + DateAry[2]).slice(-2);
			}
		}
		dateVal.match(/([0-9]{4})([0-9]{2})([0-9]{2})?/);
		var year = RegExp.$1
		var month = RegExp.$2;
		var day = RegExp.$3;
		if(year){
			var ret = year + '年';
				ret += month + '月';
		}
		if(day){
			ret += day + '日';
		}
	}	
	return ret;
}
//数字チェック
function NumberCheck(val) {
	var str = val;
	if( str.match( /[^0-9]+/ ) ) {
	  return false;
	}
	return true;
}

//クエリー取得
function GetQueryString() {
	if (1 < document.location.search.length) {
		// 最初の1文字 (?記号) を除いた文字列を取得する
		var query = document.location.search.substring(1);
	
		// クエリの区切り記号 (&) で文字列を配列に分割する
		var parameters = query.split('&');
		var result = new Object();
		for (var i = 0; i < parameters.length; i++) {
			// パラメータ名とパラメータ値に分割する
			var element = parameters[i].split('=');
			var paramName = decodeURIComponent(element[0]);
			var paramValue = decodeURIComponent(element[1]);

			// パラメータ名をキーとして連想配列に追加する
			result[paramName] = decodeURIComponent(paramValue);
			
		}
		return result;
	}
	return null;
}
//PC版へボタンリンク作成
function LocationToPC(paramAry) {
	var param = href = '';
	if(typeof paramAry !== "undefined"){
		for(i in paramAry){
			//vpc除く
			if(i == 'vpc'){
				continue;
			}
			param += i + '=' + paramAry[i];
		}
	}
	if(param){
		href = location.protocol + '//' + location.host + location.pathname + '?vpc=1' + '&' + param;
	}else{
		href = location.protocol + '//' + location.host + location.pathname + '?vpc=1';
	}
	location.href= href;
}

//コンテンツの高さでBOXにfixed
function setIniBox(pageID, obj){
	var box = $(pageID + ' .dpfixedBox');
	var boxParent = $(pageID + ' .fixedBox');
	var offset = boxParent.offset();
	var boxParentHeight = boxParent.height();
	var wheiht = $(window).height();
	
	var flg = getChk(obj);
	
	if($(window).scrollTop() + wheiht <= offset.top + boxParentHeight) {
		if(flg){
			box.removeClass('non-active').addClass('fixed active');
		}else{
			box.removeClass('active').addClass('fixed non-active');
		}
	}else {
		if(flg){
			box.removeClass('fixed active').addClass('non-active');
		}else{
			box.removeClass('active fixed').addClass('non-active');
		}
	}
}

//チェック状態取得
function getChk(obj){
	var flg = false;
	if(obj.hasClass('check') || obj.hasClass('btnSelect')){
		flg = true;
	}
	return flg;
}