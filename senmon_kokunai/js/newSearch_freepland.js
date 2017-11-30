
/*
* jQuery mapSubmitFn Plugin
* Copyright (c) 2010 wlkuro
*/

var holidayData;
var jsonData;
var $dir = location.href.split("/");
var stateName = "searchTrapics_"+$dir[$dir.length -2];
/*--------------------
	マップリンク処理　オブジェクト
--------------------*/
var SenmonSubmit = {
	kyotenLink:  function(evt) {
		SAS_setCookie('SAS_VARS_TYPE','地図',0,'/','hankyu-travel.com','');
		$("#senmonSubmitFr").remove();
		var formObj = $("<form>").attr({
			id: "senmonSubmitFr",
			method: "post",
			action: ""
		});
		$(formObj).appendTo("body");
		var obj = evt.target || evt.srcElement;

		var inputObj = $("<input>").attr({
			name: "dept",
			type: "hidden",
			id: "senmonSubmitFrInput",
			value: ""
		});
		var inputVar = $(obj).attr("title");
		var nameVar = $(obj).attr("name");
		$(inputObj).prependTo("#senmonSubmitFr");
		$("#senmonSubmitFrInput").attr("value",inputVar);
		$("#senmonSubmitFr").attr("action",nameVar);
		$("#senmonSubmitFr").trigger("submit");
	}
	,topLink:  function(MBS_DefMainKyoten,MBS_DefSubKyoten,MBS_DefMainKyotenName,MBS_DefSubKyotenName) {
		SAS_setCookie('SAS_VARS_TYPE','地図',0,'/','hankyu-travel.com','');
		MBS_DefMainKyotenName = encodeURIComponent(MBS_DefMainKyotenName);
		MBS_DefSubKyotenName = encodeURIComponent(MBS_DefSubKyotenName);
		param = "&MBS_DefMainKyoten="+MBS_DefMainKyoten+'&MBS_DefSubKyoten='+MBS_DefSubKyoten+'&MBS_DefMainKyotenName='+MBS_DefMainKyotenName+'&MBS_DefSubKyotenName='+MBS_DefSubKyotenName;
		var myPath = location.href;
		var regKaigai = new RegExp("/kaigai/", "i");
		var regKokunai = new RegExp("/kokunai/", "i");
		if(myPath.match(regKaigai)){
		//国内トップ用
			$.ajax({
				contentType:"",
				type: "GET",
				processData:true,
				url: '/sharing/common14/phpsc/ajax_maplink.php?'+param+'&MyNaigai=i&MyPath=top&MyHomen=top',
				success: function(json) {
					$(".Map").animate({
						opacity: 0
					}, "500",function () {
						var jObj = $(json);
						$(jObj).find("div.Map").css({
							opacity: 0
						});
						$(".Map").replaceWith(json);
						//SetKyotenHFR();
						void(0);
						return false;
					});
				},
				dataType: 'html'
			});
			void(0);
			return false;
		}else if(myPath.match(regKokunai)){
		//国内トップ用
			$.ajax({
				contentType:"",
				type: "GET",
				processData:true,
				url: '/sharing/common14/phpsc/ajax_maplink.php?'+param+'&MyNaigai=d&MyPath=top&MyHomen=top',
				success: function(json) {
					$(".Map").animate({
						opacity: 0
					}, "500",function () {
						var jObj = $(json);
						$(jObj).find("div.Map").css({
							opacity: 0
						});
						$(".Map").replaceWith(json);
						//SetKyotenHFR();
						void(0);
						return false;
					});
				},
				dataType: 'html'
			});
			void(0);
			return false;
		}
		void(0);
		return false;
	}
	,attachLink: function(evt) {
		SAS_setCookie('SAS_VARS_TYPE','地図',0,'/','hankyu-travel.com','');
		var formObj = $("<form>").attr({
		  id: "senmonSubmitFr",
		  method: "post",
		  action: ""
		});
		$(formObj).appendTo("body");
		var obj = evt.target || evt.srcElement;
		var myPath = location.href;
		var regKaigai = new RegExp("/kaigai/", "i");
		var regHawaii = new RegExp("/hawaii/", "i");
		var regFreePlan = new RegExp("/freeplan-d/", "i");

		if(myPath.match(regKaigai) && !obj.name.match(regHawaii)){
		//海外トップ用
			var MBS_DefMainKyoten = $("#defMainKyoten").val();	//7大拠点3レター
			var MBS_DefSubKyoten = $("#defSubKyoten").val();	//サブ拠点3レター
			var MBS_DefMainKyotenName = $("#defMainKName").val();	//7大拠点名称
			var MBS_DefSubKyotenName = $("#defSubKName").val();	//サブ拠点名称
			var defKyotenParam = "&MBS_DefMainKyoten="+MBS_DefMainKyoten+'&MBS_DefSubKyoten='+MBS_DefSubKyoten;

			if($(obj).parent("li").attr("id")){
				var param = $(obj).parent("li").attr("id");
			}else{
				var param = $(obj).parent("p").attr("id");
			}
			$.ajax({
				contentType:"",
				type: "GET",
				processData:true,
				url: '/sharing/common14/phpsc/ajax_maplink.php?'+param,
				success: function(json) {
					$(".Map").animate({
						opacity: 0
					}, "500",function () {
						var jObj = $(json);
						$(jObj).find("div.Map").css({
							opacity: 0
						});
						$(".Map").replaceWith(json);
						//SetKyotenHFR();
					});
				},
				dataType: 'html'
			});
		}else if(myPath.match(regFreePlan)){
		//フリープラン用
			$("#senmonSubmitFr").remove();
			var formObj = $("<form>").attr({
				id: "senmonSubmitFr",
				method: "post",
				action: ""
			});
			$(formObj).appendTo("body");

			var inputObj = $("<input>").attr({
				name: "p_hatsu_sub",
				type: "hidden",
				id: "senmonSubmitFrInput",
				value: ""
			});
			var inputObj2 = $("<input>").attr({
				name: "p_mokuteki",
				type: "hidden",
				id: "senmonSubmitFrInput2",
				value: ""
			});
			var inputObj3 = $("<input>").attr({
				name: "p_bunrui",
				type: "hidden",
				id: "senmonSubmitFrInput3",
				value: ""
			});
			$(inputObj).prependTo("#senmonSubmitFr");
			$(inputObj2).prependTo("#senmonSubmitFr");
			$(inputObj3).prependTo("#senmonSubmitFr");

			var inputVar = obj.id;
			inputAry = inputVar.split("_");
			var inputVarBunrui = obj.title;
			$("#senmonSubmitFrInput").attr("value",inputAry[1]);
			$("#senmonSubmitFrInput2").attr("value",inputAry[0]);
			$("#senmonSubmitFrInput3").attr("value",inputVarBunrui);
			$("#senmonSubmitFr").attr("action",'/search/dfree.php');
			$("#senmonSubmitFr").trigger("submit");
			return false;
		}else{
		//それ以外用
			$("#senmonSubmitFr").remove();
			var formObj = $("<form>").attr({
				id: "senmonSubmitFr",
				method: "post",
				action: ""
			});
			$(formObj).appendTo("body");

			var inputVar = $("#defMainKyoten").val();
			inputVar += "-"+$("#defSubKyoten").val();
			var inputObj = $("<input>").attr({
				name: "dept",
				type: "hidden",
				id: "senmonSubmitFrInput",
				value: ""
			});
			$(inputObj).prependTo("#senmonSubmitFr");
			var nameVar = $(obj).attr("name");
			$("#senmonSubmitFrInput").attr("value",inputVar);
			$("#senmonSubmitFr").attr("action",nameVar);
			$("#senmonSubmitFr").trigger("submit");
			return false;
		}
	}
	,submitLink: function(evt) {
		SAS_setCookie('SAS_VARS_TYPE','地図',0,'/','hankyu-travel.com','');
		$("#senmonSubmitFr").remove();
		var formObj = $("<form>").attr({
			id: "senmonSubmitFr",
			method: "post",
			action: ""
		});
		$(formObj).appendTo("body");
		var obj = evt.target || evt.srcElement;

		var myPath = location.href;
		var regKaigai = new RegExp("/kaigai/", "i");
		var regHawaii = new RegExp("/kokunai/", "i");

		if(myPath.match(regKaigai) || myPath.match(regKaigai)){
			//海外トップ　国内トップ　サブミット用
			var nameVar = $(obj).attr("name");
			$("#senmonSubmitFr").attr("action",nameVar);
			$("#senmonSubmitFr").trigger("submit");
		}else{
			var inputVar = $("#defMainKyoten").val();
			inputVar += "-"+$("#defSubKyoten").val();
			var inputObj = $("<input>").attr({
				name: "dept",
				type: "hidden",
				id: "senmonSubmitFrInput",
				value: ""
			});
			$(inputObj).appendTo("#senmonSubmitFr");
			var nameVar = $(obj).attr("name");
			$("#senmonSubmitFrInput").attr("value",inputVar);
			$("#senmonSubmitFr").attr("action",nameVar);
			$("#senmonSubmitFr").trigger("submit");
		}
	}
};

