
$(function() {

    const FREEPLAN_TAB_FLAG = 'senmon2017_freeplan_tab_flag';
    const HACCHAKU_TAB_FLAG = 'senmon2017_hacchaku_tab_flag';

    // iOS向け ブラウザバック時にリロードする
    window.onpageshow = function(evt) {
        if (evt.persisted) {
            location.reload();
        }
    };

    /*---------------------------------
     ページの方面、国、都市タイプ
    ---------------------------------*/
    const  CATEGORY_TYPE_DEST = 1;
    const  CATEGORY_TYPE_COUNTRY = 2;
    const  CATEGORY_TYPE_CITY = 3;

    var category_type = $("#category_type").val();


    // scroll top
    var topBtn = $('#page-top');
    topBtn.hide();
    $(window).scroll(function() {
        if ($(this).scrollTop() > 100) {
            topBtn.fadeIn();
        } else {
            topBtn.fadeOut();
        }
    });

    topBtn.click(function() {
        $('body,html').animate({
            scrollTop: 0
        }, 500);
        return false;
    });

    if ($('#wr-banner3').length > 0) {
        var mnu1 = $('#wr-banner3').offset().top - 52;
    }
    if ($('#bltai2').length > 0) {
        var mnu2 = $('#bltai2').offset().top - 100;
    }
    if ($('#bltai3').length > 0) {
        var mnu3 = $('#bltai3').offset().top - 100;
    }
    if ($('.bltai5').length > 0) {
        var mnu4 = $('.bltai5').offset().top - 100;
    }
    $('body').on('click', '.menu-link', function() {
            var vt = 0;
            if ($(this).attr('href') == '#banner') {
                vt = mnu1;
            } else if ($(this).attr('href') == '#bltai2') {
                vt = mnu2;
            } else if ($(this).attr('href') == '#bltai3') {
                vt = mnu3;
            } else if ($(this).attr('href') == '#bltai5') {
                vt = mnu4;
            }

            $('body,html').animate({
                scrollTop: vt
            }, 500);
            return false;
        })
        // menu
    $('body').on('click', '#bltai4', function(event) {
        $('.submenu').toggle();
    });
    $('body').on('click', '.submenu-close', function(event) {
        $('.submenu').hide();
    });
    $('body').on('click', '.submenu ul li a', function(event) {
        var str = $(this).text();
        $('#p_hatsu').parent().html('<strong id="p_hatsu">' + str + '</strong>');
        $('#p_hatsu1').parent().html('<strong id="p_hatsu1">' + str + '</strong>');
        $('#loStart').text(str);
        $('#Js_setMykencode').text(str);
        $('.submenu').hide();
    });
    // $('#Js_setMykencode').tetx

    // tab
    /*
    $('.slider-sly').each(function(index, el) {
        sly(this);
    });
    $('.slider-sly-small').each(function(index, el) {
        sly(this);
        $(this).parent().find('.next').trigger('click').trigger('click');
    });
    $('.slider-sly-normal').each(function(index, el) {
        sly(this);
        $(this).parent().find('.next').trigger('click').trigger('click');
    });
    */
    $('.swiper-container').each(function(index, el) {
        // 近隣の出発地から探すなら
        if(0 < $(this).find("#other_facet").length) return true;
        swiper(this);
    });

    $('.tab-content').each(function(index, el) {
        if(isBot()) return;
        $(this).find('.tab-ct').hide();
        // タブが存在しているなら
        if($('.tab-menu').length){
            $(this).find('.tab-ct').eq($('.tab-menu li.active').index()).show();
        }else{
            // ツアータブを表示
            $(this).find('.tab-ct').eq(0).show();
        }
        if ($('.tab_genchihacchaku').length == 0) {
            if ($('#tab_ct_genchihacchaku').length > 0) {
                $('#tab_ct_genchihacchaku').show();
            }
        }
    });


    $('.tab-menu>li').click(function(event) {
        var mnu = $(this).attr("data-link");
        var navH =$('#js_freeNav').outerHeight();
        var vt = 0;
        if(!$(this).parent().hasClass('disable')){
            $('.tab-menu>li').removeClass('active');
            $(this).addClass('active');
            // $('.tab-ct').removeClass('active');
            // $('.tab-ct').eq($(this).index()).addClass('active');
            $('.tab-content').find('.tab-ct').hide();
            $('.tab-content').find('.tab-ct').eq($(this).index()).show();


            var tabClass = $('.tab-ct').eq($(this).index()).attr('class');
            var tabClassArray = tabClass.split(" ");
            FormID = '.'+tabClassArray[1]+' ';

            // 件数取得
            getHitNum();

            // フリープランの日付の初期化がまだなら
            if($(FormID+"#freeplan_date_init_flag").val() == 'false')
            {
                // 時間をおく
                setTimeout(function() {
                    initDateFreeplan();
                }, 1000);
            }

            if(isBot() == false){
                if($(this).index() == 0){
                    set_senmon2017_cookie(FREEPLAN_TAB_FLAG,false);
                    set_senmon2017_cookie(HACCHAKU_TAB_FLAG,false);
                }else if ($(this).index() == 1) {
                    // フリープランタブのフラグを設定
                    set_senmon2017_cookie(FREEPLAN_TAB_FLAG,true);
                    set_senmon2017_cookie(HACCHAKU_TAB_FLAG,false);
                }else if ($(this).index() == 2) {
                    set_senmon2017_cookie(FREEPLAN_TAB_FLAG,false);
                    set_senmon2017_cookie(HACCHAKU_TAB_FLAG,true);
                }
            }

        } else {
            vt = $(mnu).offset().top - navH;
            $('body,html').animate({
                scrollTop: vt
            }, 500);
            return false;
            }
        setGlobalNaviAction();
    });
    setTimeout(function() {
        var href = window.location.href ;

        var tab_g_flag = false;
        // URLに?tab=gがあるかフラグ
        if(href.match(/tab=g&/) != null || href.match(/tab=g$/) != null){
            tab_g_flag = true;
        }

        // URLに?tab=gがあるなら
        if ( ((href.match(/tab=g&/) != null || href.match(/tab=g$/) != null) && 0 < $('.tab-menu').length) || (isBot() == false && 0 < $('.tab-menu').length && get_senmon2017_cookie(HACCHAKU_TAB_FLAG) == 'true' && 0 < $('.tab_genchihacchaku').length && $(".tab_genchihacchaku").css('display') !== 'none')) {
            set_senmon2017_cookie(HACCHAKU_TAB_FLAG,true);
            // 発着タブが開いた状態にする
            if ($('#tab_ct_genchihacchaku').length > 0) {
                $('.tab-menu>li:nth-child(3)').click();
            }
            setGlobalNaviAction();
        }

        if(!tab_g_flag){
            // URLに?tab=fがあるなら
            if ( ((href.match(/tab=f&/) != null || href.match(/tab=f$/) != null) && 0 < $('.tab-menu').length) || (isBot() == false && 0 < $('.tab-menu').length && get_senmon2017_cookie(FREEPLAN_TAB_FLAG) == 'true')) {
                // フリープランタブのフラグを設定
                set_senmon2017_cookie(FREEPLAN_TAB_FLAG,true);
                // フリープランタブが開いた状態にする
                if ($('.tab-ct.free_plan').length > 0) {
                    $('.tab-menu>li:nth-child(2)').click();
                }
                setGlobalNaviAction();
            }
        }

    }, 1000);


    // Map country_miyazaki
    if ($('.wr-banner2 .miyazaki').length > 0) {
        $('.wr-banner2 .miyazaki').hover(function() {
            $(this).parents('.right').addClass('hover');
        }, function() {
            $(this).parents('.right').removeClass('hover');
        });
    }

    // Map
    sNowName = "00";
    jQuery("div#guide_index_sightseeing_map_highlights a").hover(function() {
        // alert($(this).attr("rel"));
        sNewName = jQuery(this).attr("rel");
        //初期化
        jQuery("div#guide_index_sightseeing_map_highlights").removeClass("guide_index_sightseeing_map_highlights" + sNowName);
        if (sNowName != "00") {
            jQuery("a#guide_index_sightseeing_map_btn" + sNowName).removeClass("guide_index_sightseeing_map_highlights" + sNowName);
        }
        //ハイライト変更
        jQuery("div#guide_index_sightseeing_map_highlights").addClass("guide_index_sightseeing_map_highlights" + sNewName);
        jQuery(this).addClass("guide_index_sightseeing_map_highlights" + sNewName);
        sNowName = sNewName;
    });

    // Menu fixed
    /*var vtmenu = $('.bn-menu').offset().top;
    $(window).scroll(function(event) {
        if ($(this).scrollTop() > vtmenu) {
            if ($('.bn-menu-wr').length <= 0) {
                $('.bn-menu').wrap("<div class='bn-menu-wr'></div>");
            }
        } else {
            $(".bn-menu-wr").replaceWith($(".bn-menu-wr").html());
        }
    });*/
    var start = "touchstart";
    var end   = "touchend";
    $(".map-list a").bind(start,function(){
        $(this).addClass("touchstart");
    });
    $(".map-list a").bind(end,function(){
        $(this).removeClass("touchstart");
    });
});

