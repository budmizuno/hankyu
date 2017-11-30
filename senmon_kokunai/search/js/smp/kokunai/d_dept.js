/**
 * 国内
 * 出発地
 */

// 外注環境なら
if(location.host == 'oflex-659-1.kagoya.net')
{
	var twoenty_five_kyoten =
		[
		 // 25発地,何番目の要素か
		 ['北海道',[0,1,2,3,4,5,6,7,8,9,10,11,12]],
		 ['青森',13],
		 ['東北',[14,15,16,17,18]],
		 ['関東',[22,23,24,25,26,27,29]],
		 ['北関東',[19,20,21]],
		 ['新潟',28],
		 ['長野',30],
		 ['名古屋',[32,33,34]],
		 ['富山',35],
		 ['石川・福井',[37,36]],
		 ['静岡',31],
		 ['関西',[38,39,40,41,42,43]],
		 ['岡山',46],
		 ['山陰',[45,48]],
		 ['広島',49],
		 ['山口',47],
		 ['香川・徳島',[52,53]],
		 ['松山',50],
		 ['高知',51],
		 ['福岡',[54,55,58]],
		 ['長崎',56],
		 ['熊本',59],
		 ['大分',57],
		 ['宮崎',60],
		 ['鹿児島',61],
		 ['沖縄',62],
		 ];
}
else
{
	var twoenty_five_kyoten =
		[
		 // 25発地,何番目の要素か
		 ['北海道',[0,1,2,3,4,5,6,7,8,9,10,11,12]],
		 ['青森',13],
		 ['東北',[14,15,16,17,18]],
		 ['関東',[22,23,24,25,26,27,29]],
		 ['北関東',[19,20,21]],
		 ['新潟',28],
		 ['長野',30],
		 ['名古屋',[32,33,34]],
		 ['富山',35],
		 ['石川・福井',[37,36]],
		 ['静岡',31],
		 ['関西',[38,39,40,41,42,43]],
		 ['岡山',45],
		 ['山陰',[44,47]],
		 ['広島',48],
		 ['山口',46],
		 ['香川・徳島',[51,52]],
		 ['松山',49],
		 ['高知',50],
		 ['福岡',[53,54,57]],
		 ['長崎',55],
		 ['熊本',58],
		 ['大分',56],
		 ['宮崎',59],
		 ['鹿児島',60],
		 ['沖縄',61],
		];
}

//親（複数の子）の子の要素数の配列
var childrenNum = [];

// 読込後に実行
window.onload = function() {

	// カテゴリトップなら
	if(!$(FormID+"#search_detail_flag").val())
	{
		// カテゴリトップの検索なら(tyo.php,osa.php...)
		if($(FormID+"#p_hatsu_name_category").val() != '' && $(FormID+"#p_hatsu_category").val() != '')
		{
			html = '<li><div><input type="button" class="del_hatsu" value="削除" data-value="'+ $(FormID+"#p_hatsu_category").val() +'"><a class="decided_link" href="#modal_hatsu"><span class="decided_text" href="#modal_hatsu">' + $(FormID+"#p_hatsu_name_category").val()+ '発</span></a></div></li>';

			//表示
			$(FormID+'.search_hatsu #decided_contents_hatsu').html(html);

			var req_para_name = getReqParamHatsu();
			$(FormID+"#" + req_para_name).val($(FormID+"#p_hatsu_category").val());

			$(FormID+"#modal_menu_hatsu").hide();
			$(FormID+"#add_contents_hatsu").show();
			$(FormID+"#add_hatsu").show();

			// フリープラン側の初期表示
			// 表示
			$('.free_plan .search_hatsu #decided_contents_hatsu').html(html);
			var req_para_name = getReqParamHatsu();
			$(".free_plan #" + req_para_name).val($(".free_plan #p_hatsu_category").val());
			$(".free_plan #modal_menu_hatsu").hide();
			$(".free_plan #add_contents_hatsu").show();
			$(".free_plan #add_hatsu").show();

			checkFooterSearch();
		}
		// index.phpなら
		else
		{
			// BOTのとき
			if (isBot()) {

				var repeat;
				// 通信環境などにより取得できない場合があるのでループさせる
				repeat = setInterval(function() {
	   				if (typeof $.ajaxSingle == "function") {
						// ツアー・フリープランの件数が取れたら
						if(($('.tour .search_result_hit').html() != "" && $('.free_plan .search_result_hit').html() != "") &&
							$('.tour .search_result_hit').html() != $('.free_plan .search_result_hit').html()){
							// ストップ
							clearInterval(repeat);
						}
						if ($('.tour .search_result_hit').html() == "" || $('.tour .search_result_hit').html() == $('.free_plan .search_result_hit').html()) {
							FormID = '.tour ';
							getHitNum();
						}
						// 時間をおく
						setTimeout(function() {
							if ($('.free_plan .search_result_hit').html() == "" || $('.tour .search_result_hit').html() == $('.free_plan .search_result_hit').html()) {
								// フリープランの件数も求める
								FormID = '.free_plan ';
								// 件数取得
								getHitNum();
							}
						}, 1000);
	   				}
				}, 2000);
			}


			// 発地連動
			if(getp_hatsu() != '')
			{
//				getDept(false);
			}
		}
	}
}

