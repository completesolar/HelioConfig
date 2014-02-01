<?php
namespace CompleteSolar\HelioConfig;
require_once "Field.php";
require_once "ValueField.php";

class HistoricalField extends ValueField{
	public $historicalFieldID;
	public $oldValue;
	public $newValue;
	public $modifiedDate;
	public $modifiedBy;
	
	public function __construct($id,$name,$description,$fieldType,$required,$defaultValue,$editable,$newValue,$newValueFieldID,$newHistoricalFieldID,$newOldValue,$newNewValue,$newModifiedDate,$newModifiedBy){
		parent::__construct($id,$name,$description,$fieldType,$required,$defaultValue,$editable,$newValue,$newValueFieldID);
		$this->historicalFieldID=$newHistoricalFieldID;
		$this->oldValue=$newOldValue;
		$this->newValue=$newNewValue;
		$this->modifiedDate=$newModifiedDate;
		$this->modifiedBy=$newModifiedBy;
	}
	
	public function getHistoricalFieldID(){
		return $this->historicalFieldID;
	}
	
	public function getOldValue(){
			return $this->oldValue;
		}
		
	public function getNewValue(){
			return $this->newValue;
		}
		
	public function getModifiedDate(){
			return $this->modifiedDate;
		}
		
	public function getModifiedBy(){
			return $this->modifiedBy;
		}
}