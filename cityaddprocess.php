<?php

$cityid = $_REQUEST['cityid'];
$smarty->assign("cityid", $cityid);

if(isset($_POST['btnExit'])){
	header("Location:CityList.php?page=1&sort=all");
}

if (isset($_POST['btnSave'])) {

		$txtCityName			=	trim($_POST['txtCityName']);
		$txtCityUrl				=	trim($_POST['txtCityUrl']);
		$DisplayOrder			=	trim($_POST['DisplayOrder']);
		$txtMetaTitle			=	trim($_POST['txtMetaTitle']);
		$txtMetaKeywords		=	trim($_POST['txtMetaKeywords']);
		$txtMetaDescription		=	trim($_POST['txtMetaDescription']);
		$status					=	trim($_POST['status']);
		$desc					=	trim($_POST['desc']);	
		 
		if( $txtCityName == '')  {
			 $ErrorMsg["txtCityName"] = "Please enter City name.";
		   }
		if( $txtCityUrl == '')   {
			 $ErrorMsg["txtCityUrl"] = "Please enter city URL.";
		 } else {
				
				if(!preg_match('/^property-in-[a-z0-9\-]+\.php$/',$txtCityUrl)){
					$ErrorMsg["txtCityUrl"] = "Please enter a valid url that contains only small characters, numerics & hyphen";
				}
			}
		
		if( $DisplayOrder == '')   {
			 $ErrorMsg["DisplayOrder"] = "Please enter display order.";
		   }
		if( $txtMetaTitle == '')   {
			 $ErrorMsg["txtMetaTitle"] = "Please enter meta title.";
		   }
		if( $txtMetaKeywords == '')  {
			 $ErrorMsg["txtMetaKeywords"] = "Please enter meta keywords.";
		   }
		if( $txtMetaDescription == '')  {
			 $ErrorMsg["txtMetaDescription"] = "Please enter meta description.";
		   }
		if( $desc == '')   {
			 $ErrorMsg["desc"] = "Please enter city description.";
		   }  

		/*******city url already exists**********/
			$qryCityUrl = "SELECT * FROM ".CITY." WHERE URL = '".$txtCityUrl."'";
			if($cityid != '')
				$qryCityUrl .= " AND CITY_ID != $cityid";
			echo $qryCityUrl;
			$resUrl     = mysql_query($qryCityUrl);
			if(mysql_num_rows($resUrl)>0)
			{
				$ErrorMsg["CtUrl"] = "This URL Already exists";
			}
		/*******end city url already exists*******/ 
	
	if(is_array($ErrorMsg)) {
		
	} 
	else if ($cityid == '') {	
		InsertCity($txtCityName, $txtCityUrl, $DisplayOrder,$txtMetaTitle,$txtMetaKeywords,$txtMetaDescription,$status,$desc);
		header("Location:CityList.php?page=1&sort=all");
		
	}else if($cityid!= ''){
	
		$updateQry = "UPDATE ".CITY." SET 
					  LABEL					=	'".$txtCityName."',
					  META_TITLE			=	'".$txtMetaTitle."',
					  META_KEYWORDS		    =	'".$txtMetaKeywords."',
					  META_DESCRIPTION		=	'".$txtMetaDescription."',
					  ACTIVE				=	'".$status."',
					  URL					=	'".$txtCityUrl."',
					  DISPLAY_ORDER			=	'".$DisplayOrder."',
					  DESCRIPTION			=	'".$desc."' WHERE CITY_ID='".$cityid."'";
		mysql_query($updateQry);
		header("Location:CityList.php?page=1&sort=all");
	}	
	
}

if($cityid!=''){

	$cityDetailsArray		=   ViewCityDetails($cityid);
	$txtCityName			=	trim($cityDetailsArray['LABEL']);
	$txtCityUrl				=	trim($cityDetailsArray['URL']);
	$DisplayOrder			=	trim($cityDetailsArray['DISPLAY_ORDER']);
	$txtMetaTitle			=	trim($cityDetailsArray['META_TITLE']);
	$txtMetaKeywords		=	trim($cityDetailsArray['META_KEYWORDS']);
	$txtMetaDescription		=	trim($cityDetailsArray['META_DESCRIPTION']);
	$status					=	trim($cityDetailsArray['ACTIVE']);
	$desc					=	trim($cityDetailsArray['DESCRIPTION']);

	
}
$smarty->assign("txtCityName", $txtCityName);
$smarty->assign("txtCityUrl", $txtCityUrl);
$smarty->assign("DisplayOrder", $DisplayOrder);
$smarty->assign("txtMetaTitle", $txtMetaTitle);
$smarty->assign("txtMetaKeywords", $txtMetaKeywords);
$smarty->assign("txtMetaDescription", $txtMetaDescription);
$smarty->assign("status", $status);	
$smarty->assign("desc", $desc);
$smarty->assign("ErrorMsg", $ErrorMsg);
 
?>
