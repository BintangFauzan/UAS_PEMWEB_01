<?php 
include '../koneksi.php';
$response = [
    'status' => false,
    'error' => '',
    'data' => []
];

//validasi method
if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    header('Content-Type: application/json');
    http_response_code(400);
    $response['error'] = 'POST method required';
    echo json_encode($response);
    exit();
}

 $username = $_POST['username'] ?? '';
 $password = $_POST['password'] ?? '';
 $ttl = $_POST['ttl'] ?? '';
 $alamat = $_POST['alamat']  ?? '';
 $jenis_kelamin = $_POST['jenis_kelamin'] ?? '';
 $no_hp = $_POST['no_hp'] ?? '';

$validasi = true;
if(empty($username)){
    $response['error'] = 'username harus diisi';
    $validasi = false;
}
if(empty($password)){
    $response['error'] = 'password harus diisi';
    $validasi = false;
}
if(empty($ttl)){
    $response['error'] = 'ttl harus diisi';
    $validasi = false;
}
if(empty($alamat)){
    $response['error'] = 'alamat harus diisi';
    $validasi = false;
}
if(empty($jenis_kelamin)){
    $response['error'] = 'jenis_kelamin harus diisi';
    $validasi = false;
}
if(empty($no_hp)){
    $response['error'] = 'no_hp harus diisi';
    $validasi = false;
}

//jika validasi gagal
if(!$validasi){
    header('Content-Type: application/json');
    echo json_encode($response);
    http_response_code(400);
    exit(0);
}
try{
    $query = "INSERT INTO user (username, password, ttl, alamat, jenis_kelamin, no_hp) 
VALUES (:username, :password, :ttl, :alamat, :jenis_kelamin, :no_hp)";
    $statement = $dbConn->prepare($query);

    $statement->bindValue(":username", $username);
    $statement->bindValue(":password", $password);
    $statement->bindValue(":ttl", $ttl);
    $statement->bindValue(":alamat", $alamat);
    $statement->bindValue(":jenis_kelamin", $jenis_kelamin);
    $statement->bindValue(":no_hp", $no_hp);

    $isOk = $statement->execute();
}catch (Exception $exception){
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
header('Content-Type: application/json');
echo json_encode($response);
?>