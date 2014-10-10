<?php
namespace CompleteSolar\HelioConfig;
require_once "ConfigController.php";
require_once "Group.php";
require_once "System.php";
require_once "ErrorReport.php";


class ConfigView {
	public $username;
	public $currentPath;
	public $controller;
	
	public function __construct($newUsername,$currentDirectory){
		$this->username = $newUsername;
		$this->currentPath = $currentDirectory;
		$this->controller = new ConfigController();
	}
	
	public function startHead(){
		echo '<!DOCTYPE HTML>';
		echo '<html>';
		echo '<head>';
		echo '<title>HelioConfig</title>';
		echo '<script type="text/javascript" charset="utf-8" src="/helioConfig/DataTables-1.9.4/media/js/jquery.js"></script>';
		echo '<script type="text/javascript" charset="utf-8" src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>';
		echo '<script type="text/javascript" charset="utf-8" src="/helioConfig/DataTables-1.9.4/media/js/jquery.dataTables.js"></script>';
		echo '<script type="text/javascript" charset="utf-8" src="/helioConfig/jquery_jeditable-1.7.3/js/jquery.jeditable.js"></script>';
		echo '<script type="text/javascript" charset="utf-8" src="http://jquery-datatables-editable.googlecode.com/svn/trunk/media/js/jquery.dataTables.editable.js"></script>';
		echo '<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">';
		echo '<style type="text/css">
  	 			   @import "/helioConfig/DataTables-1.9.4/media/css/demo_table.css";
	  		  </style>';
	}
	
	public function endHead(){
		echo '</head>';
	}
	
	public function dashboardHead(){
		$this->startHead();
		echo '<script src="JavaScripts/dashboard.js"></script>';
		$this->endHead();
	}
	
	public function addSystemHead(){
		$this->startHead();
		echo '<script src="JavaScripts/addSystem.js"></script>';
		$this->endHead();
	}
	
	public function addConfigHead(){
		$this->startHead();
		echo '<script src="JavaScripts/addConfig.js"></script>';
		$this->endHead();
	}
	
	public function viewGroupFieldsHead(){
		$this->startHead();
		echo '<script src="JavaScripts/groupFields.js"></script>';
		$this->endHead();
	}
	
	public function viewSystemFieldsHead(){
		$this->startHead();
		echo '<script src="JavaScripts/systemFields.js"></script>';
		$this->endHead();
	}
	
	public function viewSystemConfigsHead(){
		$this->startHead();
		echo '<script src="JavaScripts/systemConfigs.js"></script>';
		$this->endHead();
	}
	
	public function viewConfigsHead(){
		$this->startHead();
		echo '<script src="JavaScripts/viewConfigs.js"></script>';
		$this->endHead();
	}
	
	public function changeLogHead(){
		$this->startHead();
		echo '<script src="JavaScripts/viewChangeLog.js"></script>';
		$this->endHead();
	}
	
	public function startBody(){
		echo '<body>';
    	echo '<div id="page-wrap">';
	
	}
	
	public function setHeader(){
		echo '<div id="header">';
		echo '<div id="control">';
		echo '<p align="right">';
		echo 'user: '.$this->username.'<br>';
		echo '<a href="/" class="button" align="right">Log out</a></p>';
		echo '</div>';
		echo '<h1>';
		foreach ($this->currentPath as $key=>$value){
			if ($key!=count($this->currentPath)-1){
				echo '<a href=""> '.$value.' </a> > ';
			}else{
				echo $value.'</h1>';
			}
		}
		echo '<hr size="4">';
		echo '</div>';
	}
	
	public function endBody(){
		echo '</div>';
		echo '</body>';
		echo '</html>';
	}
	
