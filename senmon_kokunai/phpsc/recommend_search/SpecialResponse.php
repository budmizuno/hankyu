<?php

/**
 * SpecialResponse: 特集I/Fのレスポンスクラス
 * @since 2015/01/15
 * @version 1.0
 */
class SpecialResponse {
	private $response;

	public function __construct($response) {
		$this->response = $response;
	}

	public function createTours() {
		$tours = array();
		foreach ($this->getDomesticSpecialResponse() as $row) {
			if ($row->p_id !== '') {
				$tours[$row->p_id] = $row;
			}
		}
		foreach ($this->getAbroadSpecialResponse() as $row) {
			if ($row->p_id !== '') {
				$tours[$row->p_id] = $row;
			}
		}
		return $tours;
	}

	public function getResponse() {
		return $this->response;
	}

	public static function getDestinationInfo($tour) {
		$domestic = self::getDomesticDestinationInfo($tour);
		if (count($domestic) > 0) {
			return $domestic;
		}
		$abroad = self::getAbroadDestinationInfo($tour);
		if (count($abroad) > 0) {
			return $abroad;
		}
		return array();
	}
	
	private static function getDomesticDestinationInfo($tour) {
		if (!property_exists($tour, 'p_dome_destinarion_info')) {
			return null;
		}
		if (is_array($tour->p_dome_destinarion_info)) {
			return $tour->p_dome_destinarion_info;
		} else {
			return array($tour->p_dome_destinarion_info);
		}
	}
	
	private static function getAbroadDestinationInfo($tour) {
		if (!property_exists($tour, 'p_ab_destinarion_info')) {
			return null;
		}
		if (is_array($tour->p_ab_destinarion_info)) {
			return $tour->p_ab_destinarion_info;
		} else {
			return array($tour->p_ab_destinarion_info);
		}
	}
	
	private function getDomesticSpecialResponse() {
		if (is_array($this->response->p_dome_special_response)) {
			return $this->response->p_dome_special_response;
		} else {
			return array($this->response->p_dome_special_response);
		}
	}

	private function getAbroadSpecialResponse() {
		if (is_array($this->response->p_ab_special_response)) {
			return $this->response->p_ab_special_response;
		} else {
			return array($this->response->p_ab_special_response);
		}
	}

}
