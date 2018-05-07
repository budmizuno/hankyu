var bxslider;
var map = new Object();
var sl_title = [];
var sl_content = new Object();


// 読込後に実行
window.onload = function() {

    var href = window.location.href ;
    // URLに?tab=gがあるなら
    if ( (href.indexOf('tab=g') != -1 && 0 < $('.tab-menu').length) || (isBot() == false && 0 < $('.tab-menu').length && get_senmon2017_cookie(HACCHAKU_TAB_FLAG) == 'true' && 0 < $('.tab_genchihacchaku').length && $(".tab_genchihacchaku").css('display') !== 'none')) {
        set_senmon2017_cookie(HACCHAKU_TAB_FLAG,true);
        // 発着タブが開いた状態にする
        $("#bltai3 li:eq(2)").parent().children().removeClass('active');
        $("#bltai3 li:eq(2)").addClass('active');
        $("#bltai3 li:eq(2)").parents('.wr-tab').find('.tab-ct').hide();
        $("#bltai3 li:eq(2)").parents('.wr-tab').find('.tab-ct').eq(2).show();
    }


    // URLに?tab=fがあるなら
    if ( (href.indexOf('tab=f') != -1 && 0 < $('.tab-menu').length) || (isBot() == false && 0 < $('.tab-menu').length && get_senmon2017_cookie(FREEPLAN_TAB_FLAG) == 'true')) {
        set_senmon2017_cookie(FREEPLAN_TAB_FLAG,true);
        // フリープランタブが開いた状態にする
        $("#bltai3 li:eq(1)").parent().children().removeClass('active');
        $("#bltai3 li:eq(1)").addClass('active');
        $("#bltai3 li:eq(1)").parents('.wr-tab').find('.tab-ct').hide();
        $("#bltai3 li:eq(1)").parents('.wr-tab').find('.tab-ct').eq(1).show();
    }
    setGlobalNaviAction();
}

