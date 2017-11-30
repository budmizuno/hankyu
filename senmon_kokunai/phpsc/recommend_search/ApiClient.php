<?php

require_once 'SpecialRequest.php';
require_once 'SpecialSearch.php';

class ApiClient {
	
	private $specialSearch;
	private $tour_url_list;
	
	public function __construct($tour_url_list, $wsdl) {
		$this->specialSearch = new SpecialSearch($wsdl);
		$this->tour_url_list = $tour_url_list;
	}
	
	public function request() {
		$parameter = $this->specialSearch->getSpecialSearchParameter();
		foreach ($this->tour_url_list as $id => $tour_url) {
			preg_match('/\/([a-zA-Z0-9_-]*).php/i', $tour_url, $matches);
			if (empty($matches[1])) {
				continue;
			}
			//list ($path, $query) = explode('?', $tour_url);
			$_url_data  = explode('?', $tour_url);
			
			if (count($_url_data) < 2) {
				continue;
			}
			$path = $_url_data[0];
			$query = $_url_data[1];
			$pathData = explode('/', $path);
			$progremName = $pathData[count($pathData)-1];
			
			$divide = 1;
			if (strpos($progremName, 'detail_', 0) === 0){
				$divide = 0;
			} else if (strpos($progremName, 'search_', 0) === 0){
				$divide = 1;
			}
			
			
			$naigai = $this->detectNaigai($matches[1]);
			$pHei = null;
			$pCourseId = null;
			parse_str($query, $parsedQuery);
			$parsedQuery['p_naigai'] = $naigai;
			$builtQuery = array();
			foreach ($parsedQuery as $key => $value) {
				$builtQuery[] = $key.'='.$value;
				if ($key === 'p_course_id') {
					$pCourseId = $value;
				} else if ($key === 'p_hei') {
					$pHei = $value;
				}
			}
			$parameter->append(SpecialRequest::create()
				->setId($id)
				->setDivide($divide)
				->setHei($pHei)
				->setCourseId($pCourseId)
				->setNaigai($naigai)
				->setParameter(implode('&', $builtQuery))
				->setDestinationRequest(1)
			);
		}
		$response = $this->specialSearch->search();
		return $response->createTours();
	}
	
	private function detectNaigai($text) {
		return preg_match('/^[a-z]*_i$/i', $text) ? 'W' : 'J';
	}
}
