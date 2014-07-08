<?php
namespace CompleteSolar\HelioConfig;

class Configuration{
	public $configID;
	public $configName;
	public $configDescription;
	public $isActive;
	public $valueFields;
	public $historicalFields;
	
	public function __construct($newID,$newName,$newDescription,$newIsActive,$newValueFields,$newHistoricalFields){
		$this->configID = $newID;
		$this->configName = $newName;
		$this->configDescription = $newDescription;
		$this->isActive = $newIsActive;
		$this->valueFields = $newValueFields;
		$this->historicalFields = $newHistoricalFields;
	}

	public function getConfigID(){
		return $this->configID;
	}
	
	public function getConfigName(){
			return $this->configName;
	}
		
	public function getConfigDescription(){
			return $this->configDescription;
	}
		
	public function getIsActive(){
			return $this->isActive;
	}
		
	public function getValueFields(){
			return $this->valueFields;
	}
	public function getHistoricalFields(){
			return $this->historicalFields;
	}
						
	
}
?>