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

 //$id = $_POST['id'] ?? '';
 $isbn = $_POST['isbn'] ?? '';
 $username = $_POST['username'] ?? '';
 $tanggl_pinjam = $_POST['tanggl_pinjam']  ?? '';
 $tanggal_pengembalian = $_POST['tanggal_pengembalian'] ?? '';
 $judul = $_POST['judul'] ?? '';

$validasi = true;
// if(empty($id)){
//     $response['error'] = 'id harus diisi';
//     $validasi = false;
// }
if(empty($isbn)){
    $response['error'] = 'isbn harus diisi';
    $validasi = false;
}
if(empty($username)){
    $response['error'] = 'username harus diisi';
    $validasi = false;
}
if(empty($tanggl_pinjam)){
    $response['error'] = 'tanggl_pinjam harus diisi';
    $validasi = false;
}
if(empty($tanggal_pengembalian)){
    $response['error'] = 'tanggal_pengembalian harus diisi';
    $validasi = false;
}
if(empty($judul)){
    $response['error'] = 'judul harus diisi';
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
    $query = "INSERT INTO riwayat (isbn, username, tanggl_pinjam, tanggal_pengembalian, judul) 
VALUES (:isbn, :username, :tanggl_pinjam, :tanggal_pengembalian, :judul)";
    $statement = $dbConn->prepare($query);

    // $statement->bindValue(":id", $id);
    $statement->bindValue(":isbn", $isbn);
    $statement->bindValue(":username", $username);
    $statement->bindValue(":tanggl_pinjam", $tanggl_pinjam);
    $statement->bindValue(":tanggal_pengembalian", $tanggal_pengembalian);
    $statement->bindValue(":judul", $judul);

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