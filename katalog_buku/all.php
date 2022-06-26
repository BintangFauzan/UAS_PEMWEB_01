<?php 
include '../koneksi.php';

$query = "select * from katalog_buku";

$statement = $dbConn -> query($query);
$statement->setfetchMode(PDO::FETCH_OBJ);
$result = $statement->fetchALL();
header("Content-Type: application/json; charset=UTF-8");
echo json_encode($result);


?>