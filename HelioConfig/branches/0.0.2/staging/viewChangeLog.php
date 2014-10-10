<?php
namespace CompleteSolar\HelioConfig;
date_default_timezone_set("America/Los_Angeles");
require_once "setIncludePath.php";
require_once "ConfigView.php";
$username = "dzuo";
$currentPath = array("Dashboard","System Configs","View Config","View Change Log");
$view = new ConfigView($username, $currentPath);
$view->changeLogHead();
$view->startBody();
$view->setHeader();
$view->displayChangeLog($_GET["configID"]);
$view->endBody();

?>