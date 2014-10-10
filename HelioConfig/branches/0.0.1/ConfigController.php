<?php
namespace CompleteSolar\HelioConfig;
require_once "ConfigDB.php";
require_once "Group.php";
require_once "System.php";
require_once "Field.php";
require_once "Configuration.php";
require_once "ValueField.php";
require_once "HistoricalField.php";
require_once "ErrorReport.php";
class ConfigController {
	public $configdb = null;
	
	
	public function __construct(){
		$this->configdb = new ConfigDB();
	}
	
	public function isSuccess($status){
		if ($status=="SUCCESS"){
			return true;	
		}
		$this->configdb->rollback();
		return false;
		
	}
	
	/*
	 * GET methods
	 */
	
	
	public function getGroups(){
		$groups = array();
		$this->configdb->getGroupsFromDB();
		$groupsResult = $this->configdb->result;
		while ($row = $groupsResult->fetch_array()){
			$systems = $this->getSystems($row["group_id"]);
			$group = new Group($row["group_id"],$row["group_name"],$systems);
			array_push($groups,$group);
		}
		return $groups;
	}
	
	public function getSystems($groupID){
		$systems = array();
		$this->configdb->getSystemsFromDB($groupID);
		$systemsResult = $this->configdb->result;
		while ($row = $systemsResult->fetch_array()){
			$system = new System($row["system_id"],$row["system_name"],$row["system_description"]);
			array_push($systems,$system);
		}
		return $systems;
	}
	
	public function getGroupFields($groupID){
		$groupFields = array();
		$this->configdb->getGroupFieldsFromDB($groupID);
		$groupFieldsResult = $this->configdb->result;
		while ($row = $groupFieldsResult->fetch_array()){
			$groupField = new Field($row["field_id"],$row["field_name"],$row["field_description"],$row["data_type_id"],$row["required"],$row["default_value"],$row["editable"]);
			array_push($groupFields,$groupField);
		}
		return $groupFields;
	}
	
	public function getSystemFields($systemID){
		$systemFields = array();
		$this->configdb->getSystemFieldsFromDB($systemID);
		$systemFieldsResult = $this->configdb->result;
		while ($row = $systemFieldsResult->fetch_array()){
			$systemField = new Field($row["field_id"],$row["field_name"],$row["field_description"],$row["data_type_id"],$row["required"],$row["default_value"],$row["editable"]);
			array_push($systemFields,$systemField);
		}
		return $systemFields;
	}
	
	public function getSystemConfigs($systemID){
		$configs = array();
		$this->configdb->getSystemConfigsFromDB($systemID);
		$systemConfigsResult = $this->configdb->result;
		while ($row = $systemConfigsResult->fetch_array()){
			$valueFields = $this->getConfigValueFields($row["config_id"]);
			$historicalFields = $this->getHistoricalFields($row["config_id"]);
			$config = new Configuration($row["config_id"],$row["config_name"],$row["config_description"],$row["is_active"],$valueFields,$historicalFields);
			array_push($configs,$config);
		}
		return $configs;
	}
	
	public function getConfigValueFields($configID){
		$valueFields = array();
		$this->configdb->getConfigValueFieldsFromDB($configID);
		$valueFieldsResult = $this->configdb->result;
		while ($row = $valueFieldsResult->fetch_array()){
			$this->configdb->getFieldFromID($row["field_id"]);
			$field = $this->configdb->result->fetch_array();
			$valueField = new ValueField($row["field_id"],
										 $field["field_name"],
										 $field["field_description"],
										 $field["data_type_id"],
										 $field["required"],
										 $field["default_value"],
										 $field["editable"],
										 $row["value"],
										 $row["field_value_id"]);
			array_push($valueFields,$valueField);
		}
		return $valueFields;
	}
	
