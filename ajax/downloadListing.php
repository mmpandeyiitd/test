<?php

include("../appWideConfig.php");
include("../dbConfig.php");
include("../httpful.phar");

$size = 200000;
$cityId = filter_input(INPUT_GET, "cityId");
$filterArr = new stdClass();

$start = 0;

if (isset($cityId) && !empty($cityId) && ($cityId != "null") && ($cityId != "")) {
    $filterArr->and[] = array("equal" => array("cityId" => $cityId));
}


$sort = '"sort":{"field":"listingId","sortOrder":"DESC"}';
$filter = json_encode($filterArr);
$fields = '"fields":["errorMessage","imageCount","verified","seller","id","fullName","currentListingPrice","pricePerUnitArea","price","otherCharges","property","project","locality","suburb","city","label","name","builder","unitName","size","unitType","createdAt","projectId","propertyId","phaseId","updatedBy","sellerId","jsonDump","remark","homeLoanBankId","flatNumber","noOfCarParks","negotiable","transferCharges","plc","listingAmenities","amenity","amenityMaster","masterAmenityIds","floor","latitude","longitude","amenityDisplayName","isDeleted","bedrooms","bathrooms","amenityId","imagesCount","listingId","bookingStatusId","facing","direction","facingId","tower","towerId","towerName"]}';
$uriListing = RESALE_LISTING_API_V2_URL . '?selector={"paging":{"start":' . $start . ',"rows":' . $size . '},"filters":' . $filter . "," . $sort . "," . $fields . '}';

$tbsorterArr = array();
try {
    $responseLists = \Httpful\Request::get($uriListing)->send();
    if ($responseLists->body->statusCode == "2XX") {
        $data = $responseLists->body->data;
        $tbsorterArr['rows'] = array();
        foreach ($data as $index => $row) {
            $brokerName = "";
            $row->sellerId->id = $row->sellerId;
            if ($row->sellerId) {
                list($row->seller->brokerId, $row->seller->brokerName) = getBroker($row->sellerId);
            }
            if ($row->currentListingPrice->pricePerUnitArea != 0) {
                $price = "Price Per Unit Area - " . $row->currentListingPrice->pricePerUnitArea;
            } else {
                $price = "Price - " . $row->currentListingPrice->price;
            }
            if ($row->currentListingPrice->otherCharges != 0) {
                $price .= "<br>Other Charges - " . $row->currentListingPrice->otherCharges;
            }

            $data_rows = array(
                "Serial" => $start + $index + 1,
                "City" => $row->property->project->locality->suburb->city->label,
                "BrokerName" => $row->seller->brokerName,
                "ProjectId" => $row->property->project->projectId,
                "Project" => $row->property->project->name . ", " . $row->property->project->builder->name,
                "Listing" => $row->property->unitName . "-" . $row->property->size . "-" . $row->property->unitType,
                "Price" => $price,
                "ListingId" => $row->id,
                "CreatedDate" => date("Y-m-d", ($row->createdAt) / 1000),
                "Photo" => ($row->imageCount > 0) ? "Done" : "Not Done",
                "verified" => ($row->verified) ? "Yes" : "No",
                "floor" => ($row->floor) ? $row->floor : "",
                "flatNumber" => ($row->flatNumber) ? $row->flatNumber : "",
                "towerName" => ($row->tower->towerName) ? $row->tower->towerName : "",
                "plc" => $row->plc,
                "facingDirection" => $row->facing->direction,
                "errorMessage" => $row->errorMessage
            );
            array_push($tbsorterArr['rows'], $data_rows);
        }

        $pdf_content = "<table cellspacing=1 bgcolor='' cellpadding=0 width='100%' style='font-size:11px;font-family:tahoma,arial,verdana;vertical-align:middle;text-align:center;'>
    <tr bgcolor='#f2f2f2'>
    <td>Serial</td>
    <td>Listing Id</td>
    <td>Project Id</td>
    <td>Project Name</td>
    <td>City</td>
    <td>Broker Name</td>
    <td>Listing</td>
    <td>Tower Name</td>
    <td>Price</td>
    <td>Verified</td>
    <td>Floor</td>
    <td>Flat Number</td>
    <td>PLC</td>
    <td>Facing</td>
    <td>Error Message</td>
    <td>Created Date</td>
    <td>Photo</td>
    </tr>";
        foreach ($tbsorterArr['rows'] as $row) {
            $pdf_content .= "<tr  bgcolor='#FFFFFF' valign='top'><td>" . $row['Serial'] . "</td>";
            $pdf_content .= "<td align='center'>" . $row['ListingId'] . "</td>";
            $pdf_content .= "<td align='center'>" . $row['ProjectId'] . "</td>";
            $pdf_content .= "<td>" . $row['Project'] . "</td>";
            $pdf_content .= "<td>" . $row['City'] . "</td>";
            $pdf_content .= "<td>" . $row['BrokerName'] . "</td>";
            $pdf_content .= "<td>" . $row['Listing'] . "</td>";
            $pdf_content .= "<td>" . $row['towerName'] . "</td>";
            $pdf_content .= "<td align='center'>" . $row['Price'] . "</td>";
            $pdf_content .= "<td align='center'>" . $row['verified'] . "</td>";
            $pdf_content .= "<td align='center'>" . $row['floor'] . "</td>";
            $pdf_content .= "<td align='center'>" . $row['flatNumber'] . "</td>";
            $pdf_content .= "<td align='center'>" . $row['plc'] . "</td>";
            $pdf_content .= "<td align='center'>" . $row['facingDirection'] . "</td>";
            $pdf_content .= "<td align='center'>" . $row['errorMessage'] . "</td>";
            $pdf_content .= "<td align='center'>" . $row['CreatedDate'] . "</td>";
            $pdf_content .= "<td align='center'>" . $row['Photo'] . "</td></tr>";
        }
        $pdf_content .= "</table>";

        $filename = "excelreport-" . date('YmdHis') . ".xls";
        header('Content-type: application/ms-excel');
        header('Content-Disposition: attachment; filename=' . $filename);
        echo $pdf_content;
    }
} catch (Exception $ex) {
    die($ex->getMessage());
}

function getBroker($seller_id) {
    if ($seller_id) {
        $Sql = "SELECT c.name, c.id FROM company c inner join company_users cu on c.id=cu.company_id WHERE cu.user_id=" . $seller_id . " and c.status = 'Active' and cu.status='Active' ";
        $ExecSql = mysql_query($Sql) or die(mysql_error() . ' Error in fetching data from company_users');
        if (mysql_num_rows($ExecSql) > 0) {
            $Res = mysql_fetch_assoc($ExecSql);
            return array($Res['id'], $Res['name']);
        }
        return array(null, null);
    }
}