function sly(a) {
    var $frame = $(a);
    var $slidee = $frame.children('ul').eq(0);
    var $wrap = $frame.parent();
    $frame.sly({
        horizontal: 1,
        itemNav: 'basic',
        smart: 1,
        activateOn: 'click',
        mouseDragging: 1,
        touchDragging: 1,
        releaseSwing: 1,
        startAt: 0,
        scrollBar: $wrap.find('.scrollbarsly'),
        pagesBar: $wrap.find('.pages'),
        activatePageOn: 'click',
        speed: 300,
        elasticBounds: 1,
        easing: 'easeOutExpo',
        dragHandle: 1,
        dynamicHandle: 1,
        clickBar: 1,

        // Buttons
        prev: $wrap.find('.prev'),
        next: $wrap.find('.next'),
        prevPage: $wrap.find('.prevpage'),
        nextPage: $wrap.find('.nextpage')
    });
}
function swiper(a) {
    //setTimeout(function(){
        var $frame = $(a);
        var frame_id = $frame.attr('id');

        if ($frame.hasClass('set_swiper')) {
            return;
        }
        $frame.addClass('set_swiper');

        if (frame_id == 'recommend_tour_list') {
            var swiper = new Swiper(a , {
                paginationClickable: true,
                paginationType: 'progress',
                spaceBetween: 6,
                slidesPerView: 'auto',
                scrollbar: '#' + $frame.attr('id') + ' .swiper-scrollbar',
                scrollbarHide: true,
            });
        } else {
            var swiper = new Swiper(a , {
                //slidesPerView: 'auto',
                paginationClickable: true,
                paginationType: 'progress',
                spaceBetween: 6,
                slidesPerView: 1.46,
                //freeMode: true,
                //freeModeMomentum: false,
                //centeredSlides: true,
                scrollbar: '#' + $frame.attr('id') + ' .swiper-scrollbar',
                scrollbarHide: true,
                //loop: true
            });
        }
    //},1000);

}



