// ツアーかフリープランか 初期値はツアー
var FormID = '.tour ';

var botDateInitFlag = false;

// 画面下部フッター表示用
var paramArray =
	[
	 'p_hatsu_sub',
	 'p_mokuteki',
	 'p_dep_date',
	 'p_kikan_min',
	 'p_kikan_max',
	 'p_conductor',
	 'p_price_min',
	 'p_price_max',
	 'p_transport',
	 'p_dep_airport_code',
	 'p_arr_airport_code',
	 'p_stay_number',
	 'p_carr',
	 'p_bus_boarding_code',
//	 'p_seatclass',
//	 'p_timezone',
//	 'p_total_amount_divide',
	 'p_accommodation_code',
	 'p_stock',
	 'p_decide',
	 'p_mainbrand',
	 'p_early_discount_flag',
//	 'p_discount',
	 'p_bunrui',
	 'p_free_word',
	 ];



// トップ検索画面→検索結果画面、再検索画面への送信POST用
var sendParamArray =
	[
	 'p_hatsu_sub',
	 'p_mokuteki',
	 'p_dep_date',
	 'p_kikan_min',
	 'p_kikan_max',
	 'p_conductor',
	 'p_hatsu_detail',
	 'p_hatsu_detail_param',
	 'p_mokuteki_detail',
	 'p_transport',
	 'p_dep_airport_code',
	 'p_arr_airport_code',
	 'p_dep_airport_detail',
	 'p_arr_airport_detail',
	 ];



