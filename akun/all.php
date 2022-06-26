<?php 
include '../koneksi.php';
header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Headers:*');

$query = "select * from user";

$statement = $dbConn -> query($query);
$statement->setfetchMode(PDO::FETCH_OBJ);
$result["akun"] = $statement->fetchALL();
header("Content-Type: application/json; charset=UTF-8");
echo json_encode($result);


?>
