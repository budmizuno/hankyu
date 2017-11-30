<?php

/**
 * 特集I/Fのリクエストクラス
 * @since 2015/01/15
 * @version 1.0
 */
class SpecialRequest {
	private $id;
	private $divide;
	private $hei;
	private $courseId;
	private $parameter;
	private $naigai;
	private $departureRequest;
	private $destinationRequest;
	
	public function __construct() {
		$this->id = null;
		$this->divide = null;
		$this->hei = null;
		$this->courseId = null;
		$this->parameter = null;
		$this->naigai = null;
		$this->departureRequest = null;
		$this->destinationRequest = null;
	}
	
	public function getId() {
		return $this->id;
	}
	
	public function setId($id) {
		$this->id = $id;
		return $this;
	}
	
	public function getDivide() {
		return $this->divide;
	}
	
	public function setDivide($divide) {
		$this->divide = $divide;
		return $this;
	}
	
	public function getHei() {
		return $this->hei;
	}
	
	public function setHei($hei) {
		$this->hei = $hei;
		return $this;
	}
	
	public function getCourseId() {
		return $this->courseId;
	}
	
	public function setCourseId($courseId) {
		$this->courseId = $courseId;
		return $this;
	}
	
	public function getParameter() {
		return $this->parameter;
	}
	
	public function setParameter($parameter) {
		$this->parameter = $parameter;
		return $this;
	}
	
	public function getNaigai() {
		return $this->naigai;
	}
	
	public function setNaigai($naigai) {
		$this->naigai = $naigai;
		return $this;
	}
	
	public function getDepartureRequest() {
		return $this->departureRequest;
	}
	
	public function setDepartureRequest($departureRequest) {
		$this->departureRequest = $departureRequest;
		return $this;
	}
	
	public function getDestinationRequest() {
		return $this->destinationRequest;
	}
	
	public function setDestinationRequest($destinationRequest) {
		$this->destinationRequest = $destinationRequest;
		return $this;
	}
	
	public function build() {
		$parameter = new stdClass();
		$parameter->p_id = $this->getId();
		$parameter->p_divide = $this->getDivide();
		$parameter->p_hei = $this->getHei();
		$parameter->p_course_id = $this->getCourseId();
		$parameter->p_parameter = $this->getParameter();
		$parameter->p_naigai = $this->getNaigai();
		$parameter->p_departure_request = $this->getDepartureRequest();
		$parameter->p_destination_request = $this->getDestinationRequest();
		return $parameter;
	}
	
	public static function createByObject(stdClass $from, $incrementId = false) {
		$specialRequest = new self();
		$specialRequest->setId($incrementId ? $from->p_id + 1 : $from->p_id);
		$specialRequest->setDivide($from->p_divide);
		$specialRequest->setHei($from->p_hei);
		$specialRequest->setCourseId($from->p_course_id);
		$specialRequest->setParameter($from->p_parameter);
		$specialRequest->setNaigai($from->p_naigai);
		$specialRequest->setDepartureRequest($from->p_departure_request);
		$specialRequest->setDestinationRequest($from->p_destination_request);
		return $specialRequest;
	}
	
	public static function create() {
		return new self();
	}
}
