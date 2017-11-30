// ツアーかフリープランか 初期値はツアー
var FormID = '.tour ';

var botDateInitFlag = false;

// 画面下部フッター表示用
var paramArray =
	[
	 'p_hatsu',
	 'p_mokuteki',
	 'p_dep_date',
	 'p_kikan_min',
	 'p_kikan_max',
	 'p_conductor',
	 'p_price_min',
	 'p_price_max',
	 'p_carr',
	 'p_seatclass',
	 'p_timezone',
	 'p_total_amount_divide',
	 'p_hotel_code',
	 'p_stock',
	 'p_decide',
	 'p_mainbrand',
	 'p_early_discount_flag',
	 'p_discount',
	 'p_bunrui',
	 'p_free_word',
	 ];



// トップ検索画面→検索結果画面、再検索画面への送信POST用
var sendParamArray =
	[
	 'p_hatsu',
	 'p_mokuteki',
	 'p_mokuteki_kind',
	 'p_dep_date',
	 'p_kikan_min',
	 'p_kikan_max',
	 'p_conductor',
	 'p_hatsu_detail',
	 'p_hatsu_detail_param',
	 'p_mokuteki_detail',
	 'p_mainbrand',
	 ];

$(function(){




	// 現在のファイル名
	var currentFileName = window.location.href.split('/').pop();
	// サイトカタリストは再検索画面の読込時に処理することにする
	if(currentFileName == 'ifree.php' || currentFileName == 'i.php')
	{
		//サイトカタリストの処理
	 	utilityJs.SAS_setCookie('SAS_VARS_TYPE', '検索', '', '/', 'hankyu-travel.com', '');
	}

	if($(FormID+'.GlMenu.js_HatsuMenu').css('display') == 'block')
	{
		hideFooter();
	}


	var postForm = function(url, data) {
		url = 'http://' + location.host + url;
        var $form = $('<form/>', {'action': url, 'method': 'post', 'data-ajax':false ,'target':'_blank'});
//	    var $form = $('<form/>', {'action': url, 'method': 'post', 'data-ajax':false});
		for(var key in data) {
                $form.append($('<input/>', {'type': 'hidden', 'name': key, 'value': data[key]}));
        }
        $form.appendTo(document.body);
        $form.submit();
	};

	// 検索ボタンをクリックしたら
	$(".search-btn").click(function(e){

		// ツアーかフリープランか Formの判定
		setFormID(this);

		if($(FormID+"#p_hatsu").val() != "")
		{
			var text = "";
			// 再検索画面に渡す出発地と目的地の値を設定
			$(FormID+"#decided_contents_hatsu span").each(function(index, element) {

				text = ( text == "" ) ? $(element).text() : text + ',' + $(element).text();

			});

			$(FormID+"#p_hatsu_detail").val(text);
		}
		else
		{
			$(FormID+"#p_hatsu_detail").val("");
		}

		if($(FormID+"#p_mokuteki").val() != "")
		{
			text = "";
			// 再検索画面に渡す出発地と目的地の値を設定
			$(FormID+"#decided_contents_destination span").each(function(index, element) {

				text = ( text == "" ) ? $(element).text() : text + ',' + $(element).text();

			});

			$(FormID+"#p_mokuteki_detail").val(text);
		}
		else
		{
			$(FormID+"#p_mokuteki_detail").val("");
		}


		// 検索トップ画面の検索ボタン
		if($(FormID+"#searchBtn input").val() == '検索')
		{


			text = "";
			// 再検索画面に渡す出発地と目的地の値を設定。出発地で親と子の関係があるのでここで渡す。
			$(FormID+"#decided_contents_hatsu input").each(function(index, element) {

				text = ( text == "" ) ? $(element).data("value") : text + '/' + $(element).data("value");

			});

			$(FormID+"#p_hatsu_detail_param").val(text);


			// search/i.phpに渡す配列
			var data = {};

			$.each(sendParamArray,function(index,val){

				if($(FormID+"#" + val).val() != "")
				{
					data[val] = $(FormID+"#" + val).val();
				}
			});

			if(data['p_mokuteki_detail'] == 'オセアニア' || data['p_mokuteki_detail'] == '南太平洋'){
				// オセアニアと南太平洋は分解する
				if(data['p_mokuteki_detail'] == 'オセアニア'){
					data['p_mokuteki_detail'] = 'オーストラリア,ニュージーランド';
				}
				else if (data['p_mokuteki_detail'] == '南太平洋') {
					data['p_mokuteki'] = 'FOC-NC-,FOC-FJ-,FOC-VU-,FOC-PF-';
					data['p_mokuteki_detail'] = 'ニューカレドニア,フィジー,パヌアツ,タヒチ';
				}
			}
			else{
				// 初期表示で複数選択なら 初回読み込みは2回している
				if(0 < $('#search_country').val().length && $(".tour #tour_init_flag").val() == '2'){
					data['p_mokuteki'] = $(FormID+"#def_mokuteki").val();
					data['p_mokuteki_detail'] = $.parseJSON($('#search_country').val());
				}else if (0 < $('#search_country').val().length) {
					var p_mokuteki_length = data['p_mokuteki'].split(",");
					var p_mokuteki_detail_length = data['p_mokuteki_detail'].split(",");
					if(p_mokuteki_length.length != p_mokuteki_detail_length.length){
						data['p_mokuteki'] = p_mokuteki_length[0];
					}
				}
			}

			// ツアーとフリープランで遷移先を変える
			if($(FormID+"#free_flag").val() != "")
			{
				postForm('/search/ifree.php', data);
			}
			else
			{
				postForm('/search/i.php', data);
			}

		}
		// 再検索ボタン
		else
		{
			// 検索結果画面に遷移。
			$.mobile.changePage( "#search_result_page");
		}


	});


	// トップ画面のモーダルビューを表示する。
	$('.search-box').on("click",".modal-menu,.decided_text,.add-btn,.decided_link", function(e) {
		e.preventDefault();

		// ツアーかフリープランか Formの判定
		setFormID(this);

		hideFooter();

		var modalID = $(this).attr('href');

		// ホテルの時は目的地に国が設定されているか確認
		if(modalID == '#modal_hotel')
		{
			if($(FormID+"#p_mokuteki").val().indexOf('-') == -1)
			{
				alert("目的地に国を設定して下さい");
				return;
			}
		}


		$("body").append('<div id="modal-overlay"></div>');
		$("#modal-overlay").fadeIn("slow");
		centeringModalSyncer(FormID+modalID);
		$(FormID+modalID).fadeIn("slow");
		$("#modal-overlay,.GlMenuClose a").unbind().click(function(){
//			$(modalID + ",#modal-overlay").fadeOut("slow",function(){

				// モーダルビューの閉じるボタンを押した際のクリア
				switch(modalID)
				{
					// 出発地なら
					case '#modal_hatsu':

						if($(FormID+'#add_contents_hatsu').css('display') == 'block')
						{
							var text = "";
							$.each($(FormID+"#decided_contents_hatsu input"),function(index,val){

								text = ( text == "" ) ? $(this).data("value") : text + ',' + $(this).data("value");
							});

							$(FormID+"#p_hatsu").val(text);
						}
						else
						{
							$(FormID+"#p_hatsu").val("");
						}

						checkFooterSearch();

						break;
					// 目的地なら
					case '#modal_destination':

						if($(FormID+'#add_contents_destination').css('display') == 'block')
						{
							var text = "";
							$.each($(FormID+"#decided_contents_destination input"),function(index,val){

								text = ( text == "" ) ? $(this).data("value") : text + ',' + $(this).data("value");
							});

							$(FormID+"#p_mokuteki").val(text);
						}
						else
						{
							$(FormID+"#p_mokuteki").val("");
						}

						checkFooterSearch();

						break;
					// 出発日なら
					case '#modal_date':
						addControl('date','p_dep_date');
//						clearDate();
						break;
					case '#modal_kikan':
						if($(FormID+'#add_contents_kikan').css('display') == 'none')
						{
							$(FormID+'#p_kikan_min').val("");
							$(FormID+'#p_kikan_max').val("");
						}

						$(FormID+".decide-btn").removeClass('kikan');

						checkFooterSearch();
//						clearKikan();
						break;
					case '#modal_conductor':
//						clearConductor();
						break;
					case '#modal_airline':
						addControl('airline','p_carr');
//						clearAirline();
						break;
					case '#modal_seat':
						addControl('seat','p_seatclass');
//						clearSeat();
						break;
					case '#modal_timezone':
						addControl('timezone','p_timezone');
//						clearTimezone();
						break;
					case '#modal_total_amount_divide':
//						clearTotalAmountDivide();
						break;
					case '#modal_hotel':
						addControl('hotel','p_hotel_code');
//						clearHotel();
						break;
					case '#modal_stock':
//						clearStock();
						break;
					case '#modal_decide':
//						clearDecide();
						break;
					case '#modal_mainbrand':
						addControl('mainbrand','p_mainbrand');
//						clearMainbrand();
						break;
					case '#modal_early_discount_flag':
//						clearEarlyDiscountFlag();
						break;
					case '#modal_discount':
						addControl('discount','p_discount');
//						clearDiscount();
						break;
					case '#modal_bunrui':
						addControl('bunrui','p_bunrui');
//						clearBunrui();
						break;
				}

				$(FormID+".decide-btn").removeClass().addClass("decide-btn");

				checkFooterSearch();

				$(FormID+modalID).fadeOut("slow");
				$('#modal-overlay').remove();

				if(modalID == '#modal_hatsu' || modalID == '#modal_destination' || modalID == '#modal_airline' || modalID == '#modal_hotel' ||
				   modalID == '#modal_date' || modalID == '#modal_kikan' || modalID == '#modal_conductor' || modalID == '#modal_mainbrand')
				{
					var position = 0;
					switch(modalID)
					{
						case '#modal_hatsu':
							position = $(FormID+".search_hatsu").get( 0 ).offsetTop;;
							break;
						case '#modal_destination':
							position = $(FormID+".search_destination").get( 0 ).offsetTop;
							break;
						case '#modal_airline':
							position = $(FormID+".search_airline").get( 0 ).offsetTop;
							break;
						case '#modal_hotel':
							position = $(FormID+".search_hotel").get( 0 ).offsetTop;
							break;
						case '#modal_date':
							position = $(FormID+".search_date").get( 0 ).offsetTop;;
							break;
						case '#modal_kikan':
							position = $(FormID+".search_kikan").get( 0 ).offsetTop;
							break;
						case '#modal_conductor':
							position = $(FormID+".search_conductor").get( 0 ).offsetTop;
							break;
						case '#modal_mainbrand':
							position = $(FormID+".search_mainbrand").get( 0 ).offsetTop;
							break;
					}


					// モーダルの下部の閉じるボタンを押した際
					//if($(this).parent().parent().attr('class') == 'modal-footer')
					//{
						$("html, body").animate({
							scrollTop: position
							}, {
						});
					//}
				}
//			});
		});


		// モーダルビューを表示した際の初期表示
		switch(modalID)
		{
			// 出発地なら
			case '#modal_hatsu':
				// 追加ボタンなら
				if($(this).attr('class') == 'add-btn' || $(this).attr('class') == 'add-btn ui-link')
				{
					$(FormID+"#p_hatsu_add_flag").val("1");
				}

				var req_para_name = getReqParamHatsu();
				$(FormID+"#" + req_para_name).val('');
				hideFooter();

				getDept(true);
				break;
			// 目的地なら
			case '#modal_destination':
				// 追加ボタンなら
				if($(this).attr('class') == 'add-btn' || $(this).attr('class') == 'add-btn ui-link')
				{
					$(FormID+"#p_mokuteki_add_flag").val("1");
				}

				$(FormID+'#p_mokuteki').val('');
				hideFooter();

				getDest();
				break;
			// 日付なら
			case '#modal_date':
				if($(FormID+"#p_dep_date").val() != "")
				{
					$(FormID+".decide-btn").addClass("date");
					displayDecideFooter();
					return;
				}

				initDate();
				break;
			// 旅行期間なら
			case '#modal_kikan':
				if($(FormID+'#p_kikan_min').val() != "" && $(FormID+'#p_kikan_max').val() != "")
				{
					$(FormID+".decide-btn").addClass("kikan");
					displayDecideFooter();
				}

				initKikan();
				break;
			// 添乗員なら
			case '#modal_conductor':
				initConductor();
				break;
			// 航空会社なら
			case '#modal_airline':
				if($(FormID+'#p_carr').val() != "")
				{
					$(FormID+".decide-btn").addClass("airline");
					displayDecideFooter();
				}
				airlineInit();
				break;
			// 座席クラスなら
			case '#modal_seat':
				if($(FormID+'#p_seatclass').val() != "")
				{
					$(FormID+".decide-btn").addClass("seat");
					displayDecideFooter();
				}
				seatInit();
				break;
			// 出発時間帯なら
			case '#modal_timezone':
				if($(FormID+'#p_timezone').val() != "")
				{
					$(FormID+".decide-btn").addClass("timezone");
					displayDecideFooter();
				}
				timezoneInit();
				break;
			// 燃油サーチャージなら
			case '#modal_total_amount_divide':
				initTotalAmountDivide();
				break;
			// ホテルなら
			case '#modal_hotel':
				if($(FormID+'#p_hotel_code').val() != "")
				{
					$(FormID+".decide-btn").addClass("hotel");
					displayDecideFooter();
				}
				hotelInit();
				break;
			// 残席なら
			case '#modal_stock':
				initStock();
				break;
			// 催行状況なら
			case '#modal_decide':
				decideInit();
				break;
			// ブランドなら
			case '#modal_mainbrand':
				if($(FormID+'#p_mainbrand').val() != "")
				{
					$(FormID+".decide-btn").addClass("mainbrand");
					displayDecideFooter();
				}
				mainbrandInit();
				break;
			// 早期割引なら
			case '#modal_early_discount_flag':
				earlyDiscountFlagInit();
				break;
			// その他割引なら
			case '#modal_discount':
				if($(FormID+'#p_discount').val() != "")
				{
					$(FormID+".decide-btn").addClass("discount");
					displayDecideFooter();
				}
				discountInit();
				break;
			// テーマなら
			case '#modal_bunrui':
				if($(FormID+'#p_bunrui').val() != "")
				{
					$(FormID+".decide-btn").addClass("bunrui");
					displayDecideFooter();
				}
				bunruiInit();
				break;
		}
	});

	$(window).resize(centeringModalSyncer);
	function centeringModalSyncer(selecter){
		var topOffset = $(window).scrollTop();
		//画面(ウィンドウ)の幅、高さを取得
		var w = $(window).width();
		var h = $(window).height();

		//jquery mobile用
		if($(FormID+"div").hasClass("ui-page-active")){
				var a = w / 10;
				var cw = a * 9;
				$(selecter).css({
//					"left": ((w - cw)/2) + "px !important",
					"top": topOffset + 15
				})

		}
		else{
			if($(FormID+".js_GlMenu").length){
				var cw = $(selecter).outerWidth({margin:true});
				var ch = $(selecter).outerHeight({margin:true});
			}
			if(isFinite(cw)){
			}
			else{
				var a = w / 10;
				var cw = a * 9;
			}
			//センタリングを実行する
			$(selecter).css({
				"left": ((w - cw)/2) + "px",
				"top": topOffset + 15
			})
		}
	}

	// 全ての条件を削除をクリックしたら
	$(".all-delete").click(function(e){

		// ツアーかフリープランか Formの判定
		setFormID(this);

		clearHatsu();
		clearDestination();
		clearDate();
		clearKikan();
		clearConductor();
		clearMainbrand();
		$(FormID+"#p_hatsu_detail").val("");
		$(FormID+"#p_mokuteki_detail").val("");

		if($(FormID+"#searchBtn input").val() == '再検索')
		{
			clearAirline();
			clearSeat();
			clearTimezone();
			clearTotalAmountDivide();
			clearHotel();
			clearStock();
			clearDecide();
			clearMainbrand();
			clearEarlyDiscountFlag();
			clearDiscount();
			clearBunrui();
			clearPrice();
			$(FormID+"#free_word").val("");
		}

		//hideFooter();

		setTimeout(function(){
			getDept(true);
		},1000);
	});

	$(window).scroll(function(event) {

		// BOTフリープランの日付がまだ初期化されていないなら
		if(!isBot() || $(FormID+"#freeplan_date_init_flag").val() == 'true' || botDateInitFlag) return;

		// スクロール位置で判定
		if ( ($(window).height() + $(window).scrollTop()) < $('.tab-ct.free_plan').offset().top) {
			FormID = '.tour ';
		} else {
			botDateInitFlag = true;
			FormID = '.free_plan ';
			// 時間をおく
			setTimeout(function() {
				initDateFreeplan();
			}, 1000);

		}
	});


});

