<?php
$db = mysql_connect("localhost", "root", "root");
$dblink = mysql_select_db("project", $db);

// Makes db connection for activerecord orm
include_once "modelsConfig.php";

?>
