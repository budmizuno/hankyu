<?php
/*
########################################################################
# /freeplan-i/ 出発日カレンダ用
########################################################################

/*---------さわるな危険（ここから）---------*/

//正しいheaderを出力しないと、IEでエラーになる
header('Content-Type: text/html; charset=UTF-8');

include_once($_SERVER['DOCUMENT_ROOT'] . "/sharing/phpsc/path.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/attending/senmon_kaigai/sharing/phpsc/class_calendar.php");

/*--------------------
	上の部分だけを動かす
----------------------*/
if (!empty($_REQUEST['SetParam'])) {
    new AjaxSearchActionForSearchBox;
}
?>