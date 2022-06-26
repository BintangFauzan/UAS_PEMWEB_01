<?php 
include '../koneksi.php';

$query = "select * from riwayat";

$statement = $dbConn -> query($query);
$statement->setfetchMode(PDO::FETCH_OBJ);
$result = $statement->fetchALL();
header("Content-Type: application/json; charset=UTF-8");
echo json_encode($result);


?>