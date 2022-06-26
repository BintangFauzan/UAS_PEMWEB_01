<?php
    $dbHost="localhost";  
    $dbName="test";  
    $dbUser="root";      //by default root is user name.  
    $dbPassword="";     //password is blank by default  
    $dsn = "mysql:host={$dbHost};dbname={$dbName}";
    
    $dbConn = null;

    try{  
        $dbConn= new PDO($dsn, $dbUser, $dbPassword);
         
    } catch(Exception $e){  
    Echo "Koneksi Gagal" . $e->getMessage();  
    }  