$(function(){

	// 現在のファイル名
	var currentFileName = window.location.href.split('/').pop();
	// サイトカタリストは再検索画面の読込時に処理することにする
	if(currentFileName == 'dfree.php' || currentFileName == 'd.php')
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

		if($(FormID+"#p_hatsu_sub").val() != '')
		{
			var text = "";
			// 再検索画面に渡す出発地と目的地の値を設定
			$(FormID+"#decided_contents_hatsu span").each(function(index, element) {

				text = (text　== "") ? $(element).text():text + ',' + $(element).text();

			});

			$(FormID+"#p_hatsu_detail").val(text);
		}
		else
		{
			$(FormID+"#p_hatsu_detail").val("");
		}

		if($(FormID+"#p_mokuteki").val() != "")
		{
			var text = "";
			// 再検索画面に渡す出発地と目的地の値を設定
			$(FormID+"#decided_contents_destination span").each(function(index, element) {

				text = (text == "") ? $(element).text():text + ',' + $(element).text();

			});

			$(FormID+"#p_mokuteki_detail").val(text);
		}
		else
		{
			$(FormID+"#p_mokuteki_detail").val("");
		}

		if($(FormID+"#p_dep_airport_code").val() != '')
		{
			var text = "";
			// 再検索画面に渡す出発空港と到着空港の値を設定
			$(FormID+"#decided_contents_dep_airport span").each(function(index, element) {

				text = (text　== "") ? $(element).text():text + ',' + $(element).text();

			});

			$(FormID+"#p_dep_airport_detail").val(text);
		}
		else
		{
			$(FormID+"#p_dep_airport_detail").val("");
		}

		if($(FormID+"#p_arr_airport_code").val() != '')
		{
			var text = "";
			$(FormID+"#decided_contents_arr_airport span").each(function(index, element) {

				text = (text　== "") ? $(element).text():text + ',' + $(element).text();

			});

			$(FormID+"#p_arr_airport_detail").val(text);
		}
		else
		{
			$(FormID+"#p_arr_airport_detail").val("");
		}

		// 検索トップ画面の検索ボタン
		if($(FormID+"#searchBtn input").val() == '検索')
		{

			if($(FormID+"#p_hatsu_sub").val() != '')
			{
				var text = "";
				// 再検索画面に渡す出発地と目的地の値を設定。出発地で親と子の関係があるのでここで渡す。
				$(FormID+"#decided_contents_hatsu input").each(function(index, element) {

					text = (text == "") ? $(element).data("value"):text + '/' + $(element).data("value");

				});

				$(FormID+"#p_hatsu_detail_param").val(text);
			}

			// search/i.phpに渡す配列
			var data = {};

			$.each(sendParamArray,function(index,val){

				if($(FormID+"#" + val).val() != "")
				{
					data[val] = $(FormID+"#" + val).val();
				}
			});

			// 国内ツアーと国内フリープランで遷移先を変える
			if($(FormID+"#free_flag").val() != "")
			{
				postForm('/search/dfree.php', data);
			}
			else
			{
				postForm('/search/d.php', data);
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
//		$(".modal-menu,.decided_text").click(function(e){
		e.preventDefault();

		// ツアーかフリープランか Formの判定
		setFormID(this);


		hideFooter();

		var modalID = $(this).attr('href');

		// ホテルの時は目的地に都道府県が設定されているか確認
		if(modalID == '#modal_hotel')
		{
			if($("#p_mokuteki").val().indexOf('-') == -1)
			{
				alert("目的地に都道府県を設定して下さい");
				checkFooterSearch();
				return;
			}
		}
		else if(modalID == '#modal_bus_boarding')
		{
			if($(FormID+"#p_hatsu_sub").val() == '')
			{
				alert("出発地を設定して下さい");
				checkFooterSearch();
				return;
			}

			// disabledがあるなら
			if($(this).hasClass("disabled"))
			{
				checkFooterSearch();
				return;
			}
		}


		$("body").append('<div id="modal-overlay"></div>');
		$("#modal-overlay").fadeIn("slow");
		centeringModalSyncer(FormID+modalID);
		$(FormID+modalID).fadeIn("slow");
		$("#modal-overlay,.GlMenuClose a").unbind().click(function(){

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

						$(FormID+"#p_hatsu_sub").val(text);
					}
					else
					{
						$(FormID+"#p_hatsu_sub").val("");
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
					break;
				case '#modal_kikan':
					if($(FormID+'#add_contents_kikan').css('display') == 'none')
					{
						$(FormID+'#p_kikan_min').val("");
						$(FormID+'#p_kikan_max').val("");
					}

					$(FormID+".decide-btn").removeClass('kikan');

					checkFooterSearch();

					break;
				case '#modal_conductor':
					break;
				case '#modal_airline':
					addControl('airline','p_carr');
					break;
				case '#modal_transport':
					addControl('transport','p_transport');
					break;
				case '#modal_dep_airport':

					if($(FormID+"#free_flag").val() != '')
					{
						if($(FormID+'#add_contents_dep_airport').css('display') == 'block')
						{
							var text = "";
							$.each($(FormID+"#decided_contents_dep_airport input"),function(index,val){

								text = ( text == "" ) ? $(this).data("value") : text + ',' + $(this).data("value");
							});

							$(FormID+"#p_dep_airport_code").val(text);
						}
						else
						{
							$(FormID+"#p_dep_airport_code").val("");
						}

						checkFooterSearch();
					}
					else
					{
						addControl('dep_airport','p_dep_airport_code');
					}

					break;
				case '#modal_arr_airport':

					if($(FormID+"#free_flag").val() != '')
					{
						if($(FormID+'#add_contents_arr_airport').css('display') == 'block')
						{
							var text = "";
							$.each($(FormID+"#decided_contents_arr_airport input"),function(index,val){

								text = ( text == "" ) ? $(this).data("value") : text + ',' + $(this).data("value");
							});

							$(FormID+"#p_arr_airport_code").val(text);
						}
						else
						{
							$(FormID+"#p_arr_airport_code").val("");
						}

						checkFooterSearch();
					}
					else
					{
						addControl('arr_airport','p_arr_airport_code');
					}

					break;
				case '#modal_stay_number':
					break;
				case '#modal_bus_boarding':
					addControl('bus_boarding','p_bus_boarding_code');
					break;
				case '#modal_hotel':
					addControl('hotel','p_accommodation_code');
					break;
				case '#modal_stock':
					break;
				case '#modal_decide':
					break;
				case '#modal_mainbrand':
					addControl('mainbrand','p_mainbrand');
					break;
				case '#modal_early_discount_flag':
					break;
				case '#modal_bunrui':
					addControl('bunrui','p_bunrui');
					break;
			}

			$(FormID+".decide-btn").removeClass().addClass("decide-btn");

			checkFooterSearch();

			$(FormID+modalID).fadeOut("slow");
			$('#modal-overlay').remove();

			if(modalID == '#modal_hatsu' || modalID == '#modal_destination' || modalID == '#modal_dep_airport' ||
			   modalID == '#modal_arr_airport' || modalID == '#modal_airline' || modalID == '#modal_hotel' ||
			   modalID == '#modal_bus_boarding' || modalID == '#modal_date' || modalID == '#modal_kikan' || modalID == '#modal_conductor')
			{
				var position = 0;
				switch(modalID)
				{
					case '#modal_hatsu':
						position = $(FormID+".search_hatsu").get( 0 ).offsetTop;
						break;
					case '#modal_destination':
						position = $(FormID+".search_destination").get( 0 ).offsetTop;
						break;
					case '#modal_dep_airport':
						position = $(FormID+".search_dep_airport").get( 0 ).offsetTop;
						break;
					case '#modal_arr_airport':
						position = $(FormID+".search_arr_airport").get( 0 ).offsetTop;
						break;
					case '#modal_airline':
						position = $(FormID+".search_airline").get( 0 ).offsetTop;
						break;
					case '#modal_hotel':
						position = $(FormID+".search_hotel").get( 0 ).offsetTop;
						break;
					case '#modal_bus_boarding':
						position = $(FormID+".search_bus_boarding").get( 0 ).offsetTop;
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
				$("#" + req_para_name).val('');
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
			// 交通機関なら
			case '#modal_transport':
				if($(FormID+'#p_transport').val() != "")
				{
					$(FormID+".decide-btn").addClass("transport");
					displayDecideFooter();
				}
				transportInit();
				break;
			// 出発空港なら
			case '#modal_dep_airport':

				if($(FormID+'#p_dep_airport_code').val() != "")
				{
					$(FormID+".decide-btn").addClass("dep_airport");
					displayDecideFooter();
				}
				depAirportInit();
				break;
			// 到着空港なら
			case '#modal_arr_airport':
				if($(FormID+'#p_arr_airport_code').val() != "")
				{
					$(FormID+".decide-btn").addClass("arr_airport");
					displayDecideFooter();
				}
				arrAirportInit();
				break;
			// 宿泊数なら
			case '#modal_stay_number':
				initStayNumber();
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
			// バス乗車地
			case '#modal_bus_boarding':
				if($(FormID+'#p_bus_boarding_code').val() != "")
				{
					$(FormID+".decide-btn").addClass("bus_boarding");
					displayDecideFooter();
				}
				busBoardingInit();
				break;
			// ホテルなら
			case '#modal_hotel':
				if($(FormID+'#p_accommodation_code').val() != "")
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
					"top":topOffset + 15,
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
		$(FormID+"#p_hatsu_detail").val("");
		$(FormID+"#p_mokuteki_detail").val("");

		if($(FormID+"#free_flag").val() != '')
		{
			clearTransport();
			clearDepAirport();
			clearArrAirport();
		}


		if($(FormID+"#searchBtn input").val() == '再検索')
		{
			if($(FormID+"#free_flag").val() != '')
			{
				clearStayNumber();
			}
			else
			{
				clearTransport();
				clearDepAirport();
			}
			clearBusBoarding();
			clearAirline();
			clearHotel();
			clearStock();
			clearDecide();
			clearMainbrand();
			clearEarlyDiscountFlag();
			clearBunrui();
			clearPrice();
			$(FormID+"#free_word").val("");
		}

		//hideFooter();

		setTimeout(function(){
			getDept(true);
		},1500);
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
		$(FormID+'#modal-overlay').remove();
	});

	checkFooterSearch();

	// 出発地と目的地と航空会社の場合、スクロールする。
	if(param == 'hatsu' || param == 'destination' || param == 'airline' ||
	   param == 'dep_airport' || param == 'arr_airport')
	{
		var position = 0;
		var fixed_head_height = 0;
		if ($(FormID+'#js_freeNav').get(0)) {
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
			case 'dep_airport':
				position = $(FormID+".search_dep_airport").get( 0 ).offsetTop - fixed_head_height;
				break;
			case 'arr_airport':
				position = $(FormID+".search_arr_airport").get( 0 ).offsetTop - fixed_head_height;
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
	$(FormID+"#" + param2).val('');

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
					if($(FormID+"#free_flag").val() == "" && $(FormID+"#p_hatsu_sub").val() != "" && $(FormID+"#p_bus_boarding_code").val() == "")
					{
						// バス乗車地が存在しているかのチェック
						checkBusBoarding();
					}
					else
					{
						getHitNum();
					}
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
		//else if($("#" + val).val() != "")
		else
		{
			if($(FormID+"#free_flag").val() == "" && $(FormID+"#p_hatsu_sub").val() != "" && $(FormID+"#p_bus_boarding_code").val() == "")
			{
				// バス乗車地が存在しているかのチェック
				checkBusBoarding();
			}
			else
			{
				getHitNum();
			}
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

// バス乗車地の存在チェック。ほかにも存在チェックしたければ「p_rtn_data」に加えれば可能
function checkBusBoarding()
{
	var options = {
		formObj:'#searchTour',
		kind:"Detail",
		p_data_kind:1,
		p_rtn_data:'p_bus_boarding_name'
	}
	searchTour.requestProcess(options);	//Ajax通信実施

	var settings = {
		dataType: "json",
		success: function(json){

			//件数を表示
			$(FormID+'.search_result_hit').html(json.p_hit_num + '件');
//			searchTour.set_hit_num(json.p_hit_num);

			if(json.ErrMes){
				//エラー

			}else{

				if(json.p_bus_boarding_name == "")
				{
					// 選択不可にする
					$(FormID+"#modal_menu_bus_boarding a").addClass("disabled");
				}
				else
				{
					$(FormID+"#modal_menu_bus_boarding a").removeClass("disabled");
				}
			}
		}
	}
	searchTour.ajaxProcess(settings);

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

	// 初期化
	// 再検索画面の検索ボタン
	if($(FormID+"#search_detail_flag").val())
	{
		$(FormID+".all-delete").css("margin-bottom","60px");
		$(FormID+".fixed-footer").removeClass("colorposition");
		$(FormID+".fixed-footer").addClass("color");
	}
	else
	{
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
	if ( ($(window).height() + $(window).scrollTop()) < $('#tab_freePlan').offset().top) {
		FormID = '.tour ';
	} else {
		FormID = '.free_plan ';
	}
}



// ↑↑↑↑↑↑↑  モーダルビューで共通で使用するfunction群  ↑↑↑↑↑↑↑ //
