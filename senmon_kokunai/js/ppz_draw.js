/*
 *  copyright(c) activecore,Inc. 2005-2011
 *
 *  This software is licensed by activecore, Inc.
 *  Product  : ac propoza
 *  Version  : 1.1
 */

/*
* utf-8で作成しています。
* 他の文字コードをご利用の場合は、文字コードを変更して、
* 保存し直してください。
* ※日本語をご利用している場合は、文字化けするので修正してください。
*/

//◆阪急交通社様：レコメンドデータ返却イメージ◆
//タグのリクエストがあると、以下サンプルの様に、
//jsonフォーマットでデータが返却されます。
//item_listから必要な項目を取得し、表示内容の組み立てを行います。


//レコメンド返却データサンプル
/*
var info=
{"num":"5","page_no":"","div_id":"ppz_recommend", "target_item_name":"",
   "item_list":[
      {"item_id":"100574","item_name":"関空昼-午後発/ソウル午前発　ソウルフリー4日間(基本ホテルプラン)","sales_date":"","title":"【関空発11:10-13:35｜ソウル発08:30-10:00】","desc":"","image_url":"","image_alt":"","link_url":"","cart_url":"","price":"","real_price":"","stock":"","point":"","point_rate":""},
      {"item_id":"100934","item_name":"＜鹿児島港発着！＞屋久島フリープラン　２日間","sales_date":"","title":"","desc":"","image_url":"","image_alt":"","link_url":"","cart_url":"","price":"","real_price":"","stock":"","point":"","point_rate":""},
      {"item_id":"101279","item_name":"ジェットスター航空利用　トロピカル　ケアンズ　６日間　（ﾃﾞﾗｯｸｽﾌﾟﾗﾝ)","sales_date":"","title":"","desc":"","image_url":"","image_alt":"","link_url":"","cart_url":"","price":"","real_price":"","stock":"","point":"","point_rate":""},
      {"item_id":"101305","item_name":"＜宮崎発＞【レンタカー付】　屋久島フリープラン　２日間","sales_date":"","title":"","desc":"","image_url":"","image_alt":"","link_url":"","cart_url":"","price":"","real_price":"","stock":"","point":"","point_rate":""},
      {"item_id":"101735","item_name":"＜観光付＞日本航空（ＪＡＬ）ﾌﾟﾚﾐｱﾑｴｺﾉﾐｰで行く 華の都パリ７日間 ル・グラン指定","sales_date":"","title":"","desc":"","image_url":"","image_alt":"","link_url":"","cart_url":"","price":"","real_price":"","stock":"","point":"","point_rate":""}
    // 複数の場合、繰り返し
   ]
};
*/

