<?php

namespace CompleteSolar\HelioConfig;
date_default_timezone_set("America/Los_Angeles");
require_once "setIncludePath.php";
require_once "ConfigController.php";



$controller = new ConfigController();

if ($_GET["getOptionalFields"]){
	$systemFields = $controller->getSystemFields($_GET["systemID"]);
	$missingFields = array();
	foreach ($systemFields as $key=>$systemField){
		if (!in_array($systemField->getFieldID(),$_GET["existingFields"])){
			$missingFields[]=$systemField;
		}
	}
	echo json_encode($missingFields);
}

if ($_GET["getField"]){
	$status = $controller->configdb->getFieldFromID($_GET["fieldID"]);
	if (!$controller->isSuccess($status)){
		echo "Field does not exist";
		header("HTTP/1.0 400");
	}
	$row = $controller->configdb->result->fetch_array();
	$field = array();
	$field[]=$row["field_name"];
	$field[]=$row["field_description"];
	$dataType = $controller->getTypeName($row["data_type_id"]);
	$field[]=$dataType;
	$field[]=$row["default_value"];
	$field[]=$row["required"];
	$field[]=$row["editable"];
	echo json_encode($field);
}

?>