$(function() {

    const FREEPLAN_TAB_FLAG = 'senmon2017_freeplan_tab_flag';
    const HACCHAKU_TAB_FLAG = 'senmon2017_hacchaku_tab_flag';

    /*---------------------------------
     ページの方面、国、都市タイプ
    ---------------------------------*/
    const  CATEGORY_TYPE_DEST = 1;
    const  CATEGORY_TYPE_COUNTRY = 2;
    const  CATEGORY_TYPE_CITY = 3;

    var category_type = $("#category_type").val();

    var ua = navigator.userAgent; // ユーザーエージェントを代入

    // 方面ページなら
    if(category_type == CATEGORY_TYPE_DEST || $("#contetns_header_bool").val() == 1)
    {
        var default_lo = $("#map_default_display").val();
        var page_caption = $("#page_caption").val();


        var postArray = {};
        postArray['page_type'] = $("#page_type").val();
        postArray['global_master'] = $("#global_master").val();

        for (var value in mapDataJson['photoArray']) {
            sl_title.push(mapDataJson['photoArray'][value]);
        }

        // タイトル（国など）の配列
        for (var value in mapDataJson['photoArray'])
        {
            var num = 0 ;
            map['map'+value] = {};
            map['map'+value]['img_path'] = new Array();
            map['map'+value]['size'] = new Array();
            sl_content['map'+value] = new Array();
            // CSVから取得した「csv_europe_ttl2017.csv」など
            for(var i=0;i<mapDataJson['photoDataCSV'].length;i++)
            {
                if(mapDataJson['photoArray'][value] == mapDataJson['photoDataCSV'][i]['q_category'])
                {
                    map['map'+value]['img_path'][num] = mapDataJson['photoDataCSV'][i]['p_img1_filepath'];
                    if ('q_flag_ttl' in mapDataJson['photoDataCSV'][i]) {
                        map['map'+value]['size'][num] = mapDataJson['photoDataCSV'][i]['q_flag_ttl'];
                    } else {
                        map['map'+value]['size'][num] = 0;
                    }

                    num++;
                }
            }
            // ページキャプションを表示
            sl_content['map'+value] = mapDataJson['pageCaptionArray'][value];
        }

        for (var i = 0; i < sl_title.length; i++) {
            if (sl_title[i] == default_lo) {
                for (var j in map) {
                    if ('map' + (i + 1) == j) {

                        // 写真がなかったら
                        if(map[j]['img_path'].length < 1)
                        {
                            // noimageを入れる
                            $('#banner').append('<li><img src="/sharing/common14/images/noimage390.png" alt=""></li>');

                            if($('#banner-content').text() == '')
                            {
                                var char = sl_content[j];
                                $('#banner-content').text(char);
                            }
                        }
                        else
                        {
                            for (var k = 0; k < map[j]['img_path'].length; k++) {
                                var size_class = map[j]['size'][k] == 1 ? 'size_8_3' : '';
                                $('#banner').append('<li><img class="'+ size_class + '" src="' + map[j]['img_path'][k] + '" alt=""></li>');
                            }
                            if($('#banner-content').text() == '')
                            {
                                var char = sl_content[j];
                                $('#banner-content').text(char);
                            }
                            break;
                        }
                    }
                }
                $('#banner-title').text(sl_title[i]);

                $('p[data=map' + (i + 1) + ']').addClass('active');
                break;
            }
        }

        // bxsliderを設定
        bxslider = $('#banner').bxSlider({
            controls: false,
            auto: true,
            mode: 'fade',//fade,horizontal
            speed: 800,

            onSlideBefore: function($slideElement, oldIndex, newIndex) {
                $('#banner li').eq(oldIndex).css({marginLeft:"0px"}).animate({marginLeft:"-40px"},800,function(){
                    $("#banner li").eq(oldIndex).css({marginLeft:"0px"});
                });
            },

            // スライド遷移後に呼ばれる
            onSlideAfter:function($slideElement, oldIndex, newIndex){
                $('.bx-pager a').eq(oldIndex).removeClass('active');
                $('.bx-pager a').eq(newIndex).addClass('active');

                // 自動ローテション
                bxslider.startAuto();
            },
        });

        if($("#banner").find('li').length < 2){
            // 丸ページャー非表示
            $(".bx-controls").hide();
        }


        // banner
    //    var bxslider = $('#banner').bxSlider({ controls: false, auto: true });
        $('.wr-child-banner-info li.mainBgClr').hover(function() {
            $('.wr-child-banner-info li.mainBgClr').removeClass('active');
            $(this).addClass('active');
            if(map == undefined) return;

            bxslider.destroySlider();

            var tmpUl = $('ul#banner');                     // ←ulタグをコピー
            $(".wr-image-banner.bdColor").html('');         // ←写真の枠の中身を空にする
            $(".wr-image-banner.bdColor").append(tmpUl);    // ←写真の枠にコピーしたtmpUlのHTMLを追加する。

            var map_data = map[$(this).attr('data')];

            $('#banner').empty();
            $('#banner-title').empty();
            $('#banner-content').empty();

            // 写真がなかったら
            if(map_data['img_path'].length < 1)
            {
                // noimageを入れる
                $('#banner').append('<li><img src="/sharing/common14/images/noimage390.png" alt=""></li>');
            }
            else
            {
                for (var i = 0; i < map_data['img_path'].length; i++) {
                    var size_class = map_data['size'][i] == 1 ? 'size_8_3' : '';
                    $('#banner').append('<li><img class="'+ size_class + '" src="'+map_data['img_path'][i]+'" alt=""></li>');
                }
            }
            var vt = $(this).attr('data').replace('map','');

            $('#banner-title').text(sl_title[(vt-1)]);
            $('#banner-content').text(sl_content["map"+vt]);

            // bxsliderを設定
            bxslider = $('#banner').bxSlider({
                controls: false,
                auto: true,
                mode: 'fade',//fade,horizontal
                speed: 800,

                onSlideBefore: function($slideElement, oldIndex, newIndex) {
                    $('#banner li').eq(oldIndex).css({marginLeft:"0px"}).animate({marginLeft:"-40px"},800,function(){
                        $("#banner li").eq(oldIndex).css({marginLeft:"0px"});
                    });
                },

                // スライド遷移後に呼ばれる
                onSlideAfter:function($slideElement, oldIndex, newIndex){
                    $('.bx-pager a').eq(oldIndex).removeClass('active');
                    $('.bx-pager a').eq(newIndex).addClass('active');

                    // 自動ローテション
                    bxslider.startAuto();
                },
            });

            // IEの処理
            if (ua.match("MSIE") || ua.match("Trident")) { // åMSIEまたはTridentが入っていたら
                // 写真スライダーのページャー
                $('.bx-wrapper .bx-pager').css('z-index','100');
            }

            if($("#banner").find('li').length < 2){
                // 丸ページャー非表示
                $(".bx-controls").hide();
            }

        });
    }
    // 国ページなら
    else if (category_type == CATEGORY_TYPE_COUNTRY) {
        // bxsliderを設定
        bxslider = $('#banner').bxSlider({
            controls: false,
            auto: true,
            mode: 'fade',
            // スライド遷移後に呼ばれる
            onSlideAfter:function($slideElement, oldIndex, newIndex){
                $('.bx-pager a').eq(oldIndex).removeClass('active');
                $('.bx-pager a').eq(newIndex).addClass('active');
                // 自動ローテション
                bxslider.startAuto();
            },
        });

        //モーダルの閉じるリンクの設定
        $(document).on('click', '#friend_pop_close', function (e) {
            e.preventDefault();
            $.magnificPopup.close();
        });

    }
    else
    {
        // bxsliderを設定
        bxslider = $('#banner').bxSlider({
            controls: false,
            auto: true,
            mode: 'fade',
            // スライド遷移後に呼ばれる
            onSlideAfter:function($slideElement, oldIndex, newIndex){
                $('.bx-pager a').eq(oldIndex).removeClass('active');
                $('.bx-pager a').eq(newIndex).addClass('active');
                // 自動ローテション
                bxslider.startAuto();
            },
        });
    }

    // IEの処理
    if (ua.match("MSIE") || ua.match("Trident")) { // åMSIEまたはTridentが入っていたら
        // 写真スライダーのページャー
        $('.bx-wrapper .bx-pager').css('z-index','100');
    }

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

    // menu
    $('body').on('click', '.menu-link', function(event) {
        if($('.submenu').css('display')=='block'){
            $('.submenu').hide();
        }
    });
    $('body').on('click', '#bltai4', function(event) {
        if ($('.submenu').css('display') == 'none') {
            $('#bltai4').addClass('active');
            $('.submenu').show();
        } else {
            $('#bltai4').removeClass('active');
            $('.submenu').hide();
        }
    });
    $('body').on('click', '.submenu-close', function(event) {
        $('#bltai4').removeClass('active');
        $('.submenu').hide();
    });

    $('body').on('click', '.submenu ul li a', function(event) {
        var str = $(this).text();
        $('[name=p_hatsu]').parent().html('<strong name=p_hatsu>' + str + '</strong>');
        $('#loStart').text(str);
        $('#Js_setMykencode').text(str);
        $('.submenu').hide();
    });

    $('.Js_HatsuSelectPanel>div a').click(function(event) {
        $('#loStart').text($(this).text());
        $('#Js_setMykencode').text($(this).text());
        $('[name=p_hatsu]').parent().html('<strong name=p_hatsu>'+$(this).text()+'</strong>');
        $('.Js_HatsuSelectPanel').hide();
    });
    $('[name=p_hatsu]').change(function(event) {
        $('#loStart').text($(this).find('option:selected').text());
        $('#Js_setMykencode').text($(this).find('option:selected').text());
        var opselect = $(this).find('option:selected').text();
        $('[name=p_hatsu]').each(function(index, el) {
            $(this).find('option').each(function(index, el) {
                if($(this).text()==opselect){
                    $(this).attr('selected', 'selected');
                }
            });
        })

    });

    $('.Js_selectAreaBtn').click(function(ev) {
        var clickPanel = $("+.Js_HatsuSelectPanel",this);
        clickPanel.toggle();
    });

    // datepicker
    $( ".datepicker" ).datepicker({
      numberOfMonths: 3,
      showButtonPanel: true,
      altField: ".datepicker",
      altFormat: "yy/mm/dd"
    });
    // tab
    $('.slider-sly').each(function(index, el) {
        sly(this,1);
    });
    $('.slider-sly-small').each(function(index, el) {
        sly(this,2);
        // $(this).parent().find('.next').trigger('click');
        // $(this).parent().find('.prev').eq(0).addClass('disabled');
    });
    $('.slider-sly-normal').each(function(index, el) {
        sly(this,1);
        // $(this).parent().find('.next').trigger('click');
        // $(this).parent().find('.prev').eq(0).addClass('disabled');
    });
    $('.frame.slider-ft').each(function(index, el) {
        sly(this,1);
        // $(this).parent().find('.next').trigger('click');
        // $(this).parent().find('.prev').eq(0).addClass('disabled');
    });
    $('.wr-tab').each(function(index, el) {
        $(this).find('.tab-ct').hide();
        // タブが存在しているなら
        if($('.tab-menu').length){
            $(this).find('.tab-ct').eq($(this).find('.tab-menu li.active').index()).show();
        }else{
            // ツアーを表示
            $(this).find('.tab-ct').eq(0).show();
        }
    });

    $('.tab-menu>li').click(function(event) {
        if(!$(this).parent().hasClass('disable')){
            $(this).parent().children().removeClass('active');
            $(this).addClass('active');
            $(this).parents('.wr-tab').find('.tab-ct').hide();
            $(this).parents('.wr-tab').find('.tab-ct').eq($(this).index()).show();

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
        }else{
            var tabvt = 0;
            if($(this).index()==0){
                tabvt = $('#bltai3').offset().top-50;
            }else if($(this).index()==1){
                tabvt = $('#bltai3_1').offset().top-50;
            }else if($(this).index()==2){
                tabvt = $('#bltai3_2').offset().top-50;
            }

            $('body,html').animate({
                scrollTop: tabvt
            }, 500);
            return false;
        }
        setGlobalNaviAction();
    });
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

    var setTimeoutId = null ;	// タイマーの管理
    var scrolling = false ;		// スクロールのステータス (true=スクロール中、false=静止中)

    // Menu fixed
    if ($('.bn-menu').length > 0) {

        var vtmenu = $('.bn-menu').offset().top;
        $(window).scroll(function(event) {
            if ($(this).scrollTop() > vtmenu) {
                if ($('.bn-menu-wr').length <= 0) {
                    $('.bn-menu').wrap("<div class='bn-menu-wr'></div>");
                    $( "<div class='bn-menu-hidden'></div>" ).insertAfter( ".bn-menu-wr" ).css({height: 50});
                }

                // スクロール中と判断
                scrolling = true;

            } else {
                $(".bn-menu-wr").replaceWith($(".bn-menu-wr").html());
                $('.bn-menu-hidden').remove();
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
                $('.bn-menu-wr').fadeOut("first");
            // 静止中
           } else {
               // 黒帯グローバルメニュー表示
               $('.bn-menu-wr').fadeIn("first");
           }

       }, 100 ) ;
    }

    if($('input[name=p_transport]:checked').val() == 3){
        $('#cityCd').val($('#preCity').val());
    }
    //新着のタイトル部分は９６０ｐｘに広がった時のみ、文字サイズを２２pxにする
    if($('#tab_ct_tour .find-tour > li').length == 1 && $('#tab_ct_tour .find-tour > li').hasClass('last')){
        $('#tab_ct_tour .find-tour li p span').css('font-size','22px');
        $('#tab_ct_tour .find-tour li p').css('height','45px');
        $('#tab_ct_tour .find-tour li i').css({
            'width':'40px',
            'height':'40px'
        });

        // 背景画像をつける
        $('#tab_ct_tour .find-tour li p').addClass("main-title");
        $('#tab_ct_tour .find-tour li p').addClass("main-title_none");
        $('#tab_ct_tour .find-tour li p').addClass("padding_393");

    }else {
        $('#tab_ct_tour .find-tour li p span').css('font-size','16px');
    }

    if($('#tab_ct_freeplan .find-tour > li').length == 1 && $('#tab_ct_freeplan .find-tour > li').hasClass('last')){
        $('#tab_ct_freeplan .find-tour li p span').css('font-size','22px');
    }else {
        $('#tab_ct_freeplan .find-tour li p span').css('font-size','16px');
    }

    // 新着がない場合のホテル・旅館から探すのレイアウトを変更する
    // ホテル・旅館から探すのみなら
    if($('#tab_ct_tour .find-tour > li').length == 1 && !$('#tab_ct_tour .find-tour > li').hasClass('last')){
        $('#tab_ct_tour .find-tour > li > p').eq(0).css({
    	   'float':'left',
    	   'width':'53%'
        });
        $('#tab_ct_tour .find-tour > li > p').eq(1).removeClass('mt20');
        $('#tab_ct_tour .find-tour > li > p').eq(1).css({
           'float':'left',
           'width':'40%',
           'margin-left':'60px'
        });
        $('#tab_ct_tour .find-tour > li > form').css({
           'float':'left',
           'width':'40%',
           'margin-left':'64px'
        });
    }
    // フリープラン版
    if($('#tab_ct_freeplan .find-tour > li').length == 1 && !$('#tab_ct_freeplan .find-tour > li').hasClass('last')){
        $('#tab_ct_freeplan .find-tour > li > p').eq(0).css({
           'float':'left',
           'width':'53%'
        });
        $('#tab_ct_freeplan .find-tour > li > p').eq(1).removeClass('mt20');
        $('#tab_ct_freeplan .find-tour > li > p').eq(1).css({
           'float':'left',
           'width':'40%',
           'margin-left':'60px'
        });
        $('#tab_ct_freeplan .find-tour > li > form').css({
           'float':'left',
           'width':'40%',
           'margin-left':'64px'
        });
    }

    //（関東発/名古屋発/関西発/福岡発）の商品ゼロでのグレーアウト表示
    $('.submenu-block .submenu-gray a').each(function(){
        if($(this).hasClass('disabled')){
            $(this).parent().css('background','#d9d9d9');
        }
    });

});


