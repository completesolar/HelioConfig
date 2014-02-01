<?php
namespace CompleteSolar\HelioConfig;
require_once "Field.php";

class ValueField extends Field{
	public $valueFieldID;
	public $value;
	
	public function __construct($id,$name,$description,$fieldType,$required,$defaultValue,$editable,$newValue,$newValueFieldID){
		parent::__construct($id,$name,$description,$fieldType,$required,$defaultValue,$editable);
		$this->value = $newValue;
		$this->valueFieldID = $newValueFieldID;
	}
	
	public function getValue(){
		return $this->value;
	}
	
	public function getValueFieldID(){
			return $this->valueFieldID;
		}
}