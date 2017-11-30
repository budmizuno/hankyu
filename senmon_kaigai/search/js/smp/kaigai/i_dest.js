/**
 * 海外
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
var country_unlimited;
var country_name_ary = [];
// 都市の配列
var city_name_ary = [];
var except_array = {}; // 国ページの例外国

var path_name;

$(function(){
	category_type = $(FormID+"#p_category_page").val();
	// 目的地のデフォルト設定
	if(category_type != '' && $(FormID+"#def_mokuteki").val() != '')
	{
		var html = '';
		// 方面ページなら
		if(category_type == CATEGORY_TYPE_DEST){

			// /tyo.phpを削除する
			path_name = window.location.pathname.replace( /\/.{1,5}\.php$/ , "" );

			// 南太平洋、オセアニアのページなら
			if(is_spacific_oceania(path_name)){
				def_mokuteki = $(FormID+"#def_mokuteki").val();
			}else{
				def_mokuteki = $(FormID+"#def_mokuteki").val().substr(0,3);
			}
			html = '<li><div><input type="button" class="del_destination" value="削除" data-value="'+ def_mokuteki +'"><a class="decided_link" href="#modal_destination"><span class="decided_text" href="#modal_destination">'+ $(FormID+"#def_dest_name").val() +'</span></a></div></li>';
		// 国ページなら
		}else if(category_type == CATEGORY_TYPE_COUNTRY){

			// 目的地設定上限3を外すなら
			if($("#search_country_unlimited_flag").val() == '1'){
				def_mokuteki = '';
				country_unlimited = '';
				// ,で分割する
				var country_array = $.parseJSON($('#search_country_init').val()).split(",");
				var html_array = '';
				$.each(country_array,function(index,val){
					var name_code = val.split("___"); // _が3つ
					html += '<li><div><input type="button" class="del_destination" value="削除" data-value="'+ name_code[0] + '-' +'"><a class="decided_link" href="#modal_destination"><span class="decided_text" href="#modal_destination">'+ name_code[1] +'</span></a></div></li>';
					def_mokuteki = def_mokuteki == ''? name_code[0] + '-' : def_mokuteki + ',' + name_code[0] + '-';
					country_unlimited = country_unlimited == ''? name_code[1] : country_unlimited + ',' + name_code[1];
				});

				if($("#def_country_name").val() == '東欧・中欧'){
					html = '<li><div><input type="button" class="del_destination" value="削除" data-value="'+ def_mokuteki +'"><a class="decided_link" href="#modal_destination"><span class="decided_text" href="#modal_destination">'+ $(FormID+"#def_country_name").val() +'</span></a></div></li>';
				}

			}else{
				// 例外的な国なら
				if(is_except($("#def_country_name").val())){
					def_mokuteki = $(FormID+"#def_mokuteki").val();
					var country_array = $(FormID+"#def_mokuteki").val().split(",");
					var country_name_array = $("#def_country_name").val().split("・");
					var html_array = '';
					$.each(country_array,function(index,val){
						html += '<li><div><input type="button" class="del_destination" value="削除" data-value="'+ val +'"><a class="decided_link" href="#modal_destination"><span class="decided_text" href="#modal_destination">'+ country_name_array[index] +'</span></a></div></li>';
					});
				// 複数検索
				}else if(0 < $('#search_country').val().length){
					def_mokuteki = $(FormID+"#def_mokuteki").val();
					html = '<li><div><input type="button" class="del_destination" value="削除" data-value="'+ def_mokuteki +'"><a class="decided_link" href="#modal_destination"><span class="decided_text" href="#modal_destination">'+ $(FormID+"#def_country_name").val() +'</span></a></div></li>';
				}else{
					def_mokuteki = $(FormID+"#def_mokuteki").val().substr(0,7);
					html = '<li><div><input type="button" class="del_destination" value="削除" data-value="'+ def_mokuteki +'"><a class="decided_link" href="#modal_destination"><span class="decided_text" href="#modal_destination">'+ $(FormID+"#def_country_name").val() +'</span></a></div></li>';
				}
			}
		// 都市ページなら
		}else{
			def_mokuteki = $(FormID+"#def_mokuteki").val();
			html = '<li><div><input type="button" class="del_destination" value="削除" data-value="'+ def_mokuteki +'"><a class="decided_link" href="#modal_destination"><span class="decided_text" href="#modal_destination">'+ $(FormID+"#def_city_name").val() +'</span></a></div></li>';
		}

		// 確定ボタンの共通Action
		param = 'destination';

		$(FormID+"#p_mokuteki").val(def_mokuteki);
		//表示
		$(FormID+'.search_'+param+' #decided_contents_'+param).html(html);
		// 各項目の表示・非表示
		$(FormID+"#modal_menu_"+param).hide();
		$(FormID+"#add_contents_"+param).show();
		$(FormID+"#add_"+param).show();
		$(FormID+".decide-btn").removeClass(param);
		$(FormID+"#add_destination").show();
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
		if($("#search_country_unlimited_flag").val() == '1'){
			$(FormID+".destination-select").show();
		}

		// 一応戻す
		FormID = '.tour ';
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
		if($("#search_country_unlimited_flag").val() != '1'){
			if(4 <= chkObj.length)
			{
				$(this).prop("checked",false);
				alert("目的地の設定は3つまでです");
				return;
			}
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
			$.each(country_name_ary,function(index,val){
				// 方面が一緒じゃなかったら
				if(sel_cp != val.parentKey) return true;
				$.each(city_name_ary,function(index2,val2){
					// 都市の国が一緒じゃなかったら
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
			// トグルを閉じる
			$(FormID+"#cp_" + sel_cp).slideToggle("fast");
		}
		else
		{
			// activeをつけて背景色をつける
			$(this).closest("span").toggleClass("active");
			// トグルを開く
			$(FormID+"#cp_" + sel_cp).slideToggle("fast");
		}
		// チェックボックス集計してヒット件数取得
		getCheckBoxDestHitCount();
	});
	// 国チェックしたら
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
		if($("#search_country_unlimited_flag").val() != '1'){
			if(4 <= chkObj.length)
			{
				$(this).prop("checked",false);
				alert("目的地の設定は3つまでです");
				return;
			}
		}
		var countryId = $(this).data("cp");
		// activeをつける。外して背景色をつける/戻す
		$(this).closest("span").toggleClass("active");
		// チェックされているなら
		if($(this).prop('checked'))
		{
			$(FormID+'#region_all_'+countryId).slideDown("fast");
			// 都市のチェックを外す
			$.each($(FormID+"#region_" + countryId + " input:checked"),function(index,val){
				// activeを外して背景色を戻す
				$(FormID+"#"+val.id).closest("span").toggleClass("active");
				$(FormID+"#"+val.id).prop('checked',false);
			});
			$(FormID+"#region_all_"+ countryId+ ' a').text("都市の一覧を開く");
			$(FormID+"#region_all_"+ countryId).toggleClass("active");
		}
		else
		{
			$(FormID+'#region_all_'+countryId).slideUp("fast");
			$(FormID+'#region_'+countryId).slideUp("fast");
			$(FormID+"#region_all_"+ countryId+ ' a').text("都市の一覧を開く");
			$(FormID+"#region_all_"+ countryId).toggleClass("active");
		}
		getCheckBoxDestHitCount();
	});
	// 都市を開くボタンをクリックしたら
	$(this).on('click','.open_region', function(e){
		var countryId = $(this).data("value");
		// activeをつける/外す
		$(this).toggleClass("active");
		// activeを持っているなら
		if($(this).hasClass("active"))
		{
			$(this).text("都市の一覧を閉じる");
			$(this).parent().toggleClass("active");
			// すでに都市リストがあるなら
			if($(FormID+'#region_'+countryId).length)
			{
				toggleControll($(FormID+'#region_'+countryId));
			}
			else
			{
				var html = '<div id="region_'+countryId+'">';
				// チェックした国の都市のhtmlを生成
				$.each(city_name_ary,function(index,val){
					// 都市の国が一緒じゃなかったら
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
			$(this).text("都市の一覧を開く");
			$(this).parent().toggleClass("active");
			toggleControll($(FormID+'#region_'+countryId));
		}
	});
	// 都市をクリックしたら
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
		if($("#search_country_unlimited_flag").val() != '1'){
			if(4 <= chkObj.length)
			{
				$(this).prop("checked",false);
				alert("目的地の設定は3つまでです");
				return;
			}
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
		if($(FormID+"#searchBtn input").val() == '再検索' || $(FormID+"#free_flag").val() != "")
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
// チェックボックスを処理してヒット件数取得。結構面倒
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
	var countryFlg = false;
	var cityFlg = false;
	// 方面でチェックついているものをループ
	$(FormID+'[data-dest]:checked').each(function(){
		var dest = $(this).data("dest");
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
		// 国がチェックされていないなら
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
		// 国と都市が一致していないなら
//				if($(this).closest("ul").attr("id") != "region_all_"+country) return true;
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
					chkObj_text.push($(this).parent().text());
					cityFlg = true;
				});
				// 都市がチェックされていないなら
				if(!cityFlg)
				{
					// 国を追加
					chkObj.push(dest+'-'+$(this).data("country")+'-');
					chkObj_text.push($(this).parent().text());
				}
			});
			// 国がチェックされていないなら
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
		// 方面でチェックついているものをループ
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
				chkObj_text.push($(this).parent().text());
				cityFlg = true;
			});
			// 都市がチェックされていないなら
			if(!cityFlg)
			{
				// 国を追加
				chkObj.push(dest+'-'+$(this).data("country")+'-');
				chkObj_text.push($(this).parent().text());
			}
		});
	}
	// 都市ページ
	else{
		var dest = def_mokuteki_dest;
		var country = def_mokuteki_country;
		// 都市でチェックされているものをループ
		$(FormID+'[data-city]:checked').each(function(){
			// 国と都市が一致していないなら
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
	if($("#search_country_unlimited_flag").val() != '1'){
		if(chkObj.length < 3)
		{
			$(FormID+"#add_destination").show();
		}
		else
		{
			$(FormID+"#add_destination").hide();
		}
	}
	else{
		$(FormID+"#add_destination").show();
	}

	if($(FormID+"#searchBtn input").val() == '再検索' || $(FormID+"#free_flag").val() != "")
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
		p_rtn_data:'p_dest_name,p_country_name,p_city_cn'
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
// 方面から表示
function dispDestList(json){
	var html = '<ul>';
	var dest_name_ary = json.p_dest_name;
	country_name_ary = json.p_country_name;
	city_name_ary = json.p_city_cn;
	// 方面のループ
	var sel_dest = [];
	var sel_country = [];

	var except_array = '';
	if(is_spacific_oceania(path_name)){
		// ,で分割する
		var country_array = def_mokuteki.split(",");
		$.each(country_array,function(index,val){
			var name_code = val.split("-");
			except_array = except_array == ''? name_code[1] : except_array + ',' + name_code[1];
		});
	}

	$.each(dest_name_ary,function(index,val){
		if(is_spacific_oceania(path_name)){
			if(val.name == "その他海外" || 'FOC' != val.key) return true;
		}
		else{
			if(val.name == "その他海外" || def_mokuteki != val.key) return true;
		}
		html += '<li>';
		// チャックされているなら
		if(val.check == true)
		{
			if(val.facet < 1)
			{
				html += '<span class="active"><label for="p_mokuteki_cp' + val.key + '" style="color:#9d9c9c"><input type="checkbox" class="open_cp" id="p_mokuteki_cp' + val.key + '" name="p_mokuteki_cp" data-cp="' + val.key + '" data-dest="' + val.key + '" checked disabled>' + val.name + '</label></span>';
			}
			else
			{
				html += '<span class="active"><label for="p_mokuteki_cp' + val.key + '"><input type="checkbox" class="open_cp" id="p_mokuteki_cp' + val.key + '" name="p_mokuteki_cp" data-cp="' + val.key + '" data-dest="' + val.key + '" checked>' + val.name + '</label></span>';
				sel_dest.push(val.key);
			}
		}
		else
		{
			if(val.facet < 1)
			{
				html += '<span><label for="p_mokuteki_cp' + val.key + '" style="color:#9d9c9c"><input type="checkbox" class="open_cp" id="p_mokuteki_cp' + val.key + '" name="p_mokuteki_cp" data-cp="' + val.key + '" data-dest="' + val.key + '" disabled>' + val.name +'</label></span>';
			}
			else
			{
				html += '<span><label for="p_mokuteki_cp' + val.key + '"><input type="checkbox" class="open_cp" id="p_mokuteki_cp' + val.key + '" name="p_mokuteki_cp" data-cp="' + val.key + '" data-dest="' + val.key + '">' + val.name + '</label></span>';
			}
		}
		html += '<ul id="cp_' + val.key + '" style="display:none">';
		$.each(country_name_ary,function(index2,val2){
			if(is_spacific_oceania(path_name)){
				// 国の方面が一緒じゃなかったら
				if(val.key != val2.parentKey) return true;

				reg = new RegExp(val2.key);
				// 該当の国以外は
				if(except_array.match(reg) == null) return true;
			}
			else{
				// 国の方面が一緒じゃなかったら
				if(val.key != val2.parentKey) return true;
			}
			// 日本は非表示
			if(val2.key == 'JP') return true;
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
			// 都市一覧を開くの部分
			html += '<ul id="region_all_' + val2.key +'" class="parent" style="display:none"><a class="open_region" href="javascript:void(0)" onClick="return false;" data-value="' + val2.key + '"></a></ul>';
		});
		html += '</ul>';
		html += '</li>';
	});
	html += '</ul>';
	//表示
	$(FormID+'#modal_destination .area-list').html(html);
}
// 国から表示
function dispCountryList(json){
//	var html = '<ul>';
	var html = '';
	var dest_name_ary = json.p_dest_name;
	country_name_ary = json.p_country_name;
	city_name_ary = json.p_city_cn;
	def_mokuteki_dest = def_mokuteki.substr(0,3);
	def_mokuteki_country = def_mokuteki.substr(4,2);

	var is_except_check = false;
	var except_country = '';
	if(is_except($("#def_country_name").val())){
		is_except_check = true;
		except_country = except_array[$("#def_country_name").val()];
	}

	// デコードする
	var search_country = $.parseJSON($('#search_country').val());
	html += '<ul id="cp_' + def_mokuteki_dest + '">';
	$.each(country_name_ary,function(index2,val2){

		if(val2.name == " ")return true;

		// 並列選択でないなら
		if(search_country.length < 1){
			// 目的地設定上限3を外すなら
			if($("#search_country_unlimited_flag").val() == '1'){
				reg = new RegExp(val2.name);
				// 該当の国以外は
				if(country_unlimited.match(reg) == null) return true;
			}
			else {
				// 例外的な国なら
				if(is_except_check){
					reg = new RegExp(val2.name);
					// 該当の国以外は
					if(except_country.match(reg) == null) return true;
				}else{
					// 該当の国以外は
					if(def_mokuteki_country != val2.key) return true;
				}
			}
		}
		// 並列選択なら
		else{
			reg = new RegExp(val2.name);
			// 該当の国以外は
			if(search_country.match(reg) == null || def_mokuteki_dest != val2.parentKey) return true;
		}
		// 日本は非表示
		if(val2.key == 'JP') return true;
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
		// 都市一覧を開くの部分
		html += '<ul id="region_all_' + val2.key +'" class="parent" style="display:none"><a class="open_region" href="javascript:void(0)" onClick="return false;" data-value="' + val2.key + '"></a></ul>';
	});
	html += '</ul>';

	//表示
	$(FormID+'#modal_destination .area-list').html(html);

}

// 都市一覧表示
function dispCityList(json){
	var html = '<ul>';
	var dest_name_ary = json.p_dest_name;
	country_name_ary = json.p_country_name;
	city_name_ary = json.p_city_cn;
	def_mokuteki_dest = def_mokuteki.substr(0,3);
	def_mokuteki_country = def_mokuteki.substr(4,2);

	// チェックした国の都市のhtmlを生成
	$.each(city_name_ary,function(index,val){
		// 都市の国が一緒じゃなかったら
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

// 例外の動作をする目的地（国ページのみ）があるので、該当するかチェック
function is_except(def_country_name){

	// ページ名と付随する国
	except_array = {
		'クロアチア・スロベニア': 'クロアチア,スロベニア',
		'ジンバブエ・ザンビア': 'ジンバブエ,ザンビア',
	};

	// 該当するかどうか
	for (var key in except_array) {
		if (key == def_country_name) {
			return true;
		}
	}

	return false;
}

// 南太平洋とオセアニアの判定
function is_spacific_oceania(path_name){

	// 拠点とBOT
	if(path_name == '/s-pacific' || path_name == '/s-pacific/' ||
	   path_name == '/oceania' || path_name == '/oceania/'){
		return true;
	}

	return false;
}
