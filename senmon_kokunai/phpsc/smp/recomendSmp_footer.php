<?php
class recomendSmp_footer{
	function __construct() {


   	$customerId= "";
	if($_COOKIE['LCNU']){
		if(!empty($_COOKIE['LCNU'])){
			$customerId = $_COOKIE['LCNU'];
			$customerId = base64_decode($customerId);
        }
    }

    if(!empty($customerId)){
    	$this->remindSet($customerId);
    }
    else{
 		$this->historySet();
    }


	}
    function historySet(){
    	global $PathRelativeMyDir,$PathSharing,$cookie_dome_history,$cookie_ab_history;

   		include_once($_SERVER['DOCUMENT_ROOT'] . '/sharing/phpsc/solr_access.php');
		include_once($_SERVER['DOCUMENT_ROOT'] . '/sharing/phpsc/class_detail_history.php');

        $this->detailHistory = new detailHistory('all');

        $allcount = count($this->detailHistory->historyAry);

        if($allcount> 20){
       	$allcount=20;
        }
        echo '<span>'.$allcount.'</span>件';
    }

    function remindSet($id){

 		echo <<<EOD
            <script language="JavaScript" src="/sharing/common16/js/ppz_sp.js"></script>
            <script language="JavaScript" src="/sharing/common16/js/ppz_draw_smp.js"></script>
            <div id="ppz_recommend_cnt_remind01"></div>
            <script type="text/javascript">
            var ppz_cnt_remind01 = new _PPZ();
            ppz_cnt_remind01.cid = 21203;
            ppz_cnt_remind01.rid = 3;
            ppz_cnt_remind01.customer_id = '{$id}'; //顧客IDを代入
            ppz_cnt_remind01.rows = 20; //表示したいMAX件数を設定(最大20件)
            ppz_cnt_remind01.cb = 'ppz_cnt_remind01_personal'; //ここの関数でレコメンド表示を作成してください。(ppz_draw.js)
            ppz_cnt_remind01.div_id = 'ppz_recommend_cnt_remind01'; //レコメンド表示を行うタグのIDを指定(divのIDと一致させます)
            ppz_cnt_remind01.alt_html = '';
            ppz_cnt_remind01.request();
            </script>
EOD;
    }

}
