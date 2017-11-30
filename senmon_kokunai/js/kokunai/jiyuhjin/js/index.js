$(function() {
	//初期設定
	$("#tourTab li:first").addClass('select');
	$("div.contentTourDiv").not(':first').addClass('disnon');

	//タブ切り替え時のスクロール
	$("#tourTab").click(function(){
		var fstY = $('.MainCts').offset().top;
		$('html, body').animate({scrollTop:fstY}, 'slow');
	});

	//タブのcursor
	$("#tourTab > li").hover(function(){
			if(!$(this).hasClass("select")){
				$(this).css("cursor","pointer");
			}if($(this).hasClass("gray")){
				$(this).css("cursor","default");
			}
		},
	function(){
		if($(this).hasClass("select")){
			$(this).css("cursor","default");
		}
	});
	//タブのロールオーバ-
	$("#tourTab > li img").hover(function(){
			if(!$(this).parent("li").hasClass("select")){
				$(this).attr('src', $(this).attr('src').replace('_off', '_on'));
			}
			}, function(){
			if(!$(this).parent("li").hasClass("select")){
				$(this).attr('src', $(this).attr('src').replace('_on', '_off'));
			}
	});
	$("#tourTab > li").hover(
		function () {
			$(this).parent().parent().addClass("hovers");
		},
		function () {
			$(this).parent().parent().removeClass("hovers");
		}
	);

	// タブ切替処理
	// $("#tourTab li").click(function () {
	// 	changeTabContotol(this, true);
	// });
});




function getQueryVariable(variable) {
    var query = window.location.search.substring(1);
    var vars = query.split("&");
    for (var i=0;i<vars.length;i++) {
        var pair = vars[i].split("=");
        if (pair[0] == variable) {
            return pair[1];
        }
    }
}



$(function(){

	$(".TabBox li").click(function(){
		$(".TabBox li").css("cursor","");
		$(".TabBox li").removeClass("select");
		$(this).addClass("select");
		$(this).css("cursor","default");
		$(".contentTourDiv").addClass("disnon");
		$(".contentTourDiv").eq($(".TabBox li").index(this)).removeClass("disnon");
	});

	$(".BtnMore").click(function(){

		if($(this).parents("div.contentTourDiv").find(".TourBox:hidden").length < 1){
			var tgNum = parseInt($(this).parents("div.contentTourDiv").find(".TourBox:visible").length) - 5;
			$(this).parents("div.contentTourDiv").find(".TourBox:gt("+tgNum+")").hide('fast');
		}else{
			var tgNum = parseInt($(this).parents("div.contentTourDiv").find(".TourBox:visible").length) - 1 + 5;
			$(this).parents("div.contentTourDiv").find(".TourBox:lt("+tgNum+")").show('fast');
		}

		setTimeout(function(){

			if($(".OsusumeTour .TourBox:hidden").length < 1){
	      $(".OsusumeTour .BtnMore p").text("- 閉じる");
	    }else{
				$(".OsusumeTour .BtnMore p").text("＋ もっと見る");
			}

	    if($(".Cruising .TourBox:visible").length < 1){
				$(".Cruising .BtnMore p").text("- 閉じる");
			}else{
				$(".Cruising .BtnMore p").text("＋ もっと見る");
			}

	    if($(".Town .TourBox:visible").length < 1){
				$(".Town .BtnMore p").text("- 閉じる");
			}else{
				$(".Town .BtnMore p").text("＋ もっと見る");
			}

	    if($(".Observation .TourBox:visible").length < 1){
				$(".Observation .BtnMore p").text("- 閉じる");
			}else{
				$(".Observation .BtnMore p").text("＋ もっと見る");
			}

		},1000);


	});

	//$(".OsusumeTour dl:gt(14)").css("display","none");
	$('.OsusumeTour').each(function(index, el) {
        $(this).find("dl:gt(14)").css("display", "none");
    });
	$(".Cruising dl:gt(14)").css("display","none");
	$(".Town dl:gt(14)").css("display","none");
	$(".Observation dl:gt(14)").css("display","none");

	if($(".OsusumeTour .TourBox").length < 15){
    $(".OsusumeTour .BtnMore").css("display","none");
  }

  if($(".Cruising .TourBox").length < 15){
    $(".Cruising .BtnMore").css("display","none");
  }

  if($(".Town .TourBox").length < 15){
    $(".Town .BtnMore").css("display","none");
  }

  if($(".Observation .TourBox").length < 15){
    $(".Observation .BtnMore").css("display","none");
  }


	var category = getQueryVariable("category");
	if(category == 'OsusumeTour'){
		$(".TabBox li").eq(0).trigger("click");
	}else if(category == 'Cruising'){
		$(".TabBox li").eq(1).trigger("click");
	}else if(category == 'Town'){
		$(".TabBox li").eq(2).trigger("click");
	}else if(category == 'Observation'){
		$(".TabBox li").eq(3).trigger("click");
	}

});


//タブ切り替え
function changeTabContotol(myTag, clickFlg) {
	//タブが押せる場合のみ処理実行
	if ($(myTag).find("img").attr('src').indexOf("_g") === -1) {

		var num = $("#tourTab li").index(myTag);
		//親のID取得
		var navid = "#"+$(myTag).parent().attr("id");

		//ボタンの画像を初期状態にする
		$(navid+" > li img").each(function(index, element) {
			$(this).attr('src', $(this).attr('src').replace('_on', '_off'));
		});

		//クリックした画像を選択状態にする
		$(myTag).find("img").attr('src', $(myTag).find("img").attr('src').replace('_off', '_on'));

		//タブ切り替え
		$(navid+" > li").removeClass("select");
		$(myTag).addClass("select");
		$("div.contentTourDiv").addClass('disnon');
		$("div.contentTourDiv").eq(num).removeClass('disnon');
	}
}

//スライドショー
$(window).load(function() {
  $('.flexslider').flexslider({
    animation: "slide"
  });
});
