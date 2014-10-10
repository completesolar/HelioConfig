<?php
namespace CompleteSolar\HelioConfig;
require_once "setIncludePath.php";
require_once "cssDB.php";

class ConfigDB extends \CssDB {
	public function __construct($hostname="completesolar.com", 
								$username="helioconfig", 
								$password="RvLeW6z8wjmjLquP", 
								$dbname="helioconfig") {
									
		$this->result = null;
		
		$this->db = new \mysqli($hostname, $username, $password, $dbname);
		// check connection 
		if ($this->db->connect_errno) {
			printf("Connect failed: %s\n", $this->db->connect_error);
			exit();
		}
		$this->inTransaction = false;
		
	}
	
	public function getGroupsFromDB(){
		$query = "SELECT * FROM groups
				  WHERE groups.deleted=0";
		if ($this->executePreparedStatement($query)===false){
			return $this->errors[count($this->errors)-1];
		}
		return "SUCCESS";
	}
	
	public function getSystemsFromDB($groupID){
		$query = "SELECT * FROM systems
				  WHERE systems.group_id = ?
				  AND systems.deleted=0";
		if ($this->executePreparedStatement($query,"i",$groupID)===false){
			return $this->errors[count($this->errors)-1];
		}
		return "SUCCESS";
	}
	
	public function getGroupFieldsFromDB($groupID){
		$query = "SELECT * FROM fields
			 	  WHERE fields.group_id = ?
			 	  AND fields.deleted=0";
		if ($this->executePreparedStatement($query,"i",$groupID)===false){
			return $this->errors[count($this->errors)-1];
		}
		return "SUCCESS";
	}
	
	public function getSystemFieldsFromDB($systemID){
		$query = "SELECT fields.field_id,fields.field_name,fields.field_description,fields.data_type_id,systems_to_fields.default_value,systems_to_fields.required,fields.editable
				  FROM systems_to_fields
		 		  INNER JOIN fields 
		 		  ON systems_to_fields.field_id = fields.field_id 
		 		  WHERE systems_to_fields.system_id = ?
		 		  AND fields.deleted=0 
		 		  AND systems_to_fields.deleted=0";
		if ($this->executePreparedStatement($query,"i",$systemID)===false){
			return $this->errors[count($this->errors)-1];
		}
		return "SUCCESS";
	}
	
	public function getSystemConfigsFromDB($systemID){
		$query = "SELECT * FROM configurations
				  WHERE configurations.system_id = ?
				  AND configurations.deleted=0";
		if ($this->executePreparedStatement($query,"i",$systemID)===false){
			return $this->errors[count($this->errors)-1];
		}
		return "SUCCESS";
	}
	
	public function getConfigValueFieldsFromDB($configID){
		$query = "SELECT * FROM field_values
				  WHERE field_values.config_id = ?
				  AND field_values.deleted=0";
		if ($this->executePreparedStatement($query,"i",$configID)===false){
			return $this->errors[count($this->errors)-1];
		}
		return "SUCCESS";
	}
	
	public function getChangeLogFromDB($configID){
		$query = "SELECT * FROM field_value_history
				  INNER JOIN field_values
				  ON field_value_history.field_value_id = field_values.field_value_id
				  WHERE field_values.config_id = ?
				  AND field_values.deleted=0
				  AND field_value_history.deleted=0
				  ORDER BY field_value_history.date_modified DESC";
		if ($this->executePreparedStatement($query,"i",$configID)===false){
			return $this->errors[count($this->errors)-1];
		}
		return "SUCCESS";
	}
	
	public function getDataTypeFromDB($typeID){
		$query = "SELECT data_type_name FROM data_types
				  WHERE data_types.data_type_id = ?
				  AND data_types.deleted=0";
		if ($this->executePreparedStatement($query,"i",$typeID)===false){
			return $this->errors[count($this->errors)-1];
		}
		return "SUCCESS";
	}
	
	public function getDataIDFromDB($typeName){
		$query = "SELECT data_type_id FROM data_types
				  WHERE data_types.data_type_name = ?
				  AND data_types.deleted=0";
		if ($this->executePreparedStatement($query,"s",$typeName)===false){
			return $this->errors[count($this->errors)-1];
		}
		return "SUCCESS";
	}
	
	public function getFieldFromID($fieldID){
		$query = "SELECT * FROM fields
				  WHERE fields.field_id = ?
				  AND fields.deleted=0";
		if ($this->executePreparedStatement($query,"i",$fieldID)===false){
			return $this->errors[count($this->errors)-1];
		}
		return "SUCCESS";
	}
	
	public function getValueFieldFromID($valueFieldID){
		$query = "SELECT * FROM field_values
				  WHERE field_values.field_value_id = ?
				  AND field_values.deleted=0";
		if ($this->executePreparedStatement($query,"i",$valueFieldID)===false){
			return $this->errors[count($this->errors)-1];
		}
		return "SUCCESS";
	}
	
