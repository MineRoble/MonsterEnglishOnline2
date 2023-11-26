<?php
include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_config.php";

if( isset($_GET['idx']) && isset($_GET['areyousure']) ) {
    if(is_numeric($_GET['idx'])) $articleIdx = intval($_GET['idx']);
    else goto handleValidationFailure;

    if($_GET['areyousure'] != "sure") goto handleValidationFailure;
} else {
    handleValidationFailure:
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

    $stmt = $pdo->prepare("UPDATE `homeworks_article` SET `is_del`=1 WHERE idx = :idx");
    $stmt->bindValue(':idx', $articleIdx);
    $stmt->execute();
    $updatedRowCount = $stmt->rowCount();
    
    if ($updatedRowCount > 0) {
        $response = array(
            "header" => array(
                "result" => "success",
                "message" => "글 삭제가 완료되었습니다."
            )
        );
    } else {
        $response = array(
            "header" => array(
                "result" => "error",
                "message" => "삭제할 글이 없습니다."
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