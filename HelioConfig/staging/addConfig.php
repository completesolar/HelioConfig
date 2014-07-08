<?php
namespace CompleteSolar\HelioConfig;
date_default_timezone_set("America/Los_Angeles");
require_once "setIncludePath.php";
require_once "ConfigView.php";
require_once "ConfigController.php";
$username = "dzuo";
$currentPath = array("Dashboard","System Configs","Add Config");
$statusReport=NULL;

if (isset($_POST["submitConfig"])){
	$controller = new ConfigController();
	$controller->configdb->startTransaction();
	$statusReport=$controller->submitConfigToDB($_POST["systemID"], $_POST["configName"], $_POST["configDescription"], $_POST["fieldID"], $_POST["fieldName"], $_POST["fieldDescription"], $_POST["fieldType"], $_POST["value"]);
	if ($statusReport->getMessage()=="SUCCESS"){
		$controller->configdb->commitTransaction();
		header("Location: systemConfigs.php?systemID=".$_POST["systemID"]);
	}
}

$view = new ConfigView($username,$currentPath);
$view->addConfigHead();
$view->startBody();
$view->setHeader();
$view->displayNewConfigForm($statusReport);
$view->endBody();

?>