	public function getValueFieldFromFieldID($fieldID){
		$query = "SELECT * FROM field_values
				  WHERE field_values.field_id = ?
				  AND field_values.deleted=0";
		if ($this->executePreparedStatement($query,"i",$fieldID)===false){
			return $this->errors[count($this->errors)-1];
		}
		return "SUCCESS";
	}
	
	public function getConfigFromID($configID){
		$query = "SELECT * FROM configurations
				  WHERE configurations.config_name = ?
				  AND configurations.deleted=0";
		if ($this->executePreparedStatement($query,"i",$configID)===false){
			return $this->errors[count($this->errors)-1];
		}
		return "SUCCESS";
	}
	
	public function getActiveConfig($systemID){
		$query = "SELECT * FROM configurations
				  WHERE configurations.is_active = 1
				  AND configurations.system_id = ?
				  AND configurations.deleted=0";
		if ($this->executePreparedStatement($query,"i",$systemID)===false){
			return $this->errors[count($this->errors)-1];
		}
		return "SUCCESS";	
		
	}
	public function addNewGroup($groupName){
		$query = "INSERT INTO groups 
				  (group_name)VALUES (?)";
		if ($this->executePreparedStatement($query,"s",$groupName)===false){
			return $this->errors[count($this->errors)-1];
		}
		return "SUCCESS";
	}
	
	public function addNewSystem($systemName,$groupID,$systemDescription){
		$query = "INSERT INTO systems 
				  (system_name,group_id,system_description)
				  VALUES (?,?,?)";
		if ($this->executePreparedStatement($query,"sis",$systemName,$groupID,$systemDescription)===false){
			return $this->errors[count($this->errors)-1];
		}
		return "SUCCESS";
	}
	
	public function addFieldToFieldDB($fieldName,$fieldDescription,$dataTypeID,$defaultValue,$isRequired,$isEditable,$groupID=NULL){
		$query = "INSERT INTO fields 
				  (field_name,field_description,data_type_id,default_value,required,editable,group_id)
				  VALUES (?,?,?,?,?,?,?)";
		if ($this->executePreparedStatement($query,"ssisiii",$fieldName,$fieldDescription,$dataTypeID,$defaultValue,$isRequired,$isEditable,$groupID)===false){
			return $this->errors[count($this->errors)-1];
		}
		return "SUCCESS";
	}
	
	
	public function addSystemFieldToCompoundTable($systemID,$fieldID,$defaultValue,$isRequired){
		$query = "INSERT INTO systems_to_fields 
				  (system_id,field_id,default_value,required)
				  VALUES (?,?,?,?)";
		if ($this->executePreparedStatement($query,"iisi",$systemID,$fieldID,$defaultValue,$isRequired)===false){
			return $this->errors[count($this->errors)-1];
		}
		return "SUCCESS";
	}
	
	public function addNewConfig($systemID,$configName,$configDescription,$isActive=0){
		$query = "INSERT INTO configurations
				  (config_name,config_description,system_id,is_active)
				  VALUES (?,?,?,?)";
		if ($this->executePreparedStatement($query,"ssii",$configName,$configDescription,$systemID,$isActive)===false){
			return $this->errors[count($this->errors)-1];
		}
		return "SUCCESS";
	}
	
	public function insertNewValueIntoChangeLog($fieldValueID,$oldValue,$newValue){	
		$query = "INSERT INTO field_value_history
				  (field_value_id,old_value,new_value)
				  VALUES (?,?,?)";
		if ($this->executePreparedStatement($query,"iss",$fieldValueID,$oldValue,$newValue)===false){
			return $this->errors[count($this->errors)-1];
		}
		return "SUCCESS";
	}
	
	public function addNewValueField($configID,$fieldID,$value){
		$query = "INSERT INTO field_values
				  (config_id,field_id,value)
				  VALUES (?,?,?)";
		if ($this->executePreparedStatement($query,"iis",$configID,$fieldID,$value)===false){
			return $this->errors[count($this->errors)-1];
		}
		return "SUCCESS";
	}
	
	public function globalUpdateGroup($newValue,$fieldID){
		$query = "UPDATE field_values
				  SET field_values.value = ?
				  WHERE field_values.field_id = ?
				  AND field_values.deleted=0";
		if ($this->executePreparedStatement($query,"si",$newValue,$fieldID)===false){
			return $this->errors[count($this->errors)-1];
		}
		return "SUCCESS";
	}
	
