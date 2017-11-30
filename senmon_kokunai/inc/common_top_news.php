<?php
/*
#################################################################
トップニュース（or専門店ニュースor専門店PRor専門店お知らせ)
#################################################################
*/

switch ($masterCsv[KEY_MASTER_CSV_FIRST_LEVEL]) {
    case 'tohoku/':
        if(file_exists($AttendingPath.'/inc/Temp_tohoku.php')){
            include_once($AttendingPath.'/inc/Temp_tohoku.php'); // プレゼントキャンペーン
        }
        break;
    case 'aomori/':
    case 'iwate/':
    case 'miyagi/':
    case 'akita/':
    case 'yamagata/':
    case 'fukushima/':
        if(file_exists($_SERVER['DOCUMENT_ROOT'] .'/attending/tohoku/inc/Temp_tohoku.php')){
            include_once($_SERVER['DOCUMENT_ROOT'] .'/attending/tohoku/inc/Temp_tohoku.php'); // プレゼントキャンペーン
        }
        break;
    default:
        break;
}
