<?php
include '../koneksi.php';

header("Content-Type: application/json; charset=UTF-8");

$isbn = $_POST['isbn'];

$query =  "DELETE FROM `katalog_buku` WHERE ISBN = $isbn";
$execute = $dbConn->query($query);
$response = [];

if ($execute) {
    $response['status'] = 'succcess';
    $response['message'] = 'data berhasil dihapus';
} else {
    $response['status'] = 'failed';
    $response['message'] = 'data gagal dihapus';
}
$json = json_encode($response, JSON_PRETTY_PRINT);
echo $json;
