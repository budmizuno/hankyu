<?php

require_once 'SpecialSearchParameter.php';
require_once 'SpecialResponse.php';

/**
 * SpecialSearch: 特集I/F検索用クラス
 * @since 2015/01/15
 * @version 1.0
 */
class SpecialSearch {
	private static $initialized = false;
	private $specialSearchParameter;
	private $client;
	
	public function __construct($wsdl = '../wsdl/WSSearchSpecialService_was.wsdl') {
		self::initialize();
		$this->specialSearchParameter = new SpecialSearchParameter();
		$this->client = new SoapClient($wsdl);
	}
	
	public function getSpecialSearchParameter() {
		return $this->specialSearchParameter;
	}
	
	public function search() {
		try {
			$response = $this->client->searchSpecial($this->specialSearchParameter->getSearchParameter());
			return new SpecialResponse($response->return);
		} catch (Exception $e) {
			error_log('SpecialSearch::search() failed with the following reason; '.$e->getMessage());
			return new SpecialResponse(null);
		}
	}
	
	private static function initialize() {
		if (!self::$initialized) {
			ini_set('soap.wsdl_cache_enabled', '0');
			ini_set('soap.wsdl_cache_ttl', '0');
			self::$initialized = true;
		}
	}
}
