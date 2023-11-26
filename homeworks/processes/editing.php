<?php
include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_config.php";

if( isset($_POST['article_title']) && isset($_POST['article_body']) && isset($_POST['idx']) ) {
    $article = array(
        "title" => $_POST['article_title'],
        "body" => $_POST['article_body']
    );
    if( !is_numeric($_POST['idx']) ) goto handleValidationFailure;
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

    $stmt = $pdo->prepare("UPDATE `homeworks_article` SET `title` = :title , `body` = :body WHERE idx = :idx");
    $stmt->bindValue(':title', $article["title"]);
    $stmt->bindValue(':body', $article["body"]);
    $stmt->bindValue(':idx', $_POST['idx']);
    $stmt->execute();
    $updatedRowCount = $stmt->rowCount();

    if ($updatedRowCount > 0) {
        $response = array(
            "header" => array(
                "result" => "success",
                "message" => "글 수정이 완료되었습니다."
            )
        );
        goto response_handling;
    } else {
        $response = array(
            "header" => array(
                "result" => "error",
                "message" => "변경사항이 없습니다."
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