// $(document).on('click','.next',function(){
//     var prev = $(this).parent().find('.prev').eq(0);
//     var next = $(this).parent().find('.next').eq(0);
// 
//     var parentFrame = $(this).parent().parent();
//     var parentUL = parentFrame.find('ul').eq(0);
//     var liNum = parentUL.children('li').length;
// 
//     var widthFrame = parentFrame.eq(0).width();
//     var widthLi = parentUL.children('li').eq(0).width();
// 
//     var displayCount = Math.floor( widthFrame / widthLi );
// 
//     var activeNum = 0;
// 
//     // 表示個数が2個以上の場合
//     if (displayCount > 1) {
//         parentUL.children('li').each(function(index, el) {
//             if ($(this).hasClass('active')) {
//                 activeNum = index;
//             }
//         });
//         activeNum++; // 移動前のため加算
// 
//         if ((liNum - 1) <= activeNum) {
//             $(this).addClass('disabled');
//         }
//         prev.removeClass('disabled');
//     }
// });
// $(document).on('click','.prev',function(){
//     var prev = $(this).parent().find('.prev').eq(0);
//     var next = $(this).parent().find('.next').eq(0);
// 
//     var parentFrame = $(this).parent().parent();
//     var parentUL = parentFrame.find('ul').eq(0);
//     var liNum = parentUL.children('li').length;
// 
//     var widthFrame = parentFrame.eq(0).width();
//     var widthLi = parentUL.children('li').eq(0).width();
// 
//     var displayCount = Math.floor( widthFrame / widthLi );
// 
//     var activeNum = 0;
// 
//     // 表示個数が2個以上の場合
//     if (displayCount > 1) {
//         parentUL.children('li').each(function(index, el) {
//             if ($(this).hasClass('active')) {
//                 activeNum = index;
//             }
//         });
//         activeNum--; // 移動前のため減算
// 
//         if (activeNum <= 0) {
//             $(this).addClass('disabled');
//         }
//         next.removeClass('disabled');
//     }
// });

