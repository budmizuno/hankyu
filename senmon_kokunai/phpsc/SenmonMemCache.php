<?php
/**
 * APIから取得したファセットデータなどを
 * メモキャッシュに保存したり、メモキャッシュから取得するクラス
 *
 */
class SenmonMemCache{

	private $memCache = null;

	// メモキャッシュのポート番号
	private $portNumber = 11211;

	private $TYPE_MASTER_CSV  = 1;
	private $TYPE_HATSU_FACET = 2;
	private $TYPE_MAP_FASET   = 3;
	private $TYPE_POPULAR_DATA = 4;
	private $TYPE_GUIDE_CSV	  = 5;

	// データを保存する際のキー。ファセット
	private $DataKey = array(
	    1 => 'mem_senmon_master_csv_kokunai',
	    2 => 'mem_senmon_hatsu_faset_',
	    3 => 'mem_senmon_map_faset_',
		4 => 'mem_senmon_popular_data',
		5 => 'mem_senmon_guide_csv_kaigai',
	);
	// 各種データの保存期間(秒)
	private $SaveTime = array(
	    1 => 3600, // 60(秒) * 60(分)
	    2 => 3600, // 60(秒) * 60(分)
	    3 => 1800, // 60(秒) * 30(分)
		4 => 3600, // 60(秒) * 60(分)
		5 => 3600, // 60(秒) * 60(分)
	);

	/**
	 * メモキャッシュに接続
	 * @return boolean
	 */
	private function connectMemcache()
	{
		// メモキャッシュのクラスが存在しないなら。つまりメモキャッシュのモジュールがサーバーにインストールされていないなら
		if (!array_search('Memcache', get_declared_classes()))
		{
			return false;
		}

		$this->memCache = new Memcache();

		// メモキャッシュに接続できないなら
		if( !$this->memCache->connect('localhost', $this->portNumber) )
		{
			return false;
		}

		return true;
	}

	public function setMasterCsvData($param_seri, $data)
	{
	    $this->setData($this->DataKey[$this->TYPE_MASTER_CSV].$param_seri , $data, $this->SaveTime[$this->TYPE_MASTER_CSV]);
	}

	public function getMasterCsvData($param_seri)
	{
	    return $this->getData($this->DataKey[$this->TYPE_MASTER_CSV].$param_seri);
	}

	public function setHatsuFacetData($param_seri, $data)
	{
	    $key = $this->DataKey[$this->TYPE_HATSU_FACET] . $param_seri;
	    $this->setData($key, $data, $this->SaveTime[$this->TYPE_HATSU_FACET]);
	}

	public function getHatsuFacetData($param_seri)
	{
	    $key = $this->DataKey[$this->TYPE_HATSU_FACET] . $param_seri;
	    return $this->getData($key);
	}

	public function setMapFacetData($param_seri, $data)
	{
	    $key = $this->DataKey[$this->TYPE_MAP_FASET] . $param_seri;
	    $this->setData($key, $data, $this->SaveTime[$this->TYPE_MAP_FASET]);
	}

	public function getMapFacetData($param_seri)
	{
	    $key = $this->DataKey[$this->TYPE_MAP_FASET] . $param_seri;
	    return $this->getData($key);
	}
	public function setPopularData($param_seri, $data)
	{
		$key = $this->DataKey[$this->TYPE_POPULAR_DATA] . $param_seri;
		$this->setData($key, $data, $this->SaveTime[$this->TYPE_POPULAR_DATA]);
	}
	public function getPopularData($param_seri)
	{
		$key = $this->DataKey[$this->TYPE_POPULAR_DATA] . $param_seri;
		return $this->getData($key);
	}
	public function setGuideCsvData($param_seri, $data)
	{
		$key = $this->DataKey[$this->TYPE_GUIDE_CSV] . $param_seri;
		$this->setData($key, $data, $this->SaveTime[$this->TYPE_GUIDE_CSV]);
	}
	public function getGuideCsvData($param_seri)
	{
		$key = $this->DataKey[$this->TYPE_GUIDE_CSV] . $param_seri;
		return $this->getData($key);
	}
	/**
	 * メモキャッシュにファセット保存
	 * @param Object $facetData
	 */
	public function setData($key, $data, $save_time)
	{
		// メモキャッシュに接続できないなら
		if( !$this->connectMemcache())
		{
			return;
		}

		// メモキャッシュに保存。既に同じキーが存在する場合は上書き
		$this->memCache->set($key, $data, false, $save_time);

		// メモキャッシュを閉じる
		$this->memCache->close();
	}

	/**
	 * メモキャッシュからデータ取得
	 * @return Object $data
	 */
	public function getData($key)
	{
		$data;

		// メモキャッシュに接続できないなら
		if( !$this->connectMemcache() )
		{
			return $data;
		}

		// メモキャッシュから取得
		$data = $this->memCache->get($key);

		// メモキャッシュを閉じる
		$this->memCache->close();

		return $data;
	}

}