function ppz_pckokunaisenmon01_remind(info) {
     //対象レコメンドがあるかないかの判定
     var chekFlg
     var target = document.getElementById(info.div_id);
     var _ppz_nodata = "";

     var item_div = '';
     var obj = info.div_id;

     var _ppz_title = "";

     // 出発地配列化
     // 全国タブの場合は出発地判定をスルーするためにfalseをセット
     var myHatsu = (ppz_recommend_myHatsu != "") ? ppz_recommend_myHatsu : false;

     //レコメンドデータ取得処理
     var num=0;
     for (var i = 0; i < info.item_list.length; i++) {

        //各変数内容をご確認の上、表示組み立てを行ってください。
        var _p_course_no = info.item_list[i].item_id;
        var _p_corse_name = info.item_list[i].item_name;
        var _p_image_url = info.item_list[i].image_url;
        var _p_image_alt = info.item_list[i].image_alt;
        var _p_hei = info.item_list[i].desc_02;
        var _p_web_brand = info.item_list[i].title_02;
        var _p_course_id = info.item_list[i].desc;
        var _p_naigai = info.item_list[i].item_cat02;
        var _p_hatsu_code = info.item_list[i].item_cat03;
        var _p_sub_title = info.item_list[i].title;
        var _p_dest_core = info.item_list[i].item_cat01;
        var _sprice = _PPZ_formatPrice(info.item_list[i].price);
        var _eprice = _PPZ_formatPrice(info.item_list[i].real_price);
        var count =info.item_list.length;

        // 出発地判定用
        // 出発地が設定されているのは拠点タブなので判定
        // 出発地が設定されていないのは全国タブなのでスルー

        if (myHatsu) {
            if (myHatsu.indexOf(_p_hatsu_code) === -1) {
                continue;
            }
        }

        num ++;


        if(_p_naigai == 0){
            var _corse_url = 'http://www.hankyu-travel.com/tour/detail_d.php?p_course_no='+_p_course_no;
        }else{
            var _corse_url = 'http://www.hankyu-travel.com/tour/detail_i.php?p_course_no='+_p_course_no;
        }

        var _corse_name = '+ _p_corse_name';

        item_div += '<li>';
        item_div += '<a href="javascript:void(0)" onClick="ppz_pckokunaisenmon01._click(\''+ _corse_url + '\',\'' + _p_course_no +  '\')">';
        item_div += '<dl>';
        item_div += '<dd class="pht"><img src="//x.hankyu-travel.com/cms_photo_image/image_search_kikan2.php?p_photo_mno='+_p_image_url+'" alt="'+_p_corse_name+'"></dd>';
        item_div += '<dt>'+_p_corse_name+'</dt>';
        item_div += '<dd class="fee">'+ppzYoriMade(_sprice,_eprice)+'</dd>';
        item_div += '</dl>';
        item_div += '</a>';
        item_div += '</li>';


    }

    if(num==0){
        item_div += '<li class="blank1"><span>最近見たツアーが<br />入ります</span></li><li class="blank1"><span>最近見たツアーが<br />入ります</span></li><li class="blank1"><span>最近見たツアーが<br />入ります</span></li>';
    }
    else if(num==1){
        item_div += '<li class="blank1"><span>最近見たツアーが<br />入ります</span></li><li class="blank1"><span>最近見たツアーが<br />入ります</span></li>';

    }
    else if(num==2){
        item_div += '<li class="blank1"><span>最近見たツアーが<br />入ります</span></li>';
    }

    // フリーツアーの情報があったらHTML表示

        target.innerHTML += '<div class="idx_box01 recently bdr_rgt01 mb30 OnFL js-carousel-box"><h2 class="idx_icn01">最近見たツアー</h2><button class="sld_prev js-slide-controller"><img src="/sharing/common16/images/sld_prev.png"></button><button class="sld_next js-slide-controller"><img src="/sharing/common16/images/sld_next.png"></button><div class="js-carousel-container"><div id="recentTour" class="js-carousel"><ul>'+item_div+'</ul></div><div class="scrollbar"><div class="handle"><div class="mousearea"></div></div></div></div>';
}

