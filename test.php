<?php


exit();

$type = null;
if($type == "db reset") {
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

        $stmt = $pdo->prepare("DELETE FROM `coupons` WHERE 1");
        $stmt->execute();

        $stmt = $pdo->prepare("DELETE FROM `homeworks_article` WHERE 1");
        $stmt->execute();

        $stmt = $pdo->prepare("DELETE FROM `homeworks_files` WHERE 1");
        $stmt->execute();

        $stmt = $pdo->prepare("DELETE FROM `login_sessions` WHERE 1");
        $stmt->execute();

        $stmt = $pdo->prepare("DELETE FROM `schedule` WHERE 1");
        $stmt->execute();

        $stmt = $pdo->prepare("DELETE FROM `tosspayments` WHERE 1");
        $stmt->execute();
        
        $response = array(
            "header" => array(
                "result" => "success",
                "message" => "대충 리셋 됐겠지 뭐."
            )
        );
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
}