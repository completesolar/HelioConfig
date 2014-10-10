<?php
namespace CompleteSolar\HelioConfig;

class System{
	public $systemID;
	public $systemName;
	public $systemDescription;
	public $systemFields;
	public $configs;
	
	public function __construct($newID,$newName,$newDescription,$newSystemFields=array(),$newConfigs=array()){
		$this->systemID = $newID;
		$this->systemName = $newName;
		$this->systemDescription = $newDescription;
		$this->systemFields = $newSystemFields;
		$this->configs = $newConfigs;
	}

	public function getSystemID(){
		return $this->systemID;
	}
	
	public function getSystemName(){
		return $this->systemName;
	}
		
	public function getSystemDescription(){
		return $this->systemDescription;
	}
		
	public function getSystemFields(){
		return $this->systemFields;
	}
	public function getConfigs(){
		return $this->configs;
	}
}
?>