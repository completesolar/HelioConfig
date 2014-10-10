<?php
namespace CompleteSolar\HelioConfig;

class Field{
	public $fieldID;
	public $fieldName;
	public $fieldDescription;
	public $typeID;
	public $isRequired;
	public $default;
	public $isEditable;
	
	public function __construct($id,$name,$description,$fieldType,$required,$defaultValue,$editable){
		$this->fieldID = $id;
		$this->fieldName = $name;
		$this->fieldDescription = $description;
		$this->typeID = $fieldType;
		$this->isRequired = $required;
		$this->default = $defaultValue;
		$this->isEditable= $editable;
	}

	public function getFieldID(){
		return $this->fieldID;
	}
	
	public function getFieldName(){
			return $this->fieldName;
		}
		
	public function getFieldDescription(){
			return $this->fieldDescription;
		}
		
	public function getFieldTypeID(){
			return $this->typeID;
		}
		
	public function getIsRequired(){
			return $this->isRequired;
		}
		
	public function getDefaultValue(){
			return $this->default;
		}
		
	public function getIsEditable(){
			return $this->isEditable;
		}	
}
?>