// ↓↓↓↓↓↓↓  モーダルビューで共通で使用するfunction群  ↓↓↓↓↓↓↓ //

// モーダルビューで出発地、目的地、出発日などの項目をチェックした際の動き
function checkboxAction(param,param2,value)
{
	// リクエスト値変更
	$(FormID+"#" + param2).val(value);

	if(value == '')
	{
		// 下部の固定フッター非表示
		hideFooter();
	}
	else
	{
		$(FormID+".decide-btn").addClass(param);

		// 件数取得
		getHitNum();

		// 下部の固定フッター表示
		displayDecideFooter();
	}

}

// モーダルビューでの確定ボタン押した際の検索画面の動き。追加ボタンありの場合addはtrue
function decideBtnActionAdd(param,html,add)
{
	//表示
	$(FormID+'.search_'+param+' #decided_contents_'+param).html(html);

	// 各項目の表示・非表示
	$(FormID+"#modal_menu_"+param).hide();
	$(FormID+"#add_contents_"+param).show();
	if(add)
	{
		$(FormID+"#add_"+param).show();
	}

	$(FormID+".decide-btn").removeClass(param);

	// モーダルビューをフェードアウト
	$(FormID+"#modal_"+param+",#modal-overlay").fadeOut("slow",function(){
		$('#modal-overlay').remove();
	});

	checkFooterSearch();

	// 出発地と目的地と航空会社の場合、スクロールする。
	if(param == 'hatsu' || param == 'destination' || param == 'airline')
	{
		var position = 0;
		var fixed_head_height = 0;
		if ($('#js_freeNav').get(0)) {
			fixed_head_height = $(FormID+"#js_freeNav").height();
		}

		switch(param)
		{
			case 'hatsu':
				position = $(FormID+".search_hatsu").get( 0 ).offsetTop - fixed_head_height;
				break;
			case 'destination':
				position = $(FormID+".search_destination").get( 0 ).offsetTop - fixed_head_height;
				break;
			case 'airline':
				position = $(FormID+".search_airline").get( 0 ).offsetTop - fixed_head_height;
				break;
		}

		$("html, body").animate({scrollTop: position}, {});
	}
}