	public function displayDashTable(){
		echo '<div id="groups">';
		echo '<table id="dashTable" border="0">';
		foreach ($this->controller->getGroups() as $group){
			echo '<tr>
				  <form id="groupFields" method="get" action="groupFields.php">
				  <td colspan="2" align="left" width="200">'.$group->getGroupName().'</td>
				  <td colspan="2" align="right"><button type="submit">Group Fields</button>
				  <input type="hidden" name="groupID" value="'.$group->getGroupID().'"></td>
				  </form>
				  </tr>';
			$systems = $group->getSystems();
			foreach ($systems as $value){
				echo '<tr>
					  <td width="30"></td>
					  <td width="100" align="left">'.$value->getSystemName().'</td>
					  <td align="right"><form id="systemFields" method="get" action="systemFields.php">
					  <button type="submit">Fields</button>
					  <input type="hidden" name="systemID" value="'.$value->getSystemID().'">
					  </form></td>
					  <td align="right"><form id="systemConfigs" method="get" action="systemConfigs.php">
					  <button type="submit">Configs</button>
					  <input type="hidden" name="systemID" value="'.$value->getSystemID().'">
					  </form></td>
					  </tr>';
			}
		}
		echo '</table>';
		echo '</div>';
	}	
	public function displayAddNewSystem(){
		echo '<div id="addsystems">';
		echo '<button id="addNewSystem">Add New System</button>';
		echo '</div>';
		echo '<div id="dialog-form" title="Add new system">
			  <form id="groupIDForm" method="post" action="addSystem.php">
			  <label for="groupdropdown">Which group would you like to add this system to?</label>
			  <select name="groupdropdown">';
		foreach($this->controller->getGroups() as $group){	  
			$groupName = $group->getGroupName(); 
			$groupID = $group->getGroupID(); 
			echo '<option value="'.$groupID.'">'.$groupName.'</option>';
		}
		echo '<option value="0">New Group</option>';
		echo '</select>';	  
		echo '</form>
			  </div>';
	}
	
	public function displayInitialNewSystemForm($groupID,$systemName=NULL,$systemDescription=NULL){
		echo '<form id="systemForm" method="post">
			  <label for="systemName">System Name</label>
			  <input type="text" required="required" name="systemName" value="'.$systemName.'"><br>
			  <label for="systemDescription">System Description</label>
			  <input type="text" required="required" name="systemDescription" value="'.$systemDescription.'"><br>
			  <input type="hidden" name="groupID" value="'.$groupID.'">
			  <table class="display" id="systemTable" width="80%" align="left">
			  <thead>
			  <tr>
			  <th width="1%"></th><th width="5%">Field</th><th>Description</th><th>Type</th><th align="center">Required</th><th width="10%">Default</th><th align="center">Editable</th>
			  </tr></thead><tbody>';
	}
	
	public function displaySystemSubmitFooter(){
		echo '</tbody></table>';
		echo '<button type="button" id="addField">Add Field</button>';
		echo '<button type="submit" method="post" action="addSystem.php" name="submitSystem">OK</button>';
		echo '<a href="javascript:history.back()"><button type="button">Cancel</button></a>';
		echo '</form>';
	}
	
