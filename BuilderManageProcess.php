<?php

    $accessBuilder = '';
    if( $builderAuth == false )
       $accessBuilder = "No Access";
    $smarty->assign("accessBuilder",$accessBuilder);
    
    $smarty->assign("sort",$_GET['sort']);
    $smarty->assign("page",$_GET['page']);
    if ($_GET['mode'] == 'delete') {
        DeleteBuilder($_GET['builderid']);
    }

    if(isset($_GET['page'])) {
        $Page = $_GET['page'];
    } else {
        $Page = 1;
    }
    $RowsPerPage = '30';
    $PageNum = 1;
    if(isset($_GET['page'])) {
        $PageNum = $_GET['page'];
    }

    if($_POST['search']!='' && ($_POST['builders']!='')){   
            $Offset = 0;

    }else{
            $Offset = ($PageNum - 1) * $RowsPerPage;
    }
     $builderDataArr = array();
    if($_REQUEST['builderUrl']!=''){
        $qryFlg = " a.URL = '".$_REQUEST['builderUrl']."' and b.table_name = 'resi_builder' ";
    }
    elseif($_REQUEST['builders']!=''){
            $qryFlg = " a.BUILDER_NAME LIKE '".$_REQUEST['builders']."%' and b.table_name = 'resi_builder' ";
    }else{
            $qryFlg = " b.table_name = 'resi_builder' ";
    }
    $QueryMember = "Select a.*, b.META_TITLE, b.META_DESCRIPTION, b.META_KEYWORDS FROM ".RESI_BUILDER." a 
                    left join seo_data b on a.builder_id = b.table_id
                    WHERE  ".$qryFlg." and a.builder_status = 0 ORDER BY a.BUILDER_ID DESC";
    $QueryExecute 	= mysql_query($QueryMember) or die(mysql_error());
    $NumRows 		= mysql_num_rows($QueryExecute);
    $PagingQuery = "LIMIT $Offset, $RowsPerPage";
    $QueryExecute_1 = mysql_query($QueryMember." ".$PagingQuery) ;
    while ($dataArr2 = mysql_fetch_array($QueryExecute_1)){	
            array_push($builderDataArr, $dataArr2);
    }

    $link ='';
    if($_GET['builders'] != '')	{				
            $link .="&builders=".$_GET['builders']."";

    }

    if($_GET['search']!=''){
            $link.="&search=".$_GET['search']."";
    }


    if($_POST['builders'] != '')	{				
            $link.="&builders=".$_POST['builders']."";

    }

    if(isset($_POST['search']) && $_POST['search']== 'Search'){
            $link.="&search=".$_POST['search']."";
    }

    $MaxPage = (ceil($NumRows/$RowsPerPage))?ceil($NumRows/$RowsPerPage):'1' ;
    $Num = $_GET['num'];
    $Sort = $_GET['sort'];
    if ($PageNum > 1) {
            $Page = $PageNum - 1;
            $Prev = " <a href=\"$Self?page=$Page&sort=$Sort$link\">[Prev]</a> ";
            $First = " <a href=\"$Self?page=1&sort=$Sort$link\">[First Page]</a> ";
    } else {
            $Prev  = ' [Prev] ';
            $First = ' [First Page] ';
    }
    if ($PageNum < $MaxPage) {
            $Page = $PageNum + 1;
            $Next = " <a href=\"$Self?page=$Page&sort=$Sort$link\">[Next]</a> ";
            $Last = " <a href=\"$Self?page=$MaxPage&sort=$Sort$link\">[Last Page]</a> ";
    } else {
            $Next = ' [Next] ';
            $Last = ' [Last Page] ';
    }
    $Pagginnation = "<DIV align=\"left\"><font style=\"font-size:11px; color:#000000;\">" . $First . $Prev . " Showing page <strong>$PageNum</strong> of <strong>$MaxPage</strong> pages " . $Next . $Last . "</font></DIV>";
    $smarty->assign("Pagginnation", $Pagginnation);
    $smarty->assign("Pagginnation", $Pagginnation);
    $smarty->assign("Sorting", $Sorting);
    $smarty->assign("NumRows",$NumRows);
    $smarty->assign("builders",$_REQUEST['builders']);
    $smarty->assign("builderUrl",$_REQUEST['builderUrl']);
    $smarty->assign("builderDataArr", $builderDataArr);
    $smarty->assign("callerMessage", $_SESSION['callerMessage'][0]);
    $smarty->assign("mig_msg", $_SESSION['migration_success_message']);    
    unset($_SESSION['migration_success_message']);
    unset($_SESSION['callerMessage']);
?>
