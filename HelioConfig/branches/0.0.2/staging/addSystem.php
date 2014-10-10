<?php
namespace CompleteSolar\HelioConfig;
date_default_timezone_set("America/Los_Angeles");
require_once "setIncludePath.php";
require_once "ConfigView.php";
require_once "ConfigController.php";
$username = "dzuo";
$currentPath = array("Dashboard","Add System");
$statusReport = NULL;

if (isset($_POST["submitSystem"])){
	$controller = new ConfigController();
	$controller->configdb->startTransaction();
	$typeIDList = array();
	foreach($_POST["fieldType"] as $typeName){
		$typeIDList[]=$controller->getTypeID($typeName);
	}
	$statusReport=$controller->submitSystemToDB($_POST["groupID"], $_POST["systemName"], $_POST["systemDescription"], $_POST["fieldID"], $_POST["fieldName"], $_POST["fieldDescription"], $typeIDList, $_POST["defaultValue"], $_POST["isRequired"], $_POST["isEditable"]);
	if ($statusReport->getMessage()=="SUCCESS"){
		$controller->configdb->commitTransaction();
		header("Location: dashboard.php");
	}
}
print_r($_SESSION["directory"]);
$view = new ConfigView($username,$currentPath);
$view->addSystemHead();
$view->startBody();
$view->setHeader();
$view->displayNewSystemForm($statusReport);
$view->endBody();

?>