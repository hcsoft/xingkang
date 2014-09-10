<?php
/**
 * Created by PhpStorm.
 * User: oyx
 * Date: 14-8-26
 * Time: 下午5:40
 */

try {
    $conn = new PDO( "sqlsrv:Server=127.0.0.1,1433;Database=pmhs_km", 'sa', '11111111');
    $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
}

catch( PDOException $e ) {
    die( "Error connecting to SQL Server" );
}

return $conn;