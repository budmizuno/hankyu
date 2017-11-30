/**
 * 国内
 * 到着空港
 */

$(function(){

	//　チェックしたら
	$(this).on('click','.check_arr_airport', function(e){

		// チェックボックス集計してヒット件数取得
		getCheckBoxHitCountArrAirport();

		// activeをつける。外して背景色をつける/戻す
		$(this).closest("span").toggleClass("active");
	});

	// 確定ボタンをクリックしたら
	$(document).on("click",".decide-btn.arr_airport", function() {

		//チェックしてる配列作成
		var chkObj =[];
		var chkObj_text = [];

		// チェックしているなら
		$(FormID+'[name=p_arr_airport_name]:checked').each(function(){
			// 値
			chkObj.push($(this).data("value"));
			// 名称
			chkObj_text.push($(this).parent().text());
		});

		var html = '';
		// 選んだ出発地の表記のためにhtmlをループ
		for(var i=0;i<chkObj.length;i++)
		{
			html += '<li><div><input type="button" class="del_arr_airport" value="削除" data-value="'+ chkObj[i] +'"><a class="decided_link" href="#modal_arr_airport"><span class="decided_text" href="#modal_arr_airport">'+ chkObj_text[i] +'</span></a></div></li>';
		}

		// 確定ボタンの共通Action
		decideBtnActionAdd('arr_airport',html,true);

	});

	// 出発地の削除ボタンを押したらremove
	$('.search-box').on("click",".del_arr_airport", function() {

		// 削除ボタンのクリアアクションの例外
		deleteButtonActionException('arr_airport','p_arr_airport_code',this);

	});



});

// モーダルビューの閉じるボタン、選び直しなどのクリア処理
function clearArrAirport()
{
	// クリアの共通Action
	clearAction('arr_airport','p_arr_airport_code',true,true);
}

// チェックボックスを処理してヒット件数取得
function getCheckBoxHitCountArrAirport()
{
	//チェックしてる配列作成
	var chkObj =[];
	var hatsu_str = '';

	$(FormID+'[name=p_arr_airport_name]:checked').each(function(){
		chkObj.push($(this).data("value"));
	});

	hatsu_str = chkObj.join(',');

	// チェックした際の共通アクション
	checkboxAction('arr_airport','p_arr_airport_code',hatsu_str);

}


function arrAirportInit()
{
	var options = {
			formObj:'#searchTour',
			kind:"Detail",
			p_data_kind:1,
			p_rtn_data:"p_arr_airport_name"
	}
	searchTour.requestProcess(options);	//Ajax通信実施

	var settings = {
		dataType: "json",
		success: function(json){
			searchTour.jsonData = json;
			//応答結果を編集
			html(json,'p_arr_airport_name');

		}
	}
	searchTour.ajaxProcess(settings);

	var html = function(json,myname){
		var for_name = 'p_arr_airport_rd';
		var type = 'checkbox';
		var c = c2 = '';
		var in_html = '';
		var dataCnt = searchTour.count(json[myname]);
		var reqPara = searchTour.getReqParam(myname);

		in_html += '<ul>';
		for (var i = 0; i < dataCnt; i++){
			var m = json[myname][i];
			var idno = ("0"+i).slice(-2);

			if(m.facet < 1){
				//continue;
				c = '';
				c2 = ' disabled';
				c3 = 'style="color:#9d9c9c"';
			}else{
				if(m.check == true){
					c = 'active';
					c2 = ' checked';
				}else{
					c = c2 = '';
				}
				c3 = '';
			}

			in_html += '<li><span class="'+ c +'">';

			in_html += '<label for="' + for_name + idno + '" '+ c3 +'  ><input class="check_arr_airport" name="' + reqPara + '" type="' + type + '" id="' + for_name + idno + '" data-value="' + m.key + '"' + c2 + '>' + m.name + '[' + m.facet + ']</label>';

			in_html += '</span></li>';
		}


		in_html += '</ul>';

		$(FormID+"#modal_arr_airport .area-list").html(in_html);
	}

}
