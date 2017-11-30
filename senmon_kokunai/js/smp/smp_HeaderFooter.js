$(function(){
	// Tab
	/*$('.tab-content .tab-ct').hide();
	$('.wr-tab').each(function(index, el) {
		$(this).find('.tab-content .tab-ct').eq($(this).find('.tab-menu li.active').index()).show();
	});
	$('.tab-menu li').click(function(event) {
		$(this).parent().children().removeClass('active');
		$(this).addClass('active');
		$(this).parents('.wr-tab').find('.tab-ct').hide();
		$(this).parents('.wr-tab').find('.tab-ct').eq($(this).index()).show();
	});*/
	
	var consider;	
	$("#js_firstTxt").css("display","none");
	
	//検討中件数取得
	Lkey = typeof(Lkey) != "undefined" ? Lkey: '';
	if(!Lkey){
		consider = getConsiderCkieCnt.get_Cookie();
		$(".ck_num").text(consider);
	}else{
		cscnt.execAjax('i',Lkey);
	}
	
	
	//モーダルウィンドウを出現させるクリックイベント
	$(".js_toMenu").click(function(){
	
		//オーバーレイを出現させる
		$("body").append('<div id="modal-overlay"></div>');
		$("#modal-overlay").fadeIn("slow");
	
		//コンテンツをセンタリングする
		centeringModalSyncer(".js_GlMenu");
	
		//コンテンツをフェードインする
		$(".js_GlMenu").fadeIn("slow");
	
		//[#modal-overlay]、または[#modal-close]をクリックしたら…
		$("#modal-overlay,.js_GlMenuClose a").unbind().click(function(){
	
			//[#modal-content]と[#modal-overlay]をフェードアウトした後に…
			$(".js_GlMenu,#modal-overlay").fadeOut("slow",function(){
	
				//[#modal-overlay]を削除する
				$('#modal-overlay').remove();
	
			});
	
		});
	
	});

	$("#jsHatsuSet").click(function(){
	
		$("body").append('<div id="modal-overlay"></div>');
		$("#modal-overlay").fadeIn("slow");
	
		centeringModalSyncer(".js_HatsuMenu");
	
		$(".js_HatsuMenu").fadeIn("slow");
	
		$("#modal-overlay,.js_HatsuMenuClose a").unbind().click(function(){
	
			$(".js_HatsuMenu,#modal-overlay").fadeOut("slow",function(){
				$('#modal-overlay').remove();
			});
	
		});
	});
	
	
	$("#JsConsider").click(function(){
	
		$("body").append('<div id="modal-overlay"></div>');
		$("#modal-overlay").fadeIn("slow");
	
		centeringModalSyncer(".js_considerMenu");
	
		$(".js_considerMenu").fadeIn("slow");
	
		$("#modal-overlay,.js_considerMenuClose a").unbind().click(function(){
	
			$(".js_considerMenu,#modal-overlay").fadeOut("slow",function(){
				$('#modal-overlay').remove();
			});
	
		});
	});
	
	$('#js_headerSrchBtn').click(function(){
		$('form[name=digianaSiteSearch]').submit();
	});	

	//リサイズされたら、センタリングをする関数[centeringModalSyncer()]を実行する
	$(window).resize(centeringModalSyncer);
	
	//センタリングを実行する関数
	function centeringModalSyncer(selecter){
	
		//画面(ウィンドウ)の幅、高さを取得
		var w = $(window).width();
		var h = $(window).height();
	
		//jquery mobile用
		if($("div").hasClass("ui-page-active")){
				var a = w / 10;
				var cw = a * 8; 
				$(selecter).css({"left": ((w - cw)/2) + "px"})
		}
		else{
			if($(".js_GlMenu").length){
				var cw = $(selecter).outerWidth({margin:true});
				var ch = $(selecter).outerHeight({margin:true});
			}
			if(isFinite(cw)){
			}
			else{
				var a = w / 10;
				var cw = a * 8; 	
			}
			//センタリングを実行する
			$(selecter).css({"left": ((w - cw)/2) + "px"})
		}
	}
	
		
	
	var cookieName;
	var cookieValue;
	var ajaxFile ='/sharing/common16/phpsc/ajax_setKyotenName.php';//拠点名取得用
	var Js_setMykencode = '#jsHatsuSet a';//選択された拠点名
	var kyotenHatsuName;//発拠点名
	 if($.cookie('HK_MyState')){
		cookieName='HK_MyState';
		cookieValue =$.cookie('HK_MyState');
	}
	
	else{
		kyotenHatsuName ='未選択';
		$("body").append('<div id="modal-overlay"></div>');
		$("#modal-overlay").fadeIn("slow");
	
		centeringModalSyncer(".js_HatsuMenu");
	
		$(".js_HatsuMenu").fadeIn("slow");
		$("#js_firstTxt").css("display","block");
		// $(".js_HatsuMenuClose").css("display","none");
		$("#modal-overlay,.js_HatsuMenuClose a").unbind().click(function(){
	
			$(".js_HatsuMenu,#modal-overlay").fadeOut("slow",function(){
				$('#modal-overlay').remove();
			});
	
		});
		return false
	}

	if(kyotenHatsuName == null){
		$.ajax({
			type: 'post'
			,url: ajaxFile
			,dataType: 'html'
			,data: {'cookieName':cookieName,'cookieValue':cookieValue}
			,success: function(html){
				$(Js_setMykencode).text(html);
			}
		});
	}
	else{
		$(Js_setMykencode).text(kyotenHatsuName);
	}

});


