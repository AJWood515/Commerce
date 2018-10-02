<?php
$servername = "mysqlnorthwinds.mysql.database.azure.com";
$username = "alex@mysqlnorthwinds";
$password = "!Zzy2014";
$database ="northwind";

// PHP Data Objects(PDO) Sample Code:
try {
    $conn = new PDO("sqlsrv:server = tcp:northwindsdb.database.windows.net,1433; Database = northwinds", "alex", "!Zzy2014");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e) {
    print("Error connecting to SQL Server.");
    die(print_r($e));
}

// SQL Server Extension Sample Code:
$connectionInfo = array("UID" => "alex@northwindsdb", "pwd" => "{your_password_here}", "Database" => "northwinds", "LoginTimeout" => 30, "Encrypt" => 1, "TrustServerCertificate" => 0);
$serverName = "tcp:northwindsdb.database.windows.net,1433";
$conn = sqlsrv_connect($serverName, $connectionInfo);

?>
