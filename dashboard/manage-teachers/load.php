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

    $stmt = $pdo->prepare("SELECT * FROM `teachers`");
    $stmt->execute();
    $teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare("SELECT COUNT(*) as cnt FROM `teachers`");
    $stmt->execute();
    $count = $stmt->fetchColumn();

    
    if ($count === false) {
        $response = array(
            "header" => array(
                "result" => "error",
                "message" => "레코드 개수를 가져오는 중 오류가 발생했습니다."
            )
        );
    } else {
        $response = array(
            "header" => array(
                "result" => "success",
                "message" => ""
            ),
            "body" => array(
                "teachers" => $teachers,
                "count" => $count
            )
        );
    }
    
} catch (PDOException $e) {
    $response = array(
        "header" => array(
            "result" => "error",
            "message" => "데이터베이스 오류가 발생하였습니다."
        )
    );
}

response_handling:
header('Content-type: application/json');
echo json_encode($response);