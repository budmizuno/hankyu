var searchTour = {

	formObj: '',
	partsName: '',
	jsonData:'',
	searchReq:{}
	// XXX件見つかりましたの表示
	,set_hit_num: function(p_page,hit_count){

		$(FormID+'.search_result_hit').html(hit_count + '件');
	}
	//Ajax通信を行う。POST
	,ajaxProcess: function(settings){
		var params = searchReq;
		var defSetting = {
			url: '/attending/senmon_kaigai/search/ajax_d_smp.php',
			data:params,
			dataType: "html",
			cache: false,
			type: "POST",
			error: function(XMLHttpRequest, textStatus, errorThrown){
				//_errorAct(XMLHttpRequest, textStatus, errorThrown);
			},
			success: function(json){
			}
		}
		//設定
		settings = $.extend( defSetting, settings );

		//ajax 連打対応版
		$.ajaxSingle(settings);
		return this;
	}
	//リクエストパラメータの作成
	,requestProcess: function(options){
		var paramAct = function(valObj){
			valAct = new Array();

			valAct['p_hatsu_sub']= function(value){
				return this;
			}
			valAct['p_dep_date']= function(value){
				return this;
			}
			if(typeof valAct[valObj.name] == 'function'){
				valAct[valObj.name](valObj.value);
			}
			return valObj;
		};
		//formから取得
		var param = $(FormID+options.formObj).serializeArray();
		var req = {};
		var cnt = 0;
		for(i in param){
			//パラメータ毎の個別処理
			valObj = paramAct(param[i]);

			if(typeof(req[param[i].name]) == "undefined"){
				req[param[i].name] = "";
			}
			req[param[i].name] += param[i].value+',';
		}
		for(i in req){
			var trimVal = req[i];
			req[i] = trimVal.replace(/\,+$/, "");
		}
		if( req["p_sort"] == ""){
			req["p_sort"] = 1;
		}

		// 南太平洋のページなら
		if(path_name == '/s-pacific' || path_name == '/s-pacific/'){
			req["p_mokuteki"] = req["p_mokuteki"].replace( /^FOC$/ , "FOC-PF-,FOC-NC-,FOC-FJ-,FOC-PG-,FOC-VU-");
			req["p_mokuteki"] = req["p_mokuteki"].replace( /^FOC\,/ , "FOC-PF-,FOC-NC-,FOC-FJ-,FOC-PG-,FOC-VU-,");
			req["p_mokuteki"] = req["p_mokuteki"].replace( /\,FOC\,/ , ",FOC-PF-,FOC-NC-,FOC-FJ-,FOC-PG-,FOC-VU-,");
			req["p_mokuteki"] = req["p_mokuteki"].replace( /\,FOC$/ , ",FOC-PF-,FOC-NC-,FOC-FJ-,FOC-PG-,FOC-VU-");
		}
		// オセアニアのページなら
		else if (path_name == '/oceania' || path_name == '/oceania/') {
			req["p_mokuteki"] = req["p_mokuteki"].replace( /^FOC$/ , "FOC-AU-,FOC-NZ-");
			req["p_mokuteki"] = req["p_mokuteki"].replace( /^FOC\,/ , "FOC-AU-,FOC-NZ-,");
			req["p_mokuteki"] = req["p_mokuteki"].replace( /\,FOC\,/ , ",FOC-AU-,FOC-NZ-,");
			req["p_mokuteki"] = req["p_mokuteki"].replace( /\,FOC$/ , ",FOC-AU-,FOC-NZ-");
		}

		// 検索項目を変更したなら
		if(FormID == ".tour "){
			var init_num = parseInt($(".tour #tour_init_flag").val(), 10);
			if(isNaN (init_num)) init_num = 0;
			init_num++;
			$(".tour #tour_init_flag").val(init_num);
		}

		// フリープランの場合をここで値を入れる
		if($(FormID+"#free_flag").val() != "")
		{
			if(typeof req['p_bunrui'] == 'undefined' || req['p_bunrui'] == ''){
				req['p_bunrui'] = '030';
			}else if( req['p_bunrui'].indexOf('030') == -1){
				req['p_bunrui'] += ',030';
			}
		}

		// 検索結果画面以外ではフリーワードは無視
		if(options["kind"] != "GetList")
		{
			req['p_free_word'] = '';
		}

		searchReq = $.extend(req ,options );
		return this;


	}
	//Ajax通信を行う。
	,getAjaxRequest: function(url){

		if (window.ActiveXObject) {
			req = new ActiveXObject("Microsoft.XMLHTTP");
			if (req) {
				req.open("GET", url, false);
				req.send();
			} else {
				alert('通信ができませんでした。');
			}
		} else if (window.XMLHttpRequest) {
			req = new XMLHttpRequest();
			req.open("GET", url, false);
			req.send(null);
		}
		return req;
	}
	//応答パラメータから要求パラメータを取得
	,getReqParam: function(respPara){
		var reqPara;
		switch(respPara){
			case 'p_carr_cn':
				reqPara = 'p_carr';
				break;
			case 'p_dep_airport_name':
				reqPara = 'p_dep_airport_code';
				break;
			case 'p_bus_boarding_name':
				reqPara = 'p_bus_boarding_code';
				break;
			case 'p_hotel_name':
				reqPara = 'p_hotel_code';
				break;
			default :
				reqPara = respPara;
				break;
		}
		return reqPara;
	}
	//要素数カウント
	,count: function(obj){
		var cnt = 0;
		for (var key in obj) {
			cnt++;
		}
		return cnt;
	}
	//トリム
	,trim: function(str){
    	return str.replace(/^,+|,+$/g,'');
	}
	// カンマで分割し配列に格納
	,parseComma: function(str){
		var resArray = str.split(",");
		return resArray;
	}
	,unique: function(array){
		var storage = {};
		var uniqueArray = [];
		var i,value;
		for ( i=0; i<array.length; i++) {
			value = array[i];
		if (!(value in storage)) {
			storage[value] = true;
			uniqueArray.push(value);
		}
	}
	return uniqueArray;
	}
	//選択済みのパラメータをまとめる
	,get_mokuteki_select_all: function(exd_no){
		var mokuteki = '';
		//3つまとめたパラメータをセットする
		var mokuteki_ary = [];
		var k=0;
		var set_mokuteki = '';
		for(var i = 1; i < 4; i++){
			//除外番号があればその目的地は除く
			if(exd_no && exd_no == i){
				continue;
			}
			var mokuteki = $('#selected_mokuteki' + i).val();
			if(mokuteki){
				mokuteki_ary[k] = mokuteki;
				k++;
			}
		}
		if(k == 1){
			set_mokuteki = mokuteki_ary[0];
		}else if(k > 1){
			set_mokuteki = mokuteki_ary.join(',');
		}

		return set_mokuteki;
	}

}

//◆件数のみ取得&反映
function getHitNum(pageID){

	var getListObj;
	var options = {
		formObj:'#searchTour',
		kind:"GetHitNum",
		p_data_kind:1,
		p_rtn_data:'p_brand'
	}
	searchTour.requestProcess(options);	//Ajax通信実施

	var settings = {
		dataType: "json",
		success: function(json){
			//応答結果を編集
			//件数を表示 ページID,件数


			searchTour.set_hit_num(pageID,json.p_hit_num);
			if(json.ErrMes){

				//エラー
			}else{

			}
		}
	}
	searchTour.ajaxProcess(settings);
}

//オープンウィンドウ
function openRequestW(url, name){
	window.open(url, name, "resizable=yes,scrollbars=yes,status=yes");
}
