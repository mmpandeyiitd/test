<?php

	error_reporting(1);
ini_set('display_errors','1');
include("smartyConfig.php");
include("appWideConfig.php");

include("dbConfig.php");

include("modelsConfig.php");

include("includes/configs/configs.php");

include("builder_function.php");
//die("here");
include("function/functions_priority.php");
include("function/alias_functions.php");
//die("here");
AdminAuthentication();
//die("here");
    include('AliasMgmtProcess.php');
    //die("here");
	$smarty->display(PROJECT_ADD_TEMPLATE_PATH."header.tpl");
	$smarty->display(PROJECT_ADD_TEMPLATE_PATH."aliasMgmt.tpl");
	
	$smarty->display(PROJECT_ADD_TEMPLATE_PATH."footer.tpl");
	
?>