/*--------------------
	専門店カレンダー処理 オブジェクト
--------------------*/
var TravelcomFormCalendar = {
	holiday:"/sharing/phpsc/data/oyado_cal_holiday_list.txt",
	lastDate:"",
	dayStart:"",
	browserVarsion:"",
	nextVal:1,
	prevVal:-1,
	inputObj:".SachCalendar",
	year:"",
	month:"",
	dateArr: new Array(47),
	date:"",
	calChack:"",
	options: {
		blocked: [], // blocked dates
		classes: [], // ['calendar', 'prev', 'next', 'month', 'year', 'today', 'invalid', 'valid', 'inactive', 'active', 'hover', 'hilite']
		days: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'], // days of the week starting at sunday
		direction: 0, // -1 past, 0 past + future, 1 future
		draggable: true,
		months: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
		navigation: 1, // 0 = no nav; 1 = single nav for month; 2 = dual nav for month and year
		offset: 0, // first day of the week: 0 = sunday, 1 = monday, etc..
		onHideStart: "",//Class.empty,
		onHideComplete: "",//Class.empty,
		onShowStart: "",//Class.empty,
		onShowComplete: "",//Class.empty,
		pad: 1, // padding between multiple calendars
		tweak: {x: 0, y: 0} // tweak calendar positioning
	},
	//constractor
	initialize: function(ivent,options) {
		this.nextVal = 1;
		this.prevVal = -1;
		var calObj;
		$.ajax({
			type: "GET",
			url: this.holiday,
			dataType: "text",
			cache: true,
			error: function(){},
			success: function(data){
				var calChack = "def";
				calObj = TravelcomFormCalendar.calDate(calChack,data);
				TravelcomFormCalendar.display(calObj,"none");
			}
		});

	},
	calDate: function(calChack,holidayData){

		//holidayData = holidayData.split("\n");

		// 今日の日付を取得
		this.browser();
		var d = new Date();

		switch (calChack){
			case "def":
			break;

			case "next":
			var d = new Date(d.getFullYear(), d.getMonth()+TravelcomFormCalendar.nextVal, d.getDate());
			break;

			case "prev":
			var d = new Date(d.getFullYear(), d.getMonth()+TravelcomFormCalendar.prevVal, d.getDate());
			break;

			default:
			break;
		}
		TravelcomFormCalendar.year = d.getFullYear();
		TravelcomFormCalendar.month = d.getMonth();
		var date =  d.getDate();
		var day;

		this.format(TravelcomFormCalendar.year,TravelcomFormCalendar.month);

		//this.dateArr = new Array(35);
		for(i=0;i<this.dateArr.length;i++){
			this.dateArr[i] = "&nbsp;";
		}
		var x = this.dayStart;
		for(i=0;i<this.lastDate;i++){
			this.dateArr[x] = i+1;
			x=x+1;
		}

		var calObj = $(document.createElement('div'));
		$(calObj).attr({
			id: "SachCal",
			title: "SachCal"
		}).css({
			position:"relative",
			width:"auto",
			height:"auto",
			display:"block",
			clear:"both"
		});

		var divObj = $(document.createElement('div'));
		$(divObj).css({
			position:"absolute",
			top:"0",
			left:"0",
			width:"auto",
			height:"auto"
		});

		var calPObj = $('<p class="calPrev"><a href="javascript:void(0);">prev</a></p>');
		$(divObj).append(calPObj);

		$(divObj).append('<p class="calCenter"><a href="javascript:void(0);">'+TravelcomFormCalendar.year+'年'+parseInt(TravelcomFormCalendar.month+1)+'月'+'</a></p>');

		var calPObj = $('<p class="calNext"><a href="javascript:void(0);">next</a></p>')
		$(divObj).append(calPObj);

		$(divObj).append('<table></table>');
		$(divObj).find("table").css({
			width:"auto",
			height:"170px",
			clear:"both"
		});

		var URLVal = "#";
		var trObj = $(document.createElement('tr'));
		var count = 0;
		for (var i=0;i<this.dateArr.length;i++) {
			if(i==0 || i==7 || i==14 || i==21 || i==28 || i==35){
				if(this.dateArr[i] == "&nbsp;" && i!=0){
					break;
				}else{
					if(this.dateArr[i] == "&nbsp;"){
						$(trObj).append('<td style="background-color:#ffb2e5"><a href="javascript:void(0);">&nbsp;</a></td>');
						count = count+1;
					}else{
						$(trObj).append('<td style="background-color:#ffb2e5"><a href="'+URLVal+'" id="calDateId'+i+'">'+this.dateArr[i]+'</a></td>');
					}
				}
			}else if(i==6 || i==13 || i==20 || i==27 || i==34 || i==41){
				if(this.dateArr[i] == "&nbsp;"){
					$(trObj).append('<td style="background-color:#b2ccff"><a href="javascript:void(0);">&nbsp;</a></td>');
					count = count+1;
				}else{
					$(trObj).append('<td style="background-color:#b2ccff"><a href="'+URLVal+'" id="calDateId'+i+'">'+this.dateArr[i]+'</a></td>');
				}
			}else{
				if(this.dateArr[i] == "&nbsp;"){
					$(trObj).append('<td><a href="javascript:void(0);">&nbsp;</a></td>');
					count = count+1;
				}else{
					$(trObj).append('<td><a href="'+URLVal+'" id="calDateId'+i+'">'+this.dateArr[i]+'</a></td>');
				}
			}

			if((i+1)%7 == 0 && count != 7){
				$(divObj).find("table").append(trObj);
				var trObj = $(document.createElement('tr'));
				count = 0;
			}
		}

		$(calObj).append(divObj);
		return $(calObj);
	},
	display: function(calObj,displayType){

		$(calObj).css({
			display:displayType
		});
		$(calObj).find(".calNext").click(function () {
			TravelcomFormCalendar.next(calObj);
		});
		$(calObj).find(".calPrev").click(function () {
			TravelcomFormCalendar.prev(calObj);
		});

		if(this.browserVarsion == "ie6"){
			//IE6
			$("#SachCal").each(function(){
				$(this).remove();
			});
			$(TravelcomFormCalendar.inputObj).each(function(){
				var inputObj = $(this);

				$(calObj).css({
					width:$(inputObj).width()+5,
					top:"-"+$(inputObj).height()-10,
					left:$(inputObj).width()+10
				});
				$(calObj).find("td").css({
					width:($(inputObj).width()+1)/7
				});
				$(calObj).find("td > a").css({
					width:($(inputObj).width()+1)/7,
					display:"block"
				});
				$(calObj).find(".calCenter").css({
					width:$(inputObj).width()+5
				});
				$(calObj).find(".calCenter").click(function () {
					$(inputObj).val(parseInt(TravelcomFormCalendar.year)+"年"+parseInt(TravelcomFormCalendar.month+1)+"月");
				});
				for (var i=0;i<TravelcomFormCalendar.dateArr.length;i++) {
					$(calObj).find("#calDateId"+i).click(function () {
						TravelcomFormCalendar.date = $(this).text();
						$(inputObj).val(parseInt(TravelcomFormCalendar.year)+"年"+parseInt(TravelcomFormCalendar.month+1)+"月"+parseInt(TravelcomFormCalendar.date)+"日");
					});
				}

				$(calObj).insertAfter(inputObj);

				$(inputObj).focus();
				$(inputObj).click(function(){
					$(calObj).animate({
						opacity: 'show'
						//height: 'show'
					}, "500");
					$(".hideClass").hide();
				});

				$(inputObj).blur(function(){
					$(calObj).animate({
						opacity: 'hide'
						//height: 'hide'
					}, "500");
					$(".hideClass").show();
				});

			});
		}else{
			//IE6以外
			$("#SachCal").each(function(){
				$(this).remove();
			});
			$(TravelcomFormCalendar.inputObj).each(function(){
				var inputObj = $(this);

				$(calObj).css({
					width:$(inputObj).width()+5,
					top:"-"+$(inputObj).height()-10,
					left:$(inputObj).width()+10
				});
				$(calObj).find("td").css({
					width:($(inputObj).width()+5)/7
				});
				$(calObj).find("td > a").css({
					width:($(inputObj).width()+5)/7,
					display:"block"
				});
				$(calObj).find(".calCenter").css({
					width:$(inputObj).width()+5
				});
				$(calObj).find(".calCenter").click(function () {
					$(inputObj).val(TravelcomFormCalendar.year+"年"+parseInt(TravelcomFormCalendar.month+1)+"月");
				});
				for (var i=0;i<TravelcomFormCalendar.dateArr.length;i++) {
					$(calObj).find("#calDateId"+i).click(function () {
						TravelcomFormCalendar.date = $(this).text();
						$(inputObj).val(parseInt(TravelcomFormCalendar.year)+"年"+parseInt(TravelcomFormCalendar.month+1)+"月"+parseInt(TravelcomFormCalendar.date)+"日");
					});
				}

				$(calObj).insertAfter(inputObj);

				$(inputObj).focus();
				$(inputObj).click(function(){
					$(calObj).animate({
						opacity: 'show'
						//height: 'show'
					}, "500");
					//$(".hideClass").hide();

				});
				$(inputObj).blur(function(){
					$(calObj).animate({
						opacity: 'hide'
						//height: 'hide'
					}, "500");
					//$(".hideClass").show();
				});
			});
		}
	},
	next: function(calObj) {
		$(calObj).animate({
			opacity: 'hide'
		}, "50");
		var calObj = TravelcomFormCalendar.calDate("next");
		TravelcomFormCalendar.display(calObj,"block");
		this.nextVal = this.nextVal+1;
		this.prevVal = this.prevVal+1;
	},
	prev: function(calObj) {
		$(calObj).animate({
			opacity: 'hide'
		}, "50");
		var calObj = TravelcomFormCalendar.calDate("prev");
		TravelcomFormCalendar.display(calObj,"block");
		this.nextVal = this.nextVal-1;
		this.prevVal = this.prevVal-1;
	},
	format: function(year,month) {
		//日付を0にすると前月の末日を指定したことになります
		//指定月の翌月の0日を取得して末日を求めます
		var lastDateGet = new Date(year, month+1, 0);
		this.lastDate = lastDateGet.getDate();

		var dayStartGet = new Date(year, month, 1);
		this.dayStart = dayStartGet.getDay();
	},
	browser: function(){
		if(!jQuery.support.checkOn && jQuery.support.checkClone){
			this.browserVarsion = "chsa";
			//document.write('chromeもしくはsafari');
		}else if(jQuery.support.checkOn && jQuery.support.htmlSerialize && window.globalStorage){
			this.browserVarsion = "fx";
			//document.write('Firefox');
		}else if(jQuery.support.checkOn && jQuery.support.htmlSerialize && !window.globalStorage){
			this.browserVarsion = "op";
			//document.write('Opera');
		}else if(!jQuery.support.htmlSerialize && jQuery.support.scriptEval){
			this.browserVarsion = "ie9";
			//document.write('IE9');
		}else if(!jQuery.support.opacity){
			if(!jQuery.support.style){
				if (typeof document.documentElement.style.maxHeight != "undefined") {
					this.browserVarsion = "ie7";
					//document.write('IE7');
				} else {
					this.browserVarsion = "ie6";
					//document.write('IE6');
				}
			}else{
				this.browserVarsion = "ie8";
				//document.write('IE8');
			}
		}else{
			this.browserVarsion = "non";
			//document.write('ブラウザを特定できませんでした');
		}
	}
};


