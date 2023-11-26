<?php
include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_config.php";

if( isset($_POST['article_title']) && isset($_POST['article_body']) ) {
    $article = array(
        "title" => $_POST['article_title'],
        "body" => $_POST['article_body'],
        "files" => array()
    );
    
    if(isset($_FILES["article_files"])) {
        for($i=0; $i<count($_FILES["article_files"]["name"]); $i++) {

            if( !is_uploaded_file( $_FILES['article_files']['tmp_name'][$i] ) ) continue;
            
            if( $_FILES['article_files']['error'][$i] != UPLOAD_ERR_OK ) {
                switch( $_FILES['article_files']['error'][$i] ) {
                    case UPLOAD_ERR_INI_SIZE:
                    case UPLOAD_ERR_FORM_SIZE:
                        $response = array(
                            "header" => array(
                                "result" => "error",
                                "message" => "파일이 너무 큽니다. ($error)"
                            )
                        );
                        break;
                    case UPLOAD_ERR_NO_FILE:
                        $response = array(
                            "header" => array(
                                "result" => "error",
                                "message" => "파일이 첨부되지 않았습니다. ($error)"
                            )
                        );
                        break;
                    default:
                        $response = array(
                            "header" => array(
                                "result" => "error",
                                "message" => "파일이 제대로 업로드되지 않았습니다. ($error)"
                            )
                        );
                        break;
                }
                goto response_handling;
            }

            $savedname = date('YmdHis')."_".(microtime(true)*10000)."_".$i;
            $destination = $_SERVER["DOCUMENT_ROOT"]."/homeworks/files/".$savedname;

            if(move_uploaded_file($_FILES['article_files']['tmp_name'][$i], $destination)) {
                array_push($article["files"], array(
                    "name" => $_FILES['article_files']['name'][$i],
                    "size" => $_FILES['article_files']['size'][$i],
                    "savedname" => $savedname,
                    "location" => $destination
                ));
            } else {
                $response = array(
                    "header" => array(
                        "result" => "error",
                        "message" => "파일 업로드 실패 (파일명: ${$_FILES["article_files"]["name"]})"
                    )
                );
                goto response_handling;
            }
        }
    }
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

    $stmt = $pdo->prepare("INSERT INTO `homeworks_article` (title, writer, body, created_at, is_del) VALUES ( :title, :writer, :body, :created_at, 0)");
    $stmt->bindValue(':title', $article["title"]);
    $stmt->bindValue(':writer', $_SESSION['user_idx']);
    $stmt->bindValue(':body', $article["body"]);
    $now = date("Y-m-d H:i:s");
    $stmt->bindValue(':created_at', $now);
    $result = $stmt->execute();

    if ($result) {
        $article_idx = $pdo->lastInsertId();

        foreach($article["files"] as $file) {
            $stmt = $pdo->prepare("INSERT INTO `homeworks_files` (article, name, savedname, size, location) VALUES (:article, :name, :savedname, :size, :location)");
            $stmt->bindValue(':article', $article_idx);
            $stmt->bindValue(':name', $file["name"]);
            $stmt->bindValue(':savedname', $file["savedname"]);
            $stmt->bindValue(':size', $file["size"]);
            $stmt->bindValue(':location', $file["location"]);
            $stmt->execute();
        }

        $response = array(
            "header" => array(
                "result" => "success",
                "message" => "글 작성이 완료되었습니다."
            ),
            "body" => array(
                "idx" => $article_idx
            )
        );
        goto response_handling;
    } else {
        foreach($article["files"] as $file) {
            $stmt = $pdo->prepare("INSERT INTO `homeworks_files` (article, name, savedname, size, location) VALUES (-1, :name, :savedname, :size, :location)");
            $stmt->bindValue(':name', $file["name"]);
            $stmt->bindValue(':savedname', $file["savedname"]);
            $stmt->bindValue(':size', $file["size"]);
            $stmt->bindValue(':location', $file["location"]);
            $stmt->execute();
        }

        $response = array(
            "header" => array(
                "result" => "error",
                "message" => "데이터베이스 오류가 발생했습니다."
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