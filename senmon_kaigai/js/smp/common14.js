
if($('.js_Onemore').length){
	$('.js_Onemore').each(function(index, element) {

		var target = $(this);
		var n = $("ul li",this).length;
		$("ul li:gt(0)",this).hide();
		$(".moreNewTourMns",this).hide();
		$(".base-feature-moreNewTourMns",this).hide();
		if(2 > n){
			$(".moreNewTourPls",target).hide();
			$(".base-feature-moreNewTourPls",target).hide();
		}
		var Num = 1;

		$(".moreNewTourPls",this).bind('tap click',function(){

			Num +=1;

			$("ul li:lt("+Num+")",target).show();
			if(n <= Num){
				$(".moreNewTourPls",target).hide();
				$(".moreNewTourMns",target).show();
			}
		});
		$(".base-feature-moreNewTourPls",this).bind('tap click',function(){

			Num +=4;

			$("ul li:lt("+Num+")",target).show();
			if(n <= Num){
				$(".base-feature-moreNewTourPls",target).hide();
				$(".base-feature-moreNewTourMns",target).show();
			}
		});


		$(".moreNewTourMns",this).bind('tap click',function(){
			$("ul li:gt(0)",target).slideUp(200);
			$(".moreNewTourMns",target).hide();
			$(".moreNewTourPls",target).show();

			if (target) {
				var targetOffset = target.offset().top;
				if($('#js_freeNav').length){
					targetOffset=targetOffset-$('#js_freeNav').outerHeight();
				}
				$('html,body').animate({scrollTop: targetOffset},200,"");
				return false;
			}
		});
		$(".base-feature-moreNewTourMns",this).bind('tap click',function(){
			$("ul li:gt(0)",target).slideUp(200);
			$(".base-feature-moreNewTourMns",target).hide();
			$(".base-feature-moreNewTourPls",target).show();

			if (target) {
				var targetOffset = target.offset().top;
				if($('#js_freeNav').length){
					targetOffset=targetOffset-$('#js_freeNav').outerHeight();
				}
				$('html,body').animate({scrollTop: targetOffset},200,"");
				return false;
			}
		});
	});
}


	if($('.js-moreEight').length){
		$('.js-moreEight').each(function(index, element) {
           var target = $(this);
			var n = $("ul li",this).length;
			$("ul li:gt(7)",this).hide();
			if(9 > n){
				$(".moreNewTourPls",target).hide();
			}

			$(".moreNewTourMns",this).hide();
			var Num = 8;

			$(".moreNewTourPls",this).bind('tap click',function(){

				Num +=8;

				$("ul li:lt("+Num+")",target).show();
				if(n <= Num){
					$(".moreNewTourPls",target).hide();
					$(".moreNewTourMns",target).show();
				}
			});

			$(".moreNewTourMns",this).bind('tap click',function(){
				$("ul li:gt(7)",target).slideUp(200);
				$(".moreNewTourMns",target).hide();
				$(".moreNewTourPls",target).show();

				if (target) {
					var targetOffset = target.offset().top;
					if($('#js_freeNav').length){
						targetOffset=targetOffset-$('#js_freeNav').outerHeight();
					}

					$('html,body').animate({scrollTop: targetOffset},200,"");
					return false;
				}

			});
		});
	}
	if($('.js_TwomoreFour').length){
		$('.js_TwomoreFour').each(function(index, element) {

			var target = $(this);
			var n = $("ul li",this).length;
			$("ul li:gt(1)",this).hide();
			$(".moreNewTourMns",this).hide();
			$(".base-feature-moreNewTourMns",this).hide();
			if(3 > n){
				$(".moreNewTourPls",target).hide();
				$(".base-feature-moreNewTourPls",target).hide();
			}
			var Num = 2;

			$(".moreNewTourPls",this).bind('tap click',function(){

				Num +=4;

				$("ul li:lt("+Num+")",target).show();
				if(n <= Num){
					$(".moreNewTourPls",target).hide();
					$(".moreNewTourMns",target).show();
				}
			});
			$(".base-feature-moreNewTourPls",this).bind('tap click',function(){

				Num +=4;

				$("ul li:lt("+Num+")",target).show();
				if(n <= Num){
					$(".base-feature-moreNewTourPls",target).hide();
					$(".base-feature-moreNewTourMns",target).show();
				}
			});


			$(".moreNewTourMns",this).bind('tap click',function(){
				$("ul li:gt(1)",target).slideUp(200);
				$(".moreNewTourMns",target).hide();
				$(".moreNewTourPls",target).show();

				if (target) {
					var targetOffset = target.offset().top;
					if($('#js_freeNav').length){
						targetOffset=targetOffset-$('#js_freeNav').outerHeight();
					}
					$('html,body').animate({scrollTop: targetOffset},200,"");
					return false;
				}
			});
			$(".base-feature-moreNewTourMns",this).bind('tap click',function(){
				$("ul li:gt(1)",target).slideUp(200);
				$(".base-feature-moreNewTourMns",target).hide();
				$(".base-feature-moreNewTourPls",target).show();

				if (target) {
					var targetOffset = target.offset().top;
					if($('#js_freeNav').length){
						targetOffset=targetOffset-$('#js_freeNav').outerHeight();
					}
					$('html,body').animate({scrollTop: targetOffset},200,"");
					return false;
				}
			});
		});
	}

	$(function() {
    $(".Observation article:gt(4)").css("display", "none");
    $(".LocalTour article:gt(4)").css("display", "none");
    $(".more-tour").click(function(e) {
    	e.preventDefault();
        if ($(this).parents(".contentTourDiv").find(".local-tour:hidden").length < 1) {
            var tgNum = parseInt($(this).parents("div.contentTourDiv").find(".local-tour:visible").length) - 5;
            $(this).parents(".contentTourDiv").find(".local-tour:gt(" + tgNum + ")").hide('fast');
        } else {
            var tgNum = parseInt($(this).parents(".contentTourDiv").find(".local-tour:visible").length) - 1 + 5;
            $(this).parents(".contentTourDiv").find(".local-tour:lt(" + tgNum + ")").show('fast');
        }

        setTimeout(function() {

            if ($(".Observation .local-tour:hidden").length < 1) {
                $(".Observation .more-tour").text("現地集合解散ツアー閉じる");
            } else {
                $(".Observation .more-tour").text("現地集合解散ツアーもっと見る");
            }
            if ($(".LocalTour .local-tour:hidden").length < 1) {
                $(".LocalTour .more-tour").text("現地集合解散ツアー閉じる");
            } else {
                $(".LocalTour .more-tour").text("現地集合解散ツアーもっと見る");
            }


        }, 1000);


    });

    $(".map-list li").on("click", function() {
    	$(this).addClass("map-list-active").siblings().removeClass("map-list-active ");
    	return false;
    });
    $(".change-point-from, .change-start-point-text").click(function(){
		$("body").append('<div id="modal-overlay"></div>');
		$("#modal-overlay").fadeIn("slow");

		// -------------------------------------- 専門店用
		centeringModalSyncer(".js_HatsuMenuSenmon");
		$(".js_HatsuMenuSenmon").fadeIn("slow");
		$(".js_HatsuMenuSenmon").css('top', $(window).scrollTop()+100);
		$("#modal-overlay,.js_HatsuMenuClose a").unbind().click(function(){
			$(".js_HatsuMenuSenmon,#modal-overlay").fadeOut("slow",function(){
				$('#modal-overlay').remove();
			});
		});
	});

	$("#Js_otherFacetEtc").click(function(){
		$("body").append('<div id="modal-overlay"></div>');
		$("#modal-overlay").fadeIn("slow");

		// -------------------------------------- 専門店用
		centeringModalSyncer(".js_KinrinHatsuMenuSenmon");
		$(".js_KinrinHatsuMenuSenmon").fadeIn("slow");
		$(".js_KinrinHatsuMenuSenmon").css('top', $(window).scrollTop()+100);
		$("#modal-overlay,.js_HatsuMenuClose a").unbind().click(function(){
			$(".js_KinrinHatsuMenuSenmon,#modal-overlay").fadeOut("slow",function(){
				$('#modal-overlay').remove();
			});
		});
	});

	$(".free_plan #Js_otherFacetEtc").click(function(){
		$("body").append('<div id="modal-overlay"></div>');
		$("#modal-overlay").fadeIn("slow");

		// -------------------------------------- 専門店用
		centeringModalSyncer(".free_plan .js_KinrinHatsuMenuSenmon");
		$(".free_plan .js_KinrinHatsuMenuSenmon").fadeIn("slow");
		$(".free_plan .js_KinrinHatsuMenuSenmon").css('top', $(window).scrollTop()+100);
		$("#modal-overlay,.free_plan .js_HatsuMenuClose a").unbind().click(function(){
			$(".free_plan .js_KinrinHatsuMenuSenmon,#modal-overlay").fadeOut("slow",function(){
				$('#modal-overlay').remove();
			});
		});
	});



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

    var setTimeoutId = null ;	// タイマーの管理
    var scrolling = false ;		// スクロールのステータス (true=スクロール中、false=静止中)

	if ($('#js_freeNav').length > 0) {

		var nav = $('#js_freeNav');
		var offset = nav.offset();
		$(window).scroll(function () {

			if(/Android/i.test(navigator.userAgent) ) {
			 	console.log('keke');
			}
			if($(window).scrollTop() > offset.top + 100) {
				//nav.addClass('fixed');
				$('.change-start-point').addClass('fixed');

                // スクロール中と判断
                scrolling = true;

			}else {
				//nav.removeClass('fixed');
				$('.change-start-point').removeClass('fixed');

                scrolling = false;
			}

            if( setTimeoutId ) {
                clearTimeout( setTimeoutId ) ;
            }

            // 新しくsetTimeoutイベントを設定
            setTimeoutId = setTimeout( function() {
                // ステータスを静止中に変更
                scrolling = false ;

                // setTimeoutIdを空にする
                setTimeoutId = null ;
            }, 500 ) ;

		});

        setInterval( function() {
            // スクロール中
            if( scrolling ) {

                // 黒帯グローバルメニュー非表示
                $('.change-start-point').fadeOut("first");
            // 静止中
            } else {
               // 黒帯グローバルメニュー表示
               $('.change-start-point').fadeIn("first");
            }

        }, 100 ) ;
	}
});


