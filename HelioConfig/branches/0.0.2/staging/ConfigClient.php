<?php
namespace CompleteSolar\HelioConfig;
require_once "setIncludePath.php";
require_once "ConfigController.php";

class ConfigClient{
	public $controller;
	public $systemID;
	public $configID;
	public $configObject;
	
	public function __construct($currentSystemID){
		$this->controller = new ConfigController();
		$this->systemID = $currentSystemID;
		
	}
	
	
	public function setUp($configID=null){
		if (isset($configID)){
			$this->configID =$configID;
			$this->controller->configdb->getConfigFromID($this->configID);
			$configRow = $this->controller->configdb->result->fetch_array();
			
		}else{
			$this->controller->configdb->getActiveConfig($this->systemID);
			$configRow = $this->controller->configdb->result->fetch_array();
			$this->configID = $configRow["config_id"];
		}
		
		
		$this->configObject = new Configuration($this->configID, $configRow["config_name"], $configRow["config_description"], $configRow["is_active"], $this->controller->getConfigValueFields($this->configID),null);
	
		
	}
	
	public function getProperty($fieldName){
		foreach($this->configObject->getValueFields() as $valueField){
			if ($valueField->getFieldName()==$fieldName){
				switch ($valueField->getFieldTypeID()){
					case 1:
						return $valueField->getValue();
						break;
					case 2: 
						return (int)$valueField->getValue();
						break;
					case 3:
						return (float)$valueField->getValue();
						break;
					case 4:
						return (boolean)$valueField->getValue();
						break;
					case 5:
						return explode(',',$valueField->getValue());
						break;
					case 6:
						$client = new ConfigClient($this->systemID);
						$client->setUp($valueField->getValue());
						return $client;
						break;
				}
			}
		}
	}
	
	
	
}


?>