$(function() {
    $(".OsusumeTour dl:gt(4)").css("display", "none");
    $(".BusTour dl:gt(4)").css("display", "none");
    $(".BtnMore").click(function() {
        if ($(this).parents("div.contentTourDiv").find(".TourBox:hidden").length < 1) {
            var tgNum = parseInt($(this).parents("div.contentTourDiv").find(".TourBox:visible").length) - 5;
            $(this).parents("div.contentTourDiv").find(".TourBox:gt(" + tgNum + ")").hide('fast');
        } else {
            var tgNum = parseInt($(this).parents("div.contentTourDiv").find(".TourBox:visible").length) - 1 + 5;
            $(this).parents("div.contentTourDiv").find(".TourBox:lt(" + tgNum + ")").show('fast');
        }

        setTimeout(function() {

            if ($(".OsusumeTour .TourBox:hidden").length < 1) {
                $(".OsusumeTour .BtnMore p span").text("現地集合解散ツアー閉じる");
            } else {
                $(".OsusumeTour .BtnMore p span").text("現地集合解散ツアーもっと見る");
            }

            if ($(".BusTour .TourBox:hidden").length < 1) {
                $(".BusTour .BtnMore p span").text("北海道発着のバスツアー閉じる");
            } else {
                $(".BusTour .BtnMore p span").text("北海道発着のバスツアーをもっと見る");
            }
        }, 1000);


    });
});

