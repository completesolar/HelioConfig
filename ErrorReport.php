<?php
namespace CompleteSolar\HelioConfig;

class ErrorReport{
	public $errorMsg;
	public $errorKey;
	
	public function __construct($errorMessage,$key=NULL){
		$this->errorMsg=$errorMessage;
		$this->errorKey=$key;
	}
	
	public function getMessage(){
		return $this->errorMsg;
	}
	
	public function getKey(){
		return $this->errorKey;
	}
	
	
	
}