	public function displayFieldTableRow($fieldID,$fieldName,$fieldDescription,$fieldTypeName,$defaultValue,$isRequired,$isEditable,$isError=false){
		if ($fieldID!=0){
			echo '<tr>
				  <td><button type="button" class="deleteButton">Delete</button></td>
				  <td><input type="hidden" name="fieldName[]" value="'.$fieldName.'">'.$fieldName.'</td>
				  <td><input type="hidden" name="fieldDescription[]" value="'.$fieldDescription.'">'.$fieldDescription.'</td>
				  <td><input type="hidden" name="fieldType[]" value="'.$fieldTypeName.'">'.$fieldTypeName.'</td>';
			if ($isRequired){
				echo '<td align="center"><input class="required" type="checkbox" name="isRequiredCheckbox[]" checked>
					  <input class="hiddenRequired" type="hidden" name="isRequired[]" value="1"></td>';
			}else{
				echo '<td align="center"><input class="required" type="checkbox" name="isRequiredCheckbox[]">
					  <input class="hiddenRequired" type="hidden" name="isRequired[]" value="0"></td>';
			}
			echo '<td><input type="text" name="defaultValue[]" required="required" value="'.$defaultValue.'"></td>';		
			if ($isEditable){
				echo '<td align="center"><input class="editable" type="checkbox" name="isEditableCheckbox[]" disabled checked>
					  <input type="hidden" name="isEditable[]" value="1"></td>';
			}else{
				echo '<td align="center"><input class="editable" type="checkbox" name="isEditableCheckbox[]" disabled>
					  <input type="hidden" name="isEditable[]" value="0"></td>';
			}
			if ($isError){
				echo '<td style="color:red;font-size:20px">!!!</td>';
			}
			echo '<input type="hidden" name="fieldID[]" value="'.$fieldID.'">';
			echo '</tr>';
		}else{
			echo '<tr>
				  <td><button type="button" class="deleteButton">Delete</button></td>
				  <td><input type="text" name="fieldName[]" value="'.$fieldName.'"></td>
				  <td><textarea name="fieldDescription[]">'.$fieldDescription.'</textarea></td>
				  <td><input type="text" name="fieldType[]" value="'.$fieldTypeName.'"></td>';
			if ($isRequired){
				echo '<td align="center"><input class="required" type="checkbox" name="isRequiredCheckbox[]" checked>
					  <input class="hiddenRequired" type="hidden" name="isRequired[]" value="1"></td>';
			}else{
				echo '<td align="center"><input class="required" type="checkbox" name="isRequiredCheckbox[]">
					  <input class="hiddenRequired" type="hidden" name="isRequired[]" value="0"></td>';
			}	  
			echo '<td><input type="text" name="defaultValue[]" required="required" value="'.$defaultValue.'"></td>';	
			if ($isEditable){
				echo '<td align="center"><input class="editable" type="checkbox" name="isEditableCheckbox[]" checked>
					  <input type="hidden" name="isEditable[]" value="1"></td>';
			}else{
				echo '<td align="center"><input class="editable" type="checkbox" name="isEditableCheckbox[]">
					  <input type="hidden" name="isEditable[]" value="0"></td>';
			}
			if ($isError){
				echo '<td style="color:red;font-size:20px">!!!</td>';
			}
			echo '<input type="hidden" name="fieldID[]" value="'.$fieldID.'">';
			echo '</tr>';
		}
	}
	