	public function getHistoricalFields($configID){
		$historicalFields = array();
		$this->configdb->getChangeLogFromDB($configID);
		$historicalFieldsResult = $this->configdb->result;
		while ($row = $historicalFieldsResult->fetch_array()){
			$this->configdb->getValueFieldFromID($row["field_value_id"]);
			$valueField = $this->configdb->result->fetch_array();
			$this->configdb->getFieldFromID($valueField["field_id"]);
			$field = $this->configdb->result->fetch_array();
			$historicalField = new HistoricalField($valueField["field_id"],
												   $field["field_name"],
												   $field["field_description"],
												   $field["data_type_id"],
												   $field["required"],
												   $field["default_value"],
												   $field["editable"],
												   $valueField["value"],
												   $valueField["field_value_id"],
												   $row["field_value_history_id"],
												   $row["old_value"],
												   $row["new_value"],
												   $row["date_modified"],
												   $row["modified_by"]);
			array_push($historicalFields,$historicalField);
			}
		return $historicalFields;
	}
	
	public function getTypeName($typeID){
		$this->configdb->getDataTypeFromDB($typeID);
		$typeResult = $this->configdb->result;
		while ($row = $typeResult->fetch_array()){
			$type = $row["data_type_name"];
		}
		return $type;
	}
	
	public function getTypeID($typeName){
		$this->configdb->getDataIDFromDB($typeName);
		$typeResult = $this->configdb->result; 
		while ($row = $typeResult->fetch_array()){
			$typeID = $row["data_type_id"];
		}
		return $typeID;
	}

	
	public function checkValuesWithType($valueList,$typeIDList){
		foreach($valueList as $key=>$value){
			$typeID=$typeIDList[$key];
			switch ($typeID){
				case "string":
				case "1":
					continue;
					break;
				case "int":
				case "2":
					if (is_numeric($value)){
						if (strpos($value,'.')===false){
							continue;
						}
					}
					return new ErrorReport("Value of incorrect type",$key);
					break;
				case "point":
				case "3":
					if (is_numeric($value)){
						continue;
					}
					return new ErrorReport("Value of incorrect type",$key);
					break;
				case "boolean":
				case "4":
					if ($value===0 or $value===1){
						continue;
					}
					return new ErrorReport("Value of incorrect type",$key);
					break;
				case "list": 
				case "5":
					continue;
					break;
				case "config":
				case "6":
					$status = $this->configdb->getConfigFromID($value);
					if ($status=="SUCCESS"){
						continue;
					}
					return new ErrorReport("Config ID not found",$key);
					break;
			}
			return true;
		}
	}
	
	public function submitSystemToDB($groupID,$systemName,$systemDescription,$fieldIDList, $fieldNameList,$fieldDescriptionList,$typeIDList,$defaultValueList,$isRequiredList,$isEditableList){
		$newGroup=false;
		//check defaults
		$statusOfDefaults = $this->checkValuesWithType($defaultValueList, $typeIDList);
		if ($statusOfDefaults!==true){
			return $statusOfDefaults;
		}
		//if new group add it
		if ($groupID==0){
			$status=$this->configdb->addNewGroup($systemName);
			if (!$this->isSuccess($status)){
				return new ErrorReport($status);
			}
			$groupID=$this->configdb->db->insert_id;
			$newGroup = true;
		}
		
		//add new system to group
		$status=$this->configdb->addNewSystem($systemName,$groupID,$systemDescription);
		if (!$this->isSuccess($status)){
			return new ErrorReport($status);
		}
		$systemID=$this->configdb->db->insert_id;
		
		
		//add new fields to both fields and systems_to_fields table
		foreach($fieldIDList as $key=>$fieldID){
			if ($fieldID==0){
				if ($newGroup){
					$status=$this->configdb->addFieldToFieldDB($fieldNameList[$key],$fieldDescriptionList[$key],$typeIDList[$key],$defaultValueList[$key],$isRequiredList[$key],$isEditableList[$key],$groupID);
				}else{
					$status=$this->configdb->addFieldToFieldDB($fieldNameList[$key],$fieldDescriptionList[$key],$typeIDList[$key],$defaultValueList[$key],$isRequiredList[$key],$isEditableList[$key]);
				}
				if (!$this->isSuccess($status)){
					return new ErrorReport($status,$key);
				}
				$fieldID=$this->configdb->db->insert_id;
			}
			$status=$this->configdb->addSystemFieldToCompoundTable($systemID,$fieldID,$defaultValueList[$key],$isRequiredList[$key]);
			if (!$this->isSuccess($status)){
				return new ErrorReport($status,$key);
			}
		}
		//delete group fields if necessary
		$groupFields = $this->getGroupFields($groupID);
		$groupFieldIDs=array();
			foreach ($groupFields as $groupField){
				array_push($groupFieldIDs,$groupField->getFieldID());
			}
		foreach($groupFieldIDs as $groupFieldID){
					if (in_array($groupFieldID, $fieldIDList)){
						continue;
					}else{
						$this->configdb->deleteGroupField($groupFieldID);
					}
				}	
		return new ErrorReport("SUCCESS");		
	}
	