function ppz_pckokunaisenmon03_ranking(info) {

    //対象レコメンドがあるかないかの判定
     var chekFlg
     var target = document.getElementById(info.div_id);
     var _ppz_nodata = "";

     var item_div = '';
     var obj = info.div_id;

     var _ppz_title = "";

     // 出発地配列化
     // 全国タブの場合は出発地判定をスルーするためにfalseをセット
     var myHatsu = (ppz_recommend_myHatsu != "") ? ppz_recommend_myHatsu : false;

     //レコメンドデータ取得処理
     var num=0;
     for (var i = 0; i < info.item_list.length; i++) {

        //各変数内容をご確認の上、表示組み立てを行ってください。
        var _p_course_no = info.item_list[i].item_id;
        var _p_corse_name = info.item_list[i].item_name;
        var _p_image_url = info.item_list[i].image_url;
        var _p_image_alt = info.item_list[i].image_alt;
        var _p_hei = info.item_list[i].desc_02;
        var _p_web_brand = info.item_list[i].title_02;
        var _p_course_id = info.item_list[i].desc;
        var _p_naigai = info.item_list[i].item_cat02;
        var _p_hatsu_code = info.item_list[i].item_cat03;
        var _p_sub_title = info.item_list[i].title;
        var _p_dest_core = info.item_list[i].item_cat01;
        var _sprice = _PPZ_formatPrice(info.item_list[i].price);
        var _eprice = _PPZ_formatPrice(info.item_list[i].real_price);
        var count =info.item_list.length;

        // 出発地判定用
        // 出発地が設定されているのは拠点タブなので判定
        // 出発地が設定されていないのは全国タブなのでスルー
        if (myHatsu) {
            if (myHatsu.indexOf(_p_hatsu_code) === -1) {
                continue;
            }
        }

        num ++;

		if(_p_naigai == 0){
			var _corse_url = 'http://www.hankyu-travel.com/tour/detail_d.php?p_course_id='+_p_course_id+"&p_hei="+_p_hei;
		}else{
			var _corse_url = 'http://www.hankyu-travel.com/tour/detail_i.php?p_course_id='+_p_course_id+"&p_hei="+_p_hei;
		}

        var iconClass= "icon icon-num icon-num"+ num;
        if(num == 1)
        {
            iconClass= "icon icon-num icon-num1-small";
        }

        item_div += '<li class="pos-rel">';
        item_div += '<a href="javascript:void(0)" onClick="ppz_pckokunaisenmon03._click(\''+ _corse_url + '\',\'' + _p_course_no +  '\')">';
        item_div += '<img src="//x.hankyu-travel.com/cms_photo_image/image_search_kikan5.php?p_photo_mno='+_p_image_url+'" alt="'+_p_corse_name+'" class="img_uresuzi">';
        item_div += '<i class="'+iconClass+'"></i>';
        item_div += '<p class="sly3-ct">'+_p_corse_name+'</p>';
        item_div += '<p class="sly3-price">'+ppzYoriMade(_sprice,_eprice)+'</p>';
        item_div += '</a>';
        item_div += '</li>';

    }

    if(num < 3){
        $('.uresuzi_title').hide();
    }
    else{
        $('.uresuzi_title').show();

        target.innerHTML += '<div class="wr-block">'
                         + '<div class="frame slider-sly slider-sly-small ppz_ranking">'
                         + '<ul class="clearfix uresuzi">'
                         + item_div
                         + '</ul>'
                         + '</div>'
                         + '<div class="btn-group"><a href="#" class="prev"><i class="sprite sprite-slider-prev"></i></a><a href="#" class="next"><i class="sprite sprite-slider-next"></i></a></div>'
                         + '<ul class="pages uresuzi-pages"></ul>'
                         + '</div>';

        ppz_sly($('.ppz_ranking'));
//        $('.ppz_ranking').parent().find('.next').trigger('click');
    }
}