	public function displayNewSystemForm($statusReport=NULL){
		//if adding system to existing group before submission
		if ($_POST["groupdropdown"]>0){
			$this->displayInitialNewSystemForm($_POST["groupdropdown"]);
			foreach($this->controller->getGroupFields($_POST["groupdropdown"]) as $groupField){
				$groupFieldID = $groupField->getFieldID();
				$groupFieldName = $groupField->getFieldName();
				$groupFieldDescription = $groupField->getFieldDescription();
				$groupFieldType = $this->controller->getTypeName($groupField->getFieldTypeID());
				$isRequired = $groupField->getIsRequired();
				$default = $groupField->getDefaultValue();
				$isEditable = $groupField->getIsEditable();
				$this->displayFieldTableRow($groupFieldID,$groupFieldName,$groupFieldDescription,$groupFieldType,$default,$isRequired,$isEditable);
			}
			$this->displaySystemSubmitFooter();
		}
		//if adding system to new group before submission
		elseif($_POST["groupdropdown"]==="0"){
			$this->displayInitialNewSystemForm($_POST["groupdropdown"]);
			$this->displaySystemSubmitFooter();
		}
		//if return to page due to error
		else{
			$this->displayInitialNewSystemForm($_POST["groupID"],$_POST["systemName"],$_POST["systemDescription"]);
			$fieldNames=$_POST["fieldName"];
			foreach($fieldNames as $key=>$fieldName){
				if ($key==$statusReport->getKey()){
					$this->displayFieldTableRow($_POST["fieldID"][$key],$fieldName,$_POST["fieldDescription"][$key],$_POST["fieldType"][$key],$_POST["defaultValue"][$key],$_POST["isRequired"][$key],$_POST["isEditable"][$key],true);
				}else{
					$this->displayFieldTableRow($_POST["fieldID"][$key],$fieldName,$_POST["fieldDescription"][$key],$_POST["fieldType"][$key],$_POST["defaultValue"][$key],$_POST["isRequired"][$key],$_POST["isEditable"][$key]);
				}
			}	 
			echo '<p style="color:red">'.$statusReport->getMessage().'</p>';
			$this->displaySystemSubmitFooter();	
		}
	}	
	
	
	public function displayFieldsTableRow($fieldID,$fieldName,$fieldDescription,$fieldTypeName,$defaultValue,$isRequired,$isEditable){
		echo '<tr id="'.$fieldID.'">
			  <td><button type="button" class="deleteButton">Delete Field</button>
			  <td><button class="globalChangeButton" type="button">Global Change</button></td>
			  <td>'.$fieldName.'</td>
			  <td>'.$fieldDescription.'</td>
			  <td>'.$fieldTypeName.'</td>';
		if ($isRequired){
			echo '<td><input class="required" type="checkbox" name="isRequiredCheckbox[]" checked>
				  <input class="hiddenRequired" type="hidden" name="isRequired[]" value="1"></td>';
		}else{
			echo '<td><input class="required" type="checkbox" name="isRequiredCheckbox[]">
				  <input class="hiddenRequired" type="hidden" name="isRequired[]" value="0"></td>';
		}
		echo '<td>'.$defaultValue.'</td>';		
		if ($isEditable){
			echo '<td><input class="editable" type="checkbox" name="isEditableCheckbox[]" disabled checked>
				  <input class="hiddenEditable" type="hidden" name="isEditable[]" value="1"></td>';
		}else{
			echo '<td><input class="editable" type="checkbox" name="isEditableCheckbox[]" disabled>
				  <input class="hiddenEditable" type="hidden" name="isEditable[]" value="0">';
		}
		echo '<input type="hidden" class="fieldID" name="fieldID[]" value="'.$fieldID.'"></td>';
		echo '</tr>';
	}
	
	public function displaySystemConfigsRow($configID,$configName,$configDescription,$isActive,$dateModified,$modifiedBy){
		echo '<tr id="'.$configID.'">';
		if ($isActive){
			echo '<td><input type="radio" name="active" class="active" value="'.$configID.'" checked></td>';
		}else{
			echo '<td><input type="radio" name="active" class="active" value="'.$configID.'"></td>';
		}
		echo '<td><a href="viewConfigs.php?configID='.$configID.'">'.$configName.'</td>
			  <td>'.$configDescription.'</td>
			  <td>'.$dateModified.'</td>
			  <td>'.$modifiedBy.'</td>
			  </tr>';
	}
	
	public function viewGroupFieldsTable(){
		
		echo '<form id="groupFieldsForm">';
		echo '<input id="groupIDField" type="hidden" name="groupID" value="'.$_GET["groupID"].'">';
		echo '<table class="display" id="groupFieldsTable">';
		echo '<thead>
			  <tr>
			  <th></th><th></th><th>Field</th><th>Description</th><th>Type</th><th>Required</th><th>Default</th><th>Editable</th>
			  </tr></thead><tbody>';
		$groupFields=$this->controller->getGroupFields($_GET["groupID"]);
		
		foreach($groupFields as $key=>$groupField){
			$this->displayFieldsTableRow($groupField->getFieldID(), $groupField->getFieldName(), $groupField->getFieldDescription(), $this->controller->getTypeName($groupField->getFieldTypeID()), $groupField->getDefaultValue(), $groupField->getIsRequired(), $groupField->getIsEditable());
		}
		echo '</tbody></table>';
		echo '<button type="button" id="addField">Add Field</button>';
		echo '</form>';	
	}

