<?php
$errorDataArr = array();
$QueryMember = "
SELECT a.*,b.PROJECT_NAME, c.UNIT_NAME, c.UNIT_TYPE, c.SIZE, d.LABEL as CITY, e.LABEL as LOCALITY
FROM 
        proptiger.RESI_PROJECT_ERROR a LEFT JOIN 
        proptiger.RESI_PROJECT b ON a.PROJECT_ID = b.PROJECT_ID LEFT JOIN 
        proptiger.RESI_PROJECT_TYPES c ON a.PROJECT_TYPE_ID = c.TYPE_ID LEFT JOIN 
        proptiger.CITY d ON b.CITY_ID = d.CITY_ID LEFT JOIN 
        proptiger.LOCALITY e ON b.LOCALITY_ID = e.LOCALITY_ID
ORDER BY ID DESC";
$QueryExecute = mysql_query($QueryMember) or die(mysql_error());
$NumRows 	  = mysql_num_rows($QueryExecute);
while ($dataArr2 = mysql_fetch_assoc($QueryExecute)){	
    array_push($errorDataArr, $dataArr2);
}
//echo "<pre>";print_r($errorDataArr);
$smarty->assign("errorDataArr", $errorDataArr);
?>
