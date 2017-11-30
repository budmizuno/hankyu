<?php

require_once 'SpecialRequest.php';

/**
 * SpecialSearchで使用するパラメータ保持クラス
 * @since 2015/01/15
 * @version 1.0
 */
class SpecialSearchParameter {
	private $parameter = array();
	
	public function __construct() {
		$this->parameter = array();
	}
	
	public function append(SpecialRequest $specialRequest) {
		$this->parameter[] = $specialRequest->build();
	}
	
	public function getSearchParameter() {
		return array(
			'requestDto' => array(
				'p_special_request' => array($this->parameter),
			),
		);
	}
}