	public function globalUpdateSystem($newValue,$fieldID,$systemID){
		$query = "UPDATE field_values
				  SET field_values.value = ?
				  WHERE field_values.config_id IN
				  	(SELECT config_id FROM systems
				  	 WHERE systems.system_id = ?
				  	 AND systems.deleted=0) 
				  AND field_values.field_id = ?
				  AND field_values.deleted=0";
		if ($this->executePreparedStatement($query,"sii",$newValue,$fieldID,$systemID)===false){
			return $this->errors[count($this->errors)-1];
		}
		return "SUCCESS";
	}
		
	public function deleteFieldFromValueFields($fieldID){
		$query = "UPDATE field_values
				  SET field_values.deleted=1
				  WHERE field_values.field_id=?
				  AND field_values.deleted=0";
		$affectedRows = $this->executePreparedStatement($query,"i",$fieldID);
		if ($affectedRows===false){
			return $this->errors[count($this->errors)-1];
		}elseif($affectedRows===0){
			return "Field not deleted";
		}
		return "SUCCESS";		
	}
	
	public function deleteFieldFromCompoundTable($systemID,$fieldID){
		$query = "UPDATE systems_to_fields
				  SET systems_to_fields.deleted=1
				  WHERE systems_to_fields.field_id=?
				  AND systems_to_fields.system_id=?
				  AND systems_to_fields.deleted=0";
		$affectedRows = $this->executePreparedStatement($query,"ii",$systemID,$fieldID);
		if ($affectedRows===false){
			return $this->errors[count($this->errors)-1];
		}elseif($affectedRows===0){
			return "Field not deleted";
		}
		return "SUCCESS";	
	}
	
	public function deleteGroupField($fieldID){
		$query = "UPDATE fields
				  SET fields.group_id=NULL
				  WHERE fields.field_id=?
				  AND fields.deleted=0";
		$affectedRows = $this->executePreparedStatement($query,"i",$fieldID);
		if ($affectedRows===false){
			return $this->errors[count($this->errors)-1];
		}elseif($affectedRows===0){
			return "Field not deleted";
		}
		return "SUCCESS";	
	}
	
	public function updateGroupFieldInFields($fieldID,$fieldName,$fieldDescription,$fieldTypeID,$isRequired,$defaultValue,$isEditable){
		$query = "UPDATE fields
				  SET fields.field_name=?,
				  fields.field_description=?,
				  fields.data_type_id=?,
				  fields.default_value=?,
				  fields.required=?,
				  fields.editable=?
				  WHERE fields.field_id=?
				  AND fields.deleted=0";
		$affectedRows = $this->executePreparedStatement($query,"ssisiii",$fieldName,$fieldDescription,$fieldTypeID,$defaultValue,$isRequired,$isEditable,$fieldID);
		if ($affectedRows===false){
			return $this->errors[count($this->errors)-1];
		}elseif($affectedRows===0){
			return "Field not updated";
		}
		return "SUCCESS";	
	}
	
	public function updateSystemFieldInCompoundTable($systemID,$fieldID,$defaultValue,$isRequired){
		$query = "UPDATE systems_to_fields
				  SET systems_to_fields.default_value=?,
				  systems_to_fields.required=?
				  WHERE systems_to_fields.field_id=?
				  AND systems_to_fields.system_id=?
				  AND systems_to_fields.deleted=0";
		$affectedRows = $this->executePreparedStatement($query,"siii",$defaultValue,$isRequired,$fieldID,$systemID);
		if ($affectedRows===false){
			return $this->errors[count($this->errors)-1];
		}elseif($affectedRows===0){
			return "Field not updated";
		}
		return "SUCCESS";	
		
	}
	
	public function inactivateAllConfigs($systemID){
		$query = "UPDATE configurations
				  SET configurations.is_active = 0
				  WHERE configurations.system_id = ?
				  AND configurations.deleted = 0";
		$affectedRows = $this->executePreparedStatement($query,"i",$systemID);
		if ($affectedRows===false){
			return $this->errors[count($this->errors)-1];
		}
		return "SUCCESS";	
	}
	
	public function activateConfig($configID){
		$query = "UPDATE configurations
				  SET configurations.is_active = 1
				  WHERE configurations.config_id = ?
				  AND configurations.deleted = 0";
		$affectedRows = $this->executePreparedStatement($query,"i",$configID);
		if ($affectedRows===false){
			return $this->errors[count($this->errors)-1];
		}elseif($affectedRows===0){
			return "Field not updated";
		}
		return "SUCCESS";	
	}
	
	public function updateValueField($valueFieldID,$newValue){
		$query = "UPDATE field_values 
				  SET field_values.value = ?
				  WHERE field_values.field_value_id = ?
				  AND field_values.deleted = 0";
		$affectedRows = $this->executePreparedStatement($query,"si",$newValue,$valueFieldID);
		if ($affectedRows===false){
			return $this->errors[count($this->errors)-1];
		}elseif($affectedRows===0){
			return "Field not updated";
		}
		return "SUCCESS";	
	}
}
?>