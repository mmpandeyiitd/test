<?php

//error_reporting(1);
//ini_set('display_errors','1');

include("smartyConfig.php");
include("appWideConfig.php");
include("dbConfig.php");

include("modelsConfig.php");

include("includes/configs/configs.php");

include("listing_function.php");
//die("here");
include("function/functions_listing.php");
include("httpful.phar");

//die("here");

AdminAuthentication();
//die("here");
    include('ListingPriorityProcess.php');
    //die("here");
	$smarty->display(PROJECT_ADD_TEMPLATE_PATH."header.tpl");

	$smarty->display(PROJECT_ADD_TEMPLATE_PATH."ListingPriority.tpl");

	$smarty->display(PROJECT_ADD_TEMPLATE_PATH."footer.tpl");
	
?>
