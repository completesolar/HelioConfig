<?php
namespace CompleteSolar\HelioConfig;
date_default_timezone_set("America/Los_Angeles");
require_once "setIncludePath.php";
require_once "ConfigView.php";
//require_once "checkLogin.php";
$username = "dzuo";
$currentPath = array("Dashboard");


$view = new ConfigView($username,$currentPath);
$view->dashboardHead();
$view->startBody();
$view->setHeader();
$view->displayDashTable();
$view->displayAddNewSystem();
$view->endBody();


?>

