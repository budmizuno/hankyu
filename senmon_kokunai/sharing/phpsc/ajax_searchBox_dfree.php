<?php
/*
########################################################################
#
#	solrにアクセスして、必要な結果を返します。
#
#	専門店からは、ここにアクセスします
#
########################################################################

/*---------さわるな危険（ここから）---------*/
//正しいheaderを出力しないと、IEでエラーになる
header('Content-Type: text/html; charset=UTF-8');

/*---------さわるな危険（ここまで）---------*/


/*========================================
	class群をインクルードします
==========================================*/
include_once($_SERVER['DOCUMENT_ROOT'] ."/sharing/phpsc/path.php");
include_once('class_searchBox_dfree.php');


/*--------------------
	上の部分だけを動かす
----------------------*/
if(!empty($_REQUEST['SetParam'])){
	new AjaxSearchActionForSearchBoxDfree;
}
///*--------------------
//	下の部分を作る場合
//----------------------*/
//else{
//	new AjaxSearchActionForSearchBox;
//}

?>