$(function(){

	// 親（子が複数）拠点チェックしたら
	$(this).on('click','.open_ktn', function(e){
		var req_para_name = getReqParamHatsu();

		// どの親拠点か
		var sel_ktn = $(this).data("ktn");

		// 表示している時
		if($(FormID+"#ktn_" + sel_ktn).css('display') == 'block'){

			if($(this).is(':checked'))
			{
				$(this).prop('checked',false);

				// 子にチェックが付いているならすべてチェック外す
				$.each($(FormID+"#ktn_" + sel_ktn + " input:checked"),function(index,val){

					// activeを外して背景色を戻す
					$(FormID+"#"+val.id).closest("span").removeClass("active");
					$(FormID+"#"+val.id).prop('checked',false);

				});

				//　トグルを閉じる
				$(FormID+"#ktn_" + sel_ktn).slideToggle("fast");
			}
			else
			{
				// activeを外して背景色を戻す
				$(this).closest("span").toggleClass("active");

				// 子にチェックが付いているならすべてチェック外す
				$.each($(FormID+"#ktn_" + sel_ktn + " input:checked"),function(index,val){

					// activeを外して背景色を戻す
					$(FormID+"#"+val.id).closest("span").removeClass("active");
					$(FormID+"#"+val.id).prop('checked',false);

				});

				//　トグルを閉じる
				$(FormID+"#ktn_" + sel_ktn).slideToggle("fast");
			}
		}
		else
		{
			// activeをつけて背景色をつける
			$(this).closest("span").toggleClass("active");

			// 子にチェックをすべてつける
			$.each($(FormID+"#ktn_" + sel_ktn + " input"),function(index,val){

				// disabledでないなら
				if(!$(FormID+"#"+val.id).is(':disabled'))
				{
					// activeを外して背景色を戻す
					$(FormID+"#"+val.id).closest("span").addClass("active");
					$(FormID+"#"+val.id).prop('checked',true);
				}

			});

			//　トグルを開く
			$(FormID+"#ktn_" + sel_ktn).slideToggle("fast");

		}

		// チェックボックス集計してヒット件数取得
		getCheckBoxHitCount();

	});

	//　親（単数）拠点チェックしたら
	$(this).on('click','.single_parent', function(e){
		var req_para_name = getReqParamHatsu();

		// activeをつける。外して背景色をつける/戻す
		$(this).closest("span").toggleClass("active");

		// チェックボックス集計してヒット件数取得
		getCheckBoxHitCount();
	});


	//　子拠点チェックしたら
	$(this).on('click','.children', function(e){

		// activeをつける。外して背景色をつける/戻す
		$(this).closest("span").toggleClass("active");

		var id = $(this).closest("ul").attr("id");
		var array = id.split("_");
		var number = array[1];
		var now = 0;
		// 共通の子のチェックを調べる
		$.each($(FormID+"#" + id + " input"),function(index,val){

			// disabledでないなら
			if(!$(FormID+"#"+val.id).is(':disabled'))
			{
				// チェックついていないなら
				if(!$(FormID+"#"+val.id).is(':checked'))
				{
					// 親のチャックとavtiveを外す
					$(FormID+"#p_hatsu_sub_cb"+number).prop('checked',false);
					$(FormID+"#p_hatsu_sub_cb"+number).closest("span").removeClass("active");
					now++;

					// 子の全てのチェックを外したら
					if(now == childrenNum[number])
					{
						//　トグルを閉じる
						$(FormID+"#ktn_" + number).slideToggle("fast");
					}
				}
			}
		});

		// チェックボックス集計してヒット件数取得
		getCheckBoxHitCount();

	});

	// 確定ボタンをクリックしたら
	$(document).on("click",".decide-btn.hatsu", function() {

		var req_para_name = getReqParamHatsu();

		//チェックしてる配列作成
		var chkObj =[];
		var chkObj_dummy = [];
		var chkObj_text = [];

		var index = 0;
		// チェックしているなら
		$(FormID+'[name="' + req_para_name + '_cb"]:checked').each(function(){
			// 出発地の値

			var value = $(this).data("value");

			// 親（単数）or子なら
			if(typeof value == "number")
			{
				// 配列が空でないなら
				if(0 < chkObj_dummy.length)
				{
					// 親の配列に含まれていないなら
					if( chkObj_dummy[index-1].indexOf(value) == -1)
					{
						chkObj.push(value);
						chkObj_text.push($(this).parent().text());
					}
				}
				else
				{
					chkObj.push(value);
					chkObj_text.push($(this).parent().text());

					if($(this).attr('class') == 'children')
					{
						var id = $(this).closest("ul").attr("id");
						var array = [];
						array = id.split('_');
						if(value == $(FormID+"#p_hatsu_sub_cb"+ array[1]).data("value"))
						{
							chkObj.pop();
							chkObj_text.pop();
						}
					}
				}
			}
			else
			{
				// 親（複数）なら
				if( value.indexOf(',') != -1)
				{
					chkObj.push(value);
					// 出発地の名称
					chkObj_text.push($(this).parent().text());
					// 親（複数）だけの配列
					chkObj_dummy.push(value);
					index++;
				}
			}
		});


		// 追加するなら
		if($(FormID+"#p_hatsu_add_flag").val() != "")
		{
			var old_html = $(FormID+"#decided_contents_hatsu").html();

			var old_text = "";
			var plus_check = true;
			// 追加するときの条件があるので面倒。
			$.each($(FormID+"#decided_contents_hatsu input"),function(index,val){

				plus_check = true;
				for(var i=0;i<chkObj.length;i++)
				{
					var tchk = "";
					if(typeof chkObj[i] == "number")
					{
						tchk = chkObj[i].toString(10);
					}
					else
					{
						tchk = chkObj[i];
					}

					var datavalue = "";
					if(typeof $(this).data("value") == "number")
					{
						datavalue = $(this).data("value").toString(10);
					}
					else
					{
						datavalue = $(this).data("value");
					}

					// 同じものを追加するなら、削除
					if(chkObj[i] == $(this).data("value"))
					{
						chkObj[i] = 'DELETE_' + chkObj[i];
					}
					// 既存と追加するものが被るなら
					else if(tchk.indexOf(datavalue) != -1 || datavalue.indexOf(tchk) != -1)
					{
						// 既存を削除
						var delete_html = $(this).closest("li").html();
						delete_html = "<li>" + delete_html + "</li>";
						old_html = old_html.replace(delete_html,"");
						plus_check = false;
					}
				}

				if(plus_check)
				{
					old_text = ( old_text == "" ) ? $(this).data("value") : old_text + ',' + $(this).data("value");
				}
			});

			var new_chkObj = [];
			var new_chkObj_text =[];

			// 数値もしくは'DELETE_'がついていないなら、新しい配列に入れる。
			for(var i=0;i<chkObj.length;i++)
			{
				if(typeof chkObj[i] == "number")
				{
					new_chkObj.push(chkObj[i]);
					new_chkObj_text.push(chkObj_text[i]);
				}
				else if(chkObj[i].indexOf('DELETE_') == -1)
				{
					new_chkObj.push(chkObj[i]);
					new_chkObj_text.push(chkObj_text[i]);
				}
			}

			var html = '';
			var new_text = "";
			// 選んだ出発地の表記のためにhtmlをループ
			for(var i=0;i<new_chkObj.length;i++)
			{
				html += '<li><div><input type="button" class="del_hatsu" value="削除" data-value="'+ new_chkObj[i] +'"><a class="decided_link" href="#modal_hatsu"><span class="decided_text" href="#modal_hatsu">'+ new_chkObj_text[i].substring(0,new_chkObj_text[i].indexOf("[")) +'</span></a></div></li>';

				new_text = ( new_text == "" ) ? new_chkObj[i] : new_text + ',' + new_chkObj[i];
			}

			html = old_html + html;

			var value = "";
			if(new_text == "")
			{
				value = old_text;
			}
			else if(old_text == "")
			{
				value = new_text;
			}
			else
			{
				value = old_text + ',' + new_text;
			}

			$(FormID+"#p_hatsu_sub").val(value);
			$(FormID+"#p_hatsu_add_flag").val("");
		}
		else
		{
			var html = '';
			// 選んだ出発地の表記のためにhtmlをループ
			for(var i=0;i<chkObj.length;i++)
			{
				html += '<li><div><input type="button" class="del_hatsu" value="削除" data-value="'+ chkObj[i] +'"><a class="decided_link" href="#modal_hatsu"><span class="decided_text" href="#modal_hatsu">'+ chkObj_text[i].substring(0,chkObj_text[i].indexOf("[")) +'</span></a></div></li>';
			}
		}

		// 確定ボタンの共通Action
		decideBtnActionAdd('hatsu',html,true);

	});

	// 出発地の削除ボタンを押したらremove
	$('.search-box').on("click",".del_hatsu", function() {
		var req_para_name = getReqParamHatsu();

		// 削除ボタンのクリアアクションの例外
		deleteButtonActionException('hatsu',req_para_name,this);

	});



});

