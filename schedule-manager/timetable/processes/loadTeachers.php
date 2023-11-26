<?php
include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_config.php";

try {
    $pdo = new PDO(
        "mysql:host="._db_address.";dbname="._db_name.";charset=utf8",
        _db_id,_db_pwd,
        array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false
        )
    );

    $stmt = $pdo->prepare("SELECT idx, name FROM teachers");
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $response = array(
        "header" => array(
            "result" => "success",
            "message" => ""
        ),
        "body" => array(
            "teachers" => $result
        )
    );    
} catch (PDOException $e) {
    $response = array(
        "header" => array(
            "result" => "error",
            "message" => "A database error occurred."
        )
    );
}

response_handling:
header('Content-type: application/json');
echo json_encode($response);