// 再検索画面でも初期表示用追加ボタンありの場合addはtrue
function detailInitAction(param,html,add)
{
	//表示
	$(FormID+'.search_'+param+' #decided_contents_'+param).html(html);

	// 各項目の表示・非表示
	$(FormID+"#modal_menu_"+param).hide();
	$(FormID+"#add_contents_"+param).show();
	if(add)
	{
		$(FormID+"#add_"+param).show();
	}
}

// クリアアクション。追加ボタンありの場合addはtrue。検索画面→モーダルビューのクリア処理はtype=false
function clearAction(param,param2,add,type)
{
	// ツアーかフリープランか Formの判定
	setFormIDofClear();

	$(FormID+'.search_'+param+' #decided_contents_'+param).html("");

	// 各項目の表示・非表示
	$(FormID+"#modal_menu_"+param).show();
	$(FormID+"#add_contents_"+param).hide();
	if(add)
	{
		$(FormID+"#add_"+param).hide();
	}

	$(FormID+".decide-btn").removeClass(param);

	// input hiddenのリクエスト値クリア
	$("#" + param2).val('');

	if(type)
	{
		checkFooterSearch();
	}

}

// 削除ボタンのクリアアクションの例外。出発地と目的地は複数選択できるため
function deleteButtonActionException(param,param2,element)
{
	// ツアーかフリープランか Formの判定
	setFormID(element);

	// リクエスト値変更から削除した値を削除
	var value = $(FormID+"#"+param2).val().replace( $(element).data("value") , "" ) ;

	// 先頭が','なら削除
	if(value.substr( 0, 1 ) == ',')
	{
		value = value.substr( 1 );
	}
	// 末尾が','なら削除
	if(value.substr( value.length-1 ) == ',')
	{
		value = value.substr( 0, value.length-1 );
	}

	// ',,'を削除
	value = value.replace( /,,/g , "," ) ;


	//リクエスト値変更
	$(FormID+"#"+param2).val(value);

	$(element).parent().parent().remove();

	if(value == "")
	{
		$(FormID+"#modal_menu_"+param).show();
		$(FormID+"#add_contents_"+param).hide();
		$(FormID+"#add_"+param).hide();
	}
	else if($(FormID+"#add_"+param).css('display') == 'none')
	{
		$(FormID+"#add_"+param).show();
	}

	checkFooterSearch();
}

