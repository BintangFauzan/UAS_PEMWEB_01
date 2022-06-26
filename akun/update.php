<?php 
include '../koneksi.php';

$response = [
	'status' => false,
	'error' => '',
	'data' => []
];

if($_SERVER['REQUEST_METHOD'] !== 'POST'){
	header('Content-Type: application/json');
	http_response_code(400);
	$response['error'] = 'POST method required';
	echo json_encode($response);
	exit();
}

//input data 

$password = $_POST['password'] ?? '';
$alamat = $_POST['alamat'] ?? '';
$no_hp = $_POST['alamat'] ?? '';

//validasi jika kolom yang diinputkan kosong
$validasi = true;

if (empty($password)) {
	$response['error'] = 'password harus diisi';
	$validasi = false;
}
if(empty($alamat)){
	$response['error'] = 'alamat harus diisi';
	$validasi = false;
}
if (empty($no_hp)) {
	$response['error'] = 'no hp harus diisi';
	$validasi = false;
}
//jika valiadasi gagal
if ($validasi) {
	header('Content-Type: application/json');
	echo json_encode($response);
	http_response_code(400);
	exit(0);
}

//query update
try{
	$kolom = [];
$query = "UPDATE user SET alamat = :alamat, no_hp = :no_hp WHERE password = :pasword";
$statement = $dbConn->prepare($query);

$statement->bindValue(":password",$password);
$statement->bindValue(":alamat",$alamat);
$statement->bindValue(":no_hp",$no_hp);

$isOk = $statement->execute();
}catch(Exception $exception){
	header('Content-Type: application/json');
	$response['error'] = $exception->getMessage();
	echo json_encode($response);
	http_response_code(400);
	exit(0);
}

if(!$isOk){
	$response['error'] = $statement->errorInfo();
	http_response_code(400);
}

$response['status'] = $isOk;
header("Content-Type: application/json");
echo json_encode($response);



?>