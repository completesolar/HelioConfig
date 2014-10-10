<?php
namespace CompleteSolar\HelioConfig;
date_default_timezone_set("America/Los_Angeles");
require_once "setIncludePath.php";
require_once "ConfigView.php";
$username = "dzuo";
$currentPath = array("Dashboard","System Configs");

if ($_GET["newActive"]){
	$controller = new ConfigController();
	$controller->configdb->startTransaction();
	$statusReport = $controller->newActiveConfig($_GET["systemID"],$_GET["activeConfigID"]);
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
$view->viewSystemConfigsHead();
$view->startBody();
$view->setHeader();
$view->viewSystemConfigsTable($_GET["systemID"]);
$view->endBody();

?>