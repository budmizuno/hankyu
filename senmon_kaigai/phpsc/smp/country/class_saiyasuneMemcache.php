<?php
/*
########################################################################
#
#	memcacheを使って、情報を保存するclass群です。
#
########################################################################
*/

$saiyasuneKey = 'saiyasune_';



class setSaiyasuneTourMemcache{

	#=======================
	#	コンストラクタ
	#=======================
	function __construct($obj) {
		global $saiyasuneKey,$p_hatsu,$mokuteki;



		//p_hatsuとmokutekiがキーなのでなかったらreturn
		if(empty($p_hatsu) || empty($mokuteki)){
			return false;
		}

		$memcache = new Memcache;
		//memを使える場合のみ処理
		if(@$memcache->connect('localhost', 11211)){

			$this->Action($memcache,$obj);
		}
		//使えなかったらサヨナラ
		else{

			return;
		}

		//閉じて終わり
		$memcache->close();
	}

	function Action($memcache,$obj){
		global $saiyasuneKey,$p_hatsu,$mokuteki;

		//保存するのは3時間
		$setTime = 60 * 60 * 3;

		$memcache->set($saiyasuneKey.$p_hatsu.'_'.$mokuteki, $obj, false, $setTime);
	}

}


class getSaiyasuneTourMemcache{

	#=======================
	#	コンストラクタ
	#=======================
	function __construct() {
		global $saiyasuneKey,$p_hatsu,$mokuteki;

		$Data;

		//p_hatsuとmokutekiがキーなのでなかったらreturn
		if(empty($p_hatsu) || empty($mokuteki)){
			return false;
		}

		$memcache = new Memcache;
		//memを使える場合のみ処理
		if(@$memcache->connect('localhost', 11211)){

			$Data = $this->Action($memcache);
		}
		//使えなかったらサヨナラ
		else{

			return false;
		}

		//閉じて終わり
		$memcache->close();

		return $Data;
	}

	function Action($memcache){
		global $saiyasuneKey,$p_hatsu,$mokuteki;

		return $memcache->get($saiyasuneKey.$p_hatsu.'_'.$mokuteki);

	}

}

class deleteSaiyasuneTourMemcache{
	#=======================
	#	コンストラクタ
	#=======================
	function __construct() {
		global $saiyasuneKey,$p_hatsu,$mokuteki;

		//p_hatsuとmokutekiがキーなのでなかったらreturn
		if(empty($p_hatsu) || empty($mokuteki)){
			return false;
		}

		$memcache = new Memcache;
		//memを使える場合のみ処理
		if(@$memcache->connect('localhost', 11211)){

			$this->Action($memcache);
		}
		//使えなかったらサヨナラ
		else{

			return false;
		}

		//閉じて終わり
		$memcache->close();
	}

	function Action($memcache){
		global $saiyasuneKey,$p_hatsu,$mokuteki;

		$memcache->delete($saiyasuneKey.$p_hatsu.'_'.$mokuteki);

	}

}

?>
