<?php
/*
#################################################################
	スマホ検索で使用する目的地名
#################################################################
*/

$def_dest_name = '';
$def_country_name = '';
$def_city_name = '';

getMasterCsvMokutekiSmp();

function getMasterCsvMokutekiSmp()
{
    global $masterCsv,$getCsvItem,$categoryType,$def_dest_name,$def_country_name,$def_city_name;

    // 方面ページなら
    if($categoryType == CATEGORY_TYPE_DEST){
        $def_dest_name = $masterCsv[KEY_MASTER_CSV_NAME_JA];

    // 国ページなら
    }elseif ($categoryType == CATEGORY_TYPE_COUNTRY) {

        // 方面のディレクトリ名を取得
        $homenDir = $masterCsv[KEY_MASTER_CSV_HOMEN];
        // 末尾の/を削除して先頭に/を加える
        $homenDir = '/'. rtrim($homenDir, '/');

        $array = array();
        if (is_array($getCsvItem->masterCsvAllData) && count($getCsvItem->masterCsvAllData) > 0) {
            foreach ($getCsvItem->masterCsvAllData as $value) {
                if($homenDir == $value[KEY_MASTER_CSV_DIRNAME])
                {
                    $array = $value;
                    break;
                }
            }
        }

        $def_dest_name = $array[KEY_MASTER_CSV_NAME_JA];
        $def_country_name = $masterCsv[KEY_MASTER_CSV_NAME_JA];

    }else{ // 都市ページなら

        // 国を取得
        $countryDir = preg_replace('/\/([\w]+?)$/','',$masterCsv[KEY_MASTER_CSV_DIRNAME]);

        $array = array();
        if (is_array($getCsvItem->masterCsvAllData) && count($getCsvItem->masterCsvAllData) > 0) {
            foreach ($getCsvItem->masterCsvAllData as $value) {
                if($countryDir == $value[KEY_MASTER_CSV_DIRNAME])
                {
                    $array = $value;
                    break;
                }
            }
        }

        $def_country_name = $array[KEY_MASTER_CSV_NAME_JA];
        $def_city_name = $masterCsv[KEY_MASTER_CSV_NAME_JA];

    }

}
