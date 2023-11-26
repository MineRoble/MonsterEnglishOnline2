<?php
include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_config.php";

$user_id = strtolower($_POST['user_id']);
$user_pwd = $_POST['user_pwd'];
$user_name = $_POST['user_name'];
$user_email = $_POST['user_email'];
$user_phone = $_POST['user_phone'];

if( !empty($_POST['user_newpwd']) ) {
    $user_newpwd = $_POST['user_newpwd'];
    if( $user_newpwd != $_POST["user_renewpwd"] ) {
        $response = array(
            "header" => array(
                "result" => "error",
                "message" => "새 비밀번호와 새 비밀번호 재입력이 일치하지 않습니다."
            )
        );
        goto response_handling;
    }
    if ( strlen($user_newpwd) < 8 || !preg_match('/^[a-zA-Z0-9!@#$%^&*]+$/', $user_newpwd) ) {
        $response = array(
            "header" => array(
                "result" => "error",
                "message" => "비밀번호는 대소문자, 숫자, 특수기호(!@#$%^&*?)로만 이루어진 8자리 이상이여야 합니다."
            )
        );
        goto response_handling;
    }
} else $user_newpwd = $user_pwd;

if( $_POST['privacy'] != "checked" ){
    $response = array(
        "header" => array(
            "result" => "error",
            "message" => "개인정보 수집 및 이용에 동의해주세요."
        )
    );
    goto response_handling;
}
if( $_POST['user-agreement'] != "checked" ){
    $response = array(
        "header" => array(
            "result" => "error",
            "message" => "서비스 이용약관에 동의해주세요."
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

try {
    $pdo = new PDO(
        "mysql:host="._db_address.";dbname="._db_name.";charset=utf8",
        _db_id,_db_pwd,
        array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false
        )
    );

    $stmt = $pdo->prepare("SELECT pwd FROM members WHERE idx = :user_idx");
    $stmt->bindValue(':user_idx', $_SESSION["user_idx"]);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if( !isset($result['pwd']) ) {
        $response = array(
            "header" => array(
                "result" => "error",
                "message" => "해당 계정의 정보를 확인할 수 없습니다."
            )
        );
        goto response_handling;
    }
    elseif (password_verify($user_pwd, $result['pwd'])) {
        if( !empty($_POST['user_newpwd']) ) {
            $stmt = $pdo->prepare("UPDATE members SET id = :user_id , pwd = :user_newpwd , name = :user_name , phone = :user_phone , email = :user_email WHERE idx = :idx");
            $hashedPwd = password_hash($user_newpwd, PASSWORD_DEFAULT);
            $stmt->bindValue(':user_newpwd', $hashedPwd);
        } else $stmt = $pdo->prepare("UPDATE members SET id = :user_id , name = :user_name , phone = :user_phone , email = :user_email WHERE idx = :idx");

        $stmt->bindValue(':user_id', $user_id);
        $stmt->bindValue(':user_name', $user_name);
        $stmt->bindValue(':user_phone', $user_phone);
        $stmt->bindValue(':user_email', $user_email);
        $stmt->bindValue(':idx', $_SESSION["user"]["idx"]);
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
    } else {
        $response = array(
            "header" => array(
                "result" => "error",
                "message" => "비밀번호가 올바르지 않습니다."
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