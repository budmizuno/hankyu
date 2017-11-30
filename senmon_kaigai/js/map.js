
/*
* jQuery mapSubmitFn Plugin
* Copyright (c) 2010 wlkuro
*/

var holidayData;
var jsonData;

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
		nameVar ='http://'+location.hostname+nameVar;
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
		//海外トップ用
			$.ajax({
				contentType:"",
				type: "GET",
				processData:true,
				url: '/attending/kaigai/phpsc/ajax_maplink.php?'+param+'&MyNaigai=i&MyPath=top&MyHomen=top',
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
			param +='&MyPageName=kaigai';//人気の観光地表示用

			$.ajax({
				contentType:"",
				type: "GET",
				processData:true,
				url: '/attending/kaigai/phpsc/ajax_maplink.php?'+param,
				success: function(json) {
					
					$(".Map").animate({
						opacity: 0
					}, "500",function () {
						var jObj = $(json);
						$(jObj).find("div.Map").css({  
					
							opacity: 0  
						});
						$(".Map").replaceWith(json);
	
					});
										
				},
				dataType: 'html'
			});
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
			var hostname = window.location.hostname ;
			var nameVar = $(obj).attr("name");
			$("#senmonSubmitFrInput").attr("value",inputVar);
			$("#senmonSubmitFr").attr("action","http://"+hostname+nameVar);
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
			var hostname = window.location.hostname ;
			var nameVar = $(obj).attr("name");
			$("#senmonSubmitFr").attr("action","http://"+hostname+nameVar);
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
			nameVar ='http://'+location.hostname+nameVar;
			$("#senmonSubmitFrInput").attr("value",inputVar);
			$("#senmonSubmitFr").attr("action",nameVar);
			$("#senmonSubmitFr").trigger("submit");
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



