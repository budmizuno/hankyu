/**
 * 国内
 * 目的地
 */
 /*---------------------------------
  ページの方面、国、都市タイプ
 ---------------------------------*/
const  CATEGORY_TYPE_DEST = 1;
const  CATEGORY_TYPE_COUNTRY = 2;
const  CATEGORY_TYPE_CITY = 3;
var category_type;
var def_mokuteki;
var def_mokuteki_dest;
var def_mokuteki_country;
var prefecture_name_ary = [];
// 地域の配列
var region_name_ary = [];
$(function(){
	category_type = $(FormID+"#p_category_page").val();

	// 目的地のデフォルト設定
	if(category_type != '' && $(FormID+"#def_mokuteki").val() != '')
	{
		var html = '';
		def_mokuteki = '';
		// 方面ページなら
		if(category_type == CATEGORY_TYPE_DEST){
			def_mokuteki = $(FormID+"#def_mokuteki").val().substr(0,2);
			html = '<li><div><input type="button" class="del_destination" value="削除" data-value="'+ def_mokuteki +'"><a class="decided_link" href="#modal_destination"><span class="decided_text" href="#modal_destination">'+ $(FormID+"#def_dest_name").val() +'</span></a></div></li>';
		// 国ページなら
		}else if(category_type == CATEGORY_TYPE_COUNTRY){

			def_mokuteki = $(FormID+"#def_mokuteki").val();
			html = '<li><div><input type="button" class="del_destination" value="削除" data-value="'+ def_mokuteki +'"><a class="decided_link" href="#modal_destination"><span class="decided_text" href="#modal_destination">'+ $(FormID+"#def_country_name").val() +'</span></a></div></li>';
		// 都市ページなら
		}else{
			def_mokuteki = $(FormID+"#def_mokuteki").val();

			//カスタムパラメータ
			var options = {
				formObj:'#searchTour',
				kind:"Detail",
				p_data_kind:1,
				p_rtn_data:'p_dest_name,p_prefecture_name,p_region_cn'
			}
			searchTour.requestProcess(options);	//Ajax通信実施
			var settings = {
				dataType: "json",
				success: function(json){
					//応答結果を編集
					if(json.ErrMes){
						//エラー
					}else{

						var return_item = initCheckCity(json);

						var item_value = def_mokuteki.split(",");

						if(0 < return_item.length){

							$.each(return_item,function(index,val){

								html += '<li><div><input type="button" class="del_destination" value="削除" data-value="'+ item_value[index] +'"><a class="decided_link" href="#modal_destination"><span class="decided_text" href="#modal_destination">'+ val +'</span></a></div></li>';
							});

							$(FormID+"#p_mokuteki").val(def_mokuteki);
							// 確定ボタンの共通Action
							param = 'destination';
							//表示
							$(FormID+'.search_'+param+' #decided_contents_'+param).html(html);
							// 各項目の表示・非表示
							$(FormID+"#modal_menu_"+param).hide();
							$(FormID+"#add_contents_"+param).show();
							$(FormID+"#add_"+param).show();
							$(FormID+".decide-btn").removeClass(param);
							$(FormID+"#add_destination").show();
							if($(FormID+"#free_flag").val() != "")
							{
								$(FormID+".destination-select").show();
							}
							// フリープランも実装
							FormID = '.free_plan ';
							$(FormID+"#p_mokuteki").val(def_mokuteki);
							// 確定ボタンの共通Action
							//表示
							$(FormID+'.search_'+param+' #decided_contents_'+param).html(html);
							// 各項目の表示・非表示
							$(FormID+"#modal_menu_"+param).hide();
							$(FormID+"#add_contents_"+param).show();
							$(FormID+"#add_"+param).show();
							$(FormID+".decide-btn").removeClass(param);
							$(FormID+"#add_destination").show();
							if($(FormID+"#free_flag").val() != "")
							{
								$(FormID+".destination-select").show();
							}
							// 一応戻す
							FormID = '.tour ';
						}
						else{
							$(".tour #p_mokuteki").val(def_mokuteki);
							$(".free_plan #p_mokuteki").val(def_mokuteki);
						}
					}
				}
			}
			searchTour.ajaxProcess(settings);
		}

		// 都市ページ以外
		if(category_type != CATEGORY_TYPE_CITY){
			$(FormID+"#p_mokuteki").val(def_mokuteki);
			// 確定ボタンの共通Action
			param = 'destination';
			//表示
			$(FormID+'.search_'+param+' #decided_contents_'+param).html(html);
			// 各項目の表示・非表示
			$(FormID+"#modal_menu_"+param).hide();
			$(FormID+"#add_contents_"+param).show();
			$(FormID+"#add_"+param).show();
			$(FormID+".decide-btn").removeClass(param);
			$(FormID+"#add_destination").show();
			if($(FormID+"#free_flag").val() != "")
			{
				$(FormID+".destination-select").show();
			}
			// フリープランも実装
			FormID = '.free_plan ';
			$(FormID+"#p_mokuteki").val(def_mokuteki);
			// 確定ボタンの共通Action
			//表示
			$(FormID+'.search_'+param+' #decided_contents_'+param).html(html);
			// 各項目の表示・非表示
			$(FormID+"#modal_menu_"+param).hide();
			$(FormID+"#add_contents_"+param).show();
			$(FormID+"#add_"+param).show();
			$(FormID+".decide-btn").removeClass(param);
			$(FormID+"#add_destination").show();
			if($(FormID+"#free_flag").val() != "")
			{
				$(FormID+".destination-select").show();
			}
			// 一応戻す
			FormID = '.tour ';
		}
	}
	// 方面チェックしたら
	$(this).on('click','.open_cp', function(e){
		// チェックボックスの数を調査
		var chkObj = countCheckBox();
		// 追加しているなら
		if($(FormID+"#p_mokuteki_add_flag").val() != "")
		{
			$.each($(FormID+"#decided_contents_destination input"),function(index,val){
				chkObj.push($(this).data("value"));
			});
		}
		if(4 <= chkObj.length)
		{
			$(this).prop("checked",false);
			alert("目的地の設定は3つまでです");
			return;
		}
		// どの方面か
		var sel_cp = $(this).data("cp");
		// 表示している時
		if($(FormID+"#cp_" + sel_cp).css('display') == 'block'){
			// activeを外して背景色を戻す
			$(this).closest("span").toggleClass("active");
			// 子にチェックが付いているならすべてチェック外す
			$.each($(FormID+"#cp_" + sel_cp + " input:checked"),function(index,val){
				// activeを外して背景色を戻す
				$(FormID+"#"+val.id).closest("span").toggleClass("active");
				$(FormID+"#"+val.id).prop('checked',false);
			});
			// 方面内のトグルを閉じる
			$.each(prefecture_name_ary,function(index,val){
				// 方面が一緒じゃなかったら
				if(sel_cp != val.parentKey) return true;
				$.each(region_name_ary,function(index2,val2){
					// 地域の都道府県が一緒じゃなかったら
					if(val.key != val2.parentKey) return true;
					// 表示されているなら
					if($(FormID+'#region_all_'+val.key).css('display') == 'block')
					{
						$(FormID+'#region_all_'+val.key).slideUp("fast");
					}
					// 存在しているなら
					if($(FormID+'#region_'+val.key).length)
					{
						if($(FormID+'#region_'+val.key).css('display') == 'block')
						{
							$(FormID+'#region_'+val.key).slideUp("fast");
						}
					}
				});
			});
			//　トグルを閉じる
			$(FormID+"#cp_" + sel_cp).slideToggle("fast");
		}
		else
		{
			// activeをつけて背景色をつける
			$(this).closest("span").toggleClass("active");
			//　トグルを開く
			$(FormID+"#cp_" + sel_cp).slideToggle("fast");
		}
		// チェックボックス集計してヒット件数取得
		getCheckBoxDestHitCount();
	});
	//　都道府県チェックしたら
	$(this).on('click','.check_country', function(e){
		// チェックボックスの数を調査
		var chkObj = countCheckBox();
		// 追加しているなら
		if($(FormID+"#p_mokuteki_add_flag").val() != "")
		{
			$.each($(FormID+"#decided_contents_destination input"),function(index,val){
				chkObj.push($(this).data("value"));
			});
		}
		if(4 <= chkObj.length)
		{
			$(this).prop("checked",false);
			alert("目的地の設定は3つまでです");
			return;
		}
		var countryId = $(this).data("cp");
		// activeをつける。外して背景色をつける/戻す
		$(this).closest("span").toggleClass("active");
		// チェックされているなら
		if($(this).prop('checked'))
		{
			$(FormID+'#region_all_'+countryId).slideDown("fast");
//			$('#region_'+countryId).slideDown("fast");
			// 地域のチェックを外す
			$.each($(FormID+"#region_" + countryId + " input:checked"),function(index,val){
				// activeを外して背景色を戻す
				$(FormID+"#"+val.id).closest("span").toggleClass("active");
				$(FormID+"#"+val.id).prop('checked',false);
			});
			$(FormID+"#region_all_"+ countryId+ ' a').text("観光地の一覧を開く");
			$(FormID+"#region_all_"+ countryId).toggleClass("active");
		}
		else
		{
			$(FormID+'#region_all_'+countryId).slideUp("fast");
			$(FormID+'#region_'+countryId).slideUp("fast");
			$(FormID+"#region_all_"+ countryId+ ' a').text("観光地の一覧を開く");
			$(FormID+"#region_all_"+ countryId).toggleClass("active");
		}
		getCheckBoxDestHitCount();
	});
	// 地域を開くボタンをクリックしたら
	$(this).on('click','.open_region', function(e){
		var countryId = $(this).data("value");
		// activeをつける/外す
		$(this).toggleClass("active");
		// activeを持っているなら
		if($(this).hasClass("active"))
		{
			$(this).text("観光地の一覧を閉じる");
			$(this).parent().toggleClass("active");
			// すでに地域リストがあるなら
			if($(FormID+'#region_'+countryId).length)
			{
				toggleControll($(FormID+'#region_'+countryId));
			}
			else
			{
				var html = '<div id="region_'+countryId+'">';
				// チェックした都道府県の地域のhtmlを生成
				$.each(region_name_ary,function(index,val){
					// 地域の都道府県が一緒じゃなかったら
					if(countryId != val.parentKey) return true;
					html += '<li>';
					// ファセットなしなら
					if(val.facet < 1)
					{
						html += '<span><label for="p_mokuteki_cp' + val.key + '" style="color:#9d9c9c"><input type="checkbox" class="check_city" id="p_mokuteki_cp' + val.key + '" name="p_mokuteki_cp" data-value="' + val.key + '" data-city="' + val.key + '" disabled>' + val.name + '[' + val.facet + ']</label></span>';
					}
					// チェックされているなら
					else if(val.check == true)
					{
						html += '<span class="active"><label for="p_mokuteki_cp' + val.key + '"><input type="checkbox" class="check_city" id="p_mokuteki_cp' + val.key + '" name="p_mokuteki_cp" data-value="' + val.key + '" data-city="' + val.key + '" checked>' + val.name + '[' + val.facet + ']</label></span>';
					}
					// 通常
					else
					{
						html += '<span><label for="p_mokuteki_cp' + val.key + '"><input type="checkbox" class="check_city" id="p_mokuteki_cp' + val.key + '" name="p_mokuteki_cp" data-value="' + val.key + '" data-city="' + val.key + '">' + val.name + '[' + val.facet + ']</label></span>';
					}
					html += '</li>';
				});
				html += '</div>';
				$(FormID+'#region_all_'+countryId+' a').after(html);
			}
		}
		else
		{
			$(this).text("観光地の一覧を開く");
			$(this).parent().toggleClass("active");
			toggleControll($(FormID+'#region_'+countryId));
		}
	});
	// 地域をクリックしたら
	$(this).on('click','.check_city', function(e){
		// チェックボックスの数を調査
		var chkObj = countCheckBox();
		// 追加しているなら
		if($(FormID+"#p_mokuteki_add_flag").val() != "")
		{
			$.each($(FormID+"#decided_contents_destination input"),function(index,val){
				chkObj.push($(this).data("value"));
			});
		}
		if(4 <= chkObj.length)
		{
			$(this).prop("checked",false);
			alert("目的地の設定は3つまでです");
			return;
		}
		var cityId = $(this).data("value");
		// activeをつける。外して背景色をつける/戻す
		$(this).closest("span").toggleClass("active");
		getCheckBoxDestHitCount();
	});
	// 再検索画面で目的種別（周遊orいずれかor指定のみ）
	$(this).on('click','.destination-select input', function(e){
		$(FormID+"#p_mokuteki_kind").val($(this).data("value"));
		checkFooterSearch();
	});
	// 確定ボタンをクリックしたら
//	$(".decide-btn").click(function(e){
	$(document).on("click",".decide-btn.destination", function() {
		decideFunction();
	});
	// 出発地の削除ボタンを押したらremove
	$('.search-box').on("click",".del_destination", function() {
		if($(FormID+"#searchBtn input").val() == '再検索')
		{
			var number = 0;
			$.each($(FormID+"#decided_contents_destination input"),function(index,val){
				number = index;
			});
			if(number < 2)
			{
				$(FormID+".destination-select").hide();
			}
			else
			{
				$(FormID+".destination-select").show();
			}
		}
		// 削除ボタンのクリアアクションの例外
		deleteButtonActionException('destination','p_mokuteki',this);
	});
});
// トグル操作。入れ子になっててslideToggleでは制御できないので
function toggleControll(id)
{
	if(id.css('display') == 'block')
	{
		id.slideUp("fast");
	}
	else
	{
		id.slideDown("fast");
	}
}
// モーダルビューの閉じるボタン、選び直しなどのクリア処理
function clearDestination()
{
	$(FormID+".destination-select").hide();
	// クリアの共通Action
	clearAction('destination','p_mokuteki',true,true);
}
//チェックボックスを処理してヒット件数取得。結構面倒
function getCheckBoxDestHitCount()
{
	var chkObj = countCheckBox();
	hatsu_str = chkObj.join(',');
	// チェックした際の共通アクション
	checkboxAction('destination','p_mokuteki',hatsu_str);
}
function countCheckBox()
{
	// 方面ページ
	if(category_type == CATEGORY_TYPE_DEST){
		return countCheckBoxDest();
	}
	// 国ページ
	else if (category_type == CATEGORY_TYPE_COUNTRY) {
		return countCheckBoxCountry();
	}
	// 都市ページ
	else{
		return countCheckBoxCity();
	}
}
function countCheckBoxDest(){

	//チェックしてる配列作成
	var chkObj =[];
	var hatsu_str = '';
	var countryFlg = false;
	var cityFlg = false;
	// 方面でチェックついているものをループ
	$(FormID+'[data-dest]:checked').each(function(){
		var dest = $(this).data("dest");
		countryFlg = false;
		// 都道府県でチェックされているものをループ
		$(FormID+'[data-country]:checked').each(function(){
			// 方面と都道府県が一致していないなら
			if($(this).closest("ul").attr("id") != "cp_"+dest) return true;
			countryFlg = true;
			var country = $(this).data("country");
			cityFlg = false;
			// 地域でチェックされているものをループ
			$(FormID+'[data-city]:checked').each(function(){
				// 都道府県と地域が一致していないなら
				if($(this).closest("ul").attr("id") != "region_all_"+country) return true;
				chkObj.push(dest+'-'+country+'-'+$(this).data("city"));
				cityFlg = true;
			});
			// 地域がチェックされていないなら
			if(!cityFlg)
			{
				// 都道府県を追加。最後に'-'をつける
				chkObj.push(dest+'-'+$(this).data("country")+'-');
			}
		});
		// 都道府県がチェックされていないなら
		if(!countryFlg)
		{
			// 方面を追加
			chkObj.push($(this).data("dest"));
		}
	});
	return chkObj;
}
function countCheckBoxCountry(){
	//チェックしてる配列作成
	var chkObj =[];
	var countryFlg = false;
	var cityFlg = false;
	var dest = def_mokuteki_dest;
	countryFlg = false;
	// 国でチェックされているものをループ
	$(FormID+'[data-country]:checked').each(function(){
		// 方面と国が一致していないなら
		if($(this).closest("ul").attr("id") != "cp_"+dest) return true;
		countryFlg = true;
		var country = $(this).data("country");
		cityFlg = false;
		// 都市でチェックされているものをループ
		$(FormID+'[data-city]:checked').each(function(){
			// 国と都市が一致していないなら
			if($(this).closest("ul").attr("id") != "region_all_"+country) return true;
			chkObj.push(dest+'-'+country+'-'+$(this).data("city"));
			cityFlg = true;
		});
		// 都市がチェックされていないなら
		if(!cityFlg)
		{
			// 国を追加。最後に'-'をつける
			chkObj.push(dest+'-'+$(this).data("country")+'-');
		}
	});

	return chkObj;

}
function countCheckBoxCity(){
	//チェックしてる配列作成
	var chkObj =[];
	var dest = def_mokuteki_dest;
	var country = def_mokuteki_country;
	// 都市でチェックされているものをループ
	$(FormID+'[data-city]:checked').each(function(){
		chkObj.push(dest+'-'+country+'-'+$(this).data("city"));
	});

	return chkObj;
}
function decideFunction()
{
	//チェックしてる配列作成
	var chkObj =[];
	var chkObj_text = [];
	var hatsu_str = '';
	var countryFlg = false;
	var cityFlg = false;

	// 方面ページ
	if(category_type == CATEGORY_TYPE_DEST){
		// 方面でチェックついているものをループ
		$(FormID+'[data-dest]:checked').each(function(){
			var dest = $(this).data("dest");
			countryFlg = false;
			// 都道府県でチェックされているものをループ
			$(FormID+'[data-country]:checked').each(function(){
				// 方面と都道府県が一致していないなら
				if($(this).closest("ul").attr("id") != "cp_"+dest) return true;
				countryFlg = true;
				var country = $(this).data("country");
				cityFlg = false;
				// 地域でチェックされているものをループ
				$(FormID+'[data-city]:checked').each(function(){
					// 都道府県と地域が一致していないなら
					if($(this).closest("ul").attr("id") != "region_all_"+country) return true;
					chkObj.push(dest+'-'+country+'-'+$(this).data("city"));
					chkObj_text.push($(this).parent().text());
					cityFlg = true;
				});
				// 地域がチェックされていないなら
				if(!cityFlg)
				{
					// 都道府県を追加
					chkObj.push(dest+'-'+$(this).data("country")+'-');
					chkObj_text.push($(this).parent().text());
				}
			});
			// 都道府県がチェックされていないなら
			if(!countryFlg)
			{
				// 方面を追加
				chkObj.push($(this).data("dest"));
				chkObj_text.push($(this).parent().text());
			}
		});
	}
	// 国ページ
	else if (category_type == CATEGORY_TYPE_COUNTRY) {
		var dest = def_mokuteki_dest;
		countryFlg = false;
		// 都道府県でチェックされているものをループ
		$(FormID+'[data-country]:checked').each(function(){
			// 方面と都道府県が一致していないなら
			if($(this).closest("ul").attr("id") != "cp_"+dest) return true;
			countryFlg = true;
			var country = $(this).data("country");
			cityFlg = false;
			// 地域でチェックされているものをループ
			$(FormID+'[data-city]:checked').each(function(){
				// 都道府県と地域が一致していないなら
				if($(this).closest("ul").attr("id") != "region_all_"+country) return true;
				chkObj.push(dest+'-'+country+'-'+$(this).data("city"));
				chkObj_text.push($(this).parent().text());
				cityFlg = true;
			});
			// 地域がチェックされていないなら
			if(!cityFlg)
			{
				// 都道府県を追加
				chkObj.push(dest+'-'+$(this).data("country")+'-');
				chkObj_text.push($(this).parent().text());
			}
		});
	}
	// 都市ページ
	else{
		var dest = def_mokuteki_dest;
		var country = def_mokuteki_country;
		// 地域でチェックされているものをループ
		$(FormID+'[data-city]:checked').each(function(){
			chkObj.push(dest+'-'+country+'-'+$(this).data("city"));
			chkObj_text.push($(this).parent().text());
		});

	}

	// 追加するなら
	if($(FormID+"#p_mokuteki_add_flag").val() != "")
	{
		var add_chkObj = [];
		var old_html = $(FormID+"#decided_contents_destination").html();
		var old_text = "";
		$.each($(FormID+"#decided_contents_destination input"),function(index,val){
			for(var i=0;i<chkObj.length;i++)
			{
				if(chkObj[i] == $(this).data("value"))
				{
					chkObj[i] = 'DELETE_' + chkObj[i];
				}
			}
			add_chkObj.push($(this).data("value"));
			old_text = ( old_text == "" ) ? $(this).data("value") : old_text + ',' + $(this).data("value");
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
			var option = '';
			// 文字数が15文字以上は文字サイズを小さくする
			if(14 < new_chkObj_text[i].length)
			{
				option = 'style="font-size: 1.0rem;"';
			}
			var name = '';
			if(new_chkObj_text[i].indexOf('[') == -1)
			{
				name = new_chkObj_text[i];
			}
			else
			{
				// ***[123]の[123]の部分を削除
				name = new_chkObj_text[i].substring(0,new_chkObj_text[i].indexOf("["));
			}
			html += '<li><div><input type="button" class="del_destination" value="削除" data-value="'+ new_chkObj[i] +'"><a class="decided_link" href="#modal_destination"><span class="decided_text" href="#modal_destination" '+ option + '>'+ name +'</span></a></div></li>';
			new_text = ( new_text == "" ) ? new_chkObj[i] : new_text + ',' + new_chkObj[i];
		}
		html = old_html + html;
		var value = ( new_text == "" ) ? old_text : old_text + ',' + new_text;
		$(FormID+"#p_mokuteki").val(value);
	}
	else
	{
		var html = '';
		// 選んだ出発地の表記のためにhtmlをループ
		for(var i=0;i<chkObj.length;i++)
		{
			var option = '';
			// 文字数が15文字以上は文字サイズを小さくする
			if(14 < chkObj_text[i].length)
			{
				option = 'style="font-size: 1.0rem;"';
			}
			var name = '';
			if(chkObj_text[i].indexOf('[') == -1)
			{
				name = chkObj_text[i];
			}
			else
			{
				// ***[123]の[123]の部分を削除
				name = chkObj_text[i].substring(0,chkObj_text[i].indexOf("["));
			}
			html += '<li><div><input type="button" class="del_destination" value="削除" data-value="'+ chkObj[i] +'"><a class="decided_link" href="#modal_destination"><span class="decided_text" href="#modal_destination" '+ option + '>'+ name +'</span></a></div></li>';
		}
	}
	// 確定ボタンの共通Action
	decideBtnActionAdd('destination',html,false);
	// 追加するなら
	if($(FormID+"#p_mokuteki_add_flag").val() != "")
	{
		add_chkObj = add_chkObj.concat(new_chkObj);
		chkObj = add_chkObj;
		$(FormID+"#p_mokuteki_add_flag").val("");
	}
	if(chkObj.length < 3)
	{
		$(FormID+"#add_destination").show();
	}
	else
	{
		$(FormID+"#add_destination").hide();
	}
	if($(FormID+"#searchBtn input").val() == '再検索')
	{
		if(chkObj.length < 2)
		{
			$(FormID+".destination-select").hide();
		}
		else
		{
			$(FormID+".destination-select").show();
		}
	}
}
function getDest(){
	//カスタムパラメータ
	var options = {
		formObj:'#searchTour',
		kind:"Detail",
		p_data_kind:1,
		p_rtn_data:'p_dest_name,p_prefecture_name,p_region_cn'
	}
	searchTour.requestProcess(options);	//Ajax通信実施
	var settings = {
		dataType: "json",
		success: function(json){
			//応答結果を編集
			if(json.ErrMes){
				//エラー
			}else{
				// 方面ページ
				if(category_type == CATEGORY_TYPE_DEST){
					dispDestList(json);
				}
				// 国ページ
				else if (category_type == CATEGORY_TYPE_COUNTRY) {
					dispCountryList(json);
				}
				// 都市ページ
				else{
					dispCityList(json);
				}
			}
		}
	}
	searchTour.ajaxProcess(settings);
}
// 方面、都道府県、地域表示
function dispDestList(json){
	var html = '<ul>';
	var dest_name_ary = json.p_dest_name;
	prefecture_name_ary = json.p_prefecture_name;
	region_name_ary = json.p_region_cn;
	// 方面のループ
	var sel_dest = [];
	var sel_country = [];
	$.each(dest_name_ary,function(index,val){
		if(def_mokuteki != val.key) return true;
		html += '<li>';
		// チャックされているなら
		if(val.check == true)
		{
			if(val.facet < 1)
			{
				html += '<span class="active"><label for="p_mokuteki_dest' + val.key + '" style="color:#9d9c9c"><input type="checkbox" class="open_cp" id="p_mokuteki_dest' + val.key + '" name="p_mokuteki_cp" data-cp="' + val.key + '" data-dest="' + val.key + '" checked disabled>' + val.name + '</label></span>';
			}
			else
			{
				html += '<span class="active"><label for="p_mokuteki_dest' + val.key + '"><input type="checkbox" class="open_cp" id="p_mokuteki_dest' + val.key + '" name="p_mokuteki_cp" data-cp="' + val.key + '" data-dest="' + val.key + '" checked>' + val.name + '</label></span>';
				sel_dest.push(val.key);
			}
		}
		else
		{
			if(val.facet < 1)
			{
				html += '<span><label for="p_mokuteki_dest' + val.key + '" style="color:#9d9c9c"><input type="checkbox" class="open_cp" id="p_mokuteki_dest' + val.key + '" name="p_mokuteki_cp" data-cp="' + val.key + '" data-dest="' + val.key + '" disabled>' + val.name + '</label></span>';
			}
			else
			{
				html += '<span><label for="p_mokuteki_dest' + val.key + '"><input type="checkbox" class="open_cp" id="p_mokuteki_dest' + val.key + '" name="p_mokuteki_cp" data-cp="' + val.key + '" data-dest="' + val.key + '">' + val.name + '</label></span>';
			}
		}
		html += '<ul id="cp_' + val.key + '" style="display:none">';
		$.each(prefecture_name_ary,function(index2,val2){
			// 都道府県の方面が一緒じゃなかったら
			if(val.key != val2.parentKey) return true;
			html += '<li>';
			// ファセットなしなら
			if(val2.facet < 1)
			{
				html += '<span><label for="p_mokuteki_cp' + val2.key + '" style="color:#9d9c9c"><input type="checkbox" class="check_country" id="p_mokuteki_cp' + val2.key + '" name="p_mokuteki_cp" data-cp="' + val2.key + '" data-country="' + val2.key + '" disabled>' + val2.name + '[' + val2.facet + ']</label></span>';
			}
			// チェックされているなら
			else if(val2.check == true)
			{
				html += '<span class="active"><label for="p_mokuteki_cp' + val2.key + '"><input type="checkbox" class="check_country" id="p_mokuteki_cp' + val2.key + '" name="p_mokuteki_cp" data-cp="' + val2.key + '" data-country="' + val2.key + '" checked>' + val2.name + '[' + val2.facet + ']</label></span>';
				sel_country.push(val2.key);
			}
			// 通常
			else
			{
				html += '<span><label for="p_mokuteki_cp' + val2.key + '"><input type="checkbox" class="check_country" id="p_mokuteki_cp' + val2.key + '" name="p_mokuteki_cp" data-cp="' + val2.key + '" data-country="' + val2.key + '">' + val2.name + '[' + val2.facet + ']</label></span>';
			}
			html += '</li>';
			// 地域一覧を開くの部分
			html += '<ul id="region_all_' + val2.key +'" class="parent" style="display:none"><a class="open_region" href="javascript:void(0)" onClick="return false;" data-value="' + val2.key + '"></a></ul>';
		});
		html += '</ul>';
		html += '</li>';
	});
	html += '</ul>';
	//表示
	$(FormID+'#modal_destination .area-list').html(html);
}
// 都道府県、地域表示
function dispCountryList(json){
	var html = '';
	var dest_name_ary = json.p_dest_name;
	prefecture_name_ary = json.p_prefecture_name;
	region_name_ary = json.p_region_cn;
	def_mokuteki_dest = def_mokuteki.substr(0,2);
	def_mokuteki_country = def_mokuteki.substr(3,2);

	html += '<ul id="cp_' + def_mokuteki_dest + '">';
	$.each(prefecture_name_ary,function(index2,val2){
		// 該当の都道府県でなかったら
		if(def_mokuteki_country != val2.key) return true;
		html += '<li>';
		// ファセットなしなら
		if(val2.facet < 1)
		{
			html += '<span><label for="p_mokuteki_cp' + val2.key + '" style="color:#9d9c9c"><input type="checkbox" class="check_country" id="p_mokuteki_cp' + val2.key + '" name="p_mokuteki_cp" data-cp="' + val2.key + '" data-country="' + val2.key + '" disabled>' + val2.name + '[' + val2.facet + ']</label></span>';
		}
		// チェックされているなら
		else if(val2.check == true)
		{
			html += '<span class="active"><label for="p_mokuteki_cp' + val2.key + '"><input type="checkbox" class="check_country" id="p_mokuteki_cp' + val2.key + '" name="p_mokuteki_cp" data-cp="' + val2.key + '" data-country="' + val2.key + '" checked>' + val2.name + '[' + val2.facet + ']</label></span>';
			sel_country.push(val2.key);
		}
		// 通常
		else
		{
			html += '<span><label for="p_mokuteki_cp' + val2.key + '"><input type="checkbox" class="check_country" id="p_mokuteki_cp' + val2.key + '" name="p_mokuteki_cp" data-cp="' + val2.key + '" data-country="' + val2.key + '">' + val2.name + '[' + val2.facet + ']</label></span>';
		}
		html += '</li>';
		// 地域一覧を開くの部分
		html += '<ul id="region_all_' + val2.key +'" class="parent" style="display:none"><a class="open_region" href="javascript:void(0)" onClick="return false;" data-value="' + val2.key + '"></a></ul>';
	});
	html += '</ul>';
	//表示
	$(FormID+'#modal_destination .area-list').html(html);
}
// 地域表示
function dispCityList(json){
	var html = '<ul>';
	var dest_name_ary = json.p_dest_name;
	prefecture_name_ary = json.p_prefecture_name;
	region_name_ary = json.p_region_cn;
	def_mokuteki_dest = def_mokuteki.substr(0,2);
	def_mokuteki_country = def_mokuteki.substr(3,2);

	// チェックした都道府県の地域のhtmlを生成
	$.each(region_name_ary,function(index,val){
		// 地域の都道府県が一緒じゃなかったら
		if(def_mokuteki_country != val.parentKey) return true;
		html += '<li>';
		// ファセットなしなら
		if(val.facet < 1)
		{
			html += '<span><label for="p_mokuteki_cp' + val.key + '" style="color:#9d9c9c"><input type="checkbox" class="check_city" id="p_mokuteki_cp' + val.key + '" name="p_mokuteki_cp" data-value="' + val.key + '" data-city="' + val.key + '" disabled>' + val.name + '[' + val.facet + ']</label></span>';
		}
		// チェックされているなら
		else if(val.check == true)
		{
			html += '<span class="active"><label for="p_mokuteki_cp' + val.key + '"><input type="checkbox" class="check_city" id="p_mokuteki_cp' + val.key + '" name="p_mokuteki_cp" data-value="' + val.key + '" data-city="' + val.key + '" checked>' + val.name + '[' + val.facet + ']</label></span>';
		}
		// 通常
		else
		{
			html += '<span><label for="p_mokuteki_cp' + val.key + '"><input type="checkbox" class="check_city" id="p_mokuteki_cp' + val.key + '" name="p_mokuteki_cp" data-value="' + val.key + '" data-city="' + val.key + '">' + val.name + '[' + val.facet + ']</label></span>';
		}
		html += '</li>';
	});
	html += '</ul>';
	//表示
	$(FormID+'#modal_destination .area-list').html(html);
}
// 都市ページの初期表示用のチェック
function initCheckCity(json){
	region_name_ary = json.p_region_cn;
	var items = new Array();

	var match_item = new Array();

	// 複数ある場合区切る
	if($(FormID+"#def_city_name").val().match('・')){
		match_item = $(FormID+"#def_city_name").val().split("・");
	}else{
		match_item.push($(FormID+"#def_city_name").val());
	}

	// チェックした都道府県の地域のhtmlを生成
	$.each(region_name_ary,function(index,val){

		reg = new RegExp(val.name);

		$.each(match_item,function(index2,val2){

			req2 = new RegExp(val2);

			// 該当の都市以外は
			if(val2.match(reg) == null && val.name.match(req2) == null) return true;

			items.push(val.name);

		});
	});

	return items;
}
