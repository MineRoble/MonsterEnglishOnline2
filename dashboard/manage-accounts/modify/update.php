<?php
include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_config.php";

$user_idx = $_POST['user_idx'];
$user_id = strtolower($_POST['user_id']);
$user_name = $_POST['user_name'];
$user_teacher_idx = intval($_POST['user_teacher']);
$user_phone = $_POST['user_phone'];
$user_email = $_POST['user_email'];
$user_permission = intval($_POST['user_permission']);
$user_grade = intval($_POST['user_grade']);

if( !intval($user_idx) ) {
    $response = array(
        "header" => array(
            "result" => "error",
            "message" => "user_idx가 누락되었습니다."
        )
    );
    goto response_handling;
}
if ( strlen($user_id) < 4 || !preg_match('/^[a-zA-Z0-9]+$/', $user_id) ) {
    $response = array(
        "header" => array(
            "result" => "error",
            "message" => "아이디는 4자리 이상의 영문과 숫자로만 구성되어야 합니다."
        )
    );
    goto response_handling;
}
if( !is_int($user_teacher_idx) ) {
    $response = array(
        "header" => array(
            "result" => "error",
            "message" => "선생님이 올바르지 않습니다."
        )
    );
    goto response_handling;
}
if ( !preg_match("/^[0-9]{11,12}$/i", $user_phone) ) {
    $response = array(
        "header" => array(
            "result" => "error",
            "message" => "전화번호가 올바르지 않습니다."
        )
    );
    goto response_handling;
}
if ( !filter_var($user_email, FILTER_VALIDATE_EMAIL) && $user_email != "" ) {
    $response = array(
        "header" => array(
            "result" => "error",
            "message" => "이메일 주소가 올바르지 않습니다."
        )
    );
    goto response_handling;
}
if( !is_int($user_permission) ) {
    $response = array(
        "header" => array(
            "result" => "error",
            "message" => "Permission이 올바르지 않습니다."
        )
    );
    goto response_handling;
}
if( !is_int($user_grade) ) {
    $response = array(
        "header" => array(
            "result" => "error",
            "message" => "Grade가 올바르지 않습니다."
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

    $stmt = $pdo->prepare("UPDATE members SET id = :user_id , name = :user_name , teacher_idx = :teacher_idx , permission = :permission , phone = :user_phone , grade = :grade , email = :user_email WHERE idx = :user_idx");
    $stmt->bindValue(':user_id', $user_id);
    $stmt->bindValue(':user_name', $user_name);
    $stmt->bindValue(':user_phone', $user_phone);
    $stmt->bindValue(':teacher_idx', $user_teacher_idx);
    $stmt->bindValue(':permission', $user_permission);
    $stmt->bindValue(':grade', $user_grade);
    $stmt->bindValue(':user_email', $user_email);
    $stmt->bindValue(':user_idx', $user_idx);
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