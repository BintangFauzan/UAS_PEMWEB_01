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

 $isbn = $_POST['isbn'] ?? '';
 $judul = $_POST['judul'] ?? '';
 $pengarang = $_POST['pengarang'] ?? '';
 $penerbit = $_POST['penerbit']  ?? '';
 $tahun_terbit = $_POST['tahun_terbit'] ?? '';
 $kategori = $_POST['kategori'] ?? '';

$validasi = true;
if(empty($isbn)){
    $response['error'] = 'isbn harus diisi';
    $validasi = false;
}
if(empty($judul)){
    $response['error'] = 'judul harus diisi';
    $validasi = false;
}
if(empty($pengarang)){
    $response['error'] = 'pengarang harus diisi';
    $validasi = false;
}
if(empty($penerbit)){
    $response['error'] = 'penerbit harus diisi';
    $validasi = false;
}
if(empty($tahun_terbit)){
    $response['error'] = 'tahun_terbit harus diisi';
    $validasi = false;
}
if(empty($kategori)){
    $response['error'] = 'kategori harus diisi';
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
    $query = "INSERT INTO katalog_buku (isbn, judul, pengarang, penerbit, tahun_terbit, kategori) 
VALUES (:isbn, :judul, :pengarang, :penerbit, :tahun_terbit, :kategori)";
    $statement = $dbConn->prepare($query);

    $statement->bindValue(":isbn", $isbn);
    $statement->bindValue(":judul", $judul);
    $statement->bindValue(":pengarang", $pengarang);
    $statement->bindValue(":penerbit", $penerbit);
    $statement->bindValue(":tahun_terbit", $tahun_terbit);
    $statement->bindValue(":kategori", $kategori);

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