// モーダルビューの閉じるボタン、選び直しなどのクリア処理
function clearHatsu()
{
	var req_para_name = getReqParamHatsu();

	// クリアの共通Action
	clearAction('hatsu',req_para_name,true,true);
}

//チェックボックスを処理してヒット件数取得
function getCheckBoxHitCount()
{
	var req_para_name = getReqParamHatsu();

	//チェックしてる配列作成
	var chkObj =[];
	var hatsu_str = '';

	$(FormID+'[name="' + req_para_name + '_cb"]:checked').each(function(){
		// 親なら
		if($(this).hasClass("open_ktn")) return true;

		chkObj.push($(this).data("value"));
	});

	hatsu_str = chkObj.join(',');

	// チェックした際の共通アクション
	checkboxAction('hatsu',req_para_name,hatsu_str);

}


// 通信
function getDept(ptn){

	var getListObj;
	var options = {
		formObj:'#searchTour',
		kind:"Detail",
		p_data_kind:1,
		p_rtn_data:'p_hatsu_name'
	}
	searchTour.requestProcess(options);	//Ajax通信実施

	var settings = {
		dataType: "json",
		success: function(json){
			//応答結果を編集
			if(json.ErrMes){
				//エラー
			}else{

				// 外注環境では、子発地に神戸がないため無理やり入れる。共通ファイルの問題
				if(location.host == 'oflex-659-1.kagoya.net')
				{
					var kobe = { check:false,facet:1,key:143,name:'神戸' };
					json.p_hatsu_name.osa.push(kobe);
				}

				if(ptn == true)
				{
					// 出発地表示
					$(FormID+'.search_result_hit').html(json.p_hit_num + '件');
					dispDesptListInit(json.p_hatsu_name);
				}
				else
				{
					// 発地連動の初期値
					dispDespInitIP(json.p_hatsu_name);
				}

			}
		}
	}
	searchTour.ajaxProcess(settings);
}