// 検索画面での固定フッターの表示、非表示
function checkFooterSearch()
{

	var check = false;

	$.each(paramArray,function(index,val){

		// 旅行代金の時はデフォルト値と異なってたら固定フッター表示
		if(val == 'p_price_min' || val == 'p_price_max')
		{
			if($(FormID+"#" + val).val() != "")
			{
				if((val == 'p_price_min' && $(FormID+"#" + val).val() != $(FormID+"#p_price_min_default").val()) ||
				   (val == 'p_price_max' && $(FormID+"#" + val).val() != $(FormID+"#p_price_max_default").val())
				)
				{
					getHitNum();
					displaySearchFooter();
					check = true;
					return false;
				}
			}
		}
		else if(val == "p_free_word")
		{
			if($(FormID+"#" + val).val() != "")
			{
				displaySearchFooter();
				$(FormID+".search_result_hit").html("");
				check = true;
				return false;
			}
		}
		else
		{
			getHitNum();
			displaySearchFooter();
			check = true;
			return false;
		}

	});

	if(!check)
	{
		hideFooter();
	}
}

function addControl(param,param2)
{
	if($(FormID+'#add_contents_'+param).css('display') == 'none')
	{
		$(FormID+"#"+param2).val("");
	}

	$(FormID+".decide-btn").removeClass(param);

	checkFooterSearch();
}


