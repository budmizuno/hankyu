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
{"num":"5","page_no":"","div_id":"ppz_recommend_spkaigaisenmon03", "target_item_name":"",
   "item_list":[
      {"item_id":"100574","item_name":"関空昼-午後発/ソウル午前発　ソウルフリー4日間(基本ホテルプラン)","sales_date":"","title":"【関空発11:10-13:35｜ソウル発08:30-10:00】","desc":"","image_url":"00021-EBP10-00264.jpg","image_alt":"","link_url":"","cart_url":"","price":"49,800","real_price":"50,800","stock":"","point":"","point_rate":""},
      {"item_id":"100934","item_name":"＜鹿児島港発着！＞屋久島フリープラン　２日間","sales_date":"","title":"","desc":"","image_url":"00021-EBP10-00264.jpg","image_alt":"","link_url":"","cart_url":"","price":"49,800","real_price":"50,800","stock":"","point":"","point_rate":""},
      {"item_id":"101279","item_name":"ジェットスター航空利用　トロピカル　ケアンズ　６日間　（ﾃﾞﾗｯｸｽﾌﾟﾗﾝ)","sales_date":"","title":"","desc":"","image_url":"00021-EBP10-00264.jpg","image_alt":"","link_url":"","cart_url":"","price":"49,800","real_price":"50,800","stock":"","point":"","point_rate":""},
      {"item_id":"101305","item_name":"＜宮崎発＞【レンタカー付】　屋久島フリープラン　２日間","sales_date":"","title":"","desc":"","image_url":"00021-EBP10-00264.jpg","image_alt":"","link_url":"","cart_url":"","price":"49,800","real_price":"50,800","stock":"","point":"","point_rate":""},
      {"item_id":"101735","item_name":"＜観光付＞日本航空（ＪＡＬ）ﾌﾟﾚﾐｱﾑｴｺﾉﾐｰで行く 華の都パリ７日間 ル・グラン指定","sales_date":"","title":"","desc":"","image_url":"00021-EBP10-00264.jpg","image_alt":"","link_url":"","cart_url":"","price":"49,800","real_price":"50,800","stock":"","point":"","point_rate":""}
    // 複数の場合、繰り返し
   ]
};
*/

function ppz_spkaigaisenmon01_remind(info) {

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
    var myHatsuSub = (ppz_recommend_myHatsuSub != "") ? ppz_recommend_myHatsuSub : false;

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

		if(_p_naigai ==1){
			if (myHatsu) {
				if (myHatsu.indexOf(_p_hatsu_code) === -1) {
					continue;
				}
			}
		}
		else if(_p_naigai ==0){
			if (myHatsuSub) {
				if (myHatsuSub.indexOf(_p_hatsu_code) === -1) {
					continue;
				}
			}
		}

        if(_p_naigai == 0){
            var _corse_url = 'http://www.hankyu-travel.com/tour/detail_d.php?p_course_no='+_p_course_no;
        }else{
            var _corse_url = 'http://www.hankyu-travel.com/tour/detail_i.php?p_course_no='+_p_course_no;
        }

        var _corse_name = '+ _p_corse_name';

		item_div += '<li class="tour swiper-slide">';
		item_div += '<a href="javascript:void(0)" onClick="ppz_spkaigaisenmon01._click(\''+ _corse_url + '\',\'' + _p_course_no +  '\')">';
		item_div += '<p><img src="//x.hankyu-travel.com/cms_photo_image/image_search_kikan3.php?p_photo_mno='+_p_image_url+'" alt="'+_p_corse_name+'"></p>';
		item_div += '<dl>';
		item_div += '<dt>'+_p_corse_name+'</dt>';
		item_div += '<dd>'+ppzYoriMade(_sprice,_eprice)+'</dd>';
		item_div += '</dl>';
		item_div += '</a>';
		item_div += '</li>';

        num ++;
    }

	if(num==0){
		item_div += '<li class="blank1 swiper-slide"><span>最近見たツアーが<br />入ります</span></li><li class="blank1 swiper-slide"><span>最近見たツアーが<br />入ります</span></li><li class="blank1 swiper-slide"><span>最近見たツアーが<br />入ります</span></li>';
	}
	else if(num==1){
		item_div += '<li class="blank1 swiper-slide"><span>最近見たツアーが<br />入ります</span></li><li class="blank1 swiper-slide"><span>最近見たツアーが<br />入ります</span></li>';
	}
	else if(num==2){
		item_div += '<li class="blank1 swiper-slide"><span>最近見たツアーが<br />入ります</span></li>';
	}

	target.innerHTML = '<section class="blue rcmnd4u"><h2>最近見たツアー</h2><div class="frame swiper-container" id="remind_tour_list"><ul class="clearfix swiper-wrapper">'+item_div+'</ul><div class="swiper-scrollbar"></div></div></section>';

    swiper($('#remind_tour_list'));
}


