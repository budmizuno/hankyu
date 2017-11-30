<?php
/*
#################################################################
トップニュース（or専門店ニュースor専門店PRor専門店お知らせ)
#################################################################
*/

switch ($masterCsv[KEY_MASTER_CSV_FIRST_LEVEL]) {
    case 'china/':
        if(file_exists($_SERVER['DOCUMENT_ROOT'].'/attending/china/inc/Temp_china.php')){
            include_once($_SERVER['DOCUMENT_ROOT'].'/attending/china/inc/Temp_china.php'); // 日中国交正常化45周年
        }
        break;
    default:
        break;
}