$(function () {

    // クリスタル
    $('.crystal-grey .brand_btn>a').click(function(event) {
        $(".crystal-grey .group-crystal-list").toggle();
    });
    // フレンドツアー
    $('.crystal-blue .brand_btn>a').click(function(event) {
        $(".crystal-blue .group-crystal-list").toggle();
    });

});
$(function () {
    //全角英数字だったら半角に
    $('.ttlCursSrchTxt').change(function () {
        var txt = $(this).val();
        var han = txt.replace(/[Ａ-Ｚａ-ｚ０-９]/g, function (s) {
            return String.fromCharCode(s.charCodeAt(0) - 0xFEE0)
        });
        $(this).val(han);
    });
    a();
    //フォーカス時にplaceholderを消す
    $(".ttlCursSrchTxt").focus(function () {
        $(this).attr('placeholder', '');
    });
    //フォーカスが外れた時、空ならplaceholderを表示
    $(".ttlCursSrchTxt").blur(function () {
        a();

    });
});
function a() {
    $(".ttlCursSrchTxt").attr('placeholder', '例）12192');
}
function c() {
    ID = $(".ttlCursSrchTxt").val();
    if (ID.charAt(0) == '#' || ID.charAt(0) == '＃') {
        alert('コース番号の一文字目は英数字で入力ください');
        return false;
    }
    IdLen = ID.length;
    var array = ["-", "−", "の", "ー", "―", "–", "‐", "－"];
    var index = -1;
    for (var i = 0; i < array.length; i++) {
        if (ID.indexOf(array[i]) != -1) {
            index = ID.indexOf(array[i]);
            break;
        }
    }
    var indexPosition;
    if (index != -1) {
        indexPosition = IdLen - index;

        if (indexPosition) {
            ID = ID.slice(0, -indexPosition);
        }
        IdLen = ID.length;
        if (IdLen < 2 || IdLen >= 8) {
            alert('ハイフン（-）またはひらがなの『の』より前のコース番号は\n2桁以上、7桁以下で入力してください');
            return false;
        }
    } else {
        if (IdLen < 2 || IdLen >= 8) {
            alert('コース番号を2桁以上、7桁以下で入力してください');
            return false;
        }
    }
}
// BOT判定
function isBot()
{
	// BOTのとき
	if ($('#js_freeNav').length <= 0) {

		return true;
	}

	return false;
}

$(function () {

    if(isBot()){

        // イチオシ枠がない時は、ツアー検索枠（+コース番号検索）ごと非表示
        if($(".tab-ct.tour #osusume_tour_list li").length <= 0){
            $(".tab-ct.tour").find('div').eq(0).hide();
            $(".tab-ct.tour #search_tour_contents").hide();
            $(".tab-ct.tour").find('section').eq(0).hide();
        }
    }

});

$(function () {
    addOnload(function() {

        // 枠の中身がないsectionタグを非表示にする
        $('.tab-content section').each(function(index, el) {
            var section_text = $(this).html();
            // 空白を削除
            section_text = section_text.replace(/\s+/g, "");
            if(section_text == ""){
                $(this).hide();
            }
        });
    });

});

// onloadイベントを追加する。
function addOnload(func)
{
    try {
        window.addEventListener("load", func, false);
    } catch (e) {
        // IE用
        window.attachEvent("onload", func);
    }
}

// cookieを取得
function get_senmon2017_cookie(key){
    return $.cookie(key);
}
// cookieを設定
function set_senmon2017_cookie(key,value){

    var expire_date = new Date();
    expire_date.setTime(expire_date.getTime() + 365*24*60*60*1000); // 1年を有効期限にする
    var expires = expire_date.toGMTString();

    var path = "/";
    var secure = "";
    var domein = '';

    switch (location.hostname) {
        case 'www-cms.hankyu-travel.com':
        case 'www.hankyu-travel.com':
            domain = 'hankyu-travel.com';
            break;
        default:
            domain = location.hostname;
            break;
    }

	document.cookie = key + "=" + escape (value) +
	((expires) ? "; expires=" + expires : "") +
	((path) ? "; path=" + path : "") +
	((domain) ? "; domain=" + domain : "") +
	((secure) ? "; secure" : "");

}

$(function() {

    // イチオシ枠で、商品がなく近隣の出発地も全て0件なら
    if(0 < $(".tab-ct.tour #kinrin_text").length){
        // 他の出発地の結果がない場合
        if($(".srchBox360b .otBtn").length < 1){
            $(".tab-ct.tour").hide();
        }
    }

    // ブランド枠の商品がない時は、そのブランド枠ごと非表示
    // クリスタル
    if($(".crystal-grey-list").find('li').length <= 0){
        $(".wrapper-crystal").hide();
    }

    if($('.js_moreFour_video').length){
        $('.js_moreFour_video').each(function(index, element) {
            var target = $(this);
            var n = $("ul li.video",this).length;
            $("ul li.video:gt(3)",this).hide();
            if(5 > n){
                $(".moreNewTourPls",target).hide();
            }

            $(".moreNewTourMns",this).hide();
            var Num = 4;

            $(".moreNewTourPls",this).bind('tap click',function(){

                Num +=4;

                $("ul li.video:lt("+Num+")",target).show();
                if(n <= Num){
                    $(".moreNewTourPls",target).hide();
                    $(".moreNewTourMns",target).show();
                }
            });

            $(".moreNewTourMns",this).bind('tap click',function(){
                $("ul li.video:gt(3)",target).slideUp(200);
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



});