	public function submitGlobalGroupUpdate($fieldID,$newValue){
		
		//check if value matches type
		$newValueList=array();
		$typeIDList=array();
		$newValueList[]=$newValue;
		$status=$this->configdb->getFieldFromID($fieldID);
		if ($status!="SUCCESS"){
			return new ErrorReport($status);
		}
		while ($row= $this->configdb->result->fetch_array()){
			$typeIDList[]=$row["data_type_id"];
		}
		$status=$this->checkValuesWithType($newValueList, $typeIDList);
		if ($status!==true){
			return $status;
		}
		
		//insert new rows into change logs
		$status=$this->configdb->getValueFieldFromFieldID($fieldID);
		if ($status!="SUCCESS"){
			return new ErrorReport($status);
		}
		$valueFieldsResult = $this->configdb->result;
		while ($row = $valueFieldsResult->fetch_array()){
			$status=$this->configdb->insertNewValueIntoChangeLog($row["field_value_id"],$row["value"],$newValue);
			if ($status!="SUCCESS"){
				return new ErrorReport($status);
			}
		}
	
		//update new value into value fields
		$status=$this->configdb->globalUpdateGroup($newValue,$fieldID);
		if (!$this->isSuccess($status)){
			return new ErrorReport($status);
		}
		return new ErrorReport("SUCCESS");
		
	}
	
	public function submitGlobalSystemUpdate($systemID,$fieldID,$newValue){
		
		//check if value matches type
		$newValueList=array();
		$typeIDList=array();
		$newValueList[]=$newValue;
		$status=$this->configdb->getFieldFromID($fieldID);
		if ($status!="SUCCESS"){
			return new ErrorReport($status);
		}
		while ($row= $this->configdb->result->fetch_array()){
			$typeIDList[]=$row["data_type_id"];
		}
		$status=$this->checkValuesWithType($newValueList, $typeIDList);
		if ($status!==true){
			return $status;
		}
		
		//insert new rows into change logs
		$status=$this->configdb->getValueFieldFromFieldID($fieldID);
		if ($status!="SUCCESS"){
			return new ErrorReport($status);
		}
		$valueFieldsResult = $this->configdb->result;
		while ($row = $valueFieldsResult->fetch_array()){
			$status=$this->configdb->insertNewValueIntoChangeLog($row["field_value_id"],$row["value"],$newValue);
			if ($status!="SUCCESS"){
				return new ErrorReport($status);
			}
		}
	
		//update new value into value fields
		$status=$this->configdb->globalUpdateSystem($newValue,$fieldID,$systemID);
		if (!$this->isSuccess($status)){
			return new ErrorReport($status);
		}
		return new ErrorReport("SUCCESS");
		
	}
	
	public function deleteGroupField($fieldID){
		$status=$this->configdb->deleteGroupField($fieldID);
		if (!$this->isSuccess($status)){
			return new ErrorReport($status);
		}
		return new ErrorReport("SUCCESS");
		
	}
	
