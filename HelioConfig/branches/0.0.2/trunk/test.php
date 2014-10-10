<?php
namespace CompleteSolar\HelioConfig;
require_once("ConfigController.php");
require_once("ConfigClient.php");
ini_set('display_errors', 'On');
error_reporting(E_ALL);
$systemID = 4;
$configName = "testing 2";
$configDescription = "just testing the add config page";
$fieldIDList = array(4,5,6,7,8,9,10,11,12);
$fieldNameList = array("offices","north_bay","san_diego","chino_hills","scheduling_days","max_distance","email_notification","later_day_penalty","meeting_duration");
$fieldDescriptionList = array("offices' names","address of the North Bay office","address of the San Diego office","address of the Chino Hills","how many days to check for availability","maximum distance from office to new appointment to check for availability","notification emails to report problems. Separate emails by "," e.g. a1@css.com,a2@css.com,a3@css.com","penalty for later days","duration of each meeting");
$fieldTypeNameList = array("list","string","string","string","int","point","list","point","point");
$valueList = array("0.3","0.7","1000","asdf","3","70","developers@completesolar.com","0.33","1.667");

$client = new ConfigClient("4");
$client->setUp();
$meetingDuration = $client->getProperty("offices");
var_dump($meetingDuration);
?>

