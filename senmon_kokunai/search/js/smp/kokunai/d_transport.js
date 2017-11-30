/**
 * 国内
 * 交通機関
 */

$(function(){

	// チェックボックスをクリックしたら
	$(this).on('click','.check_transport', function(e){

		// activeをつけて背景色をつける
		$(this).closest("span").toggleClass("active");

		// チェックボックス集計してヒット件数取得
		getCheckBoxHitCountTransport();

	});

	// 確定ボタンをクリックしたら
	$(document).on("click",".decide-btn.transport", function() {

		//チェックしてる配列作成
		var chkObj =[];
		var chkObj_text = [];

		var text = "";

		// チェックしているなら
		$(FormID+'[name=p_transport]:checked').each(function(){
			// 全日空[123]の[123]の部分を削除
			var name = $(this).parent().text().substring(0,$(this).parent().text().indexOf("["));

			text = ( text == "" ) ? name : text + '／' + name;

		});

		var html = '<li><input type="button" class="del_transport" value="全て削除" data-role="none" style="float:none"><span class="decided_text" href="#modal_transport">'+ text +'</span></li>';

		// 確定ボタンの共通Action
		decideBtnActionAdd('transport',html,false);

	});

	// 出発地の削除ボタンを押したらremove
	$('.search-box').on("click",".del_transport", function() {

		clearTransport();

	});


	// フリープランのradioボタンをクリックしたら
	$(this).on('click','.check_transport_free', function(e){

		var value = $(this).data("value");

		// 飛行機なら
		if(value == '3')
		{
			// 発地を空に
			$(FormID+"#p_hatsu_sub").val("");

			// 出発空港の値を入れる
			var chkObj = [];
			$.each($(FormID+"#decided_contents_dep_airport input"),function(index,val){
				chkObj.push($(val).data("value"));
			});
			hatsu_str = chkObj.join(',');
			$(FormID+"#p_dep_airport_code").val(hatsu_str);

			// 到着空港の値を入れる
			var chkObj = [];
			$.each($(FormID+"#decided_contents_arr_airport input"),function(index,val){
				chkObj.push($(val).data("value"));
			});
			hatsu_str = chkObj.join(',');
			$(FormID+"#p_arr_airport_code").val(hatsu_str);

			$(FormID+".search_hatsu").hide();
			$(FormID+".search_dep_airport").show();
			$(FormID+".search_arr_airport").show();
		}
		else
		{
			$(FormID+"#p_dep_airport_code").val("");
			$(FormID+"#p_arr_airport_code").val("");

			// 発地の値を入れる
			var chkObj = [];
			$.each($(FormID+"#decided_contents_hatsu input"),function(index,val){
				chkObj.push($(val).data("value"));
			});
			hatsu_str = chkObj.join(',');
			$(FormID+"#p_hatsu_sub").val(hatsu_str);


			$(FormID+".search_hatsu").show();
			$(FormID+".search_dep_airport").hide();
			$(FormID+".search_arr_airport").hide();
		}

		// リクエスト値変更
		$(FormID+"#p_transport").val(value);

		checkFooterSearch();

	});

});

// チェックボックスを処理してヒット件数取得
function getCheckBoxHitCountTransport()
{

	//チェックしてる配列作成
	var chkObj =[];
	var hatsu_str = '';

	$(FormID+'[name=p_transport]:checked').each(function(){
		chkObj.push($(this).data("value"));
	});

	hatsu_str = chkObj.join(',');

	// チェックした際の共通アクション
	checkboxAction('transport','p_transport',hatsu_str);
}

//モーダルビューの閉じるボタン、選び直しなどのクリア処理
function clearTransport()
{
	// フリープランなら
	if($(FormID+"#free_flag").val() != '')
	{
		// チェックを外す
		$(FormID+'[name="dselect"]:checked').each(function(){
			$(this).prop("checked","false");
		});

		// 指定なしをチェックする
		$(FormID+".search_transport input:eq(0)").prop("checked","true");

		$(FormID+".search_hatsu").show();
		$(FormID+".search_dep_airport").hide();
		$(FormID+".search_arr_airport").hide();
	}
	else
	{
		// クリアの共通Action
		clearAction('transport','p_transport',false,true);
	}
}


function transportInit()
{
	var options = {
		formObj:'#searchTour',
		kind:"Detail",
		p_data_kind:1,
		p_rtn_data:"p_transport"
	}
	searchTour.requestProcess(options);	//Ajax通信実施

	var settings = {
		dataType: "json",
		success: function(json){
			searchTour.jsonData = json;
			//応答結果を編集
			html(json,'p_transport');

		}
	}
	searchTour.ajaxProcess(settings);

	var html = function(json,myname){

		var for_name = 'p_transport_rd';
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

			in_html += '<label for="' + for_name + idno + '" '+ c3 +'  ><input class="check_transport" name="' + reqPara + '" type="' + type + '" id="' + for_name + idno + '" data-value="' + m.key + '"' + c2 + '>' + m.name + '[' + m.facet + ']</label>';

			in_html += '</span></li>';
		}

		in_html += '</ul>';

		$(FormID+"#modal_transport .area-list").html(in_html);
	}

}
