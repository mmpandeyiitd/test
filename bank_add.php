<?php
	include("smartyConfig.php");
	include("appWideConfig.php");
	include("dbConfig.php");
    include("modelsConfig.php");
	include("includes/configs/configs.php");
	include("builder_function.php");
	require_once("common/function.php");
    include("imageService/image_upload.php");
	AdminAuthentication();
	include('bank_add_process.php');
	$smarty->display(PROJECT_ADD_TEMPLATE_PATH."header.tpl");
	$smarty->display(PROJECT_ADD_TEMPLATE_PATH."bankadd.tpl");
	$smarty->display(PROJECT_ADD_TEMPLATE_PATH."footer.tpl");
?>