	public function deleteSystemField($systemID,$fieldID){
		//insert new rows into change logs
		$status=$this->configdb->getValueFieldFromFieldID($fieldID);
		if ($status!="SUCCESS"){
			return new ErrorReport($status);
		}
		$valueFieldsResult = $this->configdb->result;
		while ($row = $valueFieldsResult->fetch_array()){
			$status=$this->configdb->insertNewValueIntoChangeLog($row["field_value_id"],$row["value"],NULL);
			if ($status!="SUCCESS"){
				return new ErrorReport($status);
			}
		}
		
		//delete from value fields
		$status = $this->configdb->deleteFieldFromValueFields($fieldID);
		if (!$this->isSuccess($status)){
			return new ErrorReport($status);
		}
		
		//delete from systems_to_fields
		$status=$this->configdb->deleteFieldFromCompoundTable($systemID,$fieldID);
		if (!$this->isSuccess($status)){
			return new ErrorReport($status);
		}
		return new ErrorReport("SUCCESS");
	}
	
	
	public function submitGroupFieldUpdate($groupID,$fieldID,$fieldName,$fieldDescription,$fieldTypeName,$isRequired,$defaultValue,$isEditable){
		//convert type name to type id
		$fieldTypeID = $this->getTypeID($fieldTypeName);
		//check if group field already exists or not
		if ($fieldID!=0){
			$status = $this->configdb->updateGroupFieldInFields($fieldID,$fieldName,$fieldDescription,$fieldTypeID,$isRequired,$defaultValue,$isEditable);
			if (!$this->isSuccess($status)){
				return new ErrorReport($status);
			}			
		}else{
			$status = $this->configdb->addFieldToFieldDB($fieldID,$fieldName,$fieldDescription,$fieldTypeID,$isRequired,$defaultValue,$isEditable,$groupID);
			if (!$this->isSuccess($status)){
				return new ErrorReport($status);
			}		
		}
		return new ErrorReport("SUCCESS");
	}
	
	public function submitSystemFieldUpdate($systemID,$fieldID,$fieldName,$fieldDescription,$fieldTypeName,$isRequired,$defaultValue,$isEditable){
		//convert type name to type id
		$fieldTypeID = $this->getTypeID($fieldTypeName);
		//check if system field already exists or not
		if ($fieldID==0){
			//add to fields as a system field
			$status = $this->configdb->addFieldToFieldDB($fieldName,$fieldDescription,$fieldTypeID,$isRequired,$defaultValue,$isEditable);
			if (!$this->isSuccess($status)){
				return new ErrorReport($status);
			}
			$fieldID = $this->configdb->db->insert_id;
			
			//add to compound table
			$status=$this->configdb->addSystemFieldToCompoundTable($systemID,$fieldID,$defaultValue,$isRequired);
			if (!$this->isSuccess($status)){
				return new ErrorReport($status);
			}
			
			//if new field is required add them to value fields set to default value
			if ($isRequired){
				$systemConfigs = $this->getSystemConfigs($systemID);
				foreach ($systemConfigs as $key=>$config){
					$status = $this->configdb->addNewValueField($config->getConfigID(),$fieldID,$defaultValue);
					if (!$this->isSuccess($status)){
					return new ErrorReport($status);
					}
				}
			}
		}
		else{
			//update field in compound table
			$status=$this->configdb->updateSystemFieldInCompoundTable($systemID,$fieldID,$defaultValue,$isRequired);		
			if (!$this->isSuccess($status)){
				return new ErrorReport($status);
			}
			
			//if field is now required add value field to any config which doesn't already contain it
			if ($isRequired){
				$systemConfigs = $this->getSystemConfigs($systemID);
				foreach ($systemConfigs as $key=>$config){
					$configAlreadyContainsValueField = false;
					foreach ($config->getValueFields() as $valueField){
						$status=$this->configdb->getValueFieldFromID($valueField->getValueFieldID());
						if (!$this->isSuccess($status)){
							return new ErrorReport($status);
						}
						while ($row = $this->configdb->result->fetch_array()){
							$currentFieldID = $row["field_id"];
						}
						if ($currentFieldID==$fieldID){
							$configAlreadyContainsValueField = true;
						}
					}
					if (!$configAlreadyContainsValueField){
						$status = $this->configdb->addNewValueField($config->getConfigID(),$fieldID,$defaultValue);
						if (!$this->isSuccess($status)){
							return new ErrorReport($status);
						}
						$valueFieldID =  $this->configdb->db->insert_id;
						$status = $this->configdb->insertNewValueIntoChangeLog($valueFieldID,NULL,$defaultValue);
						if (!$this->isSuccess($status)){
							return new ErrorReport($status);
						}
					}
				}
			}
		}
		return new ErrorReport("SUCCESS");
	}
	
