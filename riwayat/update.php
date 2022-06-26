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
$tanggl_pinjam = $_POST['tanggl_pinjam'] ?? '';
$tanggal_pengembalian = $_POST['tanggal_pengembalian'] ?? '';

/**
 * Validation empty fields
 */
$isValidated = true;

if(empty($isbn)){
    $response['error'] = 'ISBN harus diisi';
    $isValidated = false;
}
if(empty($tanggl_pinjam)){
    $response['error'] = 'tanggl_pinjam harus diisi';
    $isValidated = false;
}
if(empty($tanggal_pengembalian)){
    $response['error'] = 'tanggal_pengembalian harus diisi';
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
    $queryCheck = "SELECT * FROM riwayat where isbn = :isbn";
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
    $query = "UPDATE riwayat SET tanggl_pinjam = :tanggl_pinjam, tanggal_pengembalian = :tanggal_pengembalian
WHERE isbn = :isbn";
    $statement = $dbConn->prepare($query);
    /**
     * Bind params
     */
    $statement->bindValue(":isbn", $isbn);
    $statement->bindValue(":tanggl_pinjam", $tanggl_pinjam);
    $statement->bindValue(":tanggal_pengembalian", $tanggal_pengembalian);
    
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