/*--------------------
	専門店マップリンク
--------------------*/
//--------専門店リンク設定--------//
if($(".SenMap")){	//適切なセレクタがあるか
	var linkSubmit = Object.create(SenmonSubmit);
}



/*
=========================================================
	検索ボックス用のJS一式
=========================================================
*/

//SearchBoxClassの略
var sbc = {
	 FormID: '#dSearchBox'	//例：#iSearchBox
	,SetTg: ''	//例：Dest	//ファセットを返したいパラメータ
	,ErrMes: '必須項目を設定してください'
	,DepDateSel: ''	//出発日セレクタ
	,SubWinID: 'SubWinBox'
	,TgIdName: '#SubWinBox'	//オーバーレイセレクタ
	,TGOverlaySelector: ':has("#SubWinBox")'
	,SetDepDateToday: ''	//出発日の入力例


	/*--------------------
		ajaxする
	----------------------*/
	,SendSearch: function(TgSelector, TgType, AddVar){
		/*変数の定義*/
		this.FormID = "#dSearchBox";
		this.SetTg = '';
		var BaseName = '/attending/senmon_kokunai/sharing/phpsc/ajax_searchBox_dfree.php';
		var ReSearchSelector = this.FormID+' input[type=text], '+this.FormID+' input[type=hidden], '+this.FormID+' input[type=checkbox]:checked, '+this.FormID+' input[type=radio]:checked';
		var ReSearchSelectorSel = this.FormID+' select';
		//var forWMDepDate = this.FormID+' input[name=p_dep_date]';
		//this.WatermarkOutDep(forWMDepDate);
		var DisabledSel = '';	//触れなくするプルダウン

		var ObjA = new Object();
		var ObjB = new Object();
		var paramObj = new Object();
		//自分の名前は何？
		var TgName = $(TgSelector).attr('name');
		var TgID = $(TgSelector).attr('ID');

		//交通機関
		if(TgName == 'p_transport'){
			//this.ClearHatsu();
			//$('select').attr('selectedIndex', '0').children('select').removeAttr('select');
			var eleSelect = document.getElementById("tab_ct_freeplan").getElementsByTagName("select");
			for(var i=0;i<eleSelect.length;i++){
				if(eleSelect[i].options.length>0 && eleSelect[i].name != 'preCity'){
					eleSelect[i].options[0].selected=true;
				}else{
					if(eleSelect[i].name == 'preCity' && $('input[name=p_transport]:checked').val() == 3){
						var cityCd = document.getElementById('cityCd').value;
						eleSelect[i].value = cityCd;
					}
				}
			}
			this.ClearDCC('Dest,Country,City');
			this.ClearCarr();
			this.ClearKikan();

			radio = document.getElementsByName('p_transport');
			// オールクリア 飛行機以外の選択
//			if(radio[0].checked || radio[2].checked || radio[3].checked){
				// デフォルトの出発地を設定
				$(this.FormID+' select[name="p_hatsu_sub"]').val($("#def_p_hatsu").val());
//			}

		}

		//出発空港
		if(TgName == 'p_dep_airport_code'){
			this.ClearArrAP();
			this.ClearDCC('Dest,Country');
			this.ClearCarr();
			this.ClearKikan();
			//値があったら、必須はhidden、無かったらview
			var RQSelector = this.FormID+' #RQ'+TgName;
			$(RQSelector).toggle($(TgSelector).val() == false);
			//固定するのは目的地
			DisabledSel = this.FormID+' #p_arr_airport_code';
			$(DisabledSel).attr('disabled', 'disabled');
		}

		//到着空港
		if(TgName == 'p_arr_airport_code'){
			this.ClearDCC('Dest,Country');
			this.ClearCarr();
			this.ClearKikan();
			//値があったら、必須はhidden、無かったらview
			var RQSelector = this.FormID+' #RQp_hatsu';
			$(RQSelector).toggle($(TgSelector).val() == false);
			//固定するのは目的地
			DisabledSel = this.FormID+' #preDest';
			$(DisabledSel).attr('disabled', 'disabled');
		}

		//自分自身がp_hatsuだったとき、方面・国・都市をクリアする必要がある
			if(TgName == 'p_hatsu' || TgName == 'p_hatsu_sub'){
			this.ClearDepAP();
			this.ClearDCC('Dest,Country');
			this.ClearBUS();
			this.ClearCarr();
			this.ClearKikan();
			//値があったら、必須はhidden、無かったらview
			var RQSelector = this.FormID+' #RQp_hatsu';
			$(RQSelector).toggle($(TgSelector).val() == false);
			//固定するのは目的地
			DisabledSel = this.FormID+' #preDest';
			$(DisabledSel).attr('disabled', 'disabled');
		}
		//自分がdestなら、国都市クリア
		if(TgName == 'preDest'){
			this.ClearDCC('Country');
			this.ClearCarr();
			this.ClearKikan();
			//値があったら、必須はhidden、無かったらview
			var RQSelector = this.FormID+' #RQ'+TgName;
			$(RQSelector).toggle($(TgSelector).val() == false);
			//固定するのは国
			DisabledSel = this.FormID+' #preCountry';
			$(DisabledSel).attr('disabled', 'disabled');
		}
		//自分がCountryなら、都市クリア
		if(TgName == 'preCountry'){
			this.ClearDCC('City');
			this.ClearCarr();
			this.ClearKikan();
			//固定するのは都市reDest
			DisabledSel = this.FormID+' #preCity';
			$(DisabledSel).attr('disabled', 'disabled');
		}
		//他もバスクリア
		if(TgName == 'preCity' || TgName == 'p_kikan_min' || TgName == 'p_kikan_max'){
			this.ClearBUS();
		}
		//キャリアもクリア
		if(TgName == 'preCity'){
			this.ClearCarr();
			this.ClearKikan();
		}

		var ObjA = FncValueSetAry(ReSearchSelector, ',');	//input型
		var ObjB = FncValueSetSelectAry(ReSearchSelectorSel, ',');	//selsect型
		var paramObj = $.extend(ObjA, ObjB);	//配列の結合

		/*Ajax*/
		switch(TgType){
			case 0:	//通常
				$.ajax({
					 data: paramObj
					,dataType: 'script'
					,url: BaseName
				});
				break;

			case 1:	//自分自身が何か＋ファセット返して欲しいのは何か
				paramObj['SetParam'] = TgName;
				if(this.SetTg == 99){
					this.SetTg = '';
				}
				paramObj['RetParam'] = this.SetTg;
				if(TgID == 'trn' || TgID == 'bs' ){
//					paramObj['RetParam'] = 4;
//					paramObj['p_hatsu_sub'] = '';
				}

				if($('#cityCd').val() != "" && TgName != 'preCity'){
					paramObj['preCity'] = $('#cityCd').val();
				}

				$.ajax({
					 data: paramObj
					,dataType: 'script'
					,url: BaseName
					,success: function(script){

						if(TgID == 'trn' || TgID == 'bs' || TgID == 'transport_none' ){
							document.getElementById('airplain').style.display = "none";
							document.getElementById('trainbus').style.display = "";

							// var myKyoten = $("#Js_setMykencode").text();
							// var options = document.getElementById('p_hatsu').options;
							// for(var i = 0; i < options.length; i++){
							// 	if(options[i].text === myKyoten){
							// 		options[i].selected = true;
							// 		break;
							// 	};
							// };
							$('select[name="p_dep_airport_code"]').attr('selectedIndex', '0').children('select').removeAttr('select');
							$('select[name="p_arr_airport_code"]').attr('selectedIndex', '0').children('select').removeAttr('select');
							$('select[name="preDest"]').attr('selectedIndex', '0').children('select').removeAttr('select');
							$('select[name="preCountry"]').attr('selectedIndex', '0').children('select').removeAttr('select');
							$('select[name="preCity"]').attr('selectedIndex', '0').children('select').removeAttr('select');

//							$("#p_hatsu").trigger('change');
							$('select[name="p_hatsu_sub"]').removeAttr("disabled");

						}
						else{
							if(TgID == 'apt' || TgID == 'none'){
								$('select[name="p_hatsu_sub"]').attr('selectedIndex', '0').children('select').removeAttr('select');
								var cityCd = $('#cityCd').val();
								$('#preCity [value="'+cityCd+'"]').attr('selected',true);
							}

						}
					}

				});
				break;
			case 2:	//出発日前へ次へ
				paramObj['SetParam'] = TgName;
				paramObj['ViewMonth'] = AddVar;
				$.ajax({
					 data: paramObj
					,dataType: 'script'
					,url: BaseName
				});
				break;
			case 3:	//出発空港ブラウザバック用
				if(TgName =='p_dep_airport_code'){
					var dV = AddVar['p_dep_airport_code'];
					paramObj['SetParam'] = TgName;
					paramObj[TgName] =  dV;
					paramObj['RetParam'] = 3;
				}


				$.ajax({
					 data: paramObj
					,dataType: 'script'
					,url: BaseName
					,success: function(html){
						if(AddVar['p_arr_airport_code']  && TgName =='p_dep_airport_code'){
							var cV = AddVar['p_arr_airport_code'];
							$('#dSearchBox').find('#p_arr_airport_code').val(cV).removeAttr("disabled");
							sbc.SendSearch("#p_arr_airport_code", 4,AddVar);
						}
						else if(AddVar['preDest']  &&TgName =='p_dep_airport_code'){
							var cV = AddVar['preDest'];
							$('#dSearchBox').find('#preDest').val(cV);
							sbc.SendSearch("#preDest", 8,AddVar);
						}
						else{
							if(AddVar['p_dep_date']){
							$('#dSearchBox input[name=p_dep_date]').val(AddVar['p_dep_date']);
						}
						if(AddVar['p_kikan_min']){
							if(AddVar['p_kikan_max'] == 0){
							$('#dSearchBox select[name=p_kikan_max]').val(AddVar['p_kikan_max']).trigger('change');
							}
							else{
							$('#dSearchBox select[name=p_kikan_min]').val(AddVar['p_kikan_min']).trigger('change');
								}
						}
						if(AddVar['p_kikan_max']){
							$('#dSearchBox select[name=p_kikan_max]').val(AddVar['p_kikan_max']).trigger('change');
						}

						}
					}
				});
				break;
			case 4:	//到着空港ブラウザバック用
				if(TgName =='p_arr_airport_code'){
					var dV = AddVar['p_arr_airport_code'];
					paramObj['SetParam'] = TgName;
					paramObj[TgName] =  dV;
					paramObj['RetParam'] = 0;
				}

				$.ajax({
					 data: paramObj
					,dataType: 'script'
					,url: BaseName
					,success: function(html){

						if(AddVar['preDest']  && TgName =='p_arr_airport_code'){
							var cV = AddVar['preDest'];
							$('#dSearchBox').find('#preDest').val(cV).removeAttr("disabled");
							sbc.SendSearch("#preDest", 8,AddVar);
						}
						else{
							if(AddVar['p_dep_date']){
							$('#dSearchBox input[name=p_dep_date]').val(AddVar['p_dep_date']);
						}
						if(AddVar['p_kikan_min']){
							if(AddVar['p_kikan_max'] == 0){
							$('#dSearchBox select[name=p_kikan_max]').val(AddVar['p_kikan_max']).trigger('change');
							}
							else{
							$('#dSearchBox select[name=p_kikan_min]').val(AddVar['p_kikan_min']).trigger('change');
								}
						}
						if(AddVar['p_kikan_max']){
							$('#dSearchBox select[name=p_kikan_max]').val(AddVar['p_kikan_max']).trigger('change');
						}

						}
					}
				});
				break;

			case 5:	//出発地ブラウザバック用
				if(TgName =='p_hatsu_sub'){
					var dV = AddVar['p_hatsu_sub'];
					paramObj['SetParam'] = TgName;
					paramObj[TgName] =  dV;
					paramObj['RetParam'] = 0;
									}

				$.ajax({
					 data: paramObj
					,dataType: 'script'
					,url: BaseName
					,success: function(html){


						if(AddVar['preDest']  && TgName =='p_hatsu_sub'){
							var cV = AddVar['preDest'];
							$('#dSearchBox').find('#preDest').val(cV).removeAttr("disabled");
							$('#dSearchBox').find('#p_hatsu').val(dV);
							sbc.SendSearch("#preDest", 8,AddVar);
						}
						else{
							$('#preDest').removeAttr("disabled");
							if(AddVar['p_dep_date']){
							$('#dSearchBox input[name=p_dep_date]').val(AddVar['p_dep_date']);
						}
						if(AddVar['p_kikan_min']){
							if(AddVar['p_kikan_max'] == 0){
							$('#dSearchBox select[name=p_kikan_max]').val(AddVar['p_kikan_max']).trigger('change');
							}
							else{
							$('#dSearchBox select[name=p_kikan_min]').val(AddVar['p_kikan_min']).trigger('change');
								}
						}
						if(AddVar['p_kikan_max']){
							$('#dSearchBox select[name=p_kikan_max]').val(AddVar['p_kikan_max']).trigger('change');
						}

						}
					}
				});
				break;
			case 6:	//countryブラウザバック用

				if(TgName =='preCountry'){
					var cV = AddVar['preCountry'];
					paramObj['SetParam'] = TgName;
					paramObj[TgName] =  cV;
					//paramObj['RetParam'] = 2;
					paramObj['RetParam'] = 0;
				}

				$.ajax({
					 data: paramObj
					,dataType: 'script'
					,url: BaseName
					,success: function(html){
						//$("select[name='preCity']").val(AddVar);
						if(AddVar['preCity']  && TgName =='preCountry'){
							var cityV = AddVar['preCity'];
							$('#dSearchBox').find('#preCity').val(cityV).removeAttr("disabled");
							sbc.SendSearch("#preCity", 7,AddVar);
						}
						else{
							if(AddVar['p_dep_date']){
							$('#dSearchBox input[name=p_dep_date]').val(AddVar['p_dep_date']);
						}
						if(AddVar['p_kikan_min']){
							if(AddVar['p_kikan_max'] == 0){
							$('#dSearchBox select[name=p_kikan_max]').val(AddVar['p_kikan_max']).trigger('change');
							}
							else{
							$('#dSearchBox select[name=p_kikan_min]').val(AddVar['p_kikan_min']).trigger('change');
								}
						}
						if(AddVar['p_kikan_max']){
							$('#dSearchBox select[name=p_kikan_max]').val(AddVar['p_kikan_max']).trigger('change');
						}

						}
					}
				});
				break;

			case 7:	//cityブラウザバック用
				if(TgName =='preCity'){
					var cityV = AddVar['preCity'];
					paramObj['SetParam'] = TgName;
					paramObj[TgName] =  cityV;
					paramObj['RetParam'] = 0;
				}

				$.ajax({
					 data: paramObj
					,dataType: 'script'
					,url: BaseName
					,timeout : 0
					,success: function(){
						if(AddVar['p_dep_date']){
							$('#dSearchBox input[name=p_dep_date]').val(AddVar['p_dep_date']);
						}
						if(AddVar['p_kikan_min']){
							if(AddVar['p_kikan_max'] == 0){
							$('#dSearchBox select[name=p_kikan_max]').val(AddVar['p_kikan_max']).trigger('change');
							}
							else{
							$('#dSearchBox select[name=p_kikan_min]').val(AddVar['p_kikan_min']).trigger('change');
								}
						}
						if(AddVar['p_kikan_max']){
							$('#dSearchBox select[name=p_kikan_max]').val(AddVar['p_kikan_max']).trigger('change');
						}

					}
					//, success: function(data) { alert(data)}
				});
				break;

				case 8:	//destブラウザバック用

				if(TgName =='preDest'){
					var dV = AddVar['preDest'];
					paramObj['SetParam'] = TgName;
					paramObj[TgName] =  dV;
					paramObj['RetParam'] = 1;
				}

				$.ajax({
					 data: paramObj
					,dataType: 'script'
					,url: BaseName
					,success: function(html){

						if(AddVar['preCountry']  && TgName =='preDest'){
							var cV = AddVar['preCountry'];
							$('#dSearchBox').find('#preCountry').val(cV).removeAttr("disabled");
							sbc.SendSearch("#preCountry", 6,AddVar);
						}
						else{
							if(AddVar['p_dep_date']){
							$('#dSearchBox input[name=p_dep_date]').val(AddVar['p_dep_date']);
						}
						if(AddVar['p_kikan_min']){
							if(AddVar['p_kikan_max'] == 0){
							$('#dSearchBox select[name=p_kikan_max]').val(AddVar['p_kikan_max']).trigger('change');
							}
							else{
							$('#dSearchBox select[name=p_kikan_min]').val(AddVar['p_kikan_min']).trigger('change');
								}
						}
						if(AddVar['p_kikan_max']){
							$('#dSearchBox select[name=p_kikan_max]').val(AddVar['p_kikan_max']).trigger('change');
						}

						}
					}
				});
				break;

			case 9:	//自分自身が何か＋ファセット返して欲しいのは何か
				paramObj['SetParam'] = TgName;
				if(this.SetTg == 99){
					this.SetTg = '';
				}
					paramObj['RetParam'] = 4;
					paramObj['p_hatsu_sub'] = '';
				var cV = AddVar['p_hatsu_sub'];

				$.ajax({
					 data: paramObj
					,dataType: 'script'
					,url: BaseName
					,success: function(){

							//$("#p_hatsu").trigger('change');
							$('select[name="p_hatsu_sub"]').removeAttr("disabled");


						 if(AddVar['p_hatsu_sub']  && TgName =='p_transport'){
							var cV = AddVar['p_hatsu_sub'];
							$('#dSearchBox').find('#p_hatsu').val(cV);
							sbc.SendSearch("#p_hatsu", 5,AddVar);
						}
						else{
							if(AddVar['p_dep_date']){
							$('#dSearchBox input[name=p_dep_date]').val(AddVar['p_dep_date']);
						}
						if(AddVar['p_kikan_min']){
							if(AddVar['p_kikan_max'] == 0){
							$('#dSearchBox select[name=p_kikan_max]').val(AddVar['p_kikan_max']).trigger('change');
							}
							else{
							$('#dSearchBox select[name=p_kikan_min]').val(AddVar['p_kikan_min']).trigger('change');
								}
						}
						if(AddVar['p_kikan_max']){
							$('#dSearchBox select[name=p_kikan_max]').val(AddVar['p_kikan_max']).trigger('change');
						}

						}
					}




				});
				break;
		}
		//固定戻す
		if(DisabledSel){
			$(DisabledSel).attr('disabled', false);
		}
		//出発日戻す
		//sbc.WatermarkDep(forWMDepDate);

	}
/*出発地をクリアする*/
	,ClearHatsu: function(){
		var HatsuSel = this.FormID+' #p_hatsu_sub';
		/*バスは全共通*/
		if($(HatsuSel).attr('id')){
			$(':gt(0)',HatsuSel).remove();
			sbc.SetTg = 4;
		}
	}

/*出発空港をクリアする*/
	,ClearDepAP: function(){
		//出発空港セレクタ
		var DepApSel = this.FormID+' select[id=p_dep_airport_code]';
		/*出発空港は全共通*/
		if($(DepApSel).attr('id')){
			$(':gt(0)',DepApSel).val();
		}
	}/*ClearDepAP*/

	,ClearArrAP: function(){
		//バスセレクタ
		var ArrSel = this.FormID+' #p_arr_airport_code';
		/*バスは全共通*/
		if($(ArrSel).attr('id')){
			$(':gt(0)',ArrSel).remove();
			sbc.SetTg = 3;
		}
	}

	/*バスをクリアする*/
	,ClearBUS: function(){
		//バスセレクタ
		var BusSel = this.FormID+' #p_bus_boarding_code';
		/*バスは全共通*/
		if($(BusSel).attr('id')){
			$(':gt(0)',BusSel).remove();
		}
	}
	/*日数をクリアする*/
	,ClearKikan: function(){
		//バスセレクタ
		var KikanMinSel = this.FormID+' #p_kikan_min';
		var KikanMaxSel = this.FormID+' #p_kikan_max';
		/*バスは全共通*/
		if($(KikanMinSel).attr('id')){
			$(':gt(0)',KikanMinSel).remove();
		}
		if($(KikanMaxSel).attr('id')){
			$(':gt(0)',KikanMaxSel).remove();
		}
	}
	/*キャリアをクリアする*/
	,ClearCarr: function(){
		//航空会社セレクタ
		var CarrSel = this.FormID+' select[id=p_carr]';
		/*航空会社は全共通*/
		if($(CarrSel).attr('id')){
			$(':gt(0)',CarrSel).remove();
		}
	}

	/*方面・国・都市をクリアするサブモジュール*/
	,ClearDCC: function(Type){
		//方面・国・都市セレクタ
		var DestSel = this.FormID+' #preDest';
		var CountrySel = this.FormID+' #preCountry';
		var CitySel = this.FormID+' #preCity';
		var formId = this.FormID;
		var destObj = $(formId).find("#preDest");
		var countryObj = $(formId).find("#preCountry");
		var cityObj = $(formId).find("#preCity");

		var TypeAry = Type.split(',');
		jQuery.each(TypeAry, function(i, str) {
			switch(str){
				case 'Dest':
					if(destObj.attr('type') != 'hidden'){
						if($(':first',destObj).val() == ''){
							$(':gt(0)',destObj).remove();
							if(sbc.SetTg === ''){
								sbc.SetTg = 0;
							}
							var RQSelector = sbc.FormID+' #RQpreDest';
							$(RQSelector).show();
						}
						else{
							if(sbc.SetTg === ''){
								sbc.SetTg = 99;
							}
						}
					}
					break;
				case 'Country':
					if(countryObj.attr('type') != 'hidden'){
						if($(':first',countryObj).val() == ''){
						//if($(testForm).find("#preCountry").eq(0).val() == ''){
							$(':gt(0)',countryObj).remove();
							if(sbc.SetTg === ''){
								sbc.SetTg = 1;
							}
						//}
						}
						else{
							if(sbc.SetTg === ''){
								sbc.SetTg = 99;
							}
						}
					}
					break;
				case 'City':
					if(cityObj.attr('type') != 'hidden'){
						if($(':first',cityObj).val() == ''){
							$(':gt(0)',cityObj).remove();
							if(sbc.SetTg === ''){
								sbc.SetTg = 2;
							}
						}
						else{
							if(sbc.SetTg === ''){
								sbc.SetTg = 99;
							}
						}
					}
					break;
			}
		});
	}
	/*送信するとき*/
	,Submit: function(ClickBtn){

		this.FormID = "#dSearchBox";

		var ReSearchSelector = this.FormID+' input[type=text], '+this.FormID+' input[type=hidden]';
		var ReSearchSelectorSel = this.FormID+' select';

		var ObjA = new Object();
		var ObjB = new Object();
		var paramObj = new Object();

		var ObjA = FncValueSetAry(ReSearchSelector, ',');	//input型
		var ObjB = FncValueSetSelectAry(ReSearchSelectorSel, ',');	//selsect型
		var paramObj = $.extend(ObjA, ObjB);	//配列の結合

		/*出発日処理*/
		var forWMDepDate = this.FormID+' input[name=p_dep_date]';
		//this.WatermarkOutDep(forWMDepDate);

		/*必須チェック*/
		//出発地
		var checkHatsuVal = false;
		if(paramObj['MyNaigai'] == 'i'){
			checkHatsuVal = paramObj['p_hatsu'];
		}
/*		else if(paramObj['MyNaigai'] == 'd'){
			checkHatsuVal = paramObj['p_hatsu_sub'];
		}*/

		//ちぇっくちぇっく
//		if(!checkHatsuVal || !paramObj['preDest']){
		/*if(!checkHatsuVal){
			alert(sbc.ErrMes);
			void(0);
			return false;
		}*/

		/*サイトカタリストの処理*/
		utilityJs.SAS_setCookie('SAS_VARS_TYPE', '検索', '', '/', 'hankyu-travel.com', '');

		/*目的地の処理*/
		var MokutekiVal = '';
		if(paramObj['preDest']){
			var DestSplit = paramObj['preDest'].split(',');
			if(DestSplit.length > 1){	//複数方面
				jQuery.each(DestSplit, function(i, val) {
					if(i > 0){
						MokutekiVal += ',';
					}
					MokutekiVal += val + '--';
				});
			}
			else{
				//方面はひとつ、国が複数
				if(paramObj['preCountry']){
					var CountrySplit = paramObj['preCountry'].split(',');
					if(CountrySplit.length > 1){	//複数国
						jQuery.each(CountrySplit, function(i, val) {
							if(i > 0){
								MokutekiVal += ',';
							}
							MokutekiVal += paramObj['preDest'] + '-' + val + '-';
						});
					}
					else{
						if((paramObj['fksCity'] && paramObj['fksCity'].length > 0 && !paramObj['preCity']) || (paramObj['preCity'] && paramObj['preCity'].length <= 0)){
                            var citySplit = paramObj['fksCity'].split(',');
                            if(citySplit.length > 1){ //複数都市
                                jQuery.each(citySplit, function (i, val) {
                                    if (i > 0) {
                                        MokutekiVal += ',';
                                    }
                                    MokutekiVal += paramObj['preDest'] + '-' + paramObj['preCountry'] + '-' + val;
                                });
                            }
                        }else{
							MokutekiVal = paramObj['preDest'] + '-' + paramObj['preCountry'] + '-' + paramObj['preCity'];
						}
					}
				}
				//国はひとつ
				else{
					MokutekiVal = paramObj['preDest'] + '-' + paramObj['preCountry'] + '-' + paramObj['preCity'];
				}
			}
		}

		MokutekiVal = MokutekiVal.replace(/undefined/ig, '');
		if(MokutekiVal == '--'){
			MokutekiVal = '';
		}
		var ApStr = '<input type="hidden" name="p_mokuteki" value="' + MokutekiVal + '" />'
		$(this.FormID).append(ApStr);
		//window.historyに保存
		var stateVal = '';
		for ( var $key in paramObj ) {
			$name = $key;
			if (!paramObj[$name].match(/例/)) {
				stateVal += $name + '-' + paramObj[$name] + ':';
			}
		}
		sessionStorage.setItem(stateName, stateVal);

		var strtp = $("input[name=p_transport]:checked").val();
		sessionStorage.setItem( "p_transport" , strtp );

		$(this.FormID).submit();
		void(0);
		return false;
	}
	/*リセットするとき*/
	,Reset: function(ClickBtn){
		this.FormID = "#dSearchBox";

		$(this.FormID).each(function(){
			this.reset();
		});

		// IEの処理
		if (ua.match("MSIE") || ua.match("Trident")) { // MSIEまたはTridentが入っていたら
			$(".transport_input #apt").prop('checked', true); // 飛行機にチェック
		}

		//値があったら、必須はhidden、無かったらview
		var RQSelector = this.FormID+' #RQp_hatsu';
		$(RQSelector).toggle($('#p_hatsu').val() == false);
		var RQSelector = this.FormID+' #RQp_arr_airport_code';
		$(RQSelector).toggle($('#p_arr_airport_code').val() == false);
		var RQSelector = this.FormID+' #RQpreDest';
		$(RQSelector).toggle($('#preDest').val() == false);
		//分類バスも
		var RQSelector = this.FormID+' #bus_bunrui';
		$(RQSelector).val('');

		//リクエストしなおし
		if($('select[name=p_conductor]', this.FormID).attr('type') == 'select-one'){
			this.SendSearch($('select[name=p_conductor]', this.FormID), 1);
		}
		else if($('select[name=p_kikan_min]', this.FormID).attr('type') == 'select-one'){
			this.SendSearch($('select[name=p_kikan_min]', this.FormID), 1);
		}
		else if($('select[name=p_hatsu]', this.FormID).attr('type') == 'select-one'){
			this.SendSearch($('select[name=p_hatsu]', this.FormID), 1);
		}
		else{
			this.SendSearch($('input[name=p_transport]', this.FormID), 1);
		}
	}

	/*出発日*/
	,DepDate: function(DepDateSel){

		this.FormID = "#dSearchBox";
		//まだボックスが出てなければ表示させる
		if(!$('body').is(this.TGOverlaySelector)){
			//メッセージボックスを作る
			MakeOverLay('auto', 700, 'body', this.SubWinID, this.SubWinID);	//高さ、幅、どこに作る、ID、Class
			if(!this.FormID){
				this.FormID = '#' + $(DepDateSel).parents('form').attr('id');
			}
			// IE6でselectとobjectが前面に来る対策 - 非表示にする
			//$("select,object").css("visibility","hidden");
			$(this.TgIdName).hide();	//Ajax終わるまで隠しておく
			//通信
			this.SendSearch(DepDateSel, 1);
			//入れ物の位置を設定
			var Offset = $(DepDateSel).position();
			var scrollTop = $('body').scrollTop();
			if(scrollTop == 0){
				scrollTop = $('html').scrollTop();
			}
			var top  = Math.floor(($(window).height() - $(this.TgIdName).height()) / 2) + scrollTop -200;
			var left = Math.floor(($(window).width() - $(this.TgIdName).width()) / 2);
			if(top<0){
				top = 0;
			}
			$(this.TgIdName)
				.css({
					 "top": top
					,"left": left
			}).fadeIn();
		}
		//this.WatermarkOutDep(DepDateSel);
	}
	,DelSubWinforSenmon: function(){
		if($('body').is(sbc.TGOverlaySelector)){
			$(sbc.TgIdName).fadeOut("fast", function() {
				$(sbc.TgIdName).remove();
			});
			//IE6対策を元に戻す
			//$("select,object").css("visibility","visible");
		}
	}
	/*出発日透かし文字*/
	,WatermarkDep: function(DepDateSel){
		if(this.SetDepDateToday == ''){
			this.setDateForDep();
		}
		if($(DepDateSel).val() == '' || $(DepDateSel).val() == this.SetDepDateToday){
			$(DepDateSel).val(this.SetDepDateToday).addClass('NS_Watermark');
		}
		else{
			$(DepDateSel).removeClass('NS_Watermark');
		}
	}
	/*出発日透かし文字を消す*/
	,WatermarkOutDep: function(DepDateSel){
		if(this.SetDepDateToday == ''){
			this.setDateForDep();
		}
		if($(DepDateSel).val() == this.SetDepDateToday){
			$(DepDateSel).val('');
		}
	}
	/*出発日でAjaxしなきゃ*/
	,WatermarkAjaxDep: function(DepDateSel){
		this.FormID = "#dSearchBox";
		var FormID = this.FormID;
		if(!this.FormID){
			FormID = '#' + $(DepDateSel).parents('form').attr('id');
		}
		//リクエストしなおし
		if($('select[name=p_conductor]', FormID)[0].type == 'select-one'){
			this.SendSearch($('select[name=p_conductor]', FormID), 1);
		}
		else if($('input[name=p_conductor]', FormID)[0].type== 'hidden'){
			this.SendSearch($('input[name=p_conductor]', FormID), 1);
		}
		else if($('select[name=	p_kikan_min]', FormID)[0].type == 'select-one'){
			this.SendSearch($('select[name=p_kikan_min]', FormID), 1);
		}
		//this.WatermarkDep(DepDateSel);
	}
	/*今日の日付*/
	,setDateForDep: function(){
		//今日の日付
		var Today = new Date();
		var Y = Today.getFullYear();
		var M  = Today.getMonth() + 1;
		var D = Today.getDate();
		this.SetDepDateToday = '例）' + Y + '/' + M + '/' + D;
	}

};