	public function viewSystemFieldsTable(){
		echo '<form id="systemFieldsForm">';
		echo '<input id="systemIDField" type="hidden" name="systemID" value="'.$_GET["systemID"].'">';
		echo '<table class="display" id="systemFieldsTable">';
		echo '<thead>
			  <tr>
			  <th></th><th></th><th>Field</th><th>Description</th><th>Type</th><th>Required</th><th>Default</th><th>Editable</th>
			  </tr></thead><tbody>';
		$systemFields=$this->controller->getSystemFields($_GET["systemID"]);
		
		foreach($systemFields as $key=>$systemField){
			$this->displayFieldsTableRow($systemField->getFieldID(), $systemField->getFieldName(), $systemField->getFieldDescription(), $this->controller->getTypeName($systemField->getFieldTypeID()), $systemField->getDefaultValue(), $systemField->getIsRequired(), $systemField->getIsEditable());
		}
		echo '</tbody></table>';
		echo '<button type="button" id="addField">Add Field</button>';
		echo '</form>';	
	}
	
	public function viewSystemConfigsTable($systemID){
		echo '<table id="configsTable" border="0">';
		echo '<input type="hidden" id="systemID" value="'.$systemID.'">';
		echo '<thead>
			  <tr>
			  <th>Active</th><th>Name</th><th>Description</th><th>Date Modified</th><th>ModifiedBy</th>
			  </tr></thead><tbody>';
		$systemConfigs = $this->controller->getSystemConfigs($systemID);
		/*echo '<pre>';
		print_r($systemConfigs);
		echo '</pre>';*/
		foreach($systemConfigs as $key=>$systemConfig){
			$this->displaySystemConfigsRow($systemConfig->getConfigID(),$systemConfig->getConfigName(),$systemConfig->getConfigDescription(),$systemConfig->getIsActive(),$this->controller->getMostRecentConfigChange($systemConfig->getConfigID())->getModifiedDate(),$this->controller->getMostRecentConfigChange($systemConfig->getConfigID())->getModifiedBy());
		}
		echo '</table>';
		echo '<form id="addNewConfigForm" method="post" action="addConfig.php"><button id="addNewConfig" name="newConfigSystemID" value="'.$systemID.'">Add New Config</button></form>';
		
	}
	
	public function displayInitialNewConfigForm($systemID,$configName=NULL,$configDescription=NULL){
		echo '<form id="newConfigForm" method="post">
			  <label for="configName">Configuration Name</label>
			  <input type="text" required="required" name="configName" value="'.$configName.'"><br>
			  <label for="configDescription">Description</label>
			  <input type="text" required="required" name="configDescription" value="'.$configDescription.'"><br>
			  <input id="systemID" type="hidden" name="systemID" value="'.$systemID.'">
			  <table class="display" id="configTable">
			  <thead>
			  <tr>
			  <th></th><th>Field</th><th>Description</th><th>Type</th><th>Value</th><th></th>
			  </tr></thead><tbody>';
	}
	
	public function displayConfigSubmitFooter(){
		echo '</tbody></table>';
		echo '<button type="button" id="addField">Add Field</button>';
		echo '<button type="submit" action="addConfig.php" method="post" name="submitConfig">OK</button>';
		echo '<a href="javascript:history.back()"><button type="button">Cancel</button></a>';
		echo '</form>';
	}
	
