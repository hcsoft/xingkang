<?php
/**
 * Created by PhpStorm.
 * User: oyx
 * Date: 14-8-26
 * Time: 下午5:40
 */

$serverName = "127.0.0.1,1433";
//$serverName = "172.16.10.62,8433";

/* Get UID and PWD from application-specific files.  */
$connectionInfo = array( "UID"=>"sa",
    "PWD"=>'11111111',
    "Database"=>'pmhs_km');
//$connectionInfo = array( "UID"=>"kmwsj",
//    "PWD"=>'kmwsj2013',
//    "Database"=>'pmhs_km_zs');

/* Connect using SQL Server Authentication. */
$conn = sqlsrv_connect( $serverName, $connectionInfo);
if( $conn === false )
{
    echo "Unable to connect.</br>";
    die( print_r( sqlsrv_errors(), true));
}

return $conn;