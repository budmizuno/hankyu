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

	private $p_hatus_key;
	private $mokuteki_key;
	private $type_key;

	#=======================
	#	コンストラクタ
	#=======================
	function __construct($obj,$saiyasune_type) {
		global $saiyasuneKey,$p_hatsu,$mokuteki;

		// 発地があるなら
		if(!empty($p_hatsu))
		{
			$this->p_hatus_key = $p_hatsu;
		}
		// indexが含まれているなら
		else if(strpos($_SERVER['SCRIPT_NAME'],'index') !== false)
		{
			// BOTなら
			$this->p_hatus_key = 'index';
		}
		$this->mokuteki_key = $mokuteki;
		$this->type_key = $saiyasune_type;


		//p_hatsuとmokutekiがキーなのでなかったらreturn
		if(empty($this->p_hatus_key) || empty($this->mokuteki_key)){
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
		global $saiyasuneKey;

		//保存するのは3時間
		$setTime = 60 * 60 * 3;

		$memcache->set($saiyasuneKey.$this->p_hatus_key.'_'.$this->mokuteki_key.'_'.$this->type_key, $obj, false, $setTime);
	}

}


class getSaiyasuneTourMemcache{

	private $data;
	private $p_hatus_key;
	private $mokuteki_key;
	private $type_key;

	#=======================
	#	コンストラクタ
	#=======================
	function __construct($saiyasune_type) {
		global $saiyasuneKey,$p_hatsu,$mokuteki;

		// 発地があるなら
		if(!empty($p_hatsu))
		{
			$this->p_hatus_key = $p_hatsu;
		}
		// indexが含まれているなら
		else if(strpos($_SERVER['SCRIPT_NAME'],'index') !== false)
		{
			// BOTなら
			$this->p_hatus_key = 'index';
		}
		$this->mokuteki_key = $mokuteki;
		$this->type_key = $saiyasune_type;


		//p_hatsuとmokutekiがキーなのでなかったらreturn
		if(empty($this->p_hatus_key) || empty($this->mokuteki_key)){
			return false;
		}

		$memcache = new Memcache;
		//memを使える場合のみ処理
		if(@$memcache->connect('localhost', 11211)){

			$this->data = $this->Action($memcache);
		}
		//使えなかったらサヨナラ
		else{

			return false;
		}

		//閉じて終わり
		$memcache->close();

	}

	function getMemCacheData(){

		return $this->data;

	}

	function Action($memcache){
		global $saiyasuneKey;

		return $memcache->get($saiyasuneKey.$this->p_hatus_key.'_'.$this->mokuteki_key.'_'.$this->type_key);

	}

}

class deleteSaiyasuneTourMemcache{

	private $p_hatus_key;
	private $mokuteki_key;
	private $type_key;

	#=======================
	#	コンストラクタ
	#=======================
	function __construct($saiyasune_type) {
		global $saiyasuneKey,$p_hatsu,$mokuteki;

		// 発地があるなら
		if(!empty($p_hatsu))
		{
			$this->p_hatus_key = $p_hatsu;
		}
		// indexが含まれているなら
		else if(strpos($_SERVER['SCRIPT_NAME'],'index') !== false)
		{
			// BOTなら
			$this->p_hatus_key = 'index';
		}
		$this->mokuteki_key = $mokuteki;
		$this->type_key = $saiyasune_type;


		//p_hatsuとmokutekiがキーなのでなかったらreturn
		if(empty($this->p_hatus_key) || empty($this->mokuteki_key)){
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
		global $saiyasuneKey;

		$memcache->delete($saiyasuneKey.$this->p_hatus_key.'_'.$this->mokuteki_key.'_'.$this->type_key);

	}

}

?>