// 内外で異なるリクエストパラメータを返す
function getReqParamHatsu(){

	var naigai = $(FormID+"#MyNaigai").val();
	var req_para_name = '';
	if(naigai == 'i'){
		req_para_name = 'p_hatsu';
	}else{
		req_para_name = 'p_hatsu_sub';
	}
	return req_para_name;
}

//発地連動の初期値の選択出発地
function dispDespInitIP(hatsuAry)
{
	var oyaHatsuName = getp_hatsu();
	var html = '';
	var all_ckflg = true;
	var req_para_name = getReqParamHatsu();
	var hatsuOneArray = [];

	$.each(hatsuAry,function(index,val){
		$.each(val,function(index2,val2){
			// 多次元配列を扱いやすいように1次元配列にする。
			hatsuOneArray.push(val2)
		});
	});


	var hit = 0;
	var tatalValue = "";
	$.each(twoenty_five_kyoten,function(index,val){

		// 親だけなら（子なし）
		if(!$.isArray(val[1]))
		{
			// 親の名前が同じなら
			if(val[0] == oyaHatsuName)
			{
				html += '<li><div><input type="button" class="del_hatsu" value="削除" data-value="'+ hatsuOneArray[val[1]].key +'"><a class="decided_link" href="#modal_hatsu"><span class="decided_text" href="#modal_hatsu">' + val[0] + '発</span></a></div></li>';
				hit = hatsuOneArray[val[1]].facet;
				tatalValue = hatsuOneArray[val[1]].key;
				return true;
			}
		}
		// 親の子が複数なら
		else
		{
			// 親の名前が同じなら
			if(val[0] == oyaHatsuName)
			{
				// hatsuIdを求める
				$.each(val[1],function(index2,val2){

					if(0 < hatsuOneArray[val2].facet)
					{
						tatalValue = (tatalValue == "") ? hatsuOneArray[val2].key:tatalValue + ',' + hatsuOneArray[val2].key;

						hit += hatsuOneArray[val2].facet;
					}

				});

				html += '<li><div><input type="button" class="del_hatsu" value="削除" data-value="'+ tatalValue +'"><a class="decided_link" href="#modal_hatsu"><span class="decided_text" href="#modal_hatsu">' + val[0]+ '発</span></a></div></li>';
				return true;
			}
		}
	});

	if (tatalValue != "") {
		// ここで件数を設定
		$(FormID+'.search_result_hit').html(hit + '件');

		//表示
		$(FormID+'.search_hatsu #decided_contents_hatsu').html(html);

		var req_para_name = getReqParamHatsu();
		$(FormID+"#" + req_para_name).val(tatalValue);

		$(FormID+"#modal_menu_hatsu").hide();
		$(FormID+"#add_contents_hatsu").show();
		$(FormID+"#add_hatsu").show();

		// フリープラン側の初期値
		// ここで件数を設定
//		$('.free_plan .search_result_hit').html(hit + '件');
		//表示
		$('.free_plan .search_hatsu #decided_contents_hatsu').html(html);
		$(".free_plan #" + req_para_name).val(tatalValue);
		$(".free_plan #modal_menu_hatsu").hide();
		$(".free_plan #add_contents_hatsu").show();
		$(".free_plan #add_hatsu").show();

	}

	checkFooterSearch();

}

