<?php

	include("smartyConfig.php");
	include("appWideConfig.php");
	include("dbConfig.php");
	include("includes/configs/configs.php");
	include("builder_function.php");
	AdminAuthentication();
	include('displayorderprocess.php');;
	//$smarty->display(SERVER_PATH."/smarty/templates/admin/crawler/header.tpl");
	$smarty->display(PROJECT_ADD_TEMPLATE_PATH."header.tpl");

	$smarty->display(PROJECT_ADD_TEMPLATE_PATH."displayorder.tpl");
	$smarty->display(PROJECT_ADD_TEMPLATE_PATH."footer.tpl");
	
?>