function ppz_spkaigaisenmon02_recommend(info) {
	 //対象レコメンドがあるかないかの判定
	 var chekFlg
	 var target = document.getElementById(info.div_id);
	 var _ppz_nodata = "";
	 var item_div = '';
	 var obj = info.div_id;
	 var _ppz_title = "";

	var myHatsu = (ppz_recommend_myHatsu != "") ? ppz_recommend_myHatsu : false;
	var myHatsuSub = (ppz_recommend_myHatsuSub != "") ? ppz_recommend_myHatsuSub : false;

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

		if(_p_naigai ==1){
			if (myHatsu) {
				if (myHatsu.indexOf(_p_hatsu_code) === -1) {
					continue;
				}
			}
		}
		else if(_p_naigai ==0){
			if (myHatsuSub) {
				if (myHatsuSub.indexOf(_p_hatsu_code) === -1) {
					continue;
				}
			}
		}

		if(_p_naigai == 0){
			var _corse_url = 'http://www.hankyu-travel.com/tour/detail_d.php?p_course_id='+_p_course_id+"&p_hei="+_p_hei;
		}else{
			var _corse_url = 'http://www.hankyu-travel.com/tour/detail_i.php?p_course_id='+_p_course_id+"&p_hei="+_p_hei;
		}

		var _corse_name = '+ _p_corse_name';

		item_div += '<li class="tour swiper-slide">';
		item_div += '<a href="javascript:void(0)" onClick="ppz_spkaigaisenmon02._click(\''+ _corse_url + '\',\'' + _p_course_no +  '\')">';
		item_div += '<p><img src="//x.hankyu-travel.com/cms_photo_image/image_search_kikan3.php?p_photo_mno='+_p_image_url+'" alt="'+_p_corse_name+'"></p>';
		item_div += '<dl>';
		item_div += '<dt>'+_p_corse_name+'</dt>';
		item_div += '<dd>'+ppzYoriMade(_sprice,_eprice)+'</dd>';
		item_div += '</dl>';
		item_div += '</a>';
		item_div += '</li>';

		num ++;

	}

	if(num==0){
		item_div += '<li class="blank1 swiper-slide"><span>あなたへの<br />おすすめツアー<br />が入ります</span></li><li class="blank1 swiper-slide"><span>あなたへの<br />おすすめツアー<br />が入ります</span></li><li class="blank1 swiper-slide"><span>あなたへの<br />おすすめツアー<br />が入ります</span></li>';
	}
	else if(num==1){
		item_div += '<li class="blank1 swiper-slide"><span>あなたへのおすすめツアー<br />が入ります</span></li><li class="blank1 swiper-slide"><span>あなたへのおすすめツアー<br />が入ります</span></li>';
	}
	else if(num==2){
		item_div += '<li class="blank1 swiper-slide"><span>あなたへのおすすめツアー<br />が入ります</span></li>';
	}

	target.innerHTML = '<section class="blue rcmnd4u"><h2>あなたへのおすすめツアー</h2><div class="frame swiper-container" id="recommend_tour_list"><ul class="clearfix swiper-wrapper">'+item_div+'</ul><div class="swiper-scrollbar"></div></div></section>';

    swiper($('#recommend_tour_list'));
}

function ppz_spkaigaisenmon03_ranking(info) {
	 //対象レコメンドがあるかないかの判定
	 var chekFlg
	 var target = document.getElementById(info.div_id);
	 var _ppz_nodata = "";
	 var item_div = '';
	 var obj = info.div_id;
	 var _ppz_title = "";

	var myHatsu = (ppz_recommend_myHatsu != "") ? ppz_recommend_myHatsu : false;
	var myHatsuSub = (ppz_recommend_myHatsuSub != "") ? ppz_recommend_myHatsuSub : false;

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

		if(_p_naigai ==1){
			if (myHatsu) {
				if (myHatsu.indexOf(_p_hatsu_code) === -1) {
					continue;
				}
			}
		}
		else if(_p_naigai ==0){
			if (myHatsuSub) {
				if (myHatsuSub.indexOf(_p_hatsu_code) === -1) {
					continue;
				}
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

        item_div += '<li class="pos-rel swiper-slide">';
        item_div += '<a href="javascript:void(0)" onClick="ppz_spkaigaisenmon03._click(\''+ _corse_url + '\',\'' + _p_course_no +  '\')">';
        item_div += '<i class="'+iconClass+'"></i>';
        item_div += '<img src="//x.hankyu-travel.com/cms_photo_image/image_search_kikan5.php?p_photo_mno='+_p_image_url+'" alt="'+_p_corse_name+'" class="img_uresuzi">';
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

        target.innerHTML += '<div class="wr-block mb20">'
                         + '<div class="frame swiper-container swiper-container-horizontal" id="uresuzi_list">'
                         + '<ul class="clearfix uresuzi swiper-wrapper">'
                         + item_div
                         + '</ul>'
                         + '<div class="swiper-scrollbar" style="opacity: 0;"><div class="swiper-scrollbar-drag" style="width: 114.63px;"></div></div>'
                         + '</div>'
                         + '</div>';
        swiper('#uresuzi_list');
	}
}

//金額のヨリマデ作成
function ppzYoriMade(min,max){
	var ret = '';
	var NgStr = 0;
	var str = '円';
	if( typeof(min) == 'undefined' && typeof(max) == 'undefined' ||	min == '' && max == '' ){
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
