<?php
$serverName = "DESKTOP-DKGIRAK";
$database = "onlineshope";
$uid = "";
$pass = "";

$connection = [
    "Database" => $database,
    "Uid" => $uid,
    "PWD" => $pass
];
$con = sqlsrv_connect($serverName,$connection);


if(!$con)
 die(print_r(sqlsrv_errors(),true));



?>