	public function displayNewConfigRow($fieldID,$fieldName,$fieldDescription,$fieldTypeName,$defaultValue,$isRequired,$isEditable,$isError=false){
		if ($isEditable){
			echo '<tr>
			  <td><input type="hidden" name="isRequired[]" value="'.$isRequired.'">
			      <input type="hidden" name="isEditable[]" value="'.$isEditable.'"></td>
			  <td><input type="hidden" name="fieldName[]" value="'.$fieldName.'">'.$fieldName.'</td>
			  <td><input type="hidden" name="fieldDescription[]" value="'.$fieldDescription.'">'.$fieldDescription.'</td>
			  <td><input type="hidden" name="fieldType[]" value="'.$fieldTypeName.'">'.$fieldTypeName.'</td>';
			
			echo '<td><input type="text" name="value[]" required="required" value="'.$defaultValue.'"></td>';		
			
			if ($isError){
				echo '<td style="color:red;font-size:20px"><input type="hidden" class="fieldID" name="fieldID[]" value="'.$fieldID.'">!!!</td>';
			}else{
				echo '<td><input type="hidden" class="fieldID" name="fieldID[]" value="'.$fieldID.'"></td>';
			
			}
			echo '</tr>';
		}else{
			echo '<tr style="color:gray">
			  <td><input type="hidden" name="isRequired[]" value="'.$isRequired.'">
			      <input type="hidden" name="isEditable[]" value="'.$isEditable.'"></td>
			  <td><input type="hidden" name="fieldName[]" value="'.$fieldName.'">'.$fieldName.'</td>
			  <td><input type="hidden" name="fieldDescription[]" value="'.$fieldDescription.'">'.$fieldDescription.'</td>
			  <td><input type="hidden" name="fieldType[]" value="'.$fieldTypeName.'">'.$fieldTypeName.'</td>';
			
			echo '<td><input type="hidden" name="value[]" required="required" value="'.$defaultValue.'">'.$defaultValue.'</td>';		
			
			if ($isError){
				echo '<td style="color:red;font-size:20px"><input type="hidden" class="fieldID" name="fieldID[]" value="'.$fieldID.'">!!!</td>';
			}else{
				echo '<td><input type="hidden" class="fieldID" name="fieldID[]" value="'.$fieldID.'"></td>';
			
			}
			echo '</tr>';
		}
		
	}
	
	public function displayNewConfigForm($statusReport=NULL){
		
		//if adding system to existing group before submission
		if (isset($_POST["newConfigSystemID"])){
			
			$this->displayInitialNewConfigForm($_POST["newConfigSystemID"]);
			foreach($this->controller->getSystemFields($_POST["newConfigSystemID"]) as $systemField){
				if ($systemField->getIsRequired()){
					$systemFieldID = $systemField->getFieldID();
					$systemFieldName = $systemField->getFieldName();
					$systemFieldDescription = $systemField->getFieldDescription();
					$systemFieldType = $this->controller->getTypeName($systemField->getFieldTypeID());
					$systemFieldDefault = $systemField->getDefaultValue();
					$isEditable = $systemField->getIsEditable();
					$isRequired = $systemField->getIsRequired();
					$this->displayNewConfigRow($systemFieldID,$systemFieldName,$systemFieldDescription,$systemFieldType,$systemFieldDefault,$isRequired,$isEditable);
				}
			}
			$this->displayConfigSubmitFooter();
		}
		
		//if return to page due to error
		else{
			$this->displayInitialNewConfigForm($_POST["systemID"],$_POST["configName"],$_POST["configDescription"]);
			$fieldNames=$_POST["fieldName"];
			foreach($fieldNames as $key=>$fieldName){
				if ($key==$statusReport->getKey()){
					$this->displayNewConfigRow($_POST["fieldID"][$key],$fieldName,$_POST["fieldDescription"][$key],$_POST["fieldType"][$key],$_POST["value"][$key],$_POST["isRequired"][$key],$_POST["isEditable"][$key],true);
				}else{
					$this->displayNewConfigRow($_POST["fieldID"][$key],$fieldName,$_POST["fieldDescription"][$key],$_POST["fieldType"][$key],$_POST["value"][$key],$_POST["isRequired"][$key],$_POST["isEditable"][$key]);
				}
			}	 
			echo '<p style="color:red">'.$statusReport->getMessage().'</p>';
			$this->displayConfigSubmitFooter();	
		}
	}