function sly(a,num) {
    var $frame = $(a);
    var $slidee = $frame.children('ul').eq(0);
    var $wrap = $frame.parent();

    if ($frame.hasClass('set_sly')) {
        return;
    }
    $frame.addClass('set_sly');

    if ($frame.find('#kinrin_text').length > 0) {
        $wrap.find('.prev').eq(0).css('display', 'none');
        $wrap.find('.next').eq(0).css('display', 'none');
        $slidee.css({'background': '#ffffff'});
        $wrap.find('.pages').hide();
        return;
    }

    $frame.sly({
        horizontal: 1,
        itemNav: 'basic',
        smart: 1,
        activateOn: 'click',
        mouseDragging: 1,
        touchDragging: 1,
        releaseSwing: 1,
        startAt: 0,
        pagesBar: $wrap.find('.pages'),
        activatePageOn: 'click',
        speed: 700,
        elasticBounds: 1,
        easing: 'easeInOutQuad',
        dragHandle: 1,
        dynamicHandle: 1,
        clickBar: 1,

        // Buttons
        prevPage: $wrap.find('.prev'),
        nextPage: $wrap.find('.next')
    });
    $frame.find('pos-rel').removeClass('active');

    // スクロールバーの非表示対応
    var classKinds = $slidee.attr('class');
    if(typeof classKinds !== 'undefined'){
        // liの要素数取得
        var num = $slidee.find('li').length;
        // クラス名取得
        var className = classKinds.replace( /clearfix /g , "");
        if (num == 0){
            $wrap.find('.pages').hide();
        }
        switch (className) {
            case 'osusume':
                // 0の場合、nodata画像を複数表示させる
                if(num == 0){
                    // 近隣の発地の表示の場合
                    if ($('.osusume').find('#kinrin_text').length > 0) {
                        $slidee.css({'background': '#ffffff'});
                        $wrap.find('.pages').hide();
                    } else {
	                    $slidee.css({
	                        'background-position':'15px',
	                        'background-repeat':'repeat-x'
	                    });
	                }
                }
                else if (num <= SCROLLBAR_ICHIOSHI) {
                    $wrap.find('.pages').hide();
                }
                break;
            case 'tantosha':
                if(num <= SCROLLBAR_TANTOSHA_OSUSUME) $wrap.find('.pages').hide();
                break;
            case 'uresuzi':
                if(num <= SCROLLBAR_URESUZI) $wrap.find('.pages').hide();
                break;
        }
    }

    var widthFrame = $frame.width();
    var widthUL = $frame.find('ul').eq(0).width();
    if (widthUL <= widthFrame) {
        $wrap.find('.prev').eq(0).css('display', 'none');
        $wrap.find('.next').eq(0).css('display', 'none');
    }

    // $wrap.find('.prev').eq(0).addClass('disabled');

}