//--------------------
//	日付を押したとき（出発日）
//----------------------
function SWDate (SetVal){
	var DepDateSel = sbc.FormID + ' input[name=p_dep_date]';
	$(DepDateSel).val(SetVal).removeClass('NS_Watermark');	//セットして
	sbc.DelSubWinforSenmon();	//閉じる
	//IE6対策を元に戻す
	//$("select,object").css("visibility","visible");

}
//--------------------
//	前へ次へ（出発日）
//----------------------
function NextBackBtnAction (DepDate){
	var DepDateSel = sbc.FormID + ' input[name=p_dep_date]';
	$(DepDateSel).focus();
	//通信
	sbc.SendSearch(DepDateSel, 2, DepDate);
	void(0);
	return false;
}



/*----------------- class="JS_SearchBox"
	ロード時
-------------------*/
$(function(){
//	getInitP_hit_num();
	/*念のため、出発日サブウィンドウは消しておく*/
	sbc.DelSubWinforSenmon();
	//ターゲットのフォーム
	var SearchBoxForm = 'form[name=dSearchBox]';
	/*----- 色々触ったらAjax -----*/
	$('#dSearchBox select').change(function() {
		sbc.SendSearch(this, 1);
		void(0);
		return false;
	});
	$('#dSearchBox input[type=checkbox]').click(function() {
		//バスの場合の特別処理
		/*バスは全共通*/
		if($(this).val() == '1' && $(this).attr('name') == 'p_transport' && $("#bus_bunrui").is("*")){
			var BusChecked = $(this).attr('checked');
			if(BusChecked === false){
				$('#bus_bunrui').val('');
			}
			else{
				$('#bus_bunrui').val('813');
			}
		}
		sbc.SendSearch(this, 1);
	});
	$('#dSearchBox input[type=radio]').change(function() {

		radio = document.getElementsByName('p_transport');
		if(radio[0].checked || radio[2].checked || radio[3].checked){

			sbc.SendSearch(this, 1);
		}
		else{
			sbc.SendSearch(this, 1);
		}
	});

	/*----- Submit Reset -----*/
	$('#dSearchBox .btn_simpleSrch').click(function() {
		sbc.Submit(this);
		void(0);
		return false;
	});
	/*$(SearchBoxForm+' .JS_Reset').click(function() {
		sbc.Reset(this);
		void(0);
		return false;
	});*/

	$(document).on('click', '.clBtn a', function () {
		document.getElementById('airplain').style.display = "";
		document.getElementById('trainbus').style.display = "none";
		var eleSelect=document.getElementById("tab_ct_freeplan").getElementsByTagName("select");
		for(var i=0;i<eleSelect.length;i++){
			if(eleSelect[i].options.length>0){
				eleSelect[i].options[0].selected=true;
			}
		}
		sbc.Reset(this);
		void(0);
		return false;
	});

	/*----- 出発日は特別 -----*/
	$('#dSearchBox input[name=p_dep_date]').click(function() {
		sbc.DepDate(this);
	});
	$('#dSearchBox .js_dep_date_cal').click(function() {
		$('#dSearchBox input[name=p_dep_date]').trigger("click");
	});

	/*----- 外側クリック対策 -----*/
	$('html').click(function(depEvent) {
		var TargetClass = $(depEvent.target).attr('class');
		var TargetName = $(depEvent.target).attr('name');
		if(TargetClass !== 'SW_CalNext' && TargetClass !== 'SW_CalBack' && TargetName !== 'p_dep_date' && TargetClass !== 'js_dep_date_cal'){
			if($('html').is(sbc.TGOverlaySelector)){
				//ウィンドウ消す
				sbc.DelSubWinforSenmon();
				var forWMDepDate = 'form input[name=p_dep_date]';
				jQuery.each($(forWMDepDate), function(i, val) {
					sbc.WatermarkAjaxDep(this);
				});
			}
		}

	});
	/*----- 出発日ウォーターマーク -----*/
	var forWMDepDate = SearchBoxForm+' input[name=p_dep_date]';
	/*出発日の透析文字を設定する*/
	$(forWMDepDate).attr("placeholder","例）"+getYYYYMMDD(0));
	//sbc.WatermarkDep(forWMDepDate);
	/*変更があったときもね*/
	$(forWMDepDate).change(function() {
//		sbc.WatermarkOutDep(forWMDepDate);
		sbc.WatermarkAjaxDep(this);
	});
	var tpval = sessionStorage.getItem("p_transport");
	var stateVal = sessionStorage.getItem(stateName);
	var $setPara={};
	if(stateVal){
		var $ParaAry = stateVal.split(':');

		for ( var $num in $ParaAry ) {
			var $TmparaAry = $ParaAry[$num].split('-');

			if($TmparaAry[1] && $TmparaAry[1] != 'undefined' && $TmparaAry[1] != ''){
				$setPara[$TmparaAry[0]] =  $TmparaAry[1];
			}
		}

		if($setPara){
			SetValueStoragePara($setPara);
		}
	}
});

