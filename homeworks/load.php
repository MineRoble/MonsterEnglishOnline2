<?php
include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_config.php";

if( isset($_POST['itemPerPage']) && isset($_POST['page']) ) {
    if(is_numeric($_POST['itemPerPage'])) $itemPerPage = intval($_POST['itemPerPage']);
    else goto handleValidationFailure;

    if(is_numeric($_POST['page'])) $page = intval($_POST['page']);
    else goto handleValidationFailure;
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

    $limitSyntax = "LIMIT ".($itemPerPage*($page-1)).", ".$itemPerPage;
    $stmt = $pdo->prepare("SELECT idx, title, writer, created_at FROM homeworks_article WHERE is_del = 0 ORDER BY idx DESC $limitSyntax");
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare("SELECT COUNT(*) as cnt FROM homeworks_article WHERE is_del = 0");
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
                "message" => "",
            ),
            "body" => array(
                "articles" => array(),
                "count" => $count
            )
        );

        foreach($result as $item) {
            $stmt = $pdo->prepare("SELECT id, name FROM members WHERE idx = :idx");
            $stmt->bindValue(':idx', $item["writer"]);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            $stmt = $pdo->prepare("SELECT COUNT(*) as cnt FROM homeworks_files WHERE article = :idx");
            $stmt->bindValue(':idx', $item["idx"]);
            $stmt->execute();
            $files_count = $stmt->fetchColumn();

            array_push($response["body"]["articles"], array(
                "idx" => $item["idx"],
                "writer" => array(
                    "idx" => $item["writer"],
                    "id" => $user["id"],
                    "name" => $user["name"]
                ),
                "title" => $item["title"],
                "files_count" => $files_count,
                "created_at" => $item["created_at"]
            ));
        }
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