	public function getMostRecentConfigChange($configID){
		$this->configdb->getChangeLogFromDB($configID);
		$changeLog = $this->configdb->result->fetch_array();
		$this->configdb->getValueFieldFromID($changeLog["field_value_id"]);
		$valueField = $this->configdb->result->fetch_array();
		$this->configdb->getFieldFromID($valueField["field_id"]);
		$field = $this->configdb->result->fetch_array();
		$historicalField = new HistoricalField($valueField["field_id"],
											   $field["field_name"],
											   $field["field_description"],
											   $field["data_type_id"],
											   $field["required"],
											   $field["default_value"],
											   $field["editable"],
											   $valueField["value"],
											   $valueField["field_value_id"],
											   $changeLog["field_value_history_id"],
											   $changeLog["old_value"],
											   $changeLog["new_value"],
											   $changeLog["date_modified"],
											   $changeLog["modified_by"]);
		return $historicalField;
	}

	public function newActiveConfig($systemID,$configID){
		//inactivate all configs in system
		$status=$this->configdb->inactivateAllConfigs($systemID);
		if (!$this->isSuccess($status)){
			return new ErrorReport($status);
		}
		
		//activate the correct config
		$status=$this->configdb->activateConfig($configID);
		if (!$this->isSuccess($status)){
			return new ErrorReport($status);
		}
		return new ErrorReport("SUCCESS");
		
	}
	
	public function submitConfigToDB($systemID,$configName,$configDescription,$fieldIDList,$fieldNameList,$fieldDescriptionList,$fieldTypeNameList,$valueList){
		//check that values match type
		$typeIDList = array();
		foreach($fieldTypeNameList as $fieldTypeName){
			$typeIDList[]=$this->getTypeID($fieldTypeName);
		}	
		$statusOfValues = $this->checkValuesWithType($valueList, $typeIDList);
		if (!$statusOfValues==="true"){
			return $statusOfValues;
		}
		
		//add config to table
		$status = $this->configdb->addNewConfig($systemID,$configName,$configDescription);
		if (!$this->isSuccess($status)){
			return new ErrorReport($status);	
		}
		$configID = $this->configdb->db->insert_id;
		
		//add one value field for each field in fieldList
		$valueFieldIDs = array();
		foreach($fieldIDList as $key=>$fieldID){
			$status = $this->configdb->addNewValueField($configID,$fieldID,$valueList[$key]);
			if (!$this->isSuccess($status)){
				return new ErrorReport($status);	
			}
			$valueFieldIDs[]=$this->configdb->db->insert_id;
		}
		
		//add new historical fields for each new value field
		foreach($valueFieldIDs as $key=>$valueFieldID){
			$status = $this->configdb->insertNewValueIntoChangeLog($valueFieldID,NULL,$valueList[$key]);
			if (!$this->isSuccess($status)){
				return new ErrorReport($status);	
			}
		}
		return new ErrorReport("SUCCESS");
		
	}
	
	public function updateFieldValue($valueFieldID,$newValue){
		//check that new value matches field type
		$status = $this->configdb->getValueFieldFromID($valueFieldID);
		if (!$this->isSuccess($status)){
			return new ErrorReport($status);	
		}
		$valueFieldRow = $this->configdb->result->fetch_array();
		$fieldID = $valueFieldRow["field_id"];
		$configID = $valueFieldRow["config_id"];
		$status = $this->configdb->getFieldFromID($fieldID);
		if (!$this->isSuccess($status)){
			return new ErrorReport($status);	
		}
		$fieldRow = $this->configdb->result->fetch_array();
		$dataTypeList = array($fieldRow["data_type_id"]);
		$valueList = array($newValue);
		$statusOfValues = $this->checkValuesWithType($valueList, $dataTypeList);
		if ($statusOfValues!==true){
			return $statusOfValues;
		}
		//insert into change log
		$status = $this->configdb->insertNewValueIntoChangeLog($valueFieldID,$valueFieldRow["value"],$newValue);
		if (!$this->isSuccess($status)){
			return new ErrorReport($status);	
		}
		//update new value field
		$status = $this->configdb->updateValueField($valueFieldID,$newValue);
		if (!$this->isSuccess($status)){
			return new ErrorReport($status);	
		}
		
		return new ErrorReport("SUCCESS");
	}		
}
	
?>