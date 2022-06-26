<?php 
include '../koneksi.php';
$response = [
    'status' => false,
    'error' => '',
    'data' => []
];

if($_SERVER['REQUEST_METHOD'] !== 'DELETE'){
    header('Content-Type: application/json');
    http_response_code(400);
    $response['error'] = 'DELETE method required';
    echo json_encode($response);
    exit();
}

/**
 * Get input data from RAW data
 */
$data = file_get_contents('php://input');
if($data === false || empty($data)){
    header('Content-Type: application/json');
    http_response_code(400);
    $response['error'] = 'Form data tidak tersedia';
    echo json_encode($response);
    exit();
}
/*
 * Parse data form ke dalam array
 */
parse_str($data, $res);
$isbn = $res['isbn'] ?? '';

/**
 *
 * Cek apakah ISBN tersedia
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
 * Hapus data
 */
try{
    $queryCheck = "DELETE FROM katalog_buku where isbn = :isbn";
    $statement = $dbConn->prepare($queryCheck);
    $statement->bindValue(':isbn', $isbn);
    $statement->execute();
    $response['status'] = true;
}catch (Exception $exception){
    header('Content-Type: application/json');
    $response['error'] = $exception->getMessage();
    echo json_encode($response);
    http_response_code(400);
    exit(0);
}

header('Content-Type: application/json');
echo json_encode($response);

?>