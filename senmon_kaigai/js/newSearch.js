var sbc = {
    FormID: ""	//例：#iSearchBox
    , Naigai: 'i'
    , SetTg: ''	//例：Dest	//ファセットを返したいパラメータ
    , ErrMes: '必須項目を設定してください'
    , DepDateSel: ''	//出発日セレクタ
    , SubWinID: 'SubWinBox'
    , TgIdName: '#SubWinBox'	//オーバーレイセレクタ
    , TGOverlaySelector: ':has("#SubWinBox")'
    , SetDepDateToday: ''	//出発日の入力例

    /*--------------------
     ajaxする
     ----------------------*/
    , SendSearch: function (TgSelector, TgType, AddVar) {
        // 検索項目を変更したなら
        $("#tour_init_flag").val("1");

        /*変数の定義*/
        this.FormID = '#iSearchBox';
        this.SetTg = '';
//        var BaseName = '/sharing/__br/phpsc/ajax_searchBox.php';
        var BaseName = '/attending/senmon_kaigai/sharing/phpsc/ajax_searchBox.php';

        if ($(this.FormID + " input[type=hidden][name=MyType]").val() == "bus") {
            BaseName = '/sharing/__br/phpsc/ajax_searchBox_bus.php';
        }
        var ReSearchSelector = this.FormID + ' input[type=text], ' + this.FormID + ' input[type=hidden], ' + this.FormID + ' input[type=checkbox]:checked, ' + this.FormID + ' input[type=radio]:checked';
        var ReSearchSelectorSel = this.FormID + ' select';
        //var forWMDepDate = this.FormID+' input[name=p_dep_date]';
        var DisabledSel = '';	//触れなくするプルダウン

        var ObjA = new Object();
        var ObjB = new Object();
        var paramObj = new Object();
        //自分の名前は何？
        var TgName = $(TgSelector).attr('name');

        //自分自身がp_hatsuだったとき、方面・国・都市をクリアする必要がある
        if (TgName == 'p_hatsu' || TgName == 'p_hatsu_sub') {
            this.ClearDCC('Dest,Country,City');
            this.ClearBUS();
            this.ClearKikan();
            /*this.ClearCarr();
             this.ClearDepAP();
             this.ClearKikan();
             //値があったら、必須はhidden、無かったらview
             var RQSelector = this.FormID+' #RQp_hatsu';
             $(RQSelector).toggle($(TgSelector).val() == false);
             */
            //固定するのは目的地
            DisabledSel = this.FormID + ' #preDest';
            $(DisabledSel).attr('disabled', 'disabled');

        }
        if (TgName == 'p_bus_boarding_code') {
            this.ClearDCC('Dest,Country,City');
            //this.ClearKikan();
            //値があったら、必須はhidden、無かったらview
            //var RQSelector = this.FormID+' #RQp_hatsu';
            //$(RQSelector).toggle($(TgSelector).val() == false);
            //固定するのは目的地
            DisabledSel = this.FormID + ' #preDest';
            $(DisabledSel).attr('disabled', 'disabled');
        }
        //自分がdestなら、国都市クリア
        if (TgName == 'preDest') {
            if(TgType != 4){
                this.ClearDCC('Country,City');
                this.ClearKikan();
            }
            /*this.ClearBUS();
             this.ClearCarr();
             this.ClearKikan();
             //値があったら、必須はhidden、無かったらview
             var RQSelector = this.FormID+' #RQ'+TgName;
             $(RQSelector).toggle($(TgSelector).val() == false);
             */
            //固定するのは国
            DisabledSel = this.FormID + ' #preCountry';
            $(DisabledSel).attr('disabled', 'disabled');

        }
        //自分がCountryなら、都市クリア
        if (TgName == 'preCountry') {
            this.ClearDCC('City');
            this.ClearKikan();
            /*this.ClearBUS();
             this.ClearCarr();
             this.ClearKikan();
             */
            //固定するのは都市
            DisabledSel = this.FormID + ' #preCity';
            $(DisabledSel).attr('disabled', 'disabled');

        }
        /*
         //他もバスクリア
         if(TgName == 'preCity' || TgName == 'p_kikan_min' || TgName == 'p_kikan_max'){
         this.ClearBUS();
         }
         //キャリアもクリア
         if(TgName == 'preCity'){
         this.ClearCarr();
         this.ClearKikan();
         }
         //出発空港
         if(TgName == 'p_dep_airport_code'){
         this.ClearDCC('Dest,Country,City');
         this.ClearKikan();
         }
         //交通手段
         if(TgName == 'p_transport[]'){
         this.ClearKikan();
         }
         */
        var ObjA = FncValueSetAry(ReSearchSelector, ',');	//input型
        var ObjB = FncValueSetSelectAry(ReSearchSelectorSel, ',');	//selsect型
        var paramObj = $.extend(ObjA, ObjB);	//配列の結合

        if (!('p_mainbrand' in paramObj) && TgType != 4) {
            $('#tab_ct_tour #p_mainbrand').empty();
            $('#tab_ct_tour #p_mainbrand').append('<option value="">選択してください</option>');

            if ('AddRetType' in paramObj) {
                paramObj['AddRetType'] += ',p_mainbrand';
            } else {
                paramObj['AddRetType'] = 'p_mainbrand';
            }
        }

        paramObj['browser_back_flag'] = false;

        /*Ajax*/
        switch (TgType) {
            case 0:	//通常
                $.ajax({
                    data: paramObj
                    , dataType: 'script'
                    , url: BaseName
                });
                break;

            case 1:	//自分自身が何か＋ファセット返して欲しいのは何か
                paramObj['SetParam'] = TgName;
                if (this.SetTg == 99) {
                    this.SetTg = '';
                }
                paramObj['RetParam'] = this.SetTg;
                $.ajax({
                    data: paramObj
                    , dataType: 'script'
                    , url: BaseName
                    , success: function (html) {
                    }
                });
                break;
            case 2:	//出発日前へ次へ
                paramObj['SetParam'] = TgName;
                paramObj['ViewMonth'] = AddVar;
                $.ajax({
                    data: paramObj
                    , dataType: 'script'
                    , url: BaseName
                });
                break;
            case 3:	//自分自身が何か＋ファセット返して欲しいのは何か
                if (TgName == 'p_bus_boarding_code') {
                    var busV = AddVar['p_bus_boarding_code'];
                    paramObj['SetParam'] = TgName;
                    paramObj[TgName] = busV;
                    paramObj['RetParam'] = 0;
                }

                $.ajax({
                    data: paramObj
                    , dataType: 'script'
                    , url: BaseName
                    , success: function (html) {


                        if (AddVar['preDest'] && TgName == 'p_bus_boarding_code') {

                            var dV = AddVar['preDest'];
                            $('#dSearchBox').find('#preDest').val(dV).removeAttr("disabled");
                        }

                        else {
                            if (AddVar['p_dep_date']) {
                                $('#dSearchBox').find('#p_dep_date').val(AddVar['p_dep_date']).trigger('change');

                            }
                        }
                    }
                });
                break;

            case 4:	//ブラウザバック
                paramObj['browser_back_flag'] = true;
                paramObj['SetParam'] = TgName;
                $("#tour_init_flag").val("");

                if (TgName == 'preDest') {
                    paramObj['RetParam'] = 1;
                }
                else if (TgName == 'preCountry') {
                    paramObj['RetParam'] = 2;
                }

                if(0 < $("#p_search_country").val().length){
                    paramObj['p_search_country'] = 1;
                }
                else{
                    paramObj['p_search_country'] = 0;
                }

                // オセアニアと南太平洋なら
                if(paramObj['preDest'] == 'FOC'){
                    paramObj['RetParam'] = 2;
                }

                // 都市ページなら
                if($("#p_category").val() == 3){
                    paramObj['preCity'] = AddVar['preCity'];
                }
                // 国ページなら
                else if ($("#p_category").val() == 2) {
                    var def_country = $("#def_p_mokuteki").val().split(",");
                    var def_country_text = '';
                    jQuery.each(def_country, function (i, val) {

                        def_country_text = def_country_text == '' ? val.substr(4,2) : def_country_text + ',' + val.substr(4,2);
                    });
                    paramObj['preCountry'] = def_country_text;
                }

                var formid = AddVar['formid'];
                $.ajax({
                    data: paramObj
                    , dataType: 'script'
                    , url: BaseName
                    , success: function (html) {
                    }
                    , complete: function () {
                        if (TgName == 'preDest') {
                            if (AddVar['preCountry']) {
                                if ($(formid + ' #preCountry')[0]) {
                                    if($("#p_search_country").val().length < 1 || paramObj['preDest'] == 'FOC'){
                                        $(AddVar['formid'] + ' #preCountry').val(AddVar['preCountry']).prop("selected", true);
                                        sbc.SendSearch($(formid).find('#preCountry'), 4, AddVar);
                                    }
                                }
                            }

                        }
                        else if (TgName == 'preCountry') {

                            if (AddVar['preCity']) {

                                if ($(formid + ' #preCity')[0]) {
                                    $(AddVar['formid'] + ' #preCity').val(AddVar['preCity']).prop("selected", true);
                                    if($("#p_search_country").val().length < 1 || paramObj['preDest'] == 'FOC'){
                                        sbc.SendSearch($(AddVar['formid']).find('#p_conductor'), 1);
                                    }
                                }
                            }
                        }
                        //void(0);
                        //return false;
                    }

                });
                break;

        }
        //固定戻す
        if (DisabledSel) {
            $(DisabledSel).attr('disabled', false);
        }
        //出発日戻す
        //sbc.WatermarkDep(forWMDepDate);

    }
    , DepDate: function (DepDateSel) {
        //まだボックスが出てなければ表示させる
        if (!$('body').is(this.TGOverlaySelector)) {
            //メッセージボックスを作る
            MakeOverLay('auto', 700, 'body', this.SubWinID, this.SubWinID);	//高さ、幅、どこに作る、ID、Class
            if (!this.FormID) {
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
            if (scrollTop == 0) {
                scrollTop = $('html').scrollTop();
            }
            var top  = Math.floor(($(window).height() - $(this.TgIdName).height()) / 2) + scrollTop -200;
//            var top = 1279; //custom by Lamnv
            var left = Math.floor(($(window).width() - $(this.TgIdName).width()) / 2);
            if (top < 0) {
                top = 0;
            }
            $(this.TgIdName)
                .css({
                    "top": top
                    , "left": left
                }).fadeIn();
        }
    }
    , DelSubWinforSenmon: function () {
        if ($('body').is(sbc.TGOverlaySelector)) {
            $(sbc.TgIdName).fadeOut("fast", function () {
                $(sbc.TgIdName).remove();
            });
            //IE6対策を元に戻す
            //$("select,object").css("visibility","visible");
        }
    }
    , clearSet: function () {

        $("#iSearchBox,#dSearchBox").find("textarea, :text, select").val("").end().find(":checked").prop("checked", false);
        $("#iSearchBox,#dSearchBox").find("#p_hatsu_eu option:first,#p_hatsu_sub option:first").prop('selected', true);
    }
    /*出発日でAjaxしなきゃ*/
    , WatermarkAjaxDep: function (DepDateSel) {
        var FormID = this.FormID;
        if (!this.FormID) {
            FormID = '#' + $(DepDateSel).parents('form').attr('id');
        }

        /*if (DepDateSel.selector) {
         var b=TgSelector.selector.split(" ")
         FormID=b[0];
         }*/


        //リクエストしなおし
        if ($('select[name=p_conductor]', FormID)[0].type == 'select-one') {
            this.SendSearch($('select[name=p_conductor]', FormID), 1);
        }
        else if ($('input[name=p_conductor]', FormID)[0].type == 'hidden') {
            this.SendSearch($('input[name=p_conductor]', FormID), 1);
        }
        else if ($('select[name=	p_kikan_min]', FormID)[0].type == 'select-one') {
            this.SendSearch($('select[name=p_kikan_min]', FormID), 1);
        }

        //this.WatermarkDep(DepDateSel);
    },
    setConditions: function (req) {
        var req_obj = new Object();
        for (i in req) {
            var trimVal = req[i];
            if (typeof(trimVal) != "undefined") {
                req_obj[i] = trimVal.replace(/\,+$/, "");
            }
        }
        var url = location.href;
        this._locChange('', url, req_obj);
    },
    _locChange: function (id, url, params) {
        if ('pushState' in history) {
            history.pushState(params, '', url);
        } else {
            //Unsupported
        }
    },
    _onLocChanged: function (e) {
        if (!e.state) {
            return;
        }
        var parames = e.state;

        for (i in parames) {

            var trimVal = parames[i];
            if (typeof(trimVal) != "undefined" && trimVal != null) {
                parames[i] = trimVal;
            }
        }
        return parames;
    }

    /*送信するとき*/
    , Submit: function (ClickBtn) {

        /*エラーの条件*/
        /*変数の定義*/
        this.FormID = '#iSearchBox';

        var ReSearchSelector = this.FormID + ' input[type=text], ' + this.FormID + ' input[type=hidden]';
        var ReSearchSelectorSel = this.FormID + ' select';

        var ObjA = new Object();
        var ObjB = new Object();
        var paramObj = new Object();

        var ObjA = FncValueSetAry(ReSearchSelector, ',');	//input型
        var ObjB = FncValueSetSelectAry(ReSearchSelectorSel, ',');	//selsect型
        var paramObj = $.extend(ObjA, ObjB);	//配列の結合


        // 初期表示なら
        if($("#tour_init_flag").val() == ''){
            // 特別処理
            if(0 < $("#p_search_country").val().length){
                var ex_value = $("#def_p_mokuteki").val().split(',');
                var ex_text = '';
                jQuery.each(ex_value, function (i, val) {
                    if($("#p_category").val() == '2'){
                        ex_text = ex_text == '' ? val.substr(4,2) : ex_text + ',' + val.substr(4,2);
                    }
                    else{
                        ex_text = ex_text == '' ? val.substr(7,3) : ex_text + ',' + val.substr(7,3);
                    }
                });
                if($("#p_category").val() == '2'){
                    paramObj['preCountry'] = ex_text;
                }
                else{
                    paramObj['preCity'] = ex_text;
                }
            }
        }

        /*出発日処理*/
        //var forWMDepDate = this.FormID+' input[name=p_dep_date]';
        //this.WatermarkOutDep(forWMDepDate);

        /*必須チェック*/
        //出発地
        /*var checkHatsuVal = false;
         if(paramObj['MyNaigai'] == 'i'){
         checkHatsuVal = paramObj['p_hatsu'];
         }
         else if(paramObj['MyNaigai'] == 'd'){
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
        //utilityJs.SAS_setCookie('SAS_VARS_TYPE', '検索', '', '/', 'hankyu-travel.com', '');
        SAS_setCookie('SAS_VARS_TYPE', '検索', '', '/', '133.18.4.189', '');

        /*目的地の処理*/
        var MokutekiVal = '';
        if (paramObj['preDest']) {
            var DestSplit = paramObj['preDest'].split(',');
            if (DestSplit.length > 1) {	//複数方面
                jQuery.each(DestSplit, function (i, val) {
                    if (i > 0) {
                        MokutekiVal += ',';
                    }
                    MokutekiVal += val + '--';
                });
            }
            else {
                //方面はひとつ、国が複数
                if (paramObj['preCountry']) {
                    var CountrySplit = paramObj['preCountry'].split(',');
                    if (CountrySplit.length > 1) {	//複数国
                        jQuery.each(CountrySplit, function (i, val) {
                            if (i > 0) {
                                MokutekiVal += ',';
                            }
                            MokutekiVal += paramObj['preDest'] + '-' + val + '-';
                        });
                    }
                    else if($("#tour_init_flag").val() == '' && 0 < $("#p_search_country").val().length){
                        var CitySplit = paramObj['preCity'].split(',');
                        jQuery.each(CitySplit, function (i, val) {
                            if (i > 0) {
                                MokutekiVal += ',';
                            }
                            MokutekiVal += paramObj['preDest'] + '-' + paramObj['preCountry'] + '-' + val;
                        });
                    }
                    else {
                        MokutekiVal = paramObj['preDest'] + '-' + paramObj['preCountry'] + '-' + paramObj['preCity'];
                    }
                }
                //国はひとつ
                else {
                    MokutekiVal = paramObj['preDest'] + '-' + paramObj['preCountry'] + '-' + paramObj['preCity'];
                }
            }
        }

        MokutekiVal = MokutekiVal.replace(/undefined/ig, '');
        if (MokutekiVal == '--') {
            MokutekiVal = '';
        }
        var ApStr = '<input type="hidden" name="p_mokuteki" value="' + MokutekiVal + '" />';
        paramObj['p_mokuteki'] = MokutekiVal;
        paramObj['formid'] = this.FormID;
        this.setConditions(paramObj);

        $(this.FormID).append(ApStr);
        $(this.FormID).submit();
        void(0);
        return false;
    }
    /*バスをクリアする*/
    , ClearBUS: function () {
        //バスセレクタ
        var BusSel = this.FormID + ' select[id=p_bus_boarding_code]';
        /*バスは全共通*/
        if ($(BusSel).attr('id')) {
            $(':gt(0)', BusSel).remove();
            sbc.SetTg = 3;
        }
    }
    /*日数をクリアする*/
    , ClearKikan: function () {
        //バスセレクタ
        var KikanMinSel = this.FormID + ' #p_kikan_min';
        var KikanMaxSel = this.FormID + ' #p_kikan_max';
        // バスは全共通
        if ($(KikanMinSel).attr('id')) {
            $(':gt(0)', KikanMinSel).remove();
        }
        if ($(KikanMaxSel).attr('id')) {
            $(':gt(0)', KikanMaxSel).remove();
        }
    }
    , ClearDCC: function (Type) {
        //方面・国・都市セレクタ
        var formID = this.FormID;
        var destObj = $(formID + " #preDest");
        var countryObj = $(formID + " #preCountry");
        var cityObj = $(formID + " #preCity");

        var TypeAry = Type.split(',');
        jQuery.each(TypeAry, function (i, str) {
            switch (str) {
                case 'Dest':
                    if (destObj.attr('type') != 'hidden') {
                        if ($(':first', destObj).val() == '') {
                            $(':gt(0)', destObj).remove();
                            if (sbc.SetTg === '') {
                                sbc.SetTg = 0;
                            }
                            var RQSelector = sbc.FormID + ' #RQpreDest';
                            $(RQSelector).show();
                        }
                        else {
                            if (sbc.SetTg === '') {
                                sbc.SetTg = 99;
                            }
                        }
                    }
                    break;
                case 'Country':
                    if (countryObj.attr('type') != 'hidden') {
                        if ($(':first', countryObj).val() == '') {
                            //if($(testForm).find("#preCountry").eq(0).val() == ''){
                            $(':gt(0)', countryObj).remove();
                            if (sbc.SetTg === '') {
                                sbc.SetTg = 1;
                            }
                        }
                        else {
                            if (sbc.SetTg === '') {
                                sbc.SetTg = 99;
                            }
                        }
                    }
                    break;
                case 'City':
                    if (cityObj.attr('type') != 'hidden') {
                        if ($(':first', cityObj).val() == '') {
                            $(':gt(0)', cityObj).remove();
                            if (sbc.SetTg === '') {
                                sbc.SetTg = 2;
                            }
                        }
                        else {
                            if (sbc.SetTg === '') {
                                sbc.SetTg = 99;
                            }
                        }
                    }
                    break;
            }
        });
    }

};
//--------------------
//	日付を押したとき（出発日）
//----------------------
var SWDate = function (SetVal) {
    var DepDateSel = sbc.FormID + ' input[name=p_dep_date]';
    $(DepDateSel).val(SetVal).removeClass('NS_Watermark');	//セットして
    sbc.DelSubWinforSenmon();	//閉じる
    //IE6対策を元に戻す
    //$("select,object").css("visibility","visible");

}
//--------------------
//	前へ次へ（出発日）
//----------------------
var NextBackBtnActionTour = function (DepDate) {
//function NextBackBtnAction (DepDate){
    var DepDateSel = sbc.FormID + ' input[name=p_dep_date]';
    $(DepDateSel).focus();
    //通信
    sbc.SendSearch(DepDateSel, 2, DepDate);
    void(0);
    return false;
}
/*
 *******************************************************
 こっちは、出発地と目的エリアと
 *******************************************************
 */
var setInitSelect = function () {
    var url = window.location.href;
    var kyotenId = url.substring(url.lastIndexOf("/") + 1, url.lastIndexOf("."));
    var requestUrl = "../sharing/__br/phpsc/ajax_searchBox_init.php";
    var myNaigai = $("input[type=hidden][name='MyNaigai']");
    var param = null;
    var selectAry = null;
    var out_selectAry = null;
    var formID = null;
    var naigai = null;
    for (var i = 0; i < myNaigai.length; i++) {
        naigai = $(myNaigai[i]).val();
        formID = "#" + $(myNaigai[i]).parents("form").attr("id");
        ajax_searchBox_init(naigai, formID, kyotenId, requestUrl);
    }
    ;
}
var ajax_searchBox_init = function (naigai, formID, kyotenId, requestUrl) {
    var selectAry = $(formID + " select");
    var mtType = $(formID + " input[type=hidden][name=MtType]").val();
    if (mtType == undefined) {
        mtType = "";
    }
    var out_selectAry = "";
    for (var x = 0; x < selectAry.length; x++) {
        out_selectAry += $(selectAry[x]).data().key + ",";
    }
    out_selectAry = out_selectAry.substring(0, out_selectAry.length - 1);
    var param = {
        kyotenId: kyotenId,
        naigai: naigai,
        mtType: mtType,
        out_selectAry: out_selectAry
    };
    var successHandle = function (res) {
        var jsonData = eval("(" + res + ")");
        var out_Ary = out_selectAry.split(",");
        for (var x = 0; x < out_Ary.length; x++) {
            if (jsonData[0][out_Ary[x]]) {
                $(formID + " select[data-key=" + out_Ary[x] + "]").html(jsonData[0][out_Ary[x]]);
            }
        }
        $("#" + naigai + "p_hit_num").html(jsonData[0]["p_hit_num"] + "件");
    };
    var setting = {
        url: requestUrl,
        type: 'POST',
        data: param,
        success: successHandle
    };
    $.ajax(setting);
}
/*
 *日付取得 yyyy/mm/dd
 */
var getYYYYMMDD = function (nextDays) {
    var date = new Date();
    date.setDate(date.getDate() + nextDays);
    var year = date.getFullYear();
    var month = date.getMonth() + 1;
    var day = date.getDate();
    if (month < 10) {
        return year + "/0" + month + "/" + day;
    }
    return year + "/" + month + "/" + day;
}
var Change = function () {
    radio = document.getElementsByName('p_transport');
    if (radio[0].checked) {
        document.getElementById('airplain').style.display = "";
        document.getElementById('trainbus').style.display = "none";
        $('select').attr('selectedIndex', '0').children('select').removeAttr('select');
    }
    window.onload = Change;
}
/*
 *初期化
 */
$(function () {

    sbc.DelSubWinforSenmon();
//    sbc.clearSet();
    /*検索枠Ｃｏｍｂｏｂｏｘデータをロードする*/
    //setInitSelect();
    var req = {};
    if ('pushState' in history) {
        setTimeout(function () {
            //Get post data or default
            //req = methods.getPostParames();
            window.addEventListener('popstate', function (e) {
                if (e.state != null) {
                    //Get history data
                    req = sbc._onLocChanged(e);
                }
            });
            if (typeof window.history.state !== "undefined") {
                //Get history data
                req = sbc._onLocChanged(window.history);
            }

            if (req) {
                if (req.MyNaigai == 'd') {
                    changeSearch('.smSearchD');
                }
                else {
                    changeSearch('.smSearchI');
                }
                var formID = req.formid;
                for (i in req) {

                    var slecter = $(formID + ' input[name="' + i + '"]')[0];
                    if (slecter && $(slecter).attr('type') == 'hidden') {
                        $(formID + ' input[name="' + i + '"]').val(req[i]);
                    } else if (slecter && $(slecter).attr('name') == 'p_dep_date') {
                        var dispText = '';
                        var dateVal = String(req[i]);
                        if (dateVal) {
                            if (dateVal.indexOf('/') != -1) {
                                var DateAry = dateVal.split('/');
                                if (typeof(DateAry[2]) == 'undefined' || DateAry[2] == '') {
                                    dateVal = DateAry[0] + ( '0' + DateAry[1] ).slice(-2);
                                } else {
                                    dateVal = DateAry[0] + ("0" + DateAry[1]).slice(-2) + ("0" + DateAry[2]).slice(-2);
                                }
                            }
                            dateVal.match(/([0-9]{4})([0-9]{2})([0-9]{2})?/);
                            var year = RegExp.$1
                            var month = RegExp.$2;
                            var day = RegExp.$3;
                            if (year) {
                                var dispText = year + '/';
                                dispText += month;
                            }
                            if (day) {
                                dispText += '/' + day;
                            }
                        }
                        $(formID + ' input[name="' + i + '"]').val(dispText);
                    } else if (slecter && $(slecter).attr('type') == 'text') {
                        $(formID + ' input[name="' + i + '"]').val(req[i]);
                    }
                    else {
                        if (i != 'preCountry' && i != 'preCity') {
                            $(formID + ' select[name="' + i + '"]').val(req[i]).prop('selected', true);


                        }
                    }
                }

                if (req['preCountry']) {
                    sbc.SendSearch($(formID + ' input[name="preDest"]'), 4, req);
                    void(0);
                    return false;
                }
                else {
                    if (req['preDest']) {
                        sbc.SendSearch($(formID + ' input[name="preDest"]'), 4,req);
                    }
                    else {
                        sbc.SendSearch($(formID + ' select[name="p_conductor"]'), 4,req);
                    }
                }

            }


            //初回リクエスト
            //methods.initRequest(req);
        }, 100);
    }


    var formAry = $("form");
    var formID = null;
    for (var i = 0; i < formAry.length; i++) {
        formID = "#" + $(formAry[i]).attr("id");
        if ($(formID)) {
            /*----- 色々触ったらAjax -----*/
            $(formID + " select").change(function () {
                sbc.SendSearch(this, 1);
                void(0);


                return false;
            });

            /*----- 出発日は特別 -----*/
            $('#iSearchBox input[name=p_dep_date]').click(function () {

                sbc.DepDate(this);
            });

            $('#iSearchBox .js_dep_date_cal').click(function () {
                //$(formID+' input[name=p_dep_date]').trigger("click");
                sbc.DepDate($(this).parent('td').find('input[name=p_dep_date]'));
            });

            /*----- 出発日ウォーターマーク -----*/
            var forWMDepDate = formID + ' input[name=p_dep_date]';
            /*変更があったときもね*/
            $(forWMDepDate).change(function () {
                sbc.WatermarkAjaxDep(this);
            });
            /*出発日の透析文字を設定する*/
            $(forWMDepDate).attr("placeholder", "例）" + getYYYYMMDD(0));
            /*----------検索---------*/
            $("#iSearchBox .btn_simpleSrch").click(function () {
                sbc.Submit(this);
                void(0);
                return false;
            });
            $(formID + ' input[type=radio]').click(function () {
                sbc.SendSearch(this, 1);
            });
            $(formID + ' input[type=checkbox]').click(function () {
                //バスの場合の特別処理
                /*バスは全共通*/
                if ($(this).val() == '1' && $(this).attr('name') == 'p_transport' && $("#bus_bunrui").is("*")) {
                    var BusChecked = $(this).attr('checked');
                    if (BusChecked === false) {
                        $('#bus_bunrui').val('');
                    }
                    else {
                        $('#bus_bunrui').val('813');
                    }
                }
                sbc.SendSearch(this, 1);
            });

        }

    }


    /*----- 外側クリック対策 -----*/
    $('html').click(function (depEvent) {
        var TargetClass = $(depEvent.target).attr('class');
        var TargetName = $(depEvent.target).attr('name');
        if (TargetClass !== 'SW_CalNext' && TargetClass !== 'SW_CalBack' && TargetName !== 'p_dep_date' && TargetClass !== 'js_dep_date_cal') {
            if ($('html').is(sbc.TGOverlaySelector)) {
                //ウィンドウ消す
                sbc.DelSubWinforSenmon();
                var forWMDepDate = 'input[name=p_dep_date]';
                jQuery.each($(forWMDepDate), function (i, val) {
                    sbc.WatermarkAjaxDep(this);
                });
            }
        }

    });


});
