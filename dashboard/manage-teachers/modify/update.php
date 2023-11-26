<?php
include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_config.php";

if( isset($_POST['teacher_name']) && isset($_POST['idx']) && isset($_POST['teacher_description']) ) {
    $teacher = array(
        "idx" => $_POST['idx'],
        "name" => $_POST['teacher_name'],
        "description" => $_POST['teacher_description'],
        "img" => ""
    );
    
    if( isset($_FILES["teacher_img"]) && is_uploaded_file( $_FILES['teacher_img']['tmp_name'] ) ) {
        
        if( $_FILES['teacher_img']['error'] != UPLOAD_ERR_OK ) {
            switch( $_FILES['teacher_img']['error'] ) {
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

        $savedname = date('YmdHis')."_".(microtime(true)*10000);
        $destination = $_SERVER["DOCUMENT_ROOT"]."/assets/teachers/".$savedname;

        if(move_uploaded_file($_FILES['teacher_img']['tmp_name'], $destination)) {
            $teacher["img"] = $destination;
            // $teacher["img"] = array(
            //     "name" => $_FILES['teacher_img']['name'],
            //     "size" => $_FILES['teacher_img']['size'],
            //     "savedname" => $savedname,
            //     "location" => $destination
            // );
        } else {
            $response = array(
                "header" => array(
                    "result" => "error",
                    "message" => "파일 업로드 실패 (파일명: ${$_FILES["teacher_img"]["name"]})"
                )
            );
            goto response_handling;
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

    $stmt = $pdo->prepare("UPDATE `teachers` SET name = :name , img = :img , description = :description WHERE idx = :idx");
    $stmt->bindValue(':name', $teacher["name"]);
    $stmt->bindValue(':img', $teacher["img"]);
    $stmt->bindValue(':description', $teacher["description"]);
    $stmt->bindValue(':idx', $teacher["idx"]);
    $stmt->execute();
    $updatedRowCount = $stmt->rowCount();
    
    if ($updatedRowCount > 0) {
        $response = array(
            "header" => array(
                "result" => "success",
                "message" => "정보 수정이 완료되었습니다."
            )
        );
    } else {
        $response = array(
            "header" => array(
                "result" => "error",
                "message" => "변경 사항이 없습니다."
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