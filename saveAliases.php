<?php
//echo "hi";
//die(hello);
error_reporting(1);
ini_set('display_errors','1');
include("smartyConfig.php");
include("appWideConfig.php");
include("dbConfig.php");
include("includes/configs/configs.php");
include("builder_function.php");
include("function/alias_functions.php");
AdminAuthentication();
// ajax call for attaching, removing and creating a tag
if($_POST['task']=='attachAlias'){
	//die("here");
	if(!empty($_POST['tableName']) && !empty($_POST['tableId']) && !empty($_POST['aliasTableId']) ){
		//die("he");
		$tbname = $_POST['tableName'];
		$tbid = $_POST['tableId'];
		
		$altbid = $_POST['aliasTableId'];
		attachAliases($tbname, $tbid, $altbid);
	}
}
else if($_POST['task']=='dettachAlias'){
	//die("here");
	if(!empty($_POST['tableName']) && !empty($_POST['tableId']) && !empty($_POST['aliasTableId']) ){
		//die("he");
		$tbname = $_POST['tableName'];
		$tbid = $_POST['tableId'];
		
		$altbid = $_POST['aliasTableId'];
		dettachAliases($tbname, $tbid, $altbid);
	}
}

else if($_POST['task']=='createAlias'){
	//die("here");
	if(!empty($_POST['tableName']) && !empty($_POST['tableId']) && !empty($_POST['aliasName']) ){
		//die("he");
		$tbname = $_POST['tableName'];
		$tbid = $_POST['tableId'];
		$altbname = $_POST['aliasName'];
		createAliases($tbname, $tbid, $altbname);
	}
}

else if($_POST['task']=='createLandmarkAlias'){
	//die("here");
	if( !empty($_POST['label']) && !empty($_POST['cityId'])  ){
		//die("he");
		$label = $_POST['label'];
		$cityid = $_POST['cityId'];
		createLandmarkAliases($label, $cityid);
	}
}

else if($_POST['task']=='attachSuburb'){
	//echo "here".$_POST['localityId'];
	if(!empty($_POST['localityId']) && !empty($_POST['suburbId']) ){
		//die("he");
		$suburbId = $_POST['suburbId'];
		$localityId = $_POST['localityId'];
		//echo $suburbId;
		attachSuburbs($suburbId, $localityId);
	}
}
else if($_POST['task']=='dettachSuburb'){
	//echo "here".$_POST['localityId'];
	if(!empty($_POST['localityId']) && !empty($_POST['suburbId']) ){
		//die("he");
		$suburbId = $_POST['suburbId'];
		$localityId = $_POST['localityId'];
		//echo $suburbId;
		dettachSuburbs($suburbId, $localityId);
	}
}

else{
if(!empty($_POST['aliasname']))
{
	$aliasName   = $_POST['aliasname'];
    
	 saveAliases($aliasName);


}
else echo "4";
}

?> 