var dispTabType = 1;// 0:タブなし,1:ツアータブ,2:フリープランタブ,3:現地発着タブ
function setGlobalNaviAction() {
    if ($('.tab-menu').length <= 0) {
        dispTabType = 0;
    } else {
        var tabActiveIndex = $('.tab-menu li.active').index();
        if (tabActiveIndex == 0) {
            dispTabType = 1;
        } else if (tabActiveIndex == 1) {
            dispTabType = 2;
        } else if (tabActiveIndex == 2) {
            dispTabType = 3;
        }
    }

    if (dispTabType == 0 || dispTabType == 1) {
        // タブなし,ツアータブの場合
        if ($('.map_title').length <= 0 ) {
            $('.menu-link-map').addClass('disabled');
        } else {
            $('.menu-link-map').removeClass('disabled');
        }

        if ($('.tab-ct.tour .search-box').length <= 0 ) {
            $('.menu-link-search').addClass('disabled');
        } else {
            $('.menu-link-search').removeClass('disabled');
        }

        if ($('.tab-ct.tour #osusume_tour_list').length <= 0 ) {
            $('.menu-link-ichioshi').addClass('disabled');
        } else {
            $('.menu-link-ichioshi').removeClass('disabled');
        }

        if ($('.bltai5').length <= 0 ) {
            $('.menu-link-travel-info').addClass('disabled');
        } else {
            $('.menu-link-travel-info').removeClass('disabled');
        }
    } else if (dispTabType == 2) {
        // フリープランタブの場合
        if ($('.map_title').length <= 0 ) {
            $('.menu-link-map').addClass('disabled');
        } else {
            $('.menu-link-map').removeClass('disabled');
        }

        if ($('.tab-ct.free_plan .search-box').length <= 0 ) {
            $('.menu-link-search').addClass('disabled');
        } else {
            $('.menu-link-search').removeClass('disabled');
        }

        if ($('.tab-ct.free_plan #osusume_freeplan_list').length <= 0 ) {
            $('.menu-link-ichioshi').addClass('disabled');
        } else {
            $('.menu-link-ichioshi').removeClass('disabled');
        }

        if ($('.bltai5').length <= 0 ) {
            $('.menu-link-travel-info').addClass('disabled');
        } else {
            $('.menu-link-travel-info').removeClass('disabled');
        }
    }
}
setGlobalNaviAction();
function scrollMenuBtn(no) {
    var vt = 0;
    var targetSelecter = '';
    if (dispTabType == 0 || dispTabType == 1) {
        if (no == 1) {
            targetSelecter = '.map_title';
        } else if (no == 2) {
            targetSelecter = '.tab-ct.tour .search-box';
        } else if (no == 3) {
            targetSelecter = '.tab-ct.tour #osusume_tour_list';
        } else if (no == 4) {
            targetSelecter = '.bltai5';
        }
    } else if (dispTabType == 2) {
        if (no == 1) {
            targetSelecter = '.map_title';
        } else if (no == 2) {
            targetSelecter = '.tab-ct.free_plan .search-box';
        } else if (no == 3) {
            targetSelecter = '.tab-ct.free_plan #osusume_freeplan_list';
        } else if (no == 4) {
            targetSelecter = '.bltai5';
        }
    }
    
    if ($(targetSelecter).length > 0) {
        vt = $(targetSelecter).offset().top;
        vt -= ($('.change-start-point').height() + 8) * 2;

        $('body,html').animate({
            scrollTop: vt
        }, 500);
    }
    return false;
}
