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
$conn = sqlsrv_connect($serverName,$connection);

if(!$conn)
 die(print_r(sqlsrv_errors(),true));



?>