function ppz_pckokunaisenmon02_recommend(info) {
     //対象レコメンドがあるかないかの判定
     var chekFlg
     var target = document.getElementById(info.div_id);
     var _ppz_nodata = "";

     var item_div = '';
     var obj = info.div_id;

     var _ppz_title = "";


     // 出発地配列化
     // 全国タブの場合は出発地判定をスルーするためにfalseをセット
    var myHatsu = (ppz_recommend_myHatsu != "") ? ppz_recommend_myHatsu : false;

     //レコメンドデータ取得処理
     var num=0;

     for (var i = 0; i < info.item_list.length; i++) {

        //各変数内容をご確認の上、表示組み立てを行ってください。
        var _p_course_no = info.item_list[i].item_id;
        var _p_corse_name = info.item_list[i].item_name;
        var _p_image_url = info.item_list[i].image_url;
        var _p_image_alt = info.item_list[i].image_alt;
        var _p_hei = info.item_list[i].desc_02;
        var _p_web_brand = info.item_list[i].title_02;
        var _p_course_id = info.item_list[i].desc;
        var _p_naigai = info.item_list[i].item_cat02;
        var _p_hatsu_code = info.item_list[i].item_cat03;
        var _p_sub_title = info.item_list[i].title;
        var _p_dest_core = info.item_list[i].item_cat01;
        var _sprice = _PPZ_formatPrice(info.item_list[i].price);
        var _eprice = _PPZ_formatPrice(info.item_list[i].real_price);
        var count =info.item_list.length;

        // 出発地判定用
        // 出発地が設定されているのは拠点タブなので判定
        // 出発地が設定されていないのは全国タブなのでスルー

        /*if(_p_naigai !=1){
            continue;
        }*/

        if (myHatsu) {
            if (myHatsu.indexOf(_p_hatsu_code) === -1) {
                continue;
            }
        }

        //フリープラン判定用
        /*var _desc_b_02 = info.item_list[i].desc_b_02;

        var resArray = _desc_b_02.split("*");
        if(resArray[0].indexOf("030")!=-1){

        }
        else{
            continue;
        }*/
        num ++;
        if(num > 15){
            continue;
        }

        if(_p_naigai == 0){
            var _corse_url = 'http://www.hankyu-travel.com/tour/detail_d.php?p_course_id='+_p_course_id+"&p_hei="+_p_hei;
        }else{
            var _corse_url = 'http://www.hankyu-travel.com/tour/detail_i.php?p_course_id='+_p_course_id+"&p_hei="+_p_hei;
        }

        var _corse_name = '+ _p_corse_name';

        item_div += '<li>';
        item_div += '<a href="javascript:void(0)" onClick="ppz_pckokunaisenmon02._click(\''+ _corse_url + '\',\'' + _p_course_no +  '\')">';
        item_div += '<dl>';
        item_div += '<dd class="pht"><img src="//x.hankyu-travel.com/cms_photo_image/image_search_kikan2.php?p_photo_mno='+_p_image_url+'" alt="'+_p_corse_name+'"></dd>';
        item_div += '<dt>'+_p_corse_name+'</dt>';
        item_div += '<dd class="fee">'+ppzYoriMade(_sprice,_eprice)+'</dd>';
        item_div += '</dl>';
        item_div += '</a>';
        item_div += '</li>';

    }

    if(num==0){
        item_div += '<li class="blank1"><span>あなたへの<br />おすすめツアー<br />が入ります</span></li><li class="blank1"><span>あなたへの<br />おすすめツアー<br />が入ります</span></li><li class="blank1"><span>あなたへの<br />おすすめツアー<br />が入ります</span></li>';
    }
    else if(num==1){
        item_div += '<li class="blank1"><span>あなたへのおすすめツアー<br />が入ります</span></li><li class="blank1"><span>あなたへのおすすめツアー<br />が入ります</span></li>';
    }
    else if(num==2){
        item_div += '<li class="blank1"><span>あなたへのおすすめツアー<br />が入ります</span></li>';
    }

    // フリーツアーの情報があったらHTML表示
    target.innerHTML += '<div class="idx_box02 rc_tour mb30 OnFR js-carousel-box"><h2 class="idx_icn02">あなたへのおすすめツアー</h2><button class="sld_prev js-slide-controller"><img src="/sharing/common16/images/sld_prev.png"></button><button class="sld_next js-slide-controller"><img src="/sharing/common16/images/sld_next.png"></button><div class="js-carousel-container"><div id="rcTour" class="js-carousel"><ul>'+item_div+'</ul></div><div class="scrollbar"><div class="handle"><div class="mousearea"></div></div></div></div>';

}

//金額のヨリマデ作成
function ppzYoriMade(min,max){
    var ret = '';
    var NgStr = 0;
    var str = '円';
    if( typeof(min) == 'undefined' && typeof(max) == 'undefined' ||    min == '' && max == '' ){
        return ret;
    }
    if( min != '' || max != ''){
        //同じ場合
        if(min == max){
            if(min == 0 || min == ''){
                ret = NgStr;
            }
            else{//単一
                ret = min + str;
            }
        }
        //異なる
        else{
            if(min == '' && max != ''){
                ret = max + str;
            }else if(min != '' && max == ''){
                ret = min + str;
            }
            else if(min == 0 && max != ''){
                ret = max + str;
            }
            else if(min != '' && max == 0){
                ret = min + str;
            }else{
                ret = min + '〜' + max + str;
            }
        }
    }
    return ret;
}

function ppz_sly(a) {

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

}
