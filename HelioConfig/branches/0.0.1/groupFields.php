<?php

namespace CompleteSolar\HelioConfig;
date_default_timezone_set("America/Los_Angeles");
require_once "setIncludePath.php";
require_once "ConfigView.php";
$username = "dzuo";

$currentPath = array("Dashboard","Group Fields");

if ($_GET["globalChange"]){
	$controller = new ConfigController();
	$controller->configdb->startTransaction();
	$statusReport=$controller->submitGlobalGroupUpdate($_GET["fieldID"],$_GET["newValue"]);
	$result=array();
	if ($statusReport->getMessage()=="SUCCESS"){
		$controller->configdb->commitTransaction();
		$result["status"] = "success";
	}
	elseif(strpos($statusReport->getMessage(),'incorrect type') !== false){
		$result["status"]="Update value of incorrect type";
		header("HTTP/1.0 400");
	}else{
		$result["status"]="General error";
	}
	echo json_encode($result);
	exit();
}

if ($_GET["deleteField"]){
	$controller = new ConfigController();
	$controller->configdb->startTransaction();
	$statusReport=$controller->deleteGroupField($_GET["fieldID"]);
	$result=array();
	if ($statusReport->getMessage()=="SUCCESS"){
		$controller->configdb->commitTransaction();
		$result["status"] = "success";
	}else{
		$result["status"]="General error";
		header("HTTP/1.0 500");
	}
	echo json_encode($result);
	exit();
}

if ($_GET["updateField"]){
	$controller = new ConfigController();
	$controller->configdb->startTransaction();
	$statusReport=$controller->submitGroupFieldUpdate($_GET["groupID"],$_GET["fieldID"],$_GET["fieldName"],$_GET["fieldDescription"],$_GET["fieldTypeName"],$_GET["isRequired"],$_GET["defaultValue"],$_GET["isEditable"]);
	$result=array();
	if ($statusReport->getMessage()=="SUCCESS"){
		$controller->configdb->commitTransaction();
		$result["status"] = "success";
	}else{
		$result["status"]="General error";
		header("HTTP/1.0 500");
	}
	echo json_encode($result);
	exit();
}


$view = new ConfigView($username,$currentPath);
$view->viewGroupFieldsHead();
$view->startBody();
$view->setHeader();
$view->viewGroupFieldsTable();
$view->endBody();


?>