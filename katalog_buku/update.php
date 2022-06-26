<?php 

include '../koneksi.php';
$response = [
    'status' => false,
    'error' => '',
    'data' => []
];

/*
 * Validate http method
 */
if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    header('Content-Type: application/json');
    http_response_code(400);
    $response['error'] = 'POST method required';
    echo json_encode($response);
    exit();
}
/**
 * Get input data POST
 */
$isbn = $_POST['isbn'] ?? '';
$judul = $_POST['judul'] ?? '';
$pengarang = $_POST['pengarang'] ?? '';
$penerbit = $_POST['penerbit'] ?? '';
$tahun_terbit = $_POST['tahun_terbit'] ?? '';
$kategori = $_POST['kategori'] ?? '';

/**
 * Validation empty fields
 */
$isValidated = true;

if(empty($isbn)){
    $response['error'] = 'ISBN harus diisi';
    $isValidated = false;
}
if(empty($judul)){
    $response['error'] = 'JUDUL harus diisi';
    $isValidated = false;
}
if(empty($pengarang)){
    $response['error'] = 'PENGARANG harus diisi';
    $isValidated = false;
}
if(empty($penerbit)){
    $response['error'] = 'penerbit harus diisi';
    $isValidated = false;
}
if(empty($tahun_terbit)){
    $response['error'] = 'tahun_terbit harus diisi';
    $isValidated = false;
}
if(empty($kategori)){
    $response['error'] = 'kategori harus diisi';
    $isValidated = false;
}
/*
 * Jika filter gagal
 */
if(!$isValidated){
    header('Content-Type: application/json');
    echo json_encode($response);
    http_response_code(400);
    exit(0);
}
/**
 * METHOD OK
 * Validation OK
 * Check if data is exist
 */
try{
    $queryCheck = "SELECT * FROM katalog_buku where isbn = :isbn";
    $statement = $dbConn->prepare($queryCheck);
    $statement->bindValue(':isbn', $isbn);
    $statement->execute();
    $row = $statement->rowCount();
    /**
     * Jika data tidak ditemukan
     * rowcount == 0
     */
    if($row === 0){
        header('Content-Type: application/json');
        $response['error'] = 'Data tidak ditemukan ISBN '.$isbn;
        echo json_encode($response);
        http_response_code(400);
        exit(0);
    }
}catch (Exception $exception){
    header('Content-Type: application/json');
    $response['error'] = $exception->getMessage();
    echo json_encode($response);
    http_response_code(400);
    exit(0);
}

/**
 * Prepare query
 */
try{
    $fields = [];
    $query = "UPDATE katalog_buku SET judul = :judul, pengarang = :pengarang, penerbit = :penerbit, kategori = :kategori, tahun_terbit = :tahun_terbit 
WHERE isbn = :isbn";
    $statement = $dbConn->prepare($query);
    /**
     * Bind params
     */
    $statement->bindValue(":isbn", $isbn);
    $statement->bindValue(":judul", $judul);
    $statement->bindValue(":pengarang", $pengarang);
    $statement->bindValue(":penerbit", $penerbit);
    $statement->bindValue(":kategori", $kategori);
    $statement->bindValue(":tahun_terbit", $tahun_terbit);
    
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