	public function displayConfigsValueFieldRow($valueFieldID,$valueFieldName,$valueFieldDescription,$valueFieldTypeName,$isRequired,$isEditable,$value,$isError=false){
		if ($isEditable){
			echo '<tr class="editable">
			  <td></td>
			  <td>'.$valueFieldName.'</td>
			  <td>'.$valueFieldDescription.'</td>
			  <td>'.$valueFieldTypeName.'</td>';
			
			echo '<td>'.$value.'</td>';		
			
			if ($isError){
				echo '<td style="color:red;font-size:20px"><input type="hidden" class="valueFieldID" name="valueFieldID[]" value="'.$valueFieldID.'">!!!</td>';
			}else{
				echo '<td><input type="hidden" class="valueFieldID" name="valueFieldID[]" value="'.$valueFieldID.'"></td>';
			
			}
			echo '</tr>';
		}else{
			echo '<tr class="uneditable" style="color:gray">
			  <td></td>
			  <td>'.$valueFieldName.'</td>
			  <td>'.$valueFieldDescription.'</td>
			  <td>'.$valueFieldTypeName.'</td>';
			
			echo '<td>'.$value.'</td>';		
			
			if ($isError){
				echo '<td style="color:red;font-size:20px"><input type="hidden" class="valueFieldID" name="valueFieldID[]" value="'.$valueFieldID.'">!!!</td>';
			}else{
				echo '<td><input type="hidden" class="valueFieldID" name="valueFieldID[]" value="'.$valueFieldID.'"></td>';
			
			}
			echo '</tr>';
		}
		
	}
	
	public function viewConfigsTable($configID){
		echo '<table id="configTable" border="0">';
		echo '<input type="hidden" id="configID" value="'.$configID.'">';
		echo '<thead>
			  <tr>
			  <th></th><th>Name</th><th>Description</th><th>Type</th><th>Value</th><th></th>
			  </tr></thead><tbody>';
		$configValueFields = $this->controller->getConfigValueFields($configID);
		foreach($configValueFields as $key=>$valueField){
			$this->displayConfigsValueFieldRow($valueField->getValueFieldID(),$valueField->getFieldName(),$valueField->getFieldDescription(),$this->controller->getTypeName($valueField->getFieldTypeID()),$valueField->getIsRequired(),$valueField->getIsEditable(),$valueField->getValue());
		}
		echo '</table>';
		echo '<form id="viewChangeLog" method="get" action="viewChangeLog.php"><button id="viewChangeLog" name="configID" value="'.$configID.'">View Change Log</button></form>';
	}
	
	public function displayChangeLogRow($historicalFieldID,$fieldName,$fieldDescription,$oldValue,$newValue,$dateModified,$modifiedBy){
		echo '<tr>
			  <td><input type="hidden" name="historicalFieldID[]" vbalue="'.$historicalFieldID.'">'.$fieldName.'</td>
			  <td>'.$fieldDescription.'</td>
			  <td>'.$oldValue.'</td>
			  <td>'.$newValue.'</td>
			  <td>'.$dateModified.'</td>
			  <td>'.$modifiedBy.'</td>
			  </tr>';
	
	}
	public function displayChangeLog($configID){
		echo '<table id="changeLog" border="0">';
		echo '<input type="hidden" id="configID" value="'.$configID.'">';
		echo '<thead>
			  <tr>
			  <th>Name</th><th>Description</th><th>Old Value</th><th>New Value</th><th>Date Modified</th><th>Modified By</th>
			  </tr></thead><tbody>';
		$historicalFields = $this->controller->getHistoricalFields($configID);
		foreach($historicalFields as $key=>$historicalField){
			$this->displayChangeLogRow($historicalField->getHistoricalFieldID(),$historicalField->getFieldName(),$historicalField->getFieldDescription(),$historicalField->getOldValue(),$historicalField->getNewValue(),$historicalField->getModifiedDate(),$historicalField->getModifiedBy());
		}
		echo '</table>';
				
	}
}



?>