//=============================================
//	sessionStorageパラメータを選択BOXにセットする
//=============================================
function SetValueStoragePara($SelPara){
	var tpval = sessionStorage.getItem("p_transport");
	var stHatsuval = sessionStorage.getItem("p_hatsu_sub");

	if(tpval == 3){
		document.getElementById('airplain').style.display = "";
		document.getElementById('apt').checked = true;
		document.getElementById('trainbus').style.display = "none";

		if($SelPara['p_dep_airport_code']){
			var flg = false;
			$("#p_dep_airport_code option").each(function() {
				var key = $(this).val();
				if(key == $SelPara['p_dep_airport_code']){
					flg = true;
					return;
				}
			});
			if( flg ){
				$("#p_dep_airport_code").val($SelPara['p_dep_airport_code']);
			}else{

			}
			sbc.SendSearch('#p_dep_airport_code', 3,$SelPara);
			}
		else if($SelPara['p_arr_airport_code']){
			$("#p_arr_airport_code").val($SelPara['p_arr_airport_code']);
			sbc.SendSearch('#p_arr_airport_code', 4,$SelPara);
			}
		else if($SelPara['preDest']){
			$("#preDest").val($SelPara['preDest']);
			sbc.SendSearch('#preDest', 8,$SelPara);
			}
		else if($SelPara['preCountry']){
			$("#preCountry").val($SelPara['preCountry']);
			sbc.SendSearch('#preCountry', 6,$SelPara);
			}
		else{
			if($SelPara['p_conductor']){
				$('#dSearchBox select[name=p_conductor]').val($SelPara['p_conductor']);
			}
			if($SelPara['p_dep_date']){
				$('#dSearchBox input[name=p_dep_date]').val($SelPara['p_dep_date']);
			}
			sbc.SendSearch('#dSearchBox select[name=p_conductor]', 0);
		}
	}

	else if(tpval == 2){
		document.getElementById('airplain').style.display = "none";
		document.getElementById('trainbus').style.display = "";
		document.getElementById('trn').checked = true;

		if($SelPara['p_hatsu_sub']){
			sbc.SendSearch('#trn', 9, $SelPara);
		}

		else if($("select[name=p_hatsu_sub]")){
			$("#p_hatsu").val($SelPara['p_hatsu_sub']);
			sbc.SendSearch('#p_hatsu', 5,$SelPara);
		}
		else if($SelPara['preDest']){
			$("#preDest").val($SelPara['preDest']);
			sbc.SendSearch('#preDest', 8,$SelPara);
		}
		else if($SelPara['preCountry']){
			$("#preCountry").val($SelPara['preCountry']);
			sbc.SendSearch('#preCountry', 6,$SelPara);
		}
		else{
			if($SelPara['p_conductor']){
				$('#dSearchBox select[name=p_conductor]').val($SelPara['p_conductor']);
			}
			if($SelPara['p_dep_date']){
				$('#dSearchBox input[name=p_dep_date]').val($SelPara['p_dep_date']);
			}
			sbc.SendSearch('#dSearchBox select[name=p_conductor]', 0);
		}
	}
	else if(tpval == ''){
		document.getElementById('airplain').style.display = "none";
		document.getElementById('trainbus').style.display = "";
		document.getElementById('transport_none').checked = true;

		if($SelPara['p_hatsu_sub']){
			sbc.SendSearch('#trn', 9, $SelPara);
		}

		else if($("select[name=p_hatsu_sub]")){
			$("#p_hatsu").val($SelPara['p_hatsu_sub']);
			sbc.SendSearch('#p_hatsu', 5,$SelPara);
		}
		else if($SelPara['preDest']){
			$("#preDest").val($SelPara['preDest']);
			sbc.SendSearch('#preDest', 8,$SelPara);
		}
		else if($SelPara['preCountry']){
			$("#preCountry").val($SelPara['preCountry']);
			sbc.SendSearch('#preCountry', 6,$SelPara);
		}
		else{
			if($SelPara['p_conductor']){
				$('#dSearchBox select[name=p_conductor]').val($SelPara['p_conductor']);
			}
			if($SelPara['p_dep_date']){
				$('#dSearchBox input[name=p_dep_date]').val($SelPara['p_dep_date']);
			}
			sbc.SendSearch('#dSearchBox select[name=p_conductor]', 0);
		}
	}
	else{
		document.getElementById('airplain').style.display = "none";
		document.getElementById('trainbus').style.display = "";
		document.getElementById('bs').checked = true;

		if($SelPara['p_hatsu_sub']){
			sbc.SendSearch('#trn', 9, $SelPara);
		}

		else if($("select[name=p_hatsu_sub]")){
			$("#p_hatsu").val($SelPara['p_hatsu_sub']);
			sbc.SendSearch('#p_hatsu', 5,$SelPara);
		}
		else if($SelPara['preDest']){
			$("#preDest").val($SelPara['preDest']);
			sbc.SendSearch('#preDest', 8,$SelPara);
		}
		else if($SelPara['preCountry']){
			$("#preCountry").val($SelPara['preCountry']);
			sbc.SendSearch('#preCountry', 6,$SelPara);
		}
		else{
			if($SelPara['p_conductor']){
				$('#dSearchBox select[name=p_conductor]').val($SelPara['p_conductor']);
			}
			if($SelPara['p_dep_date']){
				$('#dSearchBox input[name=p_dep_date]').val($SelPara['p_dep_date']);
			}
			sbc.SendSearch('#dSearchBox select[name=p_conductor]', 0);
		}

		}

}

