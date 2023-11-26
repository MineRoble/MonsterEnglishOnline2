<?php
include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_config.php";

if( !isset($_POST['idx']) ) {
    $response = array(
        "header" => array(
            "result" => "error",
            "message" => "필수 파라미터가 누락되었습니다."
        )
    );
    goto response_handling;
}

try {
    $pdo = new PDO(
        "mysql:host="._db_address.";dbname="._db_name.";charset=utf8",
        _db_id,_db_pwd,
        array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false
        )
    );

    $stmt = $pdo->prepare("DELETE FROM `teachers` WHERE idx = :idx");
    $stmt->bindValue(':idx', $_POST["idx"]);
    $stmt->execute();
    $updatedRowCount = $stmt->rowCount();
    
    if ($updatedRowCount == 1) {
        $response = array(
            "header" => array(
                "result" => "success",
                "message" => "교사 삭제가 완료되었습니다."
            )
        );
    } else {
        $response = array(
            "header" => array(
                "result" => "error",
                "message" => "오류가 발생했습니다. {$updatedRowCount}의 교사가 삭제되었습니다."
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