/*============================================
県を選択してクリックした時クッキーをセット
============================================*/
function SelectKenLink(kencode,name){
	
	if (typeof sessionStorage !== 'undefined') {
		sessionStorage.clear();
	}
	var expDate = makeCookieSet.set_expires(180);
	makeCookieSet.set_Cookie("HK_MyState", kencode, expDate, "/", "hankyu-travel.com", "");
	if($.cookie('HK_CBKyoten')){
		$.cookie('HK_CBKyoten', "",{expires:-1, path: '/', domain: 'hankyu-travel.com'});
	}

	var ajaxFile ='/sharing/common16/phpsc/ajax_setKyotenName.php';//拠点名取得用
	$.ajax({
		type: 'post'
		,url: ajaxFile
		,dataType: 'html'
		,data: {'cookieName':'HK_MyState','cookieValue':kencode}
		,success: function(html){
			$(Js_setMykencode).text(html);
		}
	});
	
	
	$(".js_HatsuMenu,#modal-overlay").fadeOut("slow",function(){
		$('#modal-overlay').remove();
	});
	
	if(history.replaceState) {
		history.replaceState(null,null,location.href);
		var reloadLink = location.href.split("#");
		window.location.replace(reloadLink[0]);
	}else{
		var reloadLink = location.href.split("#");
		window.location.replace(reloadLink[0]);
	}

	return false;
}

/*============================================
クッキー作成
============================================*/
var makeCookieSet = {
	//クッキーセット
	set_Cookie:function(name, value, expires, path, domain, secure){
		document.cookie = name + "=" + escape (value) +
		((expires) ? "; expires=" + expires : "") +
		((path) ? "; path=" + path : "") +
		((domain) ? "; domain=" + domain : "") +
		((secure) ? "; secure" : "");
	},
	//有効期限の作成
	set_expires:function(day){
		var expire_date = new Date();
		expire_date.setTime(expire_date.getTime() + day*24*60*60*1000);
		var expires = expire_date.toGMTString();
		return expires;
	}

}

var getConsiderCkieCnt = {
	//consider_iとconsider_dのCookieを取得
	get_Cookie:function(){
		var cookieI,cookieD,conbain,CKAry;
		cookieI = $.cookie("consider_i");
		cookieD = $.cookie("consider_d");
		if(cookieI && cookieD){
			conbain = cookieI+","+cookieD;
		}else if(cookieI){
			conbain = cookieI;
		}else{
			conbain = cookieD;
		}
		if(conbain){
			CKAry = conbain.split(",");
			if(!CKAry[0]){
				CKAry = "";
			}
			return CKAry.length;
		}
	}
}

var cscnt = {
	dataCount:0
	,execAjax: function(naigai,Lkey){
		$.ajax({
		  url: "/tour/consider_mod.php",
		  type:'POST',
		  dataType: 'json',
		  data : {Lkey:Lkey,naigai:naigai,act:''},
		  timeout:10000,
		  success: function(JSON) {
			var considerList = JSON.considerList;
			var errCode = JSON.errorcode;
			cscnt.dataCount += parseInt(JSON.datacount);
			if(errCode != 0){
				cscnt.errorProcess(errCode);
			}

			if(naigai == 'i'){
				cscnt.execAjax('d',Lkey);
			}
			else if(naigai == 'd'){
				cscnt.countView(cscnt.dataCount);
			}
		  }
		});
	},
	countView : function($dataCnt){
		if($(".ck_num").length > 0){
			$(".ck_num").text($dataCnt);
		}
	},
	errorProcess: function($errCode){
		//エラー画面に飛ばす
		var erTxt;
		var message;

		var Url = location.href;
		var Url = Url.replace("https:", "http:");
		var backLink = Url.split('&decision=1')[0];
		switch(status){
		case "5":
		  erTxt = "登録数の上限を超えています。";
		  message = "海外旅行、国内旅行それぞれ8件までとなります。";
		  break;

		default:
		  erTxt = "システムエラーのため、ログアウトします。";
		  message = "恐れ入りますがしばらく時間をおいてから、再度お試しください。";
		  break;
		}
		$.ajax({
			url: "/tour/detail_modal/modal_consider.php",
			type: "POST",
			dataType: 'html',
			data : {status:status, erTxt:erTxt, backLink:backLink, message:message},
			timeout:10000,
			success: function(htm) {
			  $("body").append('<div id="modal"></div>');
			  $("#modal").html(htm);
			},
			complete: function(jqXHR, statusText) {
			  cscnt.onComplete(true);
			}
		});
	},
	onComplete:function(sign){
		if (sign) {
			$("div.errorModalWrapper").fadeIn(500);
		} else {
			$("div.errorModalWrapper").fadeOut(250);
		}
	}
};





