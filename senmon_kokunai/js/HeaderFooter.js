/*
=========================================================
2016　ヘッダー・フッター用

=========================================================
*/
var Js_depSelectBtn ='#Js_depSelectBtn';//出発する地域を選択吹き出し表示場所
var Js_depSelectBtnA ='#Js_depSelectBtnA';//出発する地域を選択吹き出しボタン
var Js_adHatsuBtn ='#Js_adHatsuBtn';//吹き出しのボタン
var Js_setMykencode = '#Js_setMykencode';//選択された拠点名
var ajaxFile ='/sharing/common16/phpsc/ajax_setKyotenName.php';//拠点名取得用
var kyotenHatsuName;//発拠点名

/*#########################
	Action
###########################*/
$(function(){
	//発地選択の吹き出し　県コード・発地吹き出しnoのcookieがあったら非表示
	// if($.cookie('HK_CBKyoten') || $.cookie('HK_MyState')  ||$.cookie('HK_AutoStateNone')){
	// 	$(Js_depSelectBtn).css('display','none');
	// }

	// //クッキーがあったら発地名を表示
	// var cookieName;
	// var cookieValue;
	// if($.cookie('HK_CBKyoten')){
	// 	cookieName='HK_CBKyoten';
	// 	cookieValue =$.cookie('HK_CBKyoten');
	// }
	// else if($.cookie('HK_MyState')){
	// 	cookieName='HK_MyState';
	// 	cookieValue =$.cookie('HK_MyState');
	// }
	// else if($.cookie('HK_AutoState')){
	// 	cookieName='HK_AutoState';
	// 	cookieValue =$.cookie('HK_AutoState');
	// }
	// else{
	// 	kyotenHatsuName ='未選択';
	// }

	// if(kyotenHatsuName == null){
	// 	$.ajax({
	// 		type: 'post'
	// 		,url: ajaxFile
	// 		,dataType: 'html'
	// 		,data: {'cookieName':cookieName,'cookieValue':cookieValue}
	// 		,success: function(html){
	// 			$(Js_setMykencode).text(html);
	// 		}
	// 	});
	// }
	// else{
	// 	$(Js_setMykencode).text(kyotenHatsuName);
	// }


	// //吹き出しのボタンを押した時
	// $(Js_depSelectBtnA).click(function(){
	// 	var expDate = makeCookieSet.set_expires(365);
	// 	makeCookieSet.set_Cookie("HK_AutoStateNone", '1', expDate, "/", "hankyu-travel.com", "");
	// 	$(Js_depSelectBtn).css('display','none');
	// 	return false;
	// });

	// //出発する地域を選択するをクリックした時
	// $("body").click(function(ev) {
	// 	if (!$(ev.target).is(".Js_HatsuSelectPanel:visible, .Js_HatsuSelectPanel:visible *")) {
	// 		$('.Js_HatsuSelectPanel').slideUp(0);
	// 	}
	// });
	$('.Js_HatsuSelectpanelBtn').click(function(ev) {
	 	var clickPanel = $("+.Js_HatsuSelectPanel",this);
		clickPanel.toggle();
	 	$(".Js_HatsuSelectPanel").not(clickPanel).slideUp(0);
		return false;
	});
	$('#Js_HatsuSelectPanelClose').click(function(){
	 	$(".Js_HatsuSelectPanel").slideUp(0);
	});

	// // 元からあるイベント削除
	// // 後はフォーカス関係のイベントを新しく設定
	// /*$(".Gsh_box").removeAttr("onblur");
	// $(".Gsh_box").removeAttr("onfocus");
	// $(".Gsh_box").focus(function(){
	// 	$(this).val("");
	// })
	// .blur(function(){
	// 	var GsfboxDefaultValue = this.defaultValue;
	// 	$("body").click(function(ev){
	// 		if (!$(ev.target).is(".SrchBtn.headerSrchBtn, .Gsh_box")) {
	// 			$(".Gsh_box").val(GsfboxDefaultValue);
	// 		}
	// 	});
	// });*/
	// //キーワード検索のデフォルトvalueを消す
	// $("#cse-search-box input[type='submit']").click(function() {
	// 	if($("#cse-search-box #sample1").val() =='フリーワードで探す'){
	// 		$("#cse-search-box #sample1").val("");
	// 	}
	// });
	// //キーワード検索のデフォルトvalueを消す
	// $("#cse-search-box #sample1").keypress(function (){
	// 	if($("#cse-search-box #sample1").val() =='フリーワードで探す'){
	// 		$("#cse-search-box #sample1").val("");
	// 	}
	// });
	
	// グローバルナビのマウスオーバーで黒い枠を出す
	var hoverX = 250;
    var hoverY = 10;
    $('.gNaviInBlk ul > li').hover(function (event) {
        var $target   = $(event.currentTarget);
        var $hoverBox = $target.find('.js-hover-box');

        if ($hoverBox.size() === 0) {
            return;
        }

        $target.addClass('js-hovered');

        var offset = $target.offset();
        var x      = event.pageX - offset.left
        var y      = event.pageY - offset.top
        x      = hoverX;
        y      = hoverY;

        $hoverBox.css({
            top  : y + "px",
            left : x + "px"
        });

    }, function (event) {
        $(event.currentTarget).removeClass('js-hovered');
    }).mousemove(function (event) {
        var $target   = $(event.currentTarget);
        var $hoverBox = $target.find('.js-hover-box');

        var offset = $target.offset();
        var x      = event.pageX - offset.left
        var y      = event.pageY - offset.top

        x      = hoverX;
        y      = hoverY;
        $hoverBox.css({
            top  : y + "px",
            left : x + "px"
        });
    });


});