$(function() {
    $('.OsusumeTour').each(function(index, el) {
        $(this).find("dl:gt(4)").css("display", "none");
    });
    $(".BusTour").each(function(index, el) {
        $(this).find("dl:gt(4)").css("display", "none");
    });
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
$(function() {
    $("body").click(function(ev) {
        if (!$(ev.target).is(".Js_AreaSelectPanel:visible, .Js_AreaSelectPanel:visible *")) {
            $('.Js_AreaSelectPanel').slideUp(0);
            $('.Js_AreaSelectPanel').prev().css("background","#2e4153");
        }
    });
    $(".Js_selectAreaBtn").click(function(ev) {
        var clickPanel = $(".Js_AreaSelectPanel");
        clickPanel.toggle();
        $(".Js_AreaSelectPanel").not(clickPanel).slideUp(0);
        return false;
    });
    $('#Js_AreaSelectPanelClose').click(function(){
        $(".Js_AreaSelectPanel").slideUp(0);
    });
    $('.Js_AreaSelectPanel>div a').click(function(event) {
        $('#loStart').text($(this).text());
        $('#Js_setMykencode').text($(this).text());
        $('#p_hatsu').parent().html('<strong>'+$(this).text()+'</strong>');
        $('#p_hatsu1').parent().html('<strong>'+$(this).text()+'</strong>');
        $('.Js_AreaSelectPanel').hide();
    });

    // nav
    // if($('#js_freeNav').length){
    //     var nav    = $('#js_freeNav');

    //     if($('#Slide')[0]){
    //         var offset = nav.offset();

    //         if(offset){
    //             $(window).scroll(function () {
    //               if($(window).scrollTop() > offset.top) {
    //                     nav.addClass('fixed');
    //                 }else {
    //                     nav.removeClass('fixed');
    //                 }
    //             });
    //         }

    //         /*$('#js_freeNav li a').click(function(){

    //             var Hash = $(this).attr('href');
    //             var HashOffset = $(Hash).offset().top;
    //             var navH =$('#js_freeNav').outerHeight();
    //             $('body,html').animate({scrollTop:HashOffset-navH}, 200);
    //             return false;
    //         });*/

    //     }
    //     else{

    //         var offset = nav.offset();

    //         if(offset){
    //             $(window).scroll(function () {
    //               if($(window).scrollTop() > offset.top) {
    //                     nav.addClass('fixed');
    //                 }else {
    //                     nav.removeClass('fixed');
    //                 }
    //             });
    //         }

    //         $('#js_freeNav li a').click(function(){

    //             var Hash = $(this).attr('href');
    //             var HashOffset = $(Hash).offset().top;
    //             var navH =$('#js_freeNav').outerHeight();
    //             $('body,html').animate({scrollTop:HashOffset-navH}, 200);
    //             return false;
    //         });

    //     }
    //      setTimeout(function(){
    //         if($('#Slide')[0]){
    //             var offset = nav.offset();

    //             if(offset){
    //                 $(window).scroll(function () {
    //                   if($(window).scrollTop() > offset.top) {
    //                         nav.addClass('fixed');
    //                     }else {
    //                         nav.removeClass('fixed');
    //                     }
    //                 });
    //             }

    //             /*$("#js_freeNav li a").click(function(e){
    //                 e.preventDefault();
    //                 var Hash = $(this.hash);
    //                 var HashOffset = $(Hash).offset().top;
    //                 $("html,body").stop().animate({scrollTop: HashOffset-50}, 200,"");
    //                 return false;
    //             });*/
    //         }

    //     },1000);

    // }

});

$(function() {

    // BOTの時
    if(isBot()){

        // タブ内の商品情報が何もないときの非表示対応
        // ツアー
        if($("#tab_ct_tour .osusume").length <= 0 &&
           $("#tab_ct_tour .uresuzi").length <= 0 &&
           $("#tab_ct_tour .tantosha").length <= 0 &&
           $("#tab_ct_tour .group-crystal-list .swiper-slide").length <= 0 &&
           $("#tab_ct_tour .group-friend .group-crystal-list .swiper-slide").length <= 0 ){

               $("#tab_ct_tour").hide();
               $(".tab-menu").hide();   //  タブ非表示
        }

        // フリープラン
        if($("#tab_ct_freeplan .osusume").length <= 0 &&
           $("#tab_ct_freeplan .tantosha").length <= 0 &&
           $("#tab_ct_freeplan .list-hotel").length <= 0 ){

               $("#tab_ct_freeplan").hide();
               $(".tab-menu").hide();   //  タブ非表示
        }

        // イチオシ枠がない時は、ツアー検索枠（+コース番号検索）ごと非表示
        if($("#tab_ct_tour .osusume").length <= 0){
            $("#tab_ct_tour").find('div').eq(0).hide();
        }

        // ブランド枠がない時は、そのブランド枠ごと非表示
        // クリスタル
        if($(".brand_bot .group-crystal-list").find('li').length <= 0){
            $(".brand_bot").hide();
        }

    }
});

$(function() {

    //その他の出発地を押した時
	$("#tab_ct_freeplan #Js_otherFacetEtc").click(function(){
		var clickPanel = $("#tab_ct_freeplan #otherFacetEtc");
		clickPanel.toggle();
		$("#tab_ct_freeplan #otherFacetEtc").not(clickPanel).slideUp(0);
		$("#tab_ct_freeplan #otherFacetEtc:visible").prev().css("background","#283948");
		$("#tab_ct_freeplan #otherFacetEtc:hidden").prev().css("background","#2e4153");
		return false;
	});
	$('#tab_ct_freeplan #Js_otherFacetEtcClose').click(function(){
		$("#tab_ct_freeplan #otherFacetEtc").slideUp(0);
	});

	//出発地から探すをクリックしたとき
	$("body").click(function(ev) {
		if (!$(ev.target).is("#tab_ct_freeplan #otherFacetEtc:visible, #tab_ct_freeplan #otherFacetEtc:visible *")) {
			$('#tab_ct_freeplan #otherFacetEtc').slideUp(0);
		}
	});
	$('#tab_ct_freeplan #Js_otherFacetEtc').click(function(ev) {
		if (!$(ev.target).is("#tab_ct_freeplan #otherFacetEtc:visible, #tab_ct_freeplan #otherFacetEtc:visible *")) {
			var sub = $(this).find("ul");
			if ($(sub).is(':hidden')) {
				ev.stopPropagation();
				$('#tab_ct_freeplan #otherFacetEtc').slideUp(0);
				$(sub).slideDown(0);
			}
		}
	});

});

$(function() {

    // イチオシ枠で、商品がなく近隣の出発地も全て0件なら
    if(0 < $("#tab_ct_tour #kinrin_text").length){
        // 他の出発地の結果がない場合
        if($(".srchBox360b .otBtn").length < 1){
            $("#tab_ct_tour").hide();
        }
    }

    // ブランド枠の商品がない時は、そのブランド枠ごと非表示
    // クリスタル
    if($(".group-crystal-list").eq(1).find('li').length <= 0){
        $(".group-crystal").hide();
    }

});

$(function() {

    if($("#tab_ct_tour .box_genchihacchaku").css('display') == 'block'){
        $("#tab_ct_tour .box_genchihacchaku").css('margin-top','40px');
    }

});

$(function() {
    // IEではpointer-events: none;が効かないため
    $(document).on('click', '.disabled', function (e) {

        return false;
    });
});

$(function() {

    var array = [
        '与那国島・波照間島',
    ];

    // 該当ページのフリープラン検索の見出し部分を調整する
    $.each(array,function(index,value){
        if ($(".banner1 h1").html() == value) {
            $("#tab_ct_freeplan .tab-tt").css({
                'text-align':'left',
                'padding-left':'15px',
                'font-size':'12px'
            });
        }
    });

    // 基本情報の右側nodataのheightを調整
    if(0 < $('.aside.right.nodata').length){
        $('.aside.right.nodata').css('min-height', $('.aside.right.nodata').parent().children().eq(0).height() + 'px')
    }

    // 北海道の基本情報部分
    if(0 < $(".bn-title-ltxt").length && $(".bn-title-ltxt").html() == '北海道'){
        $("#AnimeImg").css("height","initial");
    }

    $(".Js_HatsuSelectPanel").css("z-index","11000");

});


// BOT判定
function isBot()
{
	// BOTのとき
	if ($('.bn-menu').length <= 0) {

		return true;
	}

	return false;
}

// 都市ページ判定
function isCity()
{
	// 都市ページのとき
	if ($('.main.city').length > 0) {

		return true;
	}
	return false;
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


var dispTabType = 1;// 0:タブなし,1:ツアータブ,2:フリープランタブ,3:現地発着タブ
function setGlobalNaviAction() {
    // ナビが存在しない場合は、処理しない
    if ($('.bn-menu').length <= 0) {
        return;
    }


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
        if ($('.bn-title').length <= 0 ) {
            $('.menu-link-map').addClass('disabled');
        } else {
            $('.menu-link-map').removeClass('disabled');
        }

        if ($('#tab_ct_tour .tab-content-search').length <= 0 ) {
            $('.menu-link-search').addClass('disabled');
        } else {
            $('.menu-link-search').removeClass('disabled');
        }

        if ($('#tab_ct_tour .tab-content-slider').length <= 0 ) {
            $('.menu-link-ichioshi').addClass('disabled');
        } else {
            $('.menu-link-ichioshi').removeClass('disabled');
        }

        if ($('.travel-info').length <= 0 ) {
            $('.menu-link-travel-info').addClass('disabled');
        } else {
            $('.menu-link-travel-info').removeClass('disabled');
        }
    } else if (dispTabType == 2) {
        // フリープランタブの場合
        if ($('.bn-title').length <= 0 ) {
            $('.menu-link-map').addClass('disabled');
        } else {
            $('.menu-link-map').removeClass('disabled');
        }

        if ($('#tab_ct_freeplan .tab-content-search').length <= 0 ) {
            $('.menu-link-search').addClass('disabled');
        } else {
            $('.menu-link-search').removeClass('disabled');
        }

        if ($('#tab_ct_freeplan .tab-content-slider').length <= 0 ) {
            $('.menu-link-ichioshi').addClass('disabled');
        } else {
            $('.menu-link-ichioshi').removeClass('disabled');
        }

        if ($('.travel-info').length <= 0 ) {
            $('.menu-link-travel-info').addClass('disabled');
        } else {
            $('.menu-link-travel-info').removeClass('disabled');
        }
    } else if (dispTabType == 3) {
        // フリープランタブの場合
        if ($('.bn-title').length <= 0 ) {
            $('.menu-link-map').addClass('disabled');
        } else {
            $('.menu-link-map').removeClass('disabled');
        }

        $('.menu-link-search').removeClass('disabled');

        $('.menu-link-ichioshi').removeClass('disabled');

        if ($('.travel-info').length <= 0 ) {
            $('.menu-link-travel-info').addClass('disabled');
        } else {
            $('.menu-link-travel-info').removeClass('disabled');
        }
    }
}
function scrollMenuBtn(no) {
    var vt = 0;
    if (dispTabType == 0 || dispTabType == 1) {
        if (no == 1) {
            vt = $('.bn-title').offset().top;
        } else if (no == 2) {
            vt = $('#tab_ct_tour .tab-content-search').offset().top;
        } else if (no == 3) {
            if (isCity()) {
                vt = $('#tab_ct_tour .tab-content-slider').offset().top;
            } else {
                vt = $('#tab_ct_tour .tab-content-slider').offset().top - 90;
            }
        } else if (no == 4) {
            vt = $('.travel-info').offset().top;
        }
    } else if (dispTabType == 2) {
        if (no == 1) {
            vt = $('.bn-title').offset().top;
        } else if (no == 2) {
            vt = $('#tab_ct_freeplan .tab-content-search').offset().top;
        } else if (no == 3) {
            if (isCity()) {
                vt = $('#tab_ct_freeplan .tab-content-slider').offset().top;
            } else {
                vt = $('#tab_ct_freeplan .tab-content-slider').offset().top - 90;
            }
        } else if (no == 4) {
            vt = $('.travel-info').offset().top;
        }
    }
    vt -= $('.bn-menu').height();

    $('body,html').animate({
        scrollTop: vt
    }, 500);
    return false;
}
$(function() {

    // 東京の地図
    $('.change_tokyo_detail').click(function(event) {
        $('.tokyo_full').hide();
        $(this).hide();
        $('.tokyo_detail').show();
        $('.change_tokyo_full').show();
        $('.back_kanto').hide();
        tokyo_map_img('detail');
        $(".pg_12-13 .wr-banner3").css("background-image","url(/attending/senmon_kokunai/images/map/map_Tokyo_detail.png)");
    });
    $('.change_tokyo_full').click(function(event) {
        $('.tokyo_detail').hide();
        $(this).hide();
        $('.tokyo_full').show();
        $('.change_tokyo_detail').show();
        $('.back_kanto').show();
        tokyo_map_img('full');
        $(".pg_12-13 .wr-banner3").css("background-image","url(/attending/senmon_kokunai/images/map/map_Tokyo_full.png)");
    });

});


function tokyo_map_img(kinds){

    if(kinds == 'detail'){
        obj = $(".IkebukuroMejiroAkabaneItabashiNerima");
    }else{
        obj = $(".OkutamaOme");
    }

    $('.wr-child-banner-info li.mainBgClr').removeClass('active');
    obj.addClass('active');
    if(map == undefined) return;

    bxslider.destroySlider();

    var tmpUl = $('ul#banner');                     // ←ulタグをコピー
    $(".wr-image-banner.bdColor").html('');         // ←写真の枠の中身を空にする
    $(".wr-image-banner.bdColor").append(tmpUl);    // ←写真の枠にコピーしたtmpUlのHTMLを追加する。

    var map_data = map[obj.attr('data')];

    $('#banner').empty();
    $('#banner-title').empty();
    $('#banner-content').empty();

    // 写真がなかったら
    if(map_data['img_path'].length < 1)
    {
        // noimageを入れる
        $('#banner').append('<li><img src="/sharing/common14/images/noimage390.png" alt=""></li>');
    }
    else
    {
        for (var i = 0; i < map_data['img_path'].length; i++) {
            var size_class = map_data['size'][i] == 1 ? 'size_8_3' : '';
            $('#banner').append('<li><img class="'+ size_class + '" src="'+map_data['img_path'][i]+'" alt=""></li>');
        }
    }
    var vt = obj.attr('data').replace('map','');

    $('#banner-title').text(sl_title[(vt-1)]);
    $('#banner-content').text(sl_content["map"+vt]);

    // bxsliderを設定
    bxslider = $('#banner').bxSlider({
        controls: false,
        auto: true,
        mode: 'fade',//fade,horizontal
        speed: 800,

        onSlideBefore: function($slideElement, oldIndex, newIndex) {
            $('#banner li').eq(oldIndex).css({marginLeft:"0px"}).animate({marginLeft:"-40px"},800,function(){
                $("#banner li").eq(oldIndex).css({marginLeft:"0px"});
            });
        },

        // スライド遷移後に呼ばれる
        onSlideAfter:function($slideElement, oldIndex, newIndex){
            $('.bx-pager a').eq(oldIndex).removeClass('active');
            $('.bx-pager a').eq(newIndex).addClass('active');

            // 自動ローテション
            bxslider.startAuto();
        },
    });

    // IEの処理
    if (ua.match("MSIE") || ua.match("Trident")) { // åMSIEまたはTridentが入っていたら
        // 写真スライダーのページャー
        $('.bx-wrapper .bx-pager').css('z-index','100');
    }

    if($("#banner").find('li').length < 2){
        // 丸ページャー非表示
        $(".bx-controls").hide();
    }

}
