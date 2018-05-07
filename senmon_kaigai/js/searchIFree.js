//--------------------
//	文字列変換
//----------------------
/**
 * 半全角カナ変換モジュール
 * @return {[type]} [description]
 */
var kanaConverter = (function () {
    // マップ作成用関数
    var createKanaMap = function (properties, values) {
        var kanaMap = {};
        // 念のため文字数が同じかどうかをチェックする(ちゃんとマッピングできるか)
        if (properties.length === values.length) {
            for (var i = 0, len = properties.length; i < len; i++) {
                var property = properties.charCodeAt(i),
                    value = values.charCodeAt(i);
                kanaMap[property] = value;
            }
        }
        return kanaMap;
    };
    // 全角から半角への変換用マップ
    var m = createKanaMap(
        'アイウエオカキクケコサシスセソタチツテトナニヌネノハヒフヘホマミムメモヤユヨラリルレロワヲンァィゥェォッャュョ',
        'ｱｲｳｴｵｶｷｸｹｺｻｼｽｾｿﾀﾁﾂﾃﾄﾅﾆﾇﾈﾉﾊﾋﾌﾍﾎﾏﾐﾑﾒﾓﾔﾕﾖﾗﾘﾙﾚﾛﾜｦﾝｧｨｩｪｫｯｬｭｮ'
    );
    // 半角から全角への変換用マップ
    var mm = createKanaMap(
        'ｱｲｳｴｵｶｷｸｹｺｻｼｽｾｿﾀﾁﾂﾃﾄﾅﾆﾇﾈﾉﾊﾋﾌﾍﾎﾏﾐﾑﾒﾓﾔﾕﾖﾗﾘﾙﾚﾛﾜｦﾝｧｨｩｪｫｯｬｭｮ',
        'アイウエオカキクケコサシスセソタチツテトナニヌネノハヒフヘホマミムメモヤユヨラリルレロワヲンァィゥェォッャュョ'
    );
    // 全角から半角への変換用マップ
    var g = createKanaMap(
        'ガギグゲゴザジズゼゾダヂヅデドバビブベボ',
        'ｶｷｸｹｺｻｼｽｾｿﾀﾁﾂﾃﾄﾊﾋﾌﾍﾎ'
    );
    // 半角から全角への変換用マップ
    var gg = createKanaMap(
        'ｶｷｸｹｺｻｼｽｾｿﾀﾁﾂﾃﾄﾊﾋﾌﾍﾎ',
        'ガギグゲゴザジズゼゾダヂヅデドバビブベボ'
    );
    // 全角から半角への変換用マップ
    var p = createKanaMap(
        'パピプペポ',
        'ﾊﾋﾌﾍﾎ'
    );
    // 半角から全角への変換用マップ
    var pp = createKanaMap(
        'ﾊﾋﾌﾍﾎ',
        'パピプペポ'
    );
    var gMark = 'ﾞ'.charCodeAt(0),
        pMark = 'ﾟ'.charCodeAt(0);
    return {
        /**
         * 全角から半角への変換用関数
         * @param  {[type]} str 変換対象文字列
         * @return {[type]}     変換後文字列
         */
        convertKanaToOneByte: function (str) {
            for (var i = 0, len = str.length; i < len; i++) {
                // 濁音もしくは半濁音文字
                if (g.hasOwnProperty(str.charCodeAt(i)) || p.hasOwnProperty(str.charCodeAt(i))) {
                    // 濁音
                    if (g[str.charCodeAt(i)]) {
                        str = str.replace(str[i], String.fromCharCode(g[str.charCodeAt(i)]) + String.fromCharCode(gMark));
                    }
                    // 半濁音
                    else if (p[str.charCodeAt(i)]) {
                        str = str.replace(str[i], String.fromCharCode(p[str.charCodeAt(i)]) + String.fromCharCode(pMark));
                    } else {
                        break;
                    }
                    // 文字列数が増加するため調整
                    i++;
                    len = str.length;
                } else {
                    if (m[str.charCodeAt(i)]) {
                        str = str.replace(str[i], String.fromCharCode(m[str.charCodeAt(i)]));
                    }
                }
            }
            return str;
        },
        /**
         * 半角から全角への変換用関数
         * @param  {[type]} str 変換対象文字列
         * @return {[type]}     変換後文字列
         */
        convertKanaToTwoByte: function (str) {
            for (var i = 0, len = str.length; i < len; i++) {
                // 濁音もしくは半濁音文字
                if (str.charCodeAt(i) === gMark || str.charCodeAt(i) === pMark) {
                    // 濁音
                    if (str.charCodeAt(i) === gMark && gg[str.charCodeAt(i - 1)]) {
                        str = str.replace(str[i - 1], String.fromCharCode(gg[str.charCodeAt(i - 1)]))
                            .replace(str[i], '');
                    }
                    // 半濁音
                    else if (str.charCodeAt(i) === pMark && pp[str.charCodeAt(i - 1)]) {
                        str = str.replace(str[i - 1], String.fromCharCode(pp[str.charCodeAt(i - 1)]))
                            .replace(str[i], '');
                    } else {
                        break;
                    }
                    // 文字列数が減少するため調整
                    i--;
                    len = str.length;
                } else {
                    // １つ先の文字を見て濁音もしくは半濁音でないことを確認
                    if (mm[str.charCodeAt(i)] && str.charCodeAt(i + 1) !== gMark && str.charCodeAt(i + 1) !== pMark) {
                        str = str.replace(str[i], String.fromCharCode(mm[str.charCodeAt(i)]));
                    }
                }
            }
            return str;
        }
    };
})();
//--------------------
//	絞込検索
//----------------------
function NarrowSearchMain() {
}
NarrowSearchMain.prototype = {
    jsonObj: '',
    RegisteredHotelCode: '',
    init: function (partsName) {
        //ドキュメント内オブジェクト取得
        this.btn_p_carr = partsName.btn_p_carr;
        this.btn_p_hatsu = partsName.btn_p_hatsu;
        this.btn_p_hotel_code = partsName.btn_p_hotel_code;
        this.btn_p_mokuteki = partsName.btn_p_mokuteki;
        this.formParentName = partsName.formParentName;
        this.formsubWinName = partsName.formsubWinName;
        this.overlay = partsName.overlay;
        this.rBox = partsName.rBox;
        this.subKyoten = partsName.subKyoten;
        this.formObj = partsName.formObj;
        //絞込検索：旅行代金テーブル
        this.price_t = [];
        this.price_t = [1000, 5000, 10000, 20000, 30000, 40000, 50000, 60000, 70000, 80000, 90000, 100000, 110000, 120000, 130000, 140000, 150000, 200000, 250000, 300000, 350000, 400000, 450000, 500000, 600000, 700000, 800000, 900000, 1000000];
        var that = this;
        //登録ホテルコード参照
        $.ajax({
            url: '/sharing/phpsc/ajax_GetRegisteredHotelCode.php',
            data: '',
            dataType: "json",
            cache: false,
            type: "POST",
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                //_errorAct(XMLHttpRequest, textStatus, errorThrown);
            },
            success: function (json) {
                //コールバック
                that.RegisteredHotelCode = json.response.docs;
            }
        });
    },
    ajaxProcess: function (settings) {
        var naigai = [];
        naigai['i'] = 'kaigai';
        naigai['d'] = 'kokunai';
        var narrowSearchUrl = '/sharing/__br/phpsc/searchIFree.php';
        var defSetting = {
            url: narrowSearchUrl,
            data: '',
            dataType: "json",
            cache: false,
            type: "POST",
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                //_errorAct(XMLHttpRequest, textStatus, errorThrown);
            },
            success: function (json) {
                //コールバック
            }
        }
        settings = $.extend(defSetting, settings);
        //ajax 共通処理
        var opt = $.extend({}, $.ajaxSettings, settings);
        opt.success = (function (func) {
            return function (data, statusText, jqXHR) {
                // console.log('success時の共通処理 ...');
                if (func) {
                    func(data, statusText, jqXHR);
                }
            };
        })(opt.success);
        opt.error = (function (func) {
            return function (jqXHR, statusText, errorThrown) {
                // console.log('error時の共通処理 ...');
                if (func) {
                    func(jqXHR, statusText, errorThrown);
                }
            };
        })(opt.error);
        opt.complete = (function (func) {
            return function (jqXHR, statusText) {
                // console.log('complete時の共通処理 ...');
                if (func) {
                    func(jqXHR, statusText);
                }
            };
        })(opt.complete);
        return $.ajax(opt);
    },
    requestProcess: function (options) {
        var paramAct = function (valObj) {
            valAct = new Array();
            valAct['p_hatsu_sub'] = function (value) {
                return this;
            }
            valAct['p_dep_date'] = function (value) {
                if (value.indexOf('例') != -1) {
                    value = '';
                }
                else if (value.indexOf('/') != -1) {
                    var DateAry = value.split('/');
                    if (typeof(DateAry[2]) == 'undefined' || DateAry[2] == '') {
                        value = DateAry[0] + ( '0' + DateAry[1] ).slice(-2);
                    } else {
                        value = DateAry[0] + ("0" + DateAry[1]).slice(-2) + ("0" + DateAry[2]).slice(-2);
                    }
                }
                valObj.value = value;
                return this;
            }
            if (typeof valAct[valObj.name] == 'function') {
                valAct[valObj.name](valObj.value);
            }
            return valObj;
        };
        var param = $(this.formObj).serializeArray();
        var req = {};
        var cnt = 0;
        for (i in param) {
            var str = param[i].name;
            //パラメータ毎の個別処理
            valObj = paramAct(param[i]);
            //console.log(valObj);
            //リクエストパラに存在しない
            if (typeof(req[valObj.name]) == "undefined") {
                req[valObj.name] = "";
            }
            req[valObj.name] += valObj.value + ',';
            //p_bunrui  030は必ず入れる
            //console.log($(this.formObj));
            if (valObj.name == 'p_bunrui') {
                if (req['p_bunrui'].indexOf('030') == -1) {
                    if (req['p_bunrui'] == "") {
                        req['p_bunrui'] += '030'
                    } else {
                        req['p_bunrui'] += '030,'
                    }
                }
            }
        }
        //p_bunrui重複防止
        bunruiAry = req['p_bunrui'].split(',');
        var bunruiAry = bunruiAry.filter(function (x, i, self) {
            return self.indexOf(x) === i;
        });
        req['p_bunrui'] = '';
        for (var i = 0; i < bunruiAry.length; i++) {
            if (bunruiAry[i] != '') {
                req['p_bunrui'] += bunruiAry[i] + ',';
            }
        }
        for (i in req) {
            var trimVal = req[i];
            trimVal = trimVal.replace(/^\s+|\s+$/g, '');
            req[i] = trimVal.replace(/\,+$/, "");
        }
        req = $.extend(req, options);
        for (i in req) {
            var trimVal = req[i];
            if (trimVal == '') {
                delete req[i];
            }
        }
        return req;
    },
    getFacet: function (myName, json, parentVal) {
        var FacetArr = [];
        for (i in json[myName]) {
            var str = json[myName][i];
            if (typeof str != 'number') {
                str = str.replace("\n", "");
                str = str.replace("\r", "");
                //ホテル
                if (str.match(/([a-zA-Z0-9]{0,5})\,(.*)\,([A-Z]{0,2})\,([0-9A-Za-z]{3})/)) {
                    var jname = RegExp.$2;
                    jname = kanaConverter.convertKanaToTwoByte(jname);
                    var obj = {
                        'code': RegExp.$1,
                        'jname': jname,
                        'rank': RegExp.$3,
                        'city': RegExp.$4,
                        'facet': json[myName][parseInt(i) + 1]
                    }
                    //都市
                } else if (str.match(/(.*)\,(.*)\,(.*)\,(.*)/i)) {
                    if (parentVal != RegExp.$2) {
                        continue;
                    }
                    var obj = {
                        'code': RegExp.$3,
                        'jname': RegExp.$4,
                        'facet': json[myName][parseInt(i) + 1]
                    }
                }
                //国
                else if (str.match(/(.*)\,(.*)\,(.*)/i)) {
                    if (parentVal != RegExp.$1) {
                        continue;
                    }
                    var obj = {
                        'code': RegExp.$2,
                        'jname': RegExp.$3,
                        'facet': json[myName][parseInt(i) + 1]
                    }
                }
                //方面　その他ファセット
                else if (str.match(/(.*)\,(.*)/i)) {
                    var obj = {
                        'code': RegExp.$1,
                        'jname': RegExp.$2,
                        'facet': json[myName][parseInt(i) + 1]
                    }
                }
                FacetArr.push(obj);
            }
        }
        FacetArr.sort(function (a, b) {
            return b.facet - a.facet;
        });
        return FacetArr;
    },
    getMin: function (obj) {
        var cnt = 0;
        for (i in obj) {
            var val = parseInt(obj[i].key, 10);
            if (cnt == 0) {
                var num = val;
            } else {
                num = (num > val) ? val : num;
            }
            cnt++;
        }
        return num;
    },
    // /*Get Maximum value*/
    getMax: function (obj) {
        var cnt = 0;
        for (i in obj) {
            var val = parseInt(obj[i].key, 10);
            if (cnt == 0) {
                var num = val;
            } else {
                num = (num < val) ? val : num;
            }
            cnt++;
        }
        return num;
    }
};
//　追加分ここまで
//--------------------
//	絞込検索
//----------------------
function NarrowSearch() {
}
NarrowSearch.prototype.init = function (partsName) {
    this.btn_p_carr = partsName.btn_p_carr;
    this.btn_p_hatsu = partsName.btn_p_hatsu;
    this.btn_p_hotel_code = partsName.btn_p_hotel_code;
    this.btn_p_mokuteki = partsName.btn_p_mokuteki;
    this.formParentName = partsName.formParentName;
    this.formsubWinName = partsName.formsubWinName;
    this.overlay = partsName.overlay;
    this.rBox = partsName.rBox;
    this.subKyoten = partsName.subKyoten;
    this.formObj = partsName.formObj;
}
NarrowSearch.prototype.ajaxProcess = function (settings) {
    var defSetting = {
        url: '../search/ajax_ifree.php',
        data: '',
        dataType: "html",
        cache: false,
        type: "POST",
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            //_errorAct(XMLHttpRequest, textStatus, errorThrown);
        },
        success: function (json) {
            $(".searchContents").empty();
            $(".searchContents").append(json.html);
            //
        }
    }
    settings = $.extend(defSetting, settings);
    $.ajax(settings);
}
NarrowSearch.prototype.requestProcess = function (options) {
    var paramAct = function (valObj) {
        valAct = new Array();
        valAct['p_hatsu'] = function (value) {
            var value = $('#preHatsu').attr('data-code');
            return value;
        }
        valAct['p_dep_date'] = function (value) {
            if (!value.match(/^([0-9]+)\/+([0-9]+)\/*([0-9]+)$/)) {
                value = '';
            }
            if (value.indexOf('例') != -1) {
                value = '';
            }
            return value;
        }
        valAct['p_mainbrand'] = function (value) {
            var a = value.split(",");
            a = $.grep(a, function (e) {
                return e;
            });
            // 重複を削除したリスト
            var b = a.filter(function (x, i, self) {
                return self.indexOf(x) === i;
            });
            value = b.join(',');
            return value;
        }
        valAct['p_mokuteki'] = function (value) {
            var value = '';
            var dest = $('#preDest_free').attr('data-code');
            var country = $('#preCountry_free').attr('data-code');
            var city = $('#preCity_free').attr('data-code');
            if (dest || country || city) {
                value = dest + '-' + country + '-' + city;
            }
            return value;
        }
        if (typeof valAct[valObj.name] == 'function') {
            valObj.value = valAct[valObj.name](valObj.value);
        }
        return valObj;
    };
    var param = $(this.formObj).serializeArray();
    var req = {};
    var cnt = 0;
    for (i in param) {
        //パラメータ毎の個別処理
        valObj = paramAct(param[i]);
        if (typeof(req[param[i].name]) == "undefined") {
            req[param[i].name] = "";
        }
        req[param[i].name] += param[i].value + ',';
    }
    for (i in req) {
        var trimVal = req[i];
        req[i] = trimVal.replace(/^,+|,+$/g, '');
    }
    req = $.extend(req, options);
    return req;
}
//金額にカンマ付与
function addFigure(str) {
    var num = new String(str).replace(/,/g, "");
    while (num != (num = num.replace(/^(-?\d+)(\d{3})/, "$1,$2")));
    return num;
}
//金額配列
var price_t = [1000, 5000, 10000, 20000, 30000, 40000, 50000, 60000, 70000, 80000, 90000, 100000, 110000, 120000, 130000, 140000, 150000, 200000, 250000, 300000, 350000, 400000, 450000,
    500000, 600000, 700000, 800000, 900000, 1000000];
(function ($) {
    var plugname = 'searchTour';
    //var NSearch = new NarrowSearch;
    var NSearch = new NarrowSearchMain;
    var methods = {
        formObj: '',
        partsName: '',
        storage: '',
        naigai: '',
        DestNameObj: '',
        CountryNameObj: '',
        CityToCountryCode: '',
        CountryToDestCode: '',
        DCCNameObj: '',
        CityNameObj: '',
        req: {},
        init: function (options) {
            var defaults = {};
            postData = $.extend(defaults, options);
            storage = (function () {
                return window.sessionStorage;
            })();
            partsName = {
                'btn_p_hatsu': ".Box_p_hatsu",
                'btn_p_mokuteki': "Box_p_mokuteki",
                'btn_p_carr': ".Box_p_carr",
                'btn_p_hotel_code': ".Box_p_hotel_code",
                'formParentName': ".searchTour",
                'formsubWinName': ".subWinForm",
                'overlay': "#overlay",
                'rBox': "#rBox",
                'subKyoten': "input:checkbox[name^='p_hatsu']",
                'hotelRankChk': "input:checkbox[name='hotelRank']",
                'formObj': "#iSearchBox-freeplan"
            };
            methods.naigai = $("#MyNaigai").val();
//            formObj = '.searchTour';
            formObj = "#iSearchBox-freeplan";
            NSearch.init(partsName);
            /*--event 条件を変更する------------------------------------------------------------*/
            $(document).on('click', '#iSearchBox-freeplan input[type=radio],#iSearchBox-freeplan input[type=checkbox]', function () {
                //p_bunruiのヒストリーを上書きしないとおかしくなる
                if ($(this).attr('name') == 'p_bunrui') {
                    $("#iSearchBox-freeplan input:hidden[name = 'p_bunrui']").val($(this).val());
                }
                var options = {
                    kind: 'Detail',
                    p_data_kind: 3,
                    p_rtn_data: "p_conductor"
                }
                var dataVal = NSearch.requestProcess(options);
                var settings = {
                    data: dataVal,
                    dataType: "json",
                    success: function (json) {
                        $(".srchResult span").html(addFigure(json.response.p_hit_num));
                    }
                }
                NSearch.ajaxProcess(settings);
            });
            $(document).on('click', '#preHatsu', function () {
                var destFacetArr = [];
                var obj = {
                    'code': '105',
                    'jname': '北海道発',
                    'facet': 1
                }
                destFacetArr.push(obj);
                var obj = {
                    'code': '117,129,126,109,118,141,128,140,125',
                    'jname': '東北発',
                    'facet': 1
                }
                destFacetArr.push(obj);
                var obj = {
                    'code': '101,130,133,151,134,119,139',
                    'jname': '関東・甲信越発',
                    'facet': 1
                }
                destFacetArr.push(obj);
                var obj = {
                    'code': '105',
                    'jname': '東海・北陸発',
                    'facet': 1
                }
                destFacetArr.push(obj);
                var obj = {
                    'code': '105',
                    'jname': '関西発',
                    'facet': 1
                }
                destFacetArr.push(obj);
                var obj = {
                    'code': '105',
                    'jname': '中国・四国発',
                    'facet': 1
                }
                destFacetArr.push(obj);
                var obj = {
                    'code': '105',
                    'jname': '九州・沖縄発',
                    'facet': 1
                }
                destFacetArr.push(obj);
                var FacetArr = [];
                var obj = {
                    'code': '105',
                    'jname': '北海道',
                    'pjname': '北海道発',
                    'facet': 1
                }
                FacetArr.push(obj);
                var obj = {
                    'code': '117',
                    'jname': '青森',
                    'pjname': '東北発',
                    'facet': 1
                }
                FacetArr.push(obj);
                var obj = {
                    'code': '129',
                    'jname': '三沢',
                    'pjname': '東北発',
                    'facet': 1
                }
                FacetArr.push(obj);
                var obj = {
                    'code': '126',
                    'jname': '花巻',
                    'pjname': '東北発',
                    'facet': 1
                }
                FacetArr.push(obj);
                var obj = {
                    'code': '109',
                    'jname': '仙台',
                    'pjname': '東北発',
                    'facet': 1
                }
                FacetArr.push(obj);
                var obj = {
                    'code': '118',
                    'jname': '秋田',
                    'pjname': '東北発',
                    'facet': 1
                }
                FacetArr.push(obj);
                var obj = {
                    'code': '141',
                    'jname': '大館能代',
                    'pjname': '東北発',
                    'facet': 1
                }
                FacetArr.push(obj);
                var obj = {
                    'code': '128',
                    'jname': '山形',
                    'pjname': '東北発',
                    'facet': 1
                }
                FacetArr.push(obj);
                var obj = {
                    'code': '140',
                    'jname': '庄内',
                    'pjname': '東北発',
                    'facet': 1
                }
                FacetArr.push(obj);
                var obj = {
                    'code': '125',
                    'jname': '福島',
                    'pjname': '東北発',
                    'facet': 1
                }
                FacetArr.push(obj);
                var obj = {
                    'code': '101',
                    'jname': '東京（成田）',
                    'pjname': '関東・甲信越発',
                    'facet': 1
                }
                FacetArr.push(obj);
                var obj = {
                    'code': '130',
                    'jname': '東京（羽田）',
                    'pjname': '関東・甲信越発',
                    'facet': 1
                }
                FacetArr.push(obj);
                var obj = {
                    'code': '133',
                    'jname': '横浜',
                    'pjname': '関東・甲信越発',
                    'facet': 1
                }
                FacetArr.push(obj);
                var obj = {
                    'code': '151',
                    'jname': '山梨',
                    'pjname': '関東・甲信越発',
                    'facet': 1
                }
                FacetArr.push(obj);
                var obj = {
                    'code': '134',
                    'jname': '茨城',
                    'pjname': '関東・甲信越発',
                    'facet': 1
                }
                FacetArr.push(obj);
                var obj = {
                    'code': '119',
                    'jname': '新潟',
                    'pjname': '関東・甲信越発',
                    'facet': 1
                }
                FacetArr.push(obj);
                var obj = {
                    'code': '139',
                    'jname': '長野',
                    'pjname': '関東・甲信越発',
                    'facet': 1
                }
                FacetArr.push(obj);
                var obj = {
                    'code': '103',
                    'jname': '名古屋',
                    'pjname': '東海・北陸発',
                    'facet': 1
                }
                FacetArr.push(obj);
                var obj = {
                    'code': '124',
                    'jname': '富山',
                    'pjname': '東海・北陸発',
                    'facet': 1
                }
                FacetArr.push(obj);
                var obj = {
                    'code': '114',
                    'jname': '石川',
                    'pjname': '東海・北陸発',
                    'facet': 1
                }
                FacetArr.push(obj);
                var obj = {
                    'code': '112',
                    'jname': '静岡',
                    'pjname': '東海・北陸発',
                    'facet': 1
                }
                FacetArr.push(obj);
                var obj = {
                    'code': '102',
                    'jname': '大阪',
                    'pjname': '関西発',
                    'facet': 1
                }
                FacetArr.push(obj);
                var obj = {
                    'code': '143',
                    'jname': '神戸',
                    'pjname': '関西発',
                    'facet': 1
                }
                FacetArr.push(obj);
                var obj = {
                    'code': '113',
                    'jname': '岡山',
                    'pjname': '中国・四国発',
                    'facet': 1
                }
                FacetArr.push(obj);
                var obj = {
                    'code': '131',
                    'jname': '出雲',
                    'pjname': '中国・四国発',
                    'facet': 1
                }
                FacetArr.push(obj);
                var obj = {
                    'code': '135',
                    'jname': '米子',
                    'pjname': '中国・四国発',
                    'facet': 1
                }
                FacetArr.push(obj);
                var obj = {
                    'code': '136',
                    'jname': '鳥取',
                    'pjname': '中国・四国発',
                    'facet': 1
                }
                FacetArr.push(obj);
                var obj = {
                    'code': '107',
                    'jname': '広島',
                    'pjname': '中国・四国発',
                    'facet': 1
                }
                FacetArr.push(obj);
                var obj = {
                    'code': '132',
                    'jname': '山口',
                    'pjname': '中国・四国発',
                    'facet': 1
                }
                FacetArr.push(obj);
                var obj = {
                    'code': '138',
                    'jname': '岩国',
                    'pjname': '中国・四国発',
                    'facet': 1
                }
                FacetArr.push(obj);
                var obj = {
                    'code': '115',
                    'jname': '徳島',
                    'pjname': '中国・四国発',
                    'facet': 1
                }
                FacetArr.push(obj);
                var obj = {
                    'code': '111',
                    'jname': '高松',
                    'pjname': '中国・四国発',
                    'facet': 1
                }
                FacetArr.push(obj);
                var obj = {
                    'code': '108',
                    'jname': '松山',
                    'pjname': '中国・四国発',
                    'facet': 1
                }
                FacetArr.push(obj);
                var obj = {
                    'code': '110',
                    'jname': '高知',
                    'pjname': '中国・四国発',
                    'facet': 1
                }
                FacetArr.push(obj);
                var obj = {
                    'code': '104',
                    'jname': '福岡',
                    'pjname': '九州・沖縄発',
                    'facet': 1
                }
                FacetArr.push(obj);
                var obj = {
                    'code': '127',
                    'jname': '北九州',
                    'pjname': '九州・沖縄発',
                    'facet': 1
                }
                FacetArr.push(obj);
                var obj = {
                    'code': '122',
                    'jname': '長崎',
                    'pjname': '九州・沖縄発',
                    'facet': 1
                }
                FacetArr.push(obj);
                var obj = {
                    'code': '120',
                    'jname': '熊本',
                    'pjname': '九州・沖縄発',
                    'facet': 1
                }
                FacetArr.push(obj);
                var obj = {
                    'code': '121',
                    'jname': '大分',
                    'pjname': '九州・沖縄発',
                    'facet': 1
                }
                FacetArr.push(obj);
                var obj = {
                    'code': '123',
                    'jname': '宮崎',
                    'pjname': '九州・沖縄発',
                    'facet': 1
                }
                FacetArr.push(obj);
                var obj = {
                    'code': '116',
                    'jname': '鹿児島',
                    'pjname': '九州・沖縄発',
                    'facet': 1
                }
                FacetArr.push(obj);
                var obj = {
                    'code': '106',
                    'jname': '沖縄',
                    'pjname': '九州・沖縄発',
                    'facet': 1
                }
                FacetArr.push(obj);
                var makeBox = function (FacetArrReal) {
                    var html = '<div class="overlayBlk overlayDeptBox">';
                    html += '<div class="selectClose"><a href="javascript:void(0);">閉じる</a></div>';
                    html += '<div class="overlayMds04">出発地を選択してください</div>';
                    html += '<form class="subWinForm">';
                    html += '<div class="overlayLine">';
                    html += '<dl>';
                    for (var z in destFacetArr) {
                        var p = destFacetArr[z];
                        html += '<dt class="radioBox">';
                        // html += '<label for="rank"><input type="checkbox" name="p_hatsu" id="rank" value="'+p.code+'" data-name="">' + p.jname + '</label>';
                        html += '<label for="rank">' + p.jname + '</label>';
                        html += '</dt>';
                        html += '<dd><ul class="radioBox">';
                        for (var i in FacetArr) {
                            var m = FacetArr[i];
                            if (p.jname != m.pjname) {
                                continue;
                            }
                            var facet = 0;
                            for (var x in FacetArrReal) {
                                var l = FacetArrReal[x];
                                if (l.code == m.code) {
                                    facet = l.facet;
                                }
                            }
                            if (facet < 1) {
                                //continue;
                            }
                            // html += '<li><label for="rank' + i + '"><input type="checkbox" name="p_hatsu" id="rank' + i + '" value="' + m.code + '" data-name="' + m.jname + '">' + m.jname + '[' + facet + ']' + '</label></li>';
                            // html += '<li><input type="checkbox" name="p_hatsu" id="rank' + i + '" value="' + m.code + '" data-name="' + m.jname + '">'+'<label for="rank' + i + '">' + m.jname + '[' + facet + ']' + '</label></li>';
                            html += '<li><label for="rank' + i + '"><input type="checkbox" name="p_hatsu" id="rank' + i + '" value="' + m.code + '" data-name="' + m.jname + '発">' + m.jname + '[' + facet + ']' + '</label></li>';
                        }
                        html += '</ul></dd>';
                    }
                    html += '</dl>';
                    html += '</div>';
                    html += '</form>';
                    html += '</div>';
                    methods.rBoxFadeIn(html);
                    setTimeout(function () {
                        var selectVal = $("#preHatsu").attr("data-code");
                        if (selectVal.indexOf(",")) {
                            var selectArr = selectVal.split(",");
                            for (var i in selectArr) {
                                $('.subWinForm input[value="' + selectArr[i] + '"]').prop('checked', true);
                                $('.subWinForm input[value="' + selectArr[i] + '"]').parent('label').css({
                                    "background-color": "#797979",
                                    "color": "#ffffff",
                                    "padding": "5px"
                                });
                            }
                        } else {
                            var selectVal = $("#preHatsu").attr("data-code");
                            $('.subWinForm input[value="' + selectVal + '"]').prop('checked', true);
                            $('.subWinForm input[value="' + selectVal + '"]').parent('label').css({
                                "background-color": "#797979",
                                "color": "#ffffff",
                                "padding": "5px"
                            });
                        }
                    }, 500);
                    // $('.radioBox').changeRadio({'defaultBg':'#ffffff','checkedBg':'#F36B6B'});
                    //出発地選択時
                    $('.subWinForm input').on('click', function () {
                        var $targetObj = $('#preHatsu');
                        $("#rBox").fadeOut("fast");
                        var name = $(this).attr('data-name');
                        $targetObj.val(name).attr('value', name);
                        $targetObj.attr('data-code', $(this).val());
                        $("#iSearchBox-freeplan #p_hatsu").val($(this).val());
                        // 国ページで国がテキストまたは都市ページ
                        if(($("#p_category").val() == '2' && $("#p_search_country").val().length < 1 && $("#p_except_country").val().length < 1) ||
                            $("#p_except_country").val() == 'マカオ' || ($("#p_category").val() == '3')){
                            methods.mokutekiClear('City');
                        }
                        else{
                            methods.mokutekiClear('Country,City');
                        }
                        methods.getHitNum();
                        // 国ページで国がテキストまたは都市ページ
                        if(($("#p_category").val() == '2' && $("#p_search_country").val().length < 1 && $("#p_except_country").val().length < 1) ||
                            $("#p_except_country").val() == 'マカオ' || ($("#p_category").val() == '3')){
                            // 都市モーダル
                            $("#preCity_free").trigger('click');
                        }
                        else{
                            // 国モーダル
                            $("#preCountry_free").trigger('click');
                        }

                    });
                }
                var options = {
                    kind: 'Detail',
                    p_data_kind: 3,
                    p_rtn_data: "p_hatsu_name"
                }
                var dataVal = NSearch.requestProcess(options);
                delete(dataVal.p_hatsu);
                var settings = {
                    data: dataVal,
                    dataType: "json",
                    success: function (json) {
                        var FacetArrReal = NSearch.getFacet('p_hatsu_name', json.facet_counts.facet_fields);
                        makeBox(FacetArrReal);
                    }
                }
                NSearch.ajaxProcess(settings);
                // var options = {
                // 	kind: 'Detail',
                // 	p_data_kind: 3,
                // 	p_rtn_data: "p_conductor"
                // }
                // var dataVal = NSearch.requestProcess(options);
                // var settings = {
                // 	data: dataVal,
                // 	dataType: "json",
                // 	success: function(json) {
                // 		$(".JS_Submit span").html('（' + json.response.p_hit_num + '件）');
                // 	},
                // 	complete: function(json){
                // 		methods.setChecked('p_hatsu');
                // 	}
                //
                // }
                // NSearch.ajaxProcess(settings);
            });
            $(document).on('click', '.rootBox input#preDest_free,.rootBox input#preCountry_free,.rootBox input#preCity_free', function () {
                //目的地生成
                function mokutekiMake(FacetArr, id) {
                    var ttl = '';
                    if (id == 'preDest_free') {
                        ttl = '目的エリア';
                    }
                    if (id == 'preCountry_free') {
                        ttl = '国名';
                    }
                    if (id == 'preCity_free') {
                        ttl = '都市名';
                    }
                    var html = '<div class="overlayBlk overlayDaysBox destinationBox">';
                    html += '<div class="selectClose"><a href="javascript:void(0);">閉じる</a></div>';
                    html += '<div class="overlayMds04">' + ttl + 'を選択してください</div>';
                    html += '<form class="subWinForm">';
                    html += '<div class="overlayLine">';
                    html += '<dl>';

                    var search_counrty = $("#p_search_country").val();
                    var except_country = $("#p_except_country").val();
                    for (var i in FacetArr) {
                        var m = FacetArr[i];
                        if (m.facet <= 0 && (id == 'preDest_free' || id == 'preCountry_free' || id == 'preCity_free')) {
                            continue;
                        }
                        if (m.code == 'MTR' || m.code == 'JP') {
                            continue;
                        }
                        // 国ページなら
                        if($("#p_category").val() == '2'){
                            // 国モーダル
                            if(ttl == '国名'){
                                // 含むなら
                                reg = new RegExp(m.jname);
                                // 量産化CSVにあるか、例外的な国なら
                                if((0 < search_counrty.length && search_counrty.match(reg)) || except_country.match(reg)){
                                    html += '<dd class="radioBox"><label for="rank' + i + '"><input type="radio" name="' + id + '" id="rank' + i + '" value="' + m.code + '" data-name="' + m.jname + '">' + m.jname + '</label></dd>';
                                }
                            }
                            else{
                                html += '<dd class="radioBox"><label for="rank' + i + '"><input type="radio" name="' + id + '" id="rank' + i + '" value="' + m.code + '" data-name="' + m.jname + '">' + m.jname + '</label></dd>';
                            }
                        // 方面ページで南太平洋orオセアニアのページなら
                        }else if ($("#p_category").val() == '1' && ($("#def_p_mokuteki").val() == 'FOC-PF-,FOC-NC-,FOC-FJ-,FOC-PG-,FOC-VU-' || $("#def_p_mokuteki").val() == 'FOC-AU-,FOC-NZ-')) {
                            // 国モーダル
                            if(ttl == '国名'){
                                // 含むなら
                                reg = new RegExp(m.code);
                                if($("#def_p_mokuteki").val().match(reg)){
                                    html += '<dd class="radioBox"><label for="rank' + i + '"><input type="radio" name="' + id + '" id="rank' + i + '" value="' + m.code + '" data-name="' + m.jname + '">' + m.jname + '</label></dd>';
                                }
                            }
                            else{
                                html += '<dd class="radioBox"><label for="rank' + i + '"><input type="radio" name="' + id + '" id="rank' + i + '" value="' + m.code + '" data-name="' + m.jname + '">' + m.jname + '</label></dd>';
                            }
                        // 都市ページで検索の指定があるなら。シチリア島...
                        }else if ($("#p_category").val() == '3' && 0 < search_counrty.length) {
                            // 含むなら
                            reg = new RegExp(m.jname);
                            // 量産化CSVにあるか、例外的な国なら
                            if(search_counrty.match(reg)){
                                html += '<dd class="radioBox"><label for="rank' + i + '"><input type="radio" name="' + id + '" id="rank' + i + '" value="' + m.code + '" data-name="' + m.jname + '">' + m.jname + '</label></dd>';
                            }

                        }else{
                            html += '<dd class="radioBox"><label for="rank' + i + '"><input type="radio" name="' + id + '" id="rank' + i + '" value="' + m.code + '" data-name="' + m.jname + '">' + m.jname + '</label></dd>';
                        }
                    }
                    html += '</dl>';
                    html += '</div>';
                    html += '</form>';
                    html += '</div>';
                    methods.rBoxFadeIn(html);
                    setTimeout(function () {
                        var selectVal = $("#" + id).attr("data-code");
                        if (typeof selectVal !== 'undefined') {
                            var $target = $('.subWinForm input[value="' + selectVal + '"]');
                            $target.prop('checked', true);
                            $target.parent('label').css({
                                "background-color": "#797979",
                                "color": "#ffffff",
                                "padding": "5px"
                            });
                        }
                    }, 500);
                    //目的地選択時
                    $('.subWinForm input').on('click', function () {
                        var $targetObj = $('#' + id);
                        $("#rBox").fadeOut("fast");
                        $targetObj.val($(this).attr('data-name'));
                        if(id == 'preDest_free')
                        {
                            $('#preDest_free').attr('data-code', $(this).val());
                        }
                        else
                        {
                            $targetObj.attr('data-code', $(this).val());
                        }
                        var myName = $(this).attr('name');
                        switch (myName) {
                            case 'preDest_free':
                                methods.mokutekiClear('Country,City');
                                break;
                            case 'preCountry_free':
                                methods.mokutekiClear('City');
                                break;
                        }
                        mokutekiRequestSet($("#iSearchBox-freeplan"));
                        methods.getHitNum();
                        //$targetObj.next("input[id^='pre']").trigger('click');
                        methods.subWinClose('');
                        $targetObj.parents("tr").next().find("input").not('#p_dep_date_eu').trigger('click');
                    });
                }
                /*----目的地のパラメータセット---*/
                function mokutekiRequestSet($obj) {
                    $target = $(partsName.formObj + " #p_mokuteki");
                    var dest = country = city = '';
                    dest = $obj.find("#preDest_free").attr('data-code');
                    country = $obj.find("#preCountry_free").attr('data-code');
                    city = $obj.find("#preCity_free").attr('data-code');
                    var tmp = dest + '-' + country + '-' + city;
                    if (!dest && !country && !city) {
                        tmp = '';
                    }
                    if (tmp == '--') {
                        tmp = '';
                    }
                    if (tmp.length > 0) {
                        $target.val(tmp);
                    } else {
                        $target.val('');
                    }
                }
                var id = $(this).attr('id');
                var dest = $("#preDest_free").attr('data-code');
                var country = $("#preCountry_free").attr('data-code');
                var city = $("#preCity_free").attr('data-code');
                var myName = '';
                var parentName = '';
                var mokuteki = '';
                switch (id) {
                    case 'preDest_free':
                        myName = 'p_dest_name';
                        parentName = 'p_hatsu';
                        mokuteki = '';
                        break;
                    case 'preCountry_free':
                        myName = 'p_country_name';
                        parentName = 'preDest_free';
                        if (dest) {
                            mokuteki = dest + '--';
                        }
                        break;
                    case 'preCity_free':
                        myName = 'p_city_cn';
                        parentName = 'preCountry_free';
                        if (dest || country) {
                            mokuteki = dest + '-' + country + '-';
                        }
                        break;
                }
                var parentVal = $("#" + parentName).attr('data-code');
                var options = {
                    p_data_kind: 1,
                    p_mokuteki: mokuteki,
                    p_rtn_data: myName
                }
                var dataVal = NSearch.requestProcess(options);
                var settings = {
                    data: dataVal,
                    dataType: "json",
                    success: function (json) {
                        if (json.facet_counts) {
                            var FacetArr = NSearch.getFacet(myName, json.facet_counts.facet_fields, parentVal);
                            mokutekiMake(FacetArr, id);
                        }
                    },
                    complete: function (json) {
                        methods.setChecked(id, 'data-code');
                    }
                }
                NSearch.ajaxProcess(settings);
            });
            /*--方面選択時--*/
            $(document).on('change', '#preDest_free', function () {
                mokutekiClear('Country,City', $(this));
            });
            /*--国選択時--*/
            $(document).on('change', '#preCountry_free', function () {
                mokutekiClear('City', $(this));
            });
            /*--都市選択時--*/
            $(document).on('change', '#preCity_free', function () {
            });
            $(document).on('change', '.rootBox select', function () {
                //目的地生成
                function makeMokutekiSelect(nameVal, parentVal, json) {
                    var id = '';
                    switch (nameVal) {
                        case 'p_country_name':
                            myparam = 'preCountry_free';
                            break;
                        case 'p_city_cn':
                            myparam = 'preCity_free';
                            break;
                        default:
                            myparam = '';
                            break;
                    }
                    var targetObj = $('#' + myparam);
                    targetObj.children('option:gt(0)').remove();
                    for (var i in json[nameVal]) {
                        var m = json[nameVal][i];
                        if (m.facet < 1) {
                            continue;
                        }
                        if (m.parentKey == parentVal || m.parentKey == undefined) {
                            $opObj = $("<option/>").text(m.name).val(m.key);
                            targetObj.append($opObj);
                        }
                    }
                }
                /*----目的地のパラメータセット---*/
                function mokutekiRequestSet($obj) {
                    $target = $("#p_mokuteki");
                    var dest = country = city = '';
                    ($obj.siblings("#preDest_free").length != 0) ?
                        dest = $obj.siblings("#preDest_free").find("option:selected").val() :
                        dest = $obj.find("option:selected").val();
                    ($obj.siblings("#preCountry_free").length != 0) ?
                        country = $obj.siblings("#preCountry_free").find("option:selected").val() :
                        country = $obj.find("option:selected").val();
                    ($obj.siblings("#preCity_free").length != 0) ?
                        city = $obj.siblings("#preCity_free").find("option:selected").val() :
                        city = $obj.find("option:selected").val();
                    var tmp = dest + '-' + country + '-' + city;
                    if (tmp == '--') {
                        tmp = '';
                    }
                    if (tmp.length > 0) {
                        $target.val(tmp);
                    } else {
                        $target.val('');
                    }
                }
                //p_mokuteki値セット
                mokutekiRequestSet($(this));
                var id = $(this).attr('id');
                var myparam = '';
                switch (id) {
                    case 'preDest_free':
                        myparam = 'p_country_name';
                        break;
                    case 'preCountry_free':
                        myparam = 'p_city_cn';
                        break;
                    case 'preCity_free':
                        myparam = 'p_conductor';
                        break;
                }
                var parentVal = $(this).val();
                var options = {
                    kind: 'Detail',
                    p_data_kind: 3,
                    p_rtn_data: myparam
                }
                var dataVal = NSearch.requestProcess(options);
                var settings = {
                    data: dataVal,
                    dataType: "json",
                    success: function (json) {
                        makeMokutekiSelect(myparam, parentVal, json);
                        $(".srchResult span").html(addFigure(json.response.p_hit_num));
                    }
                }
                NSearch.ajaxProcess(settings);
            });
            /*-----event 出発日 ------------------------------------------------------------*/
            $('#iSearchBox-freeplan input[name="p_dep_date"]').click(function () {
                var val = $(this).val();
                methods.DepDate(val);
            });
            $('#iSearchBox-freeplan .js_dep_date_cal').click(function () {
                var val = $(this).val();
                methods.DepDate(val);
            });
            /*-----event 航空券選択 ------------------------------------------------------------*/
//            $(this).on('click', '.Box_p_carr', function () {
                $(document).on("click",".Box_p_carr", function() {
                //航空券生成
                function p_carr_Make(FacetArr, id) {
                    var html = '<div class="overlayBlk overlayDaysBox">';
                    html += '<div class="selectClose"><a href="javascript:void(0);">閉じる</a></div>';
                    html += '<div class="overlayMds04">ご希望の航空会社を選択してください</div>';
                    var ul = '';
                    for (var i in FacetArr) {
                        var m = FacetArr[i];
                        if (m.facet <= 0) {
                            continue;
                        }
                        ul += '<li>';
                        ul += '<label for="carr' + i + '">';
                        ul += '<input type="checkbox" value="' + m.code + '" id="carr' + i + '" name="p_carr" title="' + m.jname + '">';
                        ul += m.jname;
                        ul += '</label>';
                        ul += '</li>';
                    }
                    if (ul) {
                        html += '<form class="subWinForm">';
                        html += '<div class="overlayLine">';
                        html += '<ul>' + ul + '</ul>';
                        html += '</div>';
                        html += '</form>';
                        html += '<div class="selectClear kikan"><a href="javascript:void(0);" class="subWinClear" title="p_carr">選択している条件をクリア</a></div>';
                        html += '<div class="decisionBox">';
                        html += '<p class="txt">航空会社を選択したら「航空会社を決定」をクリックしてください。</p>';
                        html += '<p class="btn"><a href="javascript:void(0);" class="subWinDecide" title="p_carr">航空会社を決定</a></p>';
                        html += '</div>';
                    } else {
                        html += '選択可能な項目がございません。';
                    }
                    html += '</div>';
                    return html;
                }
                var options = {
                    kind: "Box_p_carr",
                    p_data_kind: 1,
                    p_carr: '',
                    p_rtn_data: "p_carr_cn"
                }
                var dataVal = NSearch.requestProcess(options);
                var settings = {
                    data: dataVal,
                    dataType: "json",
                    success: function (json) {
                        if (json.facet_counts) {
                            var FacetArr = NSearch.getFacet('p_carr_cn', json.facet_counts.facet_fields);
                            var html = p_carr_Make(FacetArr, 'p_carr_cn');
                        } else {
                        }
                        methods.rBoxFadeIn(html);
                        //$(".JS_Submit span").html('（' + json.response.p_hit_num + '件）');
                    },
                    complete: function (json) {
                        methods.setChecked('p_carr');
                    }
                }
                NSearch.ajaxProcess(settings);
            });
            /*-----event ホテル選択 ------------------------------------------------------------*/
//            $(this).on('click', '.Box_p_hotel_code', function () {
            $(document).on("click",".Box_p_hotel_code", function() {
                //航空券生成
                function p_hotel_code_Make(FacetArr, id, FacetAll) {
                    var data = {};
                    var obj1 = {};
                    var no = 0;
                    //ホテルファセット編集 [都市コード]-ファセット降順
                    for (var i in FacetArr) {
                        var m = FacetArr[i];
                        if (m.facet <= 0) {
                            continue;
                        }
                        var city = m.city;
                        var rank = m.rank;
                        var country = methods.CityToCountryCode[city];
                        var dest = methods.CountryToDestCode[country];
                        if (typeof dest == 'undefined' || typeof country == 'undefined') {
                            continue;
                        }
                        if (typeof city !== 'undefined') {
                            if (rank == '') {
                                rank = 'none';
                            }
                            var obj1 = {};
                            obj1[city] = {};
                            obj1[city][no] = {};
                            obj1[city][no] = FacetArr[i];
                            $.extend(true, data, obj1);
                            no++;
                        }
                    }
                    var hotelClassName = 'ご希望の';
                    var $htchk = $('.hotelGrade input[name="p_bunrui"]:checked');
                    var chkVal = $htchk.val();
                    if (typeof chkVal !== 'undefined' && chkVal !== '') {
                        hotelClassName = $htchk.parent('label').text().replace(/\s+/g, "");
                    }
                    var html = '<div class="overlayBlk overlayDaysBox">';
                    html += '<div class="selectClose"><a href="javascript:void(0);">閉じる</a></div>';
                    html += '<div class="overlayMds04">' + hotelClassName + 'ホテルを選択してください</div>';
                    var list = '';
                    var hotelCnt = 0;
                    //都市順
                    var cityCnObj = FacetAll.p_city_cn;
                    for (var k1 in cityCnObj) {
                        if (typeof cityCnObj[k1] !== 'string' || cityCnObj[k1].indexOf(',') == -1) {
                            continue;
                        }
                        var tmp = cityCnObj[k1].split(',');
                        var destCode = tmp[0];
                        var countryCode = tmp[1];
                        var cityCode = tmp[2];
                        var cityName = tmp[3];
                        var countryName = methods.CountryNameObj[countryCode];
                        if (typeof data[cityCode] == "undefined") {
                            continue;
                        }
                        var hotelList = '';
                        for (var tno in data[cityCode]) {
                            var m = data[cityCode][tno];
                            //ホテル数
                            hotelList += '<li><label for="hotel' + hotelCnt + '">';
                            hotelList += '<input type="checkbox" value="' + m.code + '" id="hotel' + hotelCnt + '" name="' + id + '" title="' + m.jname + '">';
                            hotelList += m.jname + '[' + m.facet + ']';
                            hotelList += '</label>';
                            var flg = NSearch.RegisteredHotelCode.filter(function (item, index) {
                                if ((item.P_HOTEL_CODE).indexOf(m.code) >= 0) return true;
                            });
                            if (flg.length > 0) {
                                hotelList += '<a class="facilityicon" onclick="openRequestW(' + "\'/freeplan-i/hotel/detail/h" + m.code + ".php?viewtype=s\',\'" + m.code + "\'" + ')" href="javascript:void(0);">ホテルの詳細情報</a>';
                            }
                            hotelList += '</li>';
                            hotelCnt++;
                        }
                        if (hotelList) {
                            list += '<dl class="hotelList">';
                            list += '<dt>' + countryName + ' ＞ ' + cityName + '</dt>';
                            list += '<dd>';
                            list += '<ul>' + hotelList + '</ul>';
                            list += '</dd>';
                            list += '</ul></dd>';
                            list += '</dl>';
                        }
                    }
                    if (list) {
                        html += '<form class="subWinForm">';
                        html += '<div class="overlayLine">';
                        html += list;
                        html += '</div>';
                        html += '</form>';
                        html += '<div class="selectClear kikan"><a href="javascript:void(0);" class="subWinClear" title="p_hotel_code">選択している条件をクリア</a></div>';
                        html += '<div class="decisionBox">';
                        html += '<p class="txt">ホテルを選択したら「ホテルを決定」をクリックしてください。</p>';
                        html += '<p class="btn"><a href="javascript:void(0);" class="subWinDecide" title="p_hotel_code">ホテルを決定</a></p>';
                        html += '</div>';
                    } else {
                        html += '選択可能な項目がございません。';
                    }
                    html += '</div>';
                    return html;
                }
                var options = {
                    kind: "Box_p_hotel_code",
                    p_data_kind: 1,
                    p_hotel_code: '',
                    p_rtn_data: "p_hotel_name,p_country_name,p_city_cn"
                }
                var dataVal = NSearch.requestProcess(options);
                var settings = {
                    data: dataVal,
                    dataType: "json",
                    success: function (json) {
                        if (json.facet_counts) {
                            var FacetArr = NSearch.getFacet('p_hotel_name', json.facet_counts.facet_fields);
                            var html = p_hotel_code_Make(FacetArr, 'p_hotel_code', json.facet_counts.facet_fields);
                        } else {
                        }
                        methods.rBoxFadeIn(html);
                        //$(".JS_Submit span").html('（' + json.response.p_hit_num + '件）');
                    },
                    complete: function (json) {
                        methods.setChecked('p_hotel_code');
                    }
                }
                NSearch.ajaxProcess(settings);
            });
            /*----ホテルクラス---*/
            $(document).on('click', partsName.hotelRankChk, function () {
                var targetAll = partsName.formsubWinName + ' .hotelList dt,' + partsName.formsubWinName + ' .hotelList dd';
                var valAry = new Array();
                $("input[name='hotelRank']:checked").each(function (index, element) {
                    var val = $(this).attr('data-group');
                    valAry.push(val);
                });
                if (valAry.length > 0) {
                    $(targetAll).hide();
                    for (var i = 0; i < valAry.length; i++) {
                        var g = valAry[i];
                        var target = partsName.formsubWinName + ' .' + g;
                        $(target).show();
                    }
                } else {
                    $(targetAll).show();
                }
                var tgValue = $(this).attr('data-group');
                if ($(this).prop('checked') == true) {
                    $('input[value="' + tgValue + '"]').prop('checked', true);
                } else {
                    $('input[value="' + tgValue + '"]').prop('checked', false);
                }
            });
            /*----ライトBOXクリアボタン---*/
            $(document).on('click', '.subWinClear', function () {
                var myname = $(this).attr('title');
                if (myname == 'p_carr' || myname == 'p_hotel_code') {
                    $('.subWinForm input').each(function () {
                        $(this).attr("checked", false);
                    });
                }
                var mynameTag = 'input[name="' + myname + '"]';
                $(mynameTag).val('');
            });
            /*----クリアボタン---*/
            $(document).on('click', '.clBtn a', function () {
                var $myObj = $("#iSearchBox-freeplan");
            });
            /*--event 旅行日数------------------------------------------------------------*/
            $(document).on('click', '#kikan_list td', function () {
                var max = datamin = datamax = '';
                var html = more = '';
                var selObj = new Object();
                var $myobj = $(this);
                if ($('#kikan_list td.selected').length > 1) {
                    $('#kikan_list td').removeClass("selected");
                }
                $myobj.addClass("selected");
                var val = $myobj.data('val');
                $('#kikan_list td.selected').each(function (i, val) {
                    var selval = $(this).data('val');
                    selObj[i] = new Object();
                    selObj[i].key = selval;
                });
                var selObjCnt = Object.keys(selObj).length;
                if (selObjCnt > 0) {
                    datamin = methods.getMin(selObj);
                    datamax = methods.getMax(selObj);
                    max = datamax;
                    if (datamax == 9) {
                        max = 20;
                        more = '以上';
                    }
                    if (datamin == datamax) {
                        if (datamin == 1) {
                            html += '日帰り';
                        } else {
                            html += datamax + '日間' + more;
                        }
                    } else {
                        for ($i = datamin; $i < datamax; $i++) {
                            $("#kikan_list td[data-val='" + $i + "']").addClass('selected');
                        }
                        if (datamax != 9) {
                            html += datamin + '〜' + datamax + '日間' + more;
                        } else {
                            html += datamin + '日間以上';
                        }
                    }
                } else {
                    html = '全て';
                }
                $('#iSearchBox-freeplan #p_kikan_min').val(datamin);
                $('#iSearchBox-freeplan  #p_kikan_max').val(max);
                $('#kikan_minmax').text(html);
                var options = {
                    kind: 'Detail',
                    p_data_kind: 3,
                    p_rtn_data: 'p_conductor'
                }
                var dataVal = NSearch.requestProcess(options);
                var settings = {
                    data: dataVal,
                    dataType: "json",
                    success: function (json) {
                        methods.jsonData = json;
                        methods.makeMokutekiSelect();
                        $(".srchResult span").html(addFigure(json.response.p_hit_num) );
                    }
                }
                NSearch.ajaxProcess(settings);
            });
            /*----決定ボタン------------------------------------------------------------*/
            $(document).on('click', '.subWinDecide', function () {
                var myname = $(this).attr('title');
                methods.subWinDecideAction(myname);
            });
            /*----閉じるボタン------------------------------------------------------------*/
            $(document).on('click', '.selectClose', function () {
                var paramName = $(this).parents('div.overlayBlk').find('.subWinDecide').attr('title');
                if (paramName == 'p_detail') {
                    //変更チェック
                    methods.detailChgChk();
                } else if (paramName == 'p_mokuteki') {
                    //目的地のみチェックする
                    methods.mokutekiDecideChk();
                }
                //サブウィン閉じる
                methods.subWinClose('');
                //methods.toTop();
                void(0);
                return false;
            });
            $(document).on('click', '.SB_BtnClose', function () {
                //サブウィン閉じる
                methods.DelSubWinforSenmon();
            });
            $(document).on('click', '.dayClearBtn a', function () {
                $("#p_kikan_max").val("");
                $("#p_kikan_min").val("");
                $('#kikan_minmax').text("全て");
                $('#kikan_list td').removeClass("selected");
                methods.getHitNum();
            });
            //オールクリア
            $(document).on('click', '.clBtn a', function () {
                $("#iSearchBox-freeplan").find("textarea, :text, select:not(#p_hatsu)").val("").end().find(":checked").prop("checked", false);
                $("#iSearchBox-freeplan #p_hatsu").val($('#iSearchBox-freeplan #def_p_hatsu').val());
                $("#iSearchBox-freeplan #preHatsu").attr("data-code", $("#iSearchBox-freeplan #preHatsu").attr("def-data-code"))
                                                .val($('#iSearchBox-freeplan #def_p_hatsu').attr('data-val'));
                $("#iSearchBox-freeplan #p_mokuteki").val($('#iSearchBox-freeplan #def_p_mokuteki').val());
//                $("#preDest_free").val('').attr('data-code', '');
                $("#iSearchBox-freeplan #preCountry_free").val('').attr('data-code', '');
                $("#iSearchBox-freeplan #preCity_free").val('').attr('data-code', '');
                $("#iSearchBox-freeplan #p_dep_date_eu").val("");
                $("#iSearchBox-freeplan #p_kikan_max").val("");
                $("#iSearchBox-freeplan #p_kikan_min").val("");
                $('#iSearchBox-freeplan #kikan_minmax').text("全て");
                $('#iSearchBox-freeplan #kikan_list td').removeClass("selected");
                $("#iSearchBox-freeplan .retCarr").html("指定しない");
                $("#iSearchBox-freeplan .retHotelName").html("指定しない");
                $("#iSearchBox-freeplan #p_price_min").val("");
                $("#iSearchBox-freeplan #p_price_max").val("");
                $('#iSearchBox-freeplan #p_total_amount_divide04').prop('checked', true);
                $("#iSearchBox-freeplan input[name='p_bunrui']").val("030");
                //Get post data or default
                req = NSearch.requestProcess(options);
                //初回リクエスト
                initRequest(req);
                //methods.getHitNum();
            });
            /*----- submit ------------------------------------------------------------*/
            $('#iSearchBox-freeplan .btn_simpleSrch').click(function () {
                methods.setConditions();
                //別のフォームで送信する
                var fields = $("#iSearchBox-freeplan input").serializeArray();
                var html = '';
                var seatclass_val = '';
                var bunrui_val = '';
                var discount_val = '';
                for (var i = 0; i < fields.length; i++) {
                    if (fields[i].name == 'p_seatclass') {
                        if (seatclass_val != '') {
                            seatclass_val += ',';
                        }
                        seatclass_val += fields[i].value;
                        continue;
                    }
                    if (fields[i].name == 'p_bunrui' && fields[i].value != '') {
                        if (bunrui_val != '') {
                            bunrui_val += ',';
                        }
                        bunrui_val += fields[i].value;
                        continue;
                    }
                    if (fields[i].name == 'p_discount') {
                        if (discount_val != '') {
                            discount_val += ',';
                        }
                        discount_val += fields[i].value;
                        continue;
                    }

                    html += '<input type="hidden" ' + 'name="' + fields[i].name + '"' + 'value=' + fields[i].value + '>';
                }
                html += '<input type="hidden" name="p_seatclass" value=' + seatclass_val + '>';
                html += '<input type="hidden" name="p_bunrui" value=' + bunrui_val + '>';
                html += '<input type="hidden" name="p_discount" value=' + discount_val + '>';
                var postUrl = 'http://' + window.location.hostname + '/search/ifree.php';
                var newForm = '<form method="post" action="' + postUrl + '" style="display:none" id="newForm">';
                newForm += html;
                newForm += '</form>';
                $('body').append(newForm);
                // return false;
                $('#newForm').submit();
                //$('.searchTour').submit();
            });
            /*----- onload ------------------------------------------------------------*/
            function initRequest(req) {
                var naigai = $("#MyNaigai").val();
                var rtn_data = "p_hatsu_name,p_dest_name,p_country_name,p_city_cn,p_kikan,p_carr_cn,p_price_flg,p_seatclass,p_timezone,p_total_amount_divide,p_discount,p_hotel_name,p_stock,p_decide,p_mainbrand,p_price_flg,p_web_conclusion_flag,p_conductor,p_bunrui";
                var options = {
                    MyNaigai: naigai,
                    dataType: "json",
                    kind: "Detail",
                    kindSub: "ReqOnly",
                    p_data_kind: 3,
                    p_rtn_data: rtn_data
                }
                //リクエストパラメータ
                dataVal = NSearch.requestProcess(options);
                $.extend(dataVal, req);
                methods.req = dataVal;
                //form hiddenに反映
                methods.makeSearchHidden(dataVal);
                var settings = {
                    data: dataVal,
                    dataType: "json",
                    success: function (json) {
                        //検索条件 BOXタグ生成
                        methods.reflectSearchConditions(json);
                        methods.makeDestNameObj(json);
                        methods.makeCountryNameObj(json);
                        methods.makeCityNameObj(json);
                        $(".srchResult span").html(addFigure(json.response.p_hit_num));
                        //料金スライダーの初期化
                        this.priceMin = '';
                        var p_price_flg = json.facet_counts.facet_fields.p_price_flg;
                        for (i in p_price_flg) {
                            if (typeof p_price_flg[i] == 'string') {
                                var num = parseInt(p_price_flg[i], 10);
                                var key = parseInt(key, 10);
                                if (num > key) {
                                    continue;
                                }
                                key = p_price_flg[i];
                            } else if (typeof p_price_flg[i] == 'number') {
                                if (p_price_flg[i] > 0) {
                                    this.priceMin = key;
                                }
                            }
                        }
                        if (this.priceMin > 10000) {
                            this.priceMin = 5000;
                        }
                        if (this.priceMin > 5000) {
                            this.priceMin = 1000;
                        }
                    },
                    complete: function (json) {
                        methods.startSilder(req, this.priceMin);
                    }
                }
                NSearch.ajaxProcess(settings);
            }
            var req = {};
            if ('pushState' in history) {
                setTimeout(function () {
                    //Get post data or default
                    req = NSearch.requestProcess(options);
                    window.addEventListener('popstate', function (e) {
                        if (e.state != null) {
                            //Get history data
                            req = methods._onLocChanged(e);
                        }
                    });
                    if (typeof window.history.state !== "undefined") {
                        //Get history data
                        req = methods._onLocChanged(window.history);
                        if (typeof(req) == "undefined") {
                            //Get post data or default
                            req = NSearch.requestProcess(options);
                        }
                    }
                    //初回リクエスト
                    initRequest(req);
                }, 100);
            } else {
                //Get post data or default
                //req = NSearch.requestProcess(options);
                //初回リクエスト
                //initRequest(req);
                //$('.clBtn a').trigger('click');
                setTimeout(function () {
                    //Get post data or default
                    req = NSearch.requestProcess(options);
                    //初回リクエスト
                    initRequest(req);
                }, 500);
            }
            return this;
        },
        //slider生成
        startSilder: function (req, priceMin) {
            //金額のヨリマデ作成
            //function makeYoriMade(min,max,str="円",NgStr=0){
            function makeYoriMade(min, max) {
                var ret = '';
                var NgStr = 0;
                var str = '円';
                if (typeof(min) == 'undefined' && typeof(max) == 'undefined' ||
                    min == '' && max == '') {
                    ret = '1,000〜';
                    return ret;
                }
                if (min != '' || max != '') {
                    //同じ場合
                    if (min == max) {
                        if (min == 0 || min == '') {
                            ret = NgStr;
                        }
                        else {	//単一
                            ret = addFigure(min) + str;
                            if (min >= 1000000) {
                                ret += '以上';
                            }
                        }
                    }
                    else {
                        if (min == '') {
                            min = NgStr;
                        }
                        ret = addFigure(min) + '〜' + addFigure(max) + str;
                        if (max >= 1000000) {
                            ret += '以上';
                        }
                    }
                }
                return ret;
            }
            var values_min = 0;
            if (req.p_price_min == '' || typeof req.p_price_min == 'undefined') {
                $('#p_price_min').val(priceMin);
                req.p_price_min = priceMin;
            }
            var values_max = price_t.length - 1;
            if (typeof req != 'undefined') {
                if (typeof req.p_price_min !== 'undefined' && req.p_price_min != '' && req.p_price_min != null) {
                    methods.req_p_price_min = req.p_price_min;
                    var key = methods.getKey(price_t, methods.req_p_price_min);
                    if (key !== '') {
                        values_min = parseInt(key);
                    }
                }
                if (typeof req.p_price_max !== 'undefined' && req.p_price_max != '' && req.p_price_max != null) {
                    methods.req_p_price_max = req.p_price_max;
                    var key = methods.getKey(price_t, methods.req_p_price_max);
                    if (key !== '') {
                        values_max = parseInt(key);
                    }
                }
            }
            $('#slider').slider({
                min: 0,
                max: price_t.length - 1,
                step: 1,
                range: true,
                values: [values_min, values_max],
                slide: function (e, ui) {
                    var price_min = parseInt(price_t[ui.values[0]]);
                    var price_max = parseInt(price_t[ui.values[1]]);
                    var priceStr = makeYoriMade(price_min, price_max);
                    $('.txtPric').html(priceStr);
                },
                change: function (e, ui) {
                    var price_min = parseInt(price_t[ui.values[0]]);
                    var price_max = parseInt(price_t[ui.values[1]]);
                    var priceStr = makeYoriMade(price_min, price_max);
                    $('.txtPric').html(priceStr);
                },
                stop: function (e, ui) {
                    var price_min = price_t[ui.values[0]];
                    var price_max = price_t[ui.values[1]];
                    var priceStr = makeYoriMade(price_min, price_max);
                    $('.txtPric').html(priceStr);
                    $("#p_price_min").val(price_min);
                    $("#p_price_max").val(price_max);
                    var options = {
                        kind: 'Detail',
                        p_data_kind: 3,
                        p_rtn_data: "p_conductor"
                    }
                    var dataVal = NSearch.requestProcess(options);
                    var settings = {
                        data: dataVal,
                        dataType: "json",
                        success: function (json) {
                            $(".srchResult span").html(addFigure(json.response.p_hit_num));
                        }
                    }
                    NSearch.ajaxProcess(settings);
                },
                create: function (e, ui) {
                    var priceStr = '1,000円〜';
                    var values = $(this).slider('option', 'values');
                    var price_min = $("#p_price_min").val();
                    var price_max = $("#p_price_max").val();
                    if (price_min != '' || price_max != '') {
                        var price_min = price_t[values[0]];
                        var price_max = price_t[values[1]];
                        var priceStr = makeYoriMade(price_min, price_max);
                    }
                    $('.txtPric').html(priceStr);
                }
            });
        },
        makeDestNameObj: function (json) {
            var data = json.facet_counts.facet_fields.p_dest_name;
            var obj = {};
            for (var i in data) {
                if (typeof data[i] == 'string' && data[i].indexOf(',') != -1) {
                    var tmp = data[i].split(',');
                    obj[tmp[0]] = tmp[1];
                }
            }
            methods.DestNameObj = obj;
        },
        makeCountryNameObj: function (json) {
            var data = json.facet_counts.facet_fields.p_country_name;
            var obj = {};
            for (var i in data) {
                if (typeof data[i] == 'string' && data[i].indexOf(',') != -1) {
                    var tmp = data[i].split(',');
                    obj[tmp[1]] = tmp[2];
                }
            }
            methods.CountryNameObj = obj;
        },
        makeCityNameObj: function (json) {
            var data = json.facet_counts.facet_fields.p_city_cn;
            var obj = {};
            var obj1 = {};
            var obj2 = {};
            var obj3 = {};
            for (var i in data) {
                if (typeof data[i] == 'string' && data[i].indexOf(',') != -1) {
                    var tmp = data[i].split(',');
                    obj[tmp[2]] = tmp[3];
                    obj1[tmp[2]] = tmp[1];
                    obj2[tmp[1]] = tmp[0];
                }
            }
            methods.CityNameObj = obj;
            methods.CityToCountryCode = obj1;
            methods.CountryToDestCode = obj2;
        },
        setChecked: function (myname, property) {
            if (property == null) {
                property = 'value';
            }
            var $myObj = $(partsName.formObj + ' [name="' + myname + '"]');
            var type = $('.subWinForm input[name="' + myname + '"]:first').attr('type');
            if (typeof $myObj.val() !== 'undefined' && $myObj.val() != '') {
                var val = $myObj.attr(property);
                if (type == 'checkbox') {
                    var arr = val.split(',');
                    for (i in arr) {
                        $('.subWinForm input[name="' + myname + '"][value="' + arr[i] + '"]').prop("checked", true);
                    }
                } else if (type == 'radio') {
                    $('.subWinForm input[name="' + myname + '"][value="' + val + '"]').prop("checked", true);
                }
            }
        },
        //ライトBOX決定ボタン 処理
        subWinDecideAction: function (myname) {
            var param = $('.subWinForm').serializeArray();
            var req = new Array();
            var def_req = new Object();
            var cnt = 0;
            for (i in param) {
                if (typeof(param[i]) !== "undefined" && param[i].name == myname) {
                    req[i] = param[i];
                    req[i]['title'] = $('input:checkbox[value=' + param[i].value + ']').attr('title');
                }
            }
            //タグ生成
            methods.htmlToParent(myname, req);
            //サブウィン閉じる
            methods.subWinClose(myname);
            //件数のみ取得
            methods.getHitNum();
            return this;
        },
        /*--件数のみ取得--*/
        getHitNum: function () {
            var options = {
                p_data_kind: 1,
                p_rtn_data: 'p_conductor'
            }
            var dataVal = NSearch.requestProcess(options);
            var settings = {
                data: dataVal,
                dataType: "json",
                success: function (json) {
                    $(".srchResult span").html(addFigure(json.response.p_hit_num));
                }
            }
            NSearch.ajaxProcess(settings);
        },
        getKey: function (ary, value) {
            var returnKey = '';
            for (var key in ary) {
                if (ary[key] == value) {
                    returnKey = key;
                    break;
                }
            }
            return returnKey;
        },
        /*--選択画面のCloseボタン--*/
        subWinClose: function (myname) {
            var target;
            var sel;
            //オーバーレイ消す
            target = $(partsName.overlay);
            sel = ':has("' + partsName.overlay + '")';
            if ($('body').is(sel)) {
                $(target).fadeOut("fast");
            }
            //ライトBOX消す
            target = $(partsName.rBox);
            sel = ':has("' + partsName.rBox + '")';
            if ($('body').is(sel)) {
                $(target).fadeOut("fast");
            }
            return this;
        },
        DelSubWinforSenmon: function () {
            $('.SB_BgBodyGR').fadeOut("fast", function () {
                $('.SB_BgBodyGR').remove();
            });
        },//init END
        /*出発日*/
        DepDate: function (DepDateSel) {
            var sendSearch = function () {
                var date;
                String(DepDateSel);
                if (DepDateSel.match(/([0-9]+)\/([0-9]+)\/([0-9]+)/)) {
                    var yyyy = RegExp.$1
                    var mm = ("0" + RegExp.$2).slice(-2)
                    var dd = ("0" + RegExp.$3).slice(-2)
                    dateVal = yyyy + mm + dd;
                } else if (DepDateSel.match(/([0-9]+)\/([0-9]+)/)) {
                    var yyyy = RegExp.$1
                    var mm = ("0" + RegExp.$2).slice(-2)
                    dateVal = yyyy + mm;
                } else {
                    dateVal = '';
                }
                partsName = {
                    'formParentName': ".searchTour"
                    , 'formsubWinName': ".subWinForm"
                    , 'overlay': "#overlay"
                    , 'rBox': "#rBox"
                    , 'formObj': "#iSearchBox-freeplan"
                };
                var BaseName = '/attending/senmon_kaigai/sharing/phpsc/ajax_calendar.php';
                var options = {
                    url: BaseName
                    , dataType: "script"
                    , SetParam: 'p_dep_date'
                    , RetParam: 1
                    , MyNaigai: 'i'
                }
                var NSearchCal = new NarrowSearch;
                NSearchCal.init(partsName);
                var dataVal = NSearchCal.requestProcess(options);
                var settings = {
                    url: BaseName
                    , dataType: "script"
                    , SetParam: 'p_dep_date'
                    , RetParam: 1
                    , MyNaigai: 'i'
                    , data: dataVal
                    , p_dep_date: dateVal
                    , complete: function (script) {
                        // 通信完了時の処理
                        var w = $(window).width();
                        var h = $(window).height();
                        var cw = $("#SubWinBox-Fp").outerWidth();
                        var ch = $("#SubWinBox-Fp").outerHeight();
                        var left = Math.floor((960 - $("#SubWinBox-Fp").outerWidth()) / 2);
                        // 上部ヘーダー、コンテンツ部分を最後にマイナスする
                        var top  = Math.floor(($(window).height() - $("#SubWinBox-Fp").outerHeight()) / 2) + $(window).scrollTop() - $(".banner").height() - 157;
                        //取得した値をcssに追加する
                        $("#SubWinBox-Fp").css({
                            //"left": ((w - cw) / 2) + "px",
                            //"top": ((h - ch) / 2) + "px"
                            "left": left,
                            "top": top,
                            "width": 700 + "px",
                            "z-index": 1500
                        });
                        $('#SubWinBox-Fp').fadeIn('fast');
                    }
                }
                NSearchCal.ajaxProcess(settings);
            }
            //まだボックスが出てなければ表示させる
            if (!$('body').is(this.TGOverlaySelector)) {
                //通信
                sendSearch(DepDateSel, 1);
                //入れ物の位置を設定
                var Offset = $('input[name="p_dep_date"]').position();
                var scrollTop = $('body').scrollTop();
                if (scrollTop == 0) {
                    scrollTop = $('html').scrollTop();
                }
                var top = Math.floor(($(window).height() - $(this.TgIdName).height()) / 2) + scrollTop - 200;
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
            //this.WatermarkOutDep(DepDateSel);
        },//init END
        /*--POSTリクエストと初期値マージ or 初期値--*/
        getPostParames: function (inPostKeys) {
            var req = {};
            var _key = 'param';
            //POSTあり
            var inPostKeys = $.map(postData.inPost, function (value, key) {
                return key;
            });
            //検索履歴なし
            if (inPostKeys.length > 0) {
                req = postData.inPost;
                //Default and merge
                req_default = methods.getInitParam();
                for (key in req_default) {
                    if (req[key] == null || req[key] == '') {
                        req[key] = req_default[key];
                    }
                }
            } else {
                //Get default
                req = methods.getInitParam();
            }
            return req;
        },
        //onload 検索条件反映
        reflectSearchConditions: function (json) {
            var reqParam = methods.req;
            /*--旅行日数設定--*/
            var setKikan = function (datamin, datamax) {
                var html = max = more = dispmax = '';
                if (datamin || datamax) {
                    max = datamax;
                    if (datamax == 9) {
                        max = 20;
                        more = '以上';
                    }
                    if (datamax >= 9) {
                        //日間以上
                        html += datamin + '日間以上';
                    } else {
                        //◯〜◯日間
                        if (datamin == datamax) {
                            if (datamin == 1) {
                                html += '日帰り';
                            } else {
                                html += datamax + '日間' + more;
                            }
                        } else {
                            for ($i = datamin; $i < datamax; $i++) {
                                $("#kikan_list td[data-val='" + $i + "']").addClass('selected');
                            }
                            if (datamax != 9) {
                                html += datamin + '〜' + datamax + '日間' + more;
                            } else {
                                html += datamin + '日間以上';
                            }
                        }
                    }
                } else {
                    //指定なし
                    html = '全て';
                }
                $('#iSearchBox-freeplan #p_kikan_min').val(datamin);
                $('#iSearchBox-freeplan #p_kikan_max').val(max);
                $('#iSearchBox-freeplan #kikan_minmax').text(html);
                $('#iSearchBox-freeplan #kikan_list td').each(function (i, val) {
                    var $obj = $(this);
                    var val = $obj.data('val');
                    if (val >= datamin && val <= datamax) {
                        $obj.addClass('selected');
                    }
                });
            }
            //目的地
            var mokutekiAry = Array();
            if (typeof reqParam['p_mokuteki'] !== "undefined") {
                mokutekiAry = reqParam['p_mokuteki'].split('-');
                $(".rootBox > #preDest_free").attr('data-code', mokutekiAry[0]);
                $('#preCountry_free').attr('data-code', mokutekiAry[1]);
                $('#preCity_free').attr('data-code', mokutekiAry[2]);
            }
            //旅行日数
            var kikanMin = kikanMax = '';
            kikanMin = reqParam['p_kikan_min'];
            kikanMax = reqParam['p_kikan_max'];
            setKikan(kikanMin, kikanMax);
            for (var i in reqParam) {
                switch (i) {
                    case 'p_hatsu':
                        var $preHatsu = $("#preHatsu");
                        $("#" + i).val(reqParam[i]);
                        $preHatsu.attr('data-code', reqParam[i]);
                        var Facet = NSearch.getFacet('p_hatsu_name', json.facet_counts.facet_fields);
                        for (hKey in Facet) {
                            if (Facet[hKey].code == reqParam[i]) {
                                $preHatsu.val(Facet[hKey].jname + '発');
                                break;
                            }
                        }
                        break;
                    case 'p_mokuteki':
                        var tmp = reqParam[i].split('-');
                        var dest = tmp[0];
                        var country = tmp[1];
                        var city = tmp[2];
                        var FacetDest = NSearch.getFacet('p_dest_name', json.facet_counts.facet_fields);
                        var FacetCountry = NSearch.getFacet('p_country_name', json.facet_counts.facet_fields, dest);
                        var FacetCity = NSearch.getFacet('p_city_cn', json.facet_counts.facet_fields, country);
                        for (dKey in FacetDest) {
                            if (FacetDest[dKey].code == dest) {
                                $('#preDest_free').val(FacetDest[dKey].jname);
                                // 方面名も設定
                                $('#preDest_free').parent().find('strong').text(FacetDest[dKey].jname);
                                break;
                            }
                        }
                        for (cnKey in FacetCountry) {
                            if (FacetCountry[cnKey].code == country) {
                                // 方面ページで検索項目あるなら もしくは 国ページで例外国なら
                                if(($("#p_category").val() == '1' && 0 < $("#p_search_country").val().length) || ( $("#p_category").val() == '2' && 0 < $("#p_except_country").val().length && $("#p_except_country").val() != 'マカオ')){
                                    $('#preCountry_free').attr('data-code', '');
                                }
                                else{
                                    $('#preCountry_free').val(FacetCountry[cnKey].jname);
                                }

                                // 国ページで国がテキストまたは都市ページ
                                if(($("#p_category").val() == '2' && $("#p_search_country").val().length < 1 && $("#p_except_country").val().length < 1) ||
                                    $("#p_except_country").val() == 'マカオ' || ($("#p_category").val() == '3')){
                                       // 単数選択はテキスト表示
                                       $('#preCountry_free').parent().find('strong').text(FacetCountry[cnKey].jname);
                                }
                                break;
                            }
                        }
                        for (ctKey in FacetCity) {
                            if (FacetCity[ctKey].code == city) {
                                $('#preCity_free').val(FacetCity[ctKey].jname);
                                break;
                            }
                        }
                        break;
                    case 'preHatsu':
                        $("#" + i).val(reqParam[i]+'発');
                        break;
                    case 'preDest_free':
                    case 'preCountry_free':
                    case 'preCity_free':
                        $("#" + i).attr('value', reqParam[i]);
                        break;
                    case 'p_carr':
                        $('.retCarr').html(reqParam['preCarr'] + '<input type="hidden" value="' + reqParam[i] + '" name="p_carr" ><input type="hidden" value="' + reqParam['preCarr'] + '" name="preCarr" >');
                        break;
                    case 'p_hotel_code':
                        $('.retHotelName').html(reqParam['preHotelName'] + '<input type="hidden" value="' + reqParam[i] + '" name="p_hotel_code" ><input type="hidden" value="' + reqParam['preHotelName'] + '" name="preHotelName" >');
                        break;
                    case 'p_seatclass':
                    case 'p_hotel_rank_code':
                    case 'p_discount':
                    case 'p_total_amount_divide':
                        var $target = $(partsName.formParentName + ' input[name="' + i + '"][value="' + reqParam[i] + '"]');
                        if ($target.length > 0) {
                            $target.prop('checked', true);
                        }
                        break;
                }
            }
            /*Number of requests check*/
            var errMes = methods.RS_reqCheck();
            if (errMes) {
                alert(errMes);
                void(0);
                return false;
            }
            var options = {
                kind: "GetList",
                p_data_kind: 3,
                p_rtn_data: "p_hatsu_name"
            }
            methods.requestProcess(options);
            var settings = {
                dataType: "json",
                success: function (json) {
                    $(".searchContents").empty();
                    $(".searchContents").append(json.html);
                    $(".srchResult span").html(addFigure(json.response.p_hit_num) );
                    //検討中リストチェック
                    //ccbc.OnLoadCheck();
                }
            }
            methods.ajaxProcess(settings);
        },
        RS_reqCheck: function (targetObj) {
            var p_mokutekiArr = [];
            var p_hotel_codeArr = [];
            if (0 < $('input[name=p_mokuteki]').size()) {
                var p_mokuteki = $('input[name=p_mokuteki]').val();
                p_mokutekiArr = p_mokuteki.split(",");
            }
            if (0 < $('input[name=p_hotel_code]').size()) {
                var p_hotel_code = $('input[name=p_hotel_code]').val();
                p_hotel_codeArr = p_hotel_code.split(",");
            }
            var errMes = '';
            if (p_mokutekiArr.length > 20) {
                errMes += '目的地は20件以内で指定して下さい。' + "\n";
            }
            if (p_hotel_codeArr.length > 50) {
                errMes += 'ホテルは50件以内で指定して下さい。' + "\n";
            }
            /*if(errMes != ''){
             errMes = errMes.replace(/^・+|・+$/g, "")+'の数が多すぎます。'+"\n"+'再度入力してください';
             }*/
            return errMes;
        },
        /*--初期セットを返す--*/
        getInitParam: function () {
            var req = {};
            req['p_sort'] = 1;
            req['p_start_line'] = 1;
            req['p_data_kind'] = 3;
            var MyNaigai = $("#MyNaigai").val();
            if (MyNaigai == 'i') {
                req['p_rtn_data'] = "p_hatsu_name,p_dest_name,p_country_name,p_city_cn,p_kikan,p_price_flg,p_conductor,p_carr_cn,p_timezone,p_seatclass,p_mainbrand,p_hotel_name,p_discount,p_bunrui,p_stock,p_decide,p_web_conclusion_flag,p_total_amount_divide";
            } else {
                req['p_rtn_data'] = "p_hatsu_name,p_dest_name,p_prefecture_name,p_region_cn,p_kikan,p_price_flg,p_conductor,p_carr,p_carr_cn,p_transport,p_mainbrand,p_bunrui,p_bus_boarding_name,p_stock,p_decide,p_hotel_name,p_hei,p_type,p_brand,p_web_conclusion_flag,p_dep_airport_name";
            }
            req['p_mokuteki_kind'] = 2;
            return req;
        },
        // //onload 検索BOX 条件表示 Hidden
        makeSearchHidden: function (req) {
            for (i in req) {
                if (typeof req[i] === "undefined") {
                    continue;
                }
                //要素があれば
                var $obj = $(partsName.formParentName + ' input[name="' + i + '"]');
                jQuery.each($obj, function (l, element) {
                    if ($(element).attr('type') == 'hidden') {
                        if ($(element).attr('name') == 'def_p_hatsu') {
                            return;
                        }
                        var tmp = req[i].split(',');
                        for (var x = 0; x < tmp.length; x++) {
                            $(element).val(tmp[x]);
                        }
                    } else if ($(element).attr('name') == 'p_dep_date') {
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
                        $(element).val(dispText);
                    } else if ($(element).attr('type') == 'text') {
                        $(element).val(req[i]);
                    }
                });
                // var slecter = $(partsName.formParentName + ' input[name="' + i + '"]')[0];
                // if (slecter && $(slecter).attr('type') == 'hidden' ) {
                // 	$(partsName.formParentName + ' input:hidden[name="' + i + '"]').val(req[i]);
                // }else if(slecter && $(slecter).attr('name') == 'p_dep_date' ){
                // 	var dispText = '';
                // 	var dateVal = String(req[i]);
                // 	if(dateVal){
                // 		if(dateVal.indexOf('/') != -1){
                // 			var DateAry = dateVal.split('/');
                // 			if(typeof(DateAry[2]) == 'undefined' || DateAry[2] == ''){
                // 				dateVal =  DateAry[0] + ( '0' + DateAry[1] ).slice(-2);
                // 			}else{
                // 				dateVal = DateAry[0] + ("0" + DateAry[1]).slice(-2) + ("0" + DateAry[2]).slice(-2);
                // 			}
                // 		}
                // 		dateVal.match(/([0-9]{4})([0-9]{2})([0-9]{2})?/);
                // 		var year = RegExp.$1
                // 		var month = RegExp.$2;
                // 		var day = RegExp.$3;
                // 		if(year){
                // 			var dispText = year + '/';
                // 				dispText += month;
                // 		}
                // 		if(day){
                // 			dispText += '/' + day;
                // 		}
                // 	}
                // 	$(partsName.formParentName + ' input[name="' + i + '"]').val(dispText);
                // }else if(slecter && $(slecter).attr('type') == 'text'){
                // 	$(partsName.formParentName + ' input:text[name="' + i + '"]').val(req[i]);
                // }
            }
        },
        //目的地生成
        makeMokutekiSelect: function () {
            var json = methods.jsonData;
            var myparam = methods.myparam;
            var no = methods.no;
            var targetName = methods.targetName;
            var parentVal = methods.parentVal;
            var targetObj = $('#' + targetName);
            targetObj.children('option:gt(0)').remove();
            for (var i in json[myparam]) {
                var m = json[myparam][i];
                if (m.facet < 1) {
                    continue;
                }
                if (m.parentKey == parentVal || m.parentKey == undefined) {
                    $opObj = $("<option/>").text(m.name).val(m.key);
                    targetObj.append($opObj);
                }
            }
        },
        //目的地クリア
        mokutekiAllClear: function (Type, no) {
            $('.rootBox:gt(0)').remove();
            $("input[name='p_mokuteki_kind']").attr("checked", false);
            $("input[name='p_mokuteki_kind']").val(["2"]);
            methods.mokutekiClear('Country,City', 0);
        },
        /*----目的地のパラメータセット---*/
        mokutekiRequestSet: function () {
            var setNum = $('.rootBox').length;
            var $target = $('#p_mokuteki');
            var tmp = mokuteliAry = [];
            var dest = country = city = '';
            for (i = 0; i < setNum; i++) {
                dest = $('#preDest_free' + i).val();
                country = $('#preCountry_free' + i).val();
                city = $('#preCity_free' + i).val();
                if (dest) {
                    tmp[i] = dest + '-' + country + '-' + city;
                }
            }
            if (tmp.length > 0) {
                var mokuteliAry = tmp.filter(function (x, i, self) {
                    return self.indexOf(x) === i;
                });
                var mokutekiVal = mokuteliAry.join(',');
                $target.val(mokutekiVal);
            } else {
                $target.val('');
            }
        },
        /*--LightBox FadeIn--*/
        rBoxFadeIn: function (html) {
            var TgHeight = $(document).height();
			var TgWidth = $(document).width();
			$(partsName.overlay).height(TgHeight).width(TgWidth).fadeIn();
			$(partsName.rBox).empty().append(html);
			var left = Math.floor((960 - $(partsName.rBox).width()) / 2);
            // 上部ヘーダー、コンテンツ部分を最後にマイナスする
			var top  = Math.floor(($(window).height() - $(partsName.rBox).height()) / 2) + $(window).scrollTop() - $(".banner").height() - 157;
			if(top < 0){
				top = 0;
			}
			$(partsName.rBox)
				.css({
					 "top": top
					,"left": left
                    ,"z-index": 1500
			}).fadeIn();
        },
        /*--変更フラグ設定--*/
        changeSet: function (myname, req) {
            var change = false;
            var new_req = new Array();
            var req_name = myname;
            var cnt = 0;
            for (i in req) {
                if (typeof(req[i]) !== "undefined") {
                    new_req[cnt] = req[i].value;
                    cnt++;
                }
            }
            var now_param = $(partsName.formParentName).serializeArray();
            var cnt = 0;
            var now_req = new Array();
            for (i in now_param) {
                if (typeof(now_param[i]) !== "undefined" && now_param[i].name == myname) {
                    now_req[cnt] = now_param[i].value;
                    cnt++;
                }
            }
            //ここから違い判定
            if (new_req.length !== now_req.length) {
                //数が違う
                change = true;
            } else {
                var findFlg = false;
                //数は同じ。パラメータの中身チェック
                for (i in new_req) {
                    for (j in now_req) {
                        if (now_req[j] == new_req[i]) {
                            //同じ
                            findFlg = true;
                        }
                    }
                    //１つでも値が異なるパラメータがあればフラグセット
                    if (findFlg == false) {
                        change = true;
                        break;
                    }
                }
            }
            return change;
        },
        /*親フォームにHTML反映*/
        htmlToParent: function (myname, req) {
            var html = p_carr_val = '';
            var dispVal = '';
            switch (myname) {
                case 'p_carr':
                    var ary = new Array();
                    //現在の値と比較
                    var change = methods.changeSet(myname, req);
                    for (i in req) {
                        if (typeof(req[i]) !== "undefined") {
                            var num = ("0" + cnt).slice(-2);
                            if (html) {
                                html += '、' + req[i]["title"];
                            } else {
                                html += req[i]["title"];
                            }
                            ary.push(req[i]["value"]);
                            cnt++;
                        }
                    }
                    if (html == '') {
                        html = '指定しない';
                    } else {
                        var str = html;
                        var val = ary.join(',');
                        html += '<input type="hidden" value="' + val + '" name="' + myname + '" >';
                        html += '<input type="hidden" value="' + str + '" name="preCarr" >';
                    }
                    $('.retCarr').empty().append(html);
                    break;
                case 'p_hotel_code':
                    var ary = new Array();
                    //現在の値と比較
                    var change = methods.changeSet(myname, req);
                    for (i in req) {
                        if (typeof(req[i]) !== "undefined") {
                            var num = ("0" + cnt).slice(-2);
                            if (html) {
                                html += '、' + req[i]["title"];
                            } else {
                                html += req[i]["title"];
                            }
                            ary.push(req[i]["value"]);
                            cnt++;
                        }
                    }
                    if (html == '') {
                        html = '指定しない';
                    } else {
                        var str = html;
                        var val = ary.join(',');
                        html += '<input type="hidden" value="' + val + '" name="' + myname + '" >';
                        html += '<input type="hidden" value="' + str + '" name="preHotelName" >';
                    }
                    $('.retHotelName').empty().append(html);
                    break;
                case 'p_dep_date':
                    var dispVal;
                    if (req.length > 0) {
                        var val = req[0].value;
                    }
                    if (val) {
                        if (val.length > 6) {
                            dispVal = val.substring(0, 4) + '/' + val.substring(4, 6) + '/' + val.substring(6, 8);
                        } else if (val.length > 3) {
                            dispVal = val.substring(0, 4) + '/' + val.substring(4, 6);
                        } else {
                            dispVal = val;
                        }
                    }
                    //現在の値と比較
                    var now_p_dep_date = $(partsName.formParentName + ' input[name="p_dep_date"]').val();
                    $(partsName.formParentName + ' input[name="' + myname + '"]').val(dispVal);
                    break;
                case 'p_hatsu_sub':
                case 'p_hatsu':
                    //現在の値と比較
                    var change = methods.changeSet(myname, req);
                    var cnt = 0;
                    for (i in req) {
                        if (typeof(req[i]) !== "undefined") {
                            var num = ("0" + cnt).slice(-2);
                            html += '<li><label for="dept' + num + '"><input id="dept' + num + '" type="checkbox" value="' + req[i]["value"] + '" name="' + myname + '" checked>' + req[i]["title"] + '</label></li>';
                            cnt++;
                        }
                    }
                    if (html == '') {
                        html = '<li>未設定</li>';
                    }
                    $(partsName.formParentName + ' .hatsuList li').remove();
                    $(partsName.formParentName + ' .hatsuList').append(html);
                    break;
                case 'p_dep_airport_code':
                    //現在の値と比較
                    var change = methods.changeSet(myname, req);
                    var cnt = 0;
                    for (i in req) {
                        if (typeof(req[i]) !== "undefined") {
                            var num = ("0" + cnt).slice(-2);
                            html += '<li><label for="airport' + num + '"><input id="airport' + num + '" type="checkbox" value="' + req[i]["value"] + '" name="' + myname + '" checked>' + req[i]["title"] + '</label></li>';
                            cnt++;
                        }
                    }
                    if (html == '') {
                        html = '<span class="notSet">未設定</span>';
                        $(partsName.formParentName + ' .Box_p_dep_airport_code').parents("dd.reDep_airportBtnSet").removeClass().addClass('reDep_airportBtn');
                    } else {
                        $(partsName.formParentName + ' .Box_p_dep_airport_code').parents("dd.reDep_airportBtn").removeClass().addClass('reDep_airportBtnSet');
                    }
                    $(partsName.formParentName + ' .reDep_airportForm ul li').remove();
                    $(partsName.formParentName + ' .reDep_airportForm ul .notSet').remove();
                    $(partsName.formParentName + ' .reDep_airportForm ul').append(html);
                    break;
                case 'p_arr_airport_code':
                    //現在の値と比較
                    var change = methods.changeSet(myname, req);
                    var cnt = 0;
                    for (i in req) {
                        if (typeof(req[i]) !== "undefined") {
                            var num = ("0" + cnt).slice(-2);
                            html += '<li><label for="airportArr' + num + '"><input id="airportArr' + num + '" type="checkbox" value="' + req[i]["value"] + '" name="' + myname + '" checked>' + req[i]["title"] + '</label></li>';
                            cnt++;
                        }
                    }
                    if (html == '') {
                        html = '<span class="notSet">未設定</span>';
                        $(partsName.formParentName + ' .Box_p_arr_airport_code').parents("dd.reArr_airportBtnSet").removeClass().addClass('reArr_airportBtn');
                    } else {
                        $(partsName.formParentName + ' .Box_p_arr_airport_code').parents("dd.reArr_airportBtn").removeClass().addClass('reArr_airportBtnSet');
                    }
                    $(partsName.formParentName + ' .reArr_airportForm ul li').remove();
                    $(partsName.formParentName + ' .reArr_airportForm ul .notSet').remove();
                    $(partsName.formParentName + ' .reArr_airportForm ul').append(html);
                    break;
                case 'p_kikan':
                    if (req.length > 0) {
                        var valmin = methods.getMin(req);
                        var valmax = methods.getMax(req);
                        if (valmin == valmax) {
                            if (valmin == 1) {
                                html += '<p>日帰り</p>';
                            } else {
                                html += '<p>' + valmin + '日間</p>';
                            }
                        } else {
                            html += '<p>' + valmin + '〜' + valmax + '日間</p>';
                        }
                        html += '<input type="hidden" id="days01" value="' + valmin + '" name="p_kikan_min">';
                        html += '<input type="hidden" id="days02" value="' + valmax + '" name="p_kikan_max">';
                    }
                    //現在の値と比較
                    var now_p_kikan_min = $(partsName.formParentName + ' input[name="p_kikan_min"]').val();
                    var now_p_kikan_max = $(partsName.formParentName + ' input[name="p_kikan_max"]').val();
                    //親へ反映
                    if (html == '') {
                        html = '<span class="notSet">未設定</span>';
                        $(partsName.formParentName + ' .Box_' + myname).parents("dd.reDaysBtnSet").removeClass().addClass('reDaysBtn');
                    } else {
                        $(partsName.formParentName + ' .Box_' + myname).parents("dd.reDaysBtn").removeClass().addClass('reDaysBtnSet');
                    }
                    $(partsName.formParentName + ' .reDaysForm').empty();
                    $(partsName.formParentName + ' .reDaysForm ul .notSet').remove();
                    $(partsName.formParentName + ' .reDaysForm').append(html);
                    break;
                case 'p_stay_number':
                    //現在の値と比較
                    var change = methods.changeSet(myname, req);
                    for (i in req) {
                        if (typeof(req[i]) !== "undefined" && req[i]["value"]) {
                            html += '<p>' + req[i]["value"] + '泊</p>';
                            html += '<input type="hidden" id="stayNnum" value="' + req[i]["value"] + '" name="p_stay_number">';
                        }
                    }
                    if (html == '') {
                        html = '<span class="notSet">未設定</span>';
                        $(partsName.formParentName + ' .Box_p_stay_number').parents("dd.reStayNumBtnSet").removeClass().addClass('reStayNumBtn');
                    } else {
                        $(partsName.formParentName + ' .Box_p_stay_number').parents("dd.reStayNumBtn").removeClass().addClass('reStayNumBtnSet');
                    }
                    $(partsName.formParentName + ' .reStayNumForm').html('');
                    $(partsName.formParentName + ' .reStayNumForm .notSet').remove();
                    $(partsName.formParentName + ' .reStayNumForm').append(html);
                    break;
                default :
                    break;
            }
        },
        // /*Get response parameter from request parameters*/
        getRespParam: function (respPara) {
            var reqPara;
            switch (respPara) {
                case 'p_carr':
                    reqPara = 'p_carr_cn';
                    break;
                case 'p_dep_airport_code':
                    reqPara = 'p_dep_airport_name';
                    break;
                case 'p_arr_airport_code':
                    reqPara = 'p_arr_airport_name';
                    break;
                case 'p_bus_boarding_code':
                    reqPara = 'p_bus_boarding_name';
                    break;
                case 'p_hotel_code':
                    reqPara = 'p_hotel_name';
                    break;
                default :
                    reqPara = respPara;
                    break;
            }
            return reqPara;
        },
        /*--Conditions save--*/
        setConditions: function () {
            var param = $(partsName.formParentName).serializeArray();
            var req = new Array();
            for (i in param) {
                if (typeof(req[param[i].name]) == "undefined") {
                    req[param[i].name] = "";
                }
                if (param[i].name == 'p_dep_date') {
                    if (param[i].value.indexOf('/') != -1) {
                        var DateAry = param[i].value.split('/');
                        if (typeof(DateAry[2]) == 'undefined' || DateAry[2] == '') {
                            param[i].value = DateAry[0] + ( '0' + DateAry[1] ).slice(-2);
                        } else {
                            param[i].value = DateAry[0] + ("0" + DateAry[1]).slice(-2) + ("0" + DateAry[2]).slice(-2);
                        }
                    }
                }
                req[param[i].name] += param[i].value + ',';
            }
            var req_obj = new Object();
            for (i in req) {
                var trimVal = req[i];
                if (typeof(trimVal) != "undefined") {
                    var tmp = trimVal.replace(/\,+$/, "");
                    if (tmp !== '') {
                        req_obj[i] = tmp;
                    }
                }
            }
            var url = location.href;
            methods._locChange('', url, req_obj);
        },
        /*--pushState--*/
        _locChange: function (id, url, params) {
            if ('pushState' in history) {
                history.pushState(params, '', url);
            } else {
                //Unsupported
            }
        },
        // /*--popState--*/
        _onLocChanged: function (e) {
            if (!e.state || (typeof(e.state.flgiFree) == undefined || e.state.flgiFree != 1) ) {
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
        },
        ajaxProcess: function () {
            return function (settings) {
                var defSetting = {
                    url: '../search/ajax_ifree.php',
                    data: '',
                    dataType: "html",
                    cache: false,
                    type: "POST",
                    error: function (XMLHttpRequest, textStatus, errorThrown) {
                        //_errorAct(XMLHttpRequest, textStatus, errorThrown);
                    },
                    success: function (json) {
                        $(".searchContents").empty();
                        $(".searchContents").append(json.html);
                    }
                }
                settings = $.extend(defSetting, settings);
                $.ajax(settings);
                return this;
            }
        },
        requestProcess: function (options) {
            var paramAct = function (valObj) {
                valAct = new Array();
                valAct['p_hatsu_sub'] = function (value) {
                    return this;
                }
                valAct['p_dep_date'] = function (value) {
                    if (value.indexOf('例') != -1) {
                        valObj.value = '';
                    }
                }
                valAct['p_bunrui'] = function (value) {
                    return this;
                }
                valAct['p_hotel_code'] = function (value) {
                    return this;
                }
                if (typeof valAct[valObj.name] == 'function') {
                    valAct[valObj.name](valObj.value);
                }
                return valObj;
            };
            var req = {};
            var param = $(this.formObj).serializeArray();
            for (i in param) {
                //パラメータ毎の個別処理
                valObj = paramAct(param[i]);
                if (typeof(req[param[i].name]) == "undefined") {
                    req[param[i].name] = "";
                }
                req[param[i].name] += param[i].value + ',';
            }
            for (i in req) {
                var trimVal = req[i];
                req[i] = trimVal.replace(/\,+$/, "");
            }
            if (typeof req['p_bunrui'] == 'undefined') {
                req['p_bunrui'] = '030';
            } else if (req['p_bunrui'].indexOf('030') == -1) {
                req['p_bunrui'] += ',030';
            }
            req = $.extend(req, options);
            return req;
        },
        getMin: function (obj) {
            var cnt = 0;
            for (i in obj) {
                var val = parseInt(obj[i].key, 10);
                if (cnt == 0) {
                    var num = val;
                } else {
                    num = (num > val) ? val : num;
                }
                cnt++;
            }
            return num;
        },
        // /*Get Maximum value*/
        getMax: function (obj) {
            var cnt = 0;
            for (i in obj) {
                var val = parseInt(obj[i].key, 10);
                if (cnt == 0) {
                    var num = val;
                } else {
                    num = (num < val) ? val : num;
                }
                cnt++;
            }
            return num;
        },
        mokutekiClear: function (Type) {
            var dest = '';
            var country = '';
            var city = '';
            var p_mokuteki = '';
            var $dest = $("#preDest_free");
            var $country = $("#preCountry_free");
            var $city = $("#preCity_free");
            var $p_mokuteki = $("#p_mokuteki");
            var TypeAry = Type.split(',');
            jQuery.each(TypeAry, function (i, str) {
                switch (str) {
                    case 'Dest':
                        $dest.attr('data-code', '').val('');
                        break;
                    case 'Country':
                        $country.attr('data-code', '').val('');
                        break;
                    case 'City':
                        $city.attr('data-code', '').val('');
                        break;
                }
            });
            var p_mokuteki = '';
            var dest = $dest.attr('data-code');
            var country = $country.attr('data-code');
            var city = $city.attr('data-code');
            if (dest || country || city) {
                p_mokuteki = dest + '-' + country + '-' + city;
            }
//            $p_mokuteki.val(p_mokuteki);
            $("#iSearchBox-freeplan #p_mokuteki").val(p_mokuteki);
        }
    };
    // methods.prototype = new NarrowSearch();
    $.fn[plugname] = function (method) {
        if (methods[method]) {
            return methods[method]
                .apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('Method ' + method + ' does not exist on jQuery.' + plugname);
            return this;
        }
    };
})(jQuery);
$(function () {
    $("input").on("keydown", function (e) {
        if ((e.which && e.which === 13) || (e.keyCode && e.keyCode === 13)) {
            return false;
        } else {
            return true;
        }
    });
    $(".eTop07Btn a").on('click', function () {
        if ($(".themeListBox li:hidden").length > 0) {
            $(".themeListBox li").show('fast', function () {
                $(".themeListBox dt").css({'height': 'auto'});
                $(".themeListBox dd.eTop07Txt").css({'height': 'auto'});
                setTimeout(function () {
                    $(".themeListBox dt").autoHeight({column: 4});
                    $(".themeListBox dd.eTop07Txt").autoHeight({column: 4});
                }, 1000);
            });
            $(".eTop07Btn a img").attr("src", "/attending/freeplan-i/images/2015/tokushuBtn_m.png");
        } else {
            $(".themeListBox li:gt(11)").hide('fast');
            var offset = $(".themeListBox").offset();
            $('html,body').animate({scrollTop: offset.top - 80}, 'fast');
            $(".eTop07Btn a img").attr("src", "/attending/freeplan-i/images/2015/tokushuBtn_p.png");
        }
    });
    $(".newTourbtn a").on('click', function () {
        if ($(".newTour li:hidden").length > 0) {
            $(".newTour li").show('fast');
            $(this).text("閉じる -");
        } else {
            $(".newTour li:gt(1)").hide('fast');
            $(this).text("もっと見る +");
        }
    });
});
/*============================================
 //inputのenterを無効にする
 ============================================*/
$(function () {
    $("input").on("keydown", function (e) {
        if ((e.which && e.which === 13) || (e.keyCode && e.keyCode === 13)) {
            return false;
        } else {
            return true;
        }
    });
});
/*============================================
 //マップ
 ============================================*/
//$(function() {
//
//	var NSearch = new NarrowSearch;
//
//	partsName = {
//		'btn_p_hatsu' : ".Box_p_hatsu",
//		'btn_p_mokuteki' 	: "Box_p_mokuteki",
//		'btn_p_carr' 		: ".Box_p_carr",
//		'btn_p_hotel_code' 	: ".Box_p_hotel_code",
//		'formParentName':".searchTour",
//		'formsubWinName':".subWinForm",
//		'overlay' : "#overlay",
//		'rBox' : "#rBox",
//		'subKyoten' : "input:checkbox[name^='p_hatsu']",
//		'formObj' : "#iSearchBox-freeplan"
//	};
//
//	NSearch.init(partsName);
//
//
//	$(document).on('click','.popularWrapper a',function() {
//		var mokutekiParam = '';
//		mokutekiParam = $(this).attr('id');
//		var p_hatsuVal = $("#def_p_hatsu").val();
//		if(mokutekiParam != '' && mokutekiParam != undefined){
//			$('<form/>', {action: '/search/ifree.php', method: 'post'})
//			.append($('<input/>', {type: 'hidden', name: 'MyNaigai', value: 'i'}))
//		  .append($('<input/>', {type: 'hidden', name: 'p_mokuteki', value: mokutekiParam}))
//			.append($('<input/>', {type: 'hidden', name: 'flgiFree', value: '1'}))
//			.append($('<input/>', {type: 'hidden', name: 'p_bunrui', value: '030'}))
//			.append($('<input/>', {type: 'hidden', name: 'p_hatsu', value:p_hatsuVal}))
//		  .appendTo(document.body)
//		  .submit();
//		}
//	});
//
//	$(document).on('click','.eMapComm a',function() {
//		var mokutekiParam = '';
//		mokutekiParam = $(this).attr('id');
//		var p_hatsuVal = $("#def_p_hatsu").val();
//		if(mokutekiParam != '' && mokutekiParam != undefined){
//			$('<form/>', {action: '/search/ifree.php', method: 'post'})
//			.append($('<input/>', {type: 'hidden', name: 'MyNaigai', value: 'i'}))
//		  .append($('<input/>', {type: 'hidden', name: 'p_mokuteki', value: mokutekiParam}))
//			.append($('<input/>', {type: 'hidden', name: 'flgiFree', value: '1'}))
//			.append($('<input/>', {type: 'hidden', name: 'p_bunrui', value: '030'}))
//			.append($('<input/>', {type: 'hidden', name: 'p_hatsu', value:p_hatsuVal}))
//		  .appendTo(document.body)
//		  .submit();
//		}
//	});
//
//	/*シーズンカレンダー閉じる*/
//	$("#overlay,.eMapClose a").click(function(){
//		$("#overlay").css({'display':'none'});
//		$("#rBox").css({'display':'none'});
//		$("#eMap01").css({'display':'none'});
//		$("#eMap02").css({'display':'none'});
//		$("#eMap03").css({'display':'none'});
//		$("#eMap04").css({'display':'none'});
//		$("#eMap05").css({'display':'none'});
//		$("#eMap06").css({'display':'none'});
//		$("#SubWinBox-Fp").css({'display':'none'});
//	});
//
//
//	  $('#eMap01').find('a').each(function(){
//	    var strDef = $(this).html()　+ '<span style="font-size:10px;color:#ffffff"></span>';
//	    $(this).html(strDef);
//	  });
//	  $('#eMap02').find('a').each(function(){
//	    var strDef = $(this).html()　+ '<span style="font-size:10px;color:#ffffff"></span>';
//	    $(this).html(strDef);
//	  });
//	  $('#eMap03').find('a').each(function(){
//	    var strDef = $(this).html()　+ '<span style="font-size:10px;color:#ffffff"></span>';
//	    $(this).html(strDef);
//	  });
//	  $('#eMap04').find('a').each(function(){
//	    var strDef = $(this).html()　+ '<span style="font-size:10px;color:#ffffff"></span>';
//	    $(this).html(strDef);
//	  });
//	  $('#eMap05').find('a').each(function(){
//	    var strDef = $(this).html()　+ '<span style="font-size:10px;color:#ffffff"></span>';
//	    $(this).html(strDef);
//	  });
//	  $('#eMap06').find('a').each(function(){
//	    var strDef = $(this).html()　+ '<span style="font-size:10px;color:#ffffff"></span>';
//	    $(this).html(strDef);
//	  });
//
//
//	$('.map a').each(function(){
//		$(this).on('click',function(){
//			var mapName = $(this).parent('li').attr('class');
//			var tgName = mapName.replace('mapBtn','eMap');
//
//			var docw = $(document).width();
//			var doch = $(document).height();
//			$("#overlay").css({'width':docw,'height':doch, 'display':'block'});
//			//BOXの位置
//			var left = Math.floor(($(window).width() - $("#"+tgName).width()) / 2);
//			var top  = Math.floor(($(window).height() - $("#"+tgName).height()) / 2) + $(window).scrollTop();
//			    if(top < 0){
//			        top = 0;
//			    }
//			$("#"+tgName+" a").fadeOut('fast');
//			$("#"+tgName).css({"top": top,"left": left}).fadeIn(800,function(){
//				$(this).find('a').each(function(){
//					var $that = $(this);
//
//					var mokutekiParam = $that.attr('id');
//					var p_hatsuVal = $("#def_p_hatsu").val();
//					if(mokutekiParam != '' && mokutekiParam != undefined){
//						var options = {
//							kind:'Detail',
//							p_data_kind:3,
//							p_rtn_data:'p_conductor',
//							p_bunrui:'030',
//							p_data_kind:'3',
//							p_mokuteki_kind:'2',
//							p_sort:'1',
//							p_start_line:'1',
//							p_hatsu:p_hatsuVal
//						}
//						//var dataVal = NSearch.requestProcess(options);
//						var mokutekiVal = {'p_mokuteki':mokutekiParam}
//						dataVal = $.extend(options,mokutekiVal);
//
//						var settings = {
//							data:dataVal,
//							dataType: "json",
//							success: function(json){
//								if(json.p_hit_num == 0){
//									$that.find('span').parents('li').hide();
//								}else{
//									$that.find('span').empty();
//									$that.find('span').html('（' + json.p_hit_num + '）');
//								}
//							},
//							complete: function(){
//							}
//						}
//						NSearch.ajaxProcess(settings);
//					}
//				});
//				setTimeout(function(){
//					$("#"+tgName+" a").fadeIn('fast')
//				},1100)
//			});
//
//		});
//	});
//
//});
//--------------------
//	前へ次へ（出発日）
//----------------------
function NextBackBtnAction(DepDate) {
    partsName = {
        'formParentName': ".searchTour"
        , 'formsubWinName': ".subWinForm"
        , 'overlay': "#overlay"
        , 'rBox': "#rBox"
        , 'formObj': "#iSearchBox-freeplan"
    };
    var BaseName = '/attending/senmon_kaigai/sharing/phpsc/ajax_calendar.php';
    var options = {
        url: BaseName
        , dataType: "script"
        , SetParam: 'p_dep_date'
        , RetParam: 1
        , MyNaigai: 'i'
        , ViewMonth: DepDate
    }
    var NSearch = new NarrowSearch;
    NSearch.init(partsName);
    var dataVal = NSearch.requestProcess(options);
    var settings = {
        url: BaseName
        , dataType: "script"
        , SetParam: 'p_dep_date'
        , RetParam: 1
        , MyNaigai: 'i'
        , ViewMonth: DepDate
        , data: dataVal
    }
    NSearch.ajaxProcess(settings);
}
//--------------------
//	日付を押したとき（出発日）
//----------------------
function SWDateFp(SetVal, obj) {
    partsName = {
        'formParentName': ".searchTour"
        , 'formsubWinName': ".subWinForm"
        , 'overlay': "#overlay"
        , 'rBox': "#rBox"
        , 'formObj': "#iSearchBox-freeplan"
    };
    var options = {
        kind: 'Pre',
        p_data_kind: 1,
        p_rtn_data: 'p_conductor'
    }
    $(obj).parents('td').attr('class', 'sel');
    var DepDateSel = '#iSearchBox-freeplan input[name="p_dep_date"]';
    $(DepDateSel).val(SetVal).removeClass('NS_Watermark');//セットして
    $('#SubWinBox-Fp').fadeOut('fast');
    //件数のみ取得
    var NSearch = new NarrowSearch;
    NSearch.init(partsName);
    var dataVal = NSearch.requestProcess(options);
    var settings = {
        data: dataVal,
        dataType: "json",
        success: function (json) {
            $(".srchResult span").html(addFigure(json.p_hit_num));
        }
    }
    NSearch.ajaxProcess(settings);
}
//オープンウィンドウ^¥n
function openRequestW(url, name) {

    window.open(url, name, "resizable=yes,scrollbars=yes,status=yes");
}
