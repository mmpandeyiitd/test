<?php
error_reporting(1);
ini_set('display_errors','1');
include("smartyConfig.php");
include("appWideConfig.php");
include("dbConfig.php");
include("includes/configs/configs.php");
include("modelsConfig.php");
include("builder_function.php");
AdminAuthentication();

$aID = $_SESSION['adminId'];

$sql = "SELECT CLOUDAGENT_ID FROM proptiger_admin WHERE ADMINID=" . $aID . ";";
$result = mysql_query($sql);
$row = mysql_fetch_array($result);
$agentId = $row['CLOUDAGENT_ID'];
$projectType = $_REQUEST['projectType'];
$contactNo = $_REQUEST['contactNo'];
$campaign = $_REQUEST['campaign'];

$callDetail = new CallDetails(array('AgentId'=>$aID, 'PROJECT_TYPE'=>$projectType, 'ContactNumber'=>$contactNo, 'CampaignName'=>$campaign));
$callDetail->save();
$callId= $callDetail->callid;

$url = "http://Kookoo.in/propTiger/manualDial.php?api_key=KK6553cb21f45e304ffb6c8c92a279fde5&customerNumber=" . $contactNo . "&uui=" . $callId . "&campaignName=" . $campaign . "&agentID=" . $agentId . "&username=proptiger";

$response = file_get_contents($url);
$xml = simplexml_load_string($response);

$sql = "update CallDetails set ApiResponse = '" . $xml[0] . "' where CallId = " . $callId;
mysql_query($sql);

if ($callId) 
  echo "call_" . $callId . "_" . $agentId;
else
  echo "Fail - $response";
?>