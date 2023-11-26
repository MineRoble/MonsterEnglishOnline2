<?php
include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_config.php";

$user_idx = $_POST['user_idx'];

if( !intval($user_idx) ) {
    $response = array(
        "header" => array(
            "status" => "error",
            "message" => "user_idx가 누락되었습니다."
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

    $fp = fopen('/dev/urandom', 'rb');
    $user_newPwd = bin2hex(fread($fp, 4));
    fclose($fp);
    
    $user_hasedNewPwd = password_hash($user_newPwd, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("UPDATE members SET pwd = :pwd WHERE idx = :user_idx");
    $stmt->bindValue(':user_idx', $user_idx);
    $stmt->bindValue(':pwd', $user_hasedNewPwd);
    $stmt->execute();
    $updatedRowCount = $stmt->rowCount();
    
    if ($updatedRowCount > 0) {
        $response = array(
            "header" => array(
                "result" => "success",
                "message" => "임시 비밀번호 발급이 완료되었습니다."
            ),
            "body" => array(
                "newPwd" => $user_newPwd
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
            "status" => "error",
            "message" => "데이터베이스 오류가 발생하였습니다."
        )
    );
}

response_handling:
header('Content-type: application/json');
echo json_encode($response);