//　モーダルビューの初期出発地表示
function dispDesptListInit(hatsuAry)
{
	var html = '<ul>';
	var all_ckflg = true;
	var req_para_name = getReqParamHatsu();
	var hatsuOneArray = [];

	$.each(hatsuAry,function(index,val){
		$.each(val,function(index2,val2){
			// 多次元配列を扱いやすいように1次元配列にする。
			hatsuOneArray.push(val2)
		});
	});



	var sel_ktn = [];
	$.each(twoenty_five_kyoten,function(index,val){

		// 親だけなら（子なし）
		if(!$.isArray(val[1]))
		{
			html += '<li>';

			var parentName = "";
			// 名称に「県が」含まれているなら
			if(hatsuOneArray[val[1]].name.indexOf("県") != -1)
			{
				parentName = hatsuOneArray[val[1]].name.replace('県','');
			}
			else
			{
				parentName = hatsuOneArray[val[1]].name;
			}

			if(hatsuOneArray[val[1]].check == true)
			{
				html += '<span class="active"><label for="' + req_para_name + '_cb' + hatsuOneArray[val[1]].key + '"><input type="checkbox" class="single_parent" id="' + req_para_name + '_cb' + hatsuOneArray[val[1]].key + '" name="' + req_para_name + '_cb" data-value="' + hatsuOneArray[val[1]].key + '" checked>' + parentName + '発[' + hatsuOneArray[val[1]].facet + ']</label></span>';
			}
			else if(hatsuOneArray[val[1]].facet < 1)
			{
				html += '<span><label for="' + req_para_name + '_cb' + hatsuOneArray[val[1]].key + '" style="color:#9d9c9c"><input type="checkbox" class="single_parent" id="' + req_para_name + '_cb' + hatsuOneArray[val[1]].key + '" name="' + req_para_name + '_cb" data-value="' + hatsuOneArray[val[1]].key + '" disabled>' + parentName + '発[' + hatsuOneArray[val[1]].facet + ']</label></span>';
			}
			else
			{
				html += '<span><label for="' + req_para_name + '_cb' + hatsuOneArray[val[1]].key + '"><input type="checkbox" class="single_parent" id="' + req_para_name + '_cb' + hatsuOneArray[val[1]].key + '" name="' + req_para_name + '_cb" data-value="' + hatsuOneArray[val[1]].key + '">' + parentName + '発[' + hatsuOneArray[val[1]].facet + ']</label></span>';
			}
			html += '</li>';
		}
		// 親の子が複数なら
		else
		{
			var totalFacet = 0;
			var tatalValue = "";
			var totalCheck = 0;
			var totalCount = 0;
			// ファセットの合計値、チェック済みか確認するために前もってループ
			$.each(val[1],function(index2,val2){

				totalFacet += hatsuOneArray[val2].facet;

				if(hatsuOneArray[val2].check == true)
				{
					totalCheck++;
				}
				if(0 < hatsuOneArray[val2].facet)
				{
					totalCount++;

					tatalValue = ( tatalValue == "" ) ? hatsuOneArray[val2].key : tatalValue + ',' + hatsuOneArray[val2].key;
				}
			});

			childrenNum[index] = totalCount;

			// 親の発地
			html += '<li>';

			// 全ての子でチャックされているなら
			if(0 < totalCheck && totalCheck == totalCount)
			{

				if(totalFacet < 1)
				{
					html += '<span><label for="' + req_para_name + '_cb' + index + '" style="color:#9d9c9c"><input type="checkbox" class="open_ktn" id="' + req_para_name + '_cb' + index + '" name="' + req_para_name + '_cb" data-value="'+tatalValue+'" data-ktn="' + index + '" disabled>' + val[0] + '発[' + totalFacet + ']</label></span>';
				}
				else
				{
					html += '<span class="active"><label for="' + req_para_name + '_cb' + index + '"><input type="checkbox" class="open_ktn" id="' + req_para_name + '_cb' + index + '" name="' + req_para_name + '_cb" data-value="'+tatalValue+'" data-ktn="' + index + '" checked>' + val[0] + '発[' + totalFacet + ']</label></span>';
					sel_ktn.push(index);
				}
			}
			// 子がチェックされているなら
			else if(0 < totalCheck)
			{
				if(totalFacet < 1)
				{
					html += '<span><label for="' + req_para_name + '_cb' + index + '" style="color:#9d9c9c"><input type="checkbox" class="open_ktn" id="' + req_para_name + '_cb' + index + '" name="' + req_para_name + '_cb" data-value="'+tatalValue+'" data-ktn="' + index + '" disabled>' + val[0] + '発[' + totalFacet + ']</label></span>';
				}
				else
				{
					html += '<span><label for="' + req_para_name + '_cb' + index + '"><input type="checkbox" class="open_ktn" id="' + req_para_name + '_cb' + index + '" name="' + req_para_name + '_cb" data-value="'+tatalValue+'" data-ktn="' + index + '">' + val[0] + '発[' + totalFacet + ']</label></span>';
					sel_ktn.push(index);
				}
			}
			else
			{
				if(totalFacet < 1)
				{
					html += '<span><label for="' + req_para_name + '_cb' + index + '" style="color:#9d9c9c"><input type="checkbox" class="open_ktn" id="' + req_para_name + '_cb' + index + '" name="' + req_para_name + '_cb" data-value="'+tatalValue+'" data-ktn="' + index + '" disabled>' + val[0] + '発[' + totalFacet + ']</label></span>';
				}
				else
				{
					html += '<span><label for="' + req_para_name + '_cb' + index + '"><input type="checkbox" class="open_ktn" id="' + req_para_name + '_cb' + index + '" name="' + req_para_name + '_cb" data-value="'+tatalValue+'" data-ktn="' + index + '">' + val[0] + '発[' + totalFacet + ']</label></span>';
				}
			}

			html += '<ul id="ktn_' + index + '" style="display:none">';

			$.each(val[1],function(index2,val2){

				html += '<li>';

				var childName = "";
				// 名称に「県が」含まれているなら
				if(hatsuOneArray[val2].name.indexOf("県") != -1)
				{
					childName = hatsuOneArray[val2].name.replace('県','');
				}
				else
				{
					childName = hatsuOneArray[val2].name;
				}

				// ファセットなしなら
				if(hatsuOneArray[val2].facet < 1)
				{
					html += '<span><label for="' + req_para_name + '_cb' + hatsuOneArray[val2].key + '" style="color:#9d9c9c"><input type="checkbox" class="children" id="' + req_para_name + '_cb' + hatsuOneArray[val2].key + '" name="' + req_para_name + '_cb" data-value="' + hatsuOneArray[val2].key + '" disabled>' + childName + '発[' + hatsuOneArray[val2].facet + ']</label></span>';
				}
				// チェックされているなら
				else if(hatsuOneArray[val2].check == true)
				{
					html += '<span class="active"><label for="' + req_para_name + '_cb' + hatsuOneArray[val2].key + '"><input type="checkbox" class="children" id="' + req_para_name + '_cb' + hatsuOneArray[val2].key + '" name="' + req_para_name + '_cb" data-value="' + hatsuOneArray[val2].key + '" checked>' + childName + '発[' + hatsuOneArray[val2].facet + ']</label></span>';
				}
				// 通常
				else
				{
					html += '<span><label for="' + req_para_name + '_cb' + hatsuOneArray[val2].key + '"><input type="checkbox" class="children" id="' + req_para_name + '_cb' + hatsuOneArray[val2].key + '" name="' + req_para_name + '_cb" data-value="' + hatsuOneArray[val2].key + '">' + childName + '発[' + hatsuOneArray[val2].facet + ']</label></span>';
				}

				html += '</li>';
			});

			html += '</ul>';
			html += '</li>';
		}

	});

	html += '</ul>';

	//表示
	$(FormID+'#modal_hatsu .area-list').html(html);

	// チェックを引き継いだ時に親のトグルを開く
	if(0 < sel_ktn.length)
	{
		for(var i=0;i<sel_ktn.length;i++)
		{
			//　トグルを開く
			$(FormID+"#ktn_" + sel_ktn[i]).slideToggle("fast");
		}
	}
}