/*============================================
県を選択してクリックした時クッキーをセット
============================================*/

function SelectKenLink(kencode,name){

	// if (typeof sessionStorage !== 'undefined') {
	// 	sessionStorage.clear();
	// }
	// var expDate = makeCookieSet.set_expires(180);
	// makeCookieSet.set_Cookie("HK_MyState", kencode, expDate, "/", "hankyu-travel.com", "");
	// if($.cookie('HK_CBKyoten')){
	// 	$.cookie('HK_CBKyoten', "",{expires:-1, path: '/', domain: 'hankyu-travel.com'});
	// }


	// $.ajax({
	// 	type: 'post'
	// 	,url: ajaxFile
	// 	,dataType: 'html'
	// 	,data: {'cookieName':'HK_MyState','cookieValue':kencode}
	// 	,success: function(html){
	// 		$(Js_setMykencode).text(html);
	// 	}
	// });

	// $(".Js_HatsuSelectPanel").slideUp(0);
	// $(".Js_AreaSelectPanel").slideUp(0);

	// if(history.replaceState) {
	// 	history.replaceState(null,null,location.href);
	// 	var reloadLink = location.href.split("#");
	// 	window.location.replace(reloadLink[0]);
	// }else{
	// 	var reloadLink = location.href.split("#");
	// 	window.location.replace(reloadLink[0]);
	// }

	// return false;
}

/*============================================
クッキー作成
============================================*/
// var makeCookieSet = {
// 	//クッキーセット
// 	set_Cookie:function(name, value, expires, path, domain, secure){
// 		document.cookie = name + "=" + escape (value) +
// 		((expires) ? "; expires=" + expires : "") +
// 		((path) ? "; path=" + path : "") +
// 		((domain) ? "; domain=" + domain : "") +
// 		((secure) ? "; secure" : "");
// 	},
// 	//有効期限の作成
// 	set_expires:function(day){
// 		var expire_date = new Date();
// 		expire_date.setTime(expire_date.getTime() + day*24*60*60*1000);
// 		var expires = expire_date.toGMTString();
// 		return expires;
// 	}

// }