function Change(){
	$("input[name=p_dep_date]").val("");
	radio = document.getElementsByName('p_transport')
	if(radio[1].checked) {
		document.getElementById('airplain').style.display = "";
		document.getElementById('trainbus').style.display = "none";
		var eleSelect=document.getElementById("tab_ct_freeplan").getElementsByTagName("select");
		for(var i=0;i<eleSelect.length;i++){
			if(eleSelect[i].options.length>0){
				eleSelect[i].options[0].selected=true;
			}
		}
	}

	window.onload = Change;
}

onload = function() {
  document.forms[0].reset();
}
/*
*日付取得 yyyy/mm/dd
*/
var getYYYYMMDD=function(nextDays){
	var date=new Date();
	date.setDate(date.getDate()+nextDays);
	var year=date.getFullYear();
	var month=date.getMonth()+1;
	var day=date.getDate();
	if(month<10){
		return year+"/0"+month+"/"+day;
	}
	return year+"/"+month+"/"+day;
}
/*
var getInitP_hit_num=function(){
	var param={MyNaigai:"d",
	p_bunrui:"030",
	MyType:"freeplan-d",
	p_transport:"3",
	SetParam:"p_transport",
	RetParam:0};
	$.ajax({
		url:"/attending/freeplan-d/phpsc/ajax_getInitP_hit_num.php",
		data:param,
		method:"GET",
		success:function(res){
			eval(res.substring(0,res.indexOf(";")+1));
		}
	});
}
*/