// モーダルビューでの確定ボタンフッター
function displayDecideFooter()
{
	$(FormID+"#searchBtn").hide();
	$(FormID+"#fix_footer").show();
	$(FormID+"#decideBtn").show();

	// カテゴリトップなら
	if(!$(FormID+"#search_detail_flag").val())
	{
		// 初期化
		$(FormID+".fixed-footer").removeClass().addClass("fixed-footer");
		$(FormID+".fixed-footer").addClass("color");
	}


}

// 検索画面での検索ボタンフッター
function displaySearchFooter()
{
	$(FormID+"#decideBtn").hide();
	$(FormID+"#fix_footer").show();
	$(FormID+"#searchBtn").show();

	// 再検索画面の検索ボタン
	if($(FormID+"#search_detail_flag").val())
	{
		$(FormID+".all-delete").css("margin-bottom","60px");
		$(FormID+".fixed-footer").removeClass("colorposition");
		$(FormID+".fixed-footer").addClass("color");
	}
	else
	{
		// 初期化
		$(FormID+".fixed-footer").removeClass().addClass("fixed-footer");
		$(FormID+".fixed-footer").removeClass("color");
		$(FormID+".fixed-footer").addClass("colorposition");
	}

}

// フッターを非表示
function hideFooter()
{
	$(FormID+"#fix_footer").hide();
	$(FormID+"#decideBtn").hide();
	$(FormID+"#searchBtn").hide();

	// 再検索画面の検索ボタン
	if($(FormID+"#searchBtn input").val() == '再検索')
	{
		$(FormID+".all-delete").css("margin-bottom","0px");
	}
}

// ツアーかフリープランか Formの判定
function setFormID(obj)
{
	var tabClass = $(obj).closest('.tab-ct').attr('class');
	var tabClassArray = tabClass.split(" ");
	FormID = '.'+tabClassArray[1]+' ';
}

// ツアーかフリープランか Formの判定 クリア時
function setFormIDofClear(){

	if(!isBot()) return;

	// スクロール位置で判定
	if ( ($(window).height() + $(window).scrollTop()) < $('#tab2').offset().top) {
		FormID = '.tour ';
	} else {
		FormID = '.free_plan ';
	}
}

// ↑↑↑↑↑↑↑  モーダルビューで共通で使用するfunction群  ↑↑↑↑↑↑↑ //
