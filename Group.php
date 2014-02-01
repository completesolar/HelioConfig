<?php
namespace CompleteSolar\HelioConfig;

class Group{
	public $groupID;
	public $groupName;
	public $groupFields;
	public $systems;
	
	public function __construct($newID,$newName,$newSystems=array(),$newGroupFields=array()){
		$this->groupID = $newID;
		$this->groupName = $newName;
		$this->groupFields = $newGroupFields;
		$this->systems = $newSystems;
	}

	public function getGroupID(){
		return $this->groupID;
	}
	
	public function getGroupName(){
		return $this->groupName;
	}
		
	public function getGroupFields(){
		return $this->groupFields;
	}
	public function getSystems(){
		return $this->systems;
	}
}
?>