//クッキーから都道府県IDを取得して出発地ID(p_hatsu)を取得
function getp_hatsu()
{
	// クッキーから取得
	var cookieValue ='';

	if($.cookie('HK_CBKyoten')){
		cookieValue =$.cookie('HK_CBKyoten');
	}
	else if($.cookie('HK_MyState')){
		cookieValue =$.cookie('HK_MyState');
	}
	else if($.cookie('HK_AutoState')){
		cookieValue =$.cookie('HK_AutoState');
	}


//	cookieValue = 13;

	var hatsuString = '';

	if( cookieValue == '')
	{
		return hatsuString;
	}

	return getOyaHatsuName(cookieValue);

}

// ヘッダーの出発地との連動のために都道府県で発地変換
function getOyaHatsuName(prefectureId)
{
	var hatsuArray = [];

 	hatsuArray = [
  		           '北海道',			// 北海道
  		           '青森',			// 青森県
  		           '東北',			// 岩手県
  		           '東北',			// 宮城県
  		           '東北',			// 秋田県
  		           '東北',			// 山形県
  		           '東北',			// 福島県
  		           '北関東',			// 茨城県
  		           '北関東',			// 栃木県
  		           '北関東',			// 群馬県
  		           '関東',			// 埼玉県
  		           '関東',			// 千葉県
  		           '関東',			// 東京都
  		           '関東',			// 神奈川県
  		           '新潟',			// 新潟県
  		           '富山',			// 富山県
  		           '石川・福井',		// 石川県
  		           '石川・福井',		// 福井県
  		           '関東',			// 山梨県
  		           '長野',			// 長野県
  		           '名古屋',			// 岐阜県
  		           '静岡',			// 静岡県
  		           '名古屋',			// 愛知県
  		           '名古屋',			// 三重県
  		           '関西',			// 滋賀県
  		           '関西',			// 京都府
  		           '関西',			// 大阪府
  		           '関西',			// 兵庫県
  		           '関西',			// 奈良県
  		           '関西',			// 和歌山県
  		           '山陰',			// 鳥取県
  		           '山陰',			// 島根県
  		           '岡山',			// 岡山県
  		           '広島',			// 広島県
  		           '山口',			// 山口県
  		           '香川・徳島',		// 徳島県
  		           '香川・徳島',		// 香川県
  		           '松山',			// 愛媛県
  		           '高知',			// 高知県
  		           '福岡',			// 福岡県
  		           '福岡',	 		// 佐賀県
  		           '長崎',			// 長崎県
  		           '熊本',			// 熊本県
  		           '大分',			// 大分県
  		           '宮崎',			// 宮崎県
  		           '鹿児島',			// 鹿児島県
  		           '沖縄',	    	// 沖縄県
  		         ];

	return hatsuArray[prefectureId - 1];
}
