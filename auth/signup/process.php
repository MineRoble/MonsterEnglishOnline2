<?php
include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_config.php";

if( $_SESSION['login'] ) {
    header("Location: /");
    exit();
}

$user_id = strtolower($_POST['user_id']);
$user_pwd = $_POST['user_pwd'];
$user_repwd = $_POST['user_repwd'];
$user_name = $_POST['user_name'];
$user_phone = $_POST['user_phone'];

if ( !isset($_POST['user_email']) ) {
    $user_email = '';
} else {
    $user_email = $_POST['user_email'];
}

if( $_POST['privacy'] != "checked" ){
    $response = array(
        "header" => array(
            "status" => "error",
            "message" => "개인정보 수집 및 이용에 동의해주세요."
        )
    );
    goto response_handling;
}
if( $_POST['user-agreement'] != "checked" ){
    $response = array(
        "header" => array(
            "status" => "error",
            "message" => "서비스 이용약관에 동의해주세요."
        )
    );
    goto response_handling;
}

if ( strlen($user_id) < 4 || !preg_match('/^[a-zA-Z0-9]+$/', $user_id) ) {
    $response = array(
        "header" => array(
            "status" => "error",
            "message" => "아이디는 4자리 이상의 영문과 숫자로만 구성되어야 합니다."
        )
    );
    goto response_handling;
}
if ( strlen($user_pwd) < 8 || !preg_match('/^[a-zA-Z0-9!@#$%^&*]+$/', $user_pwd) ) {
    $response = array(
        "header" => array(
            "status" => "error",
            "message" => "비밀번호는 대소문자, 숫자, 특수기호(!@#$%^&*?)로만 이루어진 8자리 이상이여야 합니다."
        )
    );
    goto response_handling;
}
if ( !preg_match("/^[0-9]{11,12}$/i", $user_phone) ) {
    $response = array(
        "header" => array(
            "status" => "error",
            "message" => "전화번호가 올바르지 않습니다."
        )
    );
    goto response_handling;
}
if( $user_pwd != $user_repwd ) {
    $response = array(
        "header" => array(
            "status" => "error",
            "message" => "비밀번호와 비밀번호 재입력이 일치하지 않습니다."
        )
    );
    goto response_handling;
}
if ( !filter_var($user_email, FILTER_VALIDATE_EMAIL) && $user_email != "" ) {
    $response = array(
        "header" => array(
            "status" => "error",
            "message" => "이메일 주소가 올바르지 않습니다."
        )
    );
    goto response_handling;
}

// $db_conn = mysqli_connect(_db_address, _db_id, _db_pwd, _db_name);
// if($db_conn) {
//     mysqli_set_charset($db_conn, "utf8");

//     $stmt = mysqli_prepare($db_conn, "SELECT id FROM members WHERE id=?");
//     if( !$stmt ) {
//         $response = array(
    // "header" => array(
    //             "status" => "error",
    //             "message" => "이미 존재하는 아이디입니다."
// )
//         );
//         goto response_handling;
//     }
//     mysqli_stmt_bind_param($stmt, "s", $user_id);
//     mysqli_stmt_execute($stmt);
//     mysqli_stmt_store_result($stmt);
    
//     if (mysqli_stmt_num_rows($stmt) > 0) {
//         $response = array(
    // "header" => array(
    //             "status" => "error",
    //             "message" => "이미 존재하는 아이디입니다."
// )
//         );
//         goto response_handling;
//     }

//     $created_at = date("Y-m-d H:i:s");
//     $hash_pwd = password_hash($user_pwd, PASSWORD_DEFAULT);
//     $stmt = mysqli_prepare($db_conn, "INSERT INTO `members` (id, pwd, name, phone, email, created_at) VALUES (?, ?, ?, ?, ?, ?)");
//     mysqli_stmt_bind_param($stmt, "ssssss", $user_id, $hash_pwd, $user_name, $user_phone, $user_email, $created_at);
//     mysqli_stmt_execute($stmt);
    
//     if (mysqli_stmt_affected_rows($stmt) > 0) {
//         $response = array(
    // "header" => array(
    //             "status" => "success",
    //             "message" => "회원가입에 성공하였습니다."
// )
//         );
//     } else {
//         $response = array(
    // "header" => array(
    //             "status" => "error",
    //             "message" => "회원가입 중 오류가 발생하였습니다.",
// )
//             "debug" => mysqli_error($db_conn)
//         );
//     }
//     goto response_handling;
// }

// PDO 객체로 데이터베이스 연결 중 에러 캐치하기 위함.
try {
    $pdo = new PDO(
        "mysql:host="._db_address.";dbname="._db_name.";charset=utf8",
        _db_id,_db_pwd,
        array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false
        )
    );

    $stmt = $pdo->prepare("SELECT id FROM members WHERE id = :user_id");
    $stmt->bindValue(':user_id', $user_id);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($result) > 0) {
        $response = array(
            "header" => array(
                "status" => "error",
                "message" => "이미 존재하는 아이디입니다."
            )
        );
        goto response_handling;
    }

    $stmt = $pdo->prepare("INSERT INTO `members` (id, pwd, name, teacher_idx, grade, permission, phone, email, created_at) VALUES ( :user_id, :hash_pwd, :user_name, :teacher_idx, :grade, :permission, :user_phone, :user_email, :created_at)");
    $stmt->bindValue(':user_id', $user_id);
    $hash_pwd = password_hash($user_pwd, PASSWORD_DEFAULT);
    $stmt->bindValue(':hash_pwd', $hash_pwd);
    $stmt->bindValue(':user_name', $user_name);
    $teacher_idx = -1;
    $stmt->bindValue(':teacher_idx', $teacher_idx);
    $user_grade = 0;
    $stmt->bindValue(':grade', $user_grade);
    $user_permission = 0;
    $stmt->bindValue(':permission', $user_permission);
    $stmt->bindValue(':user_phone', $user_phone);
    $stmt->bindValue(':user_email', $user_email);
    $created_at = date("Y-m-d H:i:s");
    $stmt->bindValue(':created_at', $created_at);
    $result = $stmt->execute();

    if ($result) {
        $response = array(
            "header" => array(
                "status" => "success",
                "message" => "회원가입에 성공하였습니다."
            )
        );
        goto response_handling;
    } else {
        // $errorInfo = $stmt->errorInfo();
        // $errorMessage = $errorInfo[2];
        $response = array(
            "header" => array(
                "status" => "error",
                "message" => "회원가입 중 오류가 발생하였습니다."
            )
            // "debug" => $errorMessage
        );
    }
} catch (PDOException $e) {
    $response = array(
        "header" => array(
            "status" => "error",
            "message" => "회원가입 중 오류가 발생하였습니다."
        )
    );
    // echo $e->getMessage(); // 예외에 대한 메시지를 반환합니다.
    // // SQLSTATE[HY000] [1049] Unknown database 'hhneweng'

    // echo $e->getCode(); // 예외에 대한 코드를 반환합니다.
    // // 1049

    // echo $e->getFile(); // 예외가 발생한 파일의 경로를 반환합니다.
    // // /volume2/docker/codeserver_newEng/workspace/auth/signup/process.php

    // echo $e->getLine(); // 예외가 발생한 줄 번호를 반환합니다.
    // // 117

    // echo $e->getTrace(); // 예외가 발생한 과정에 대한 추적 정보를 배열로 반환합니다.
    // // [
    // //     {
    // //         "file": "\/volume2\/docker\/codeserver_newEng\/workspace\/auth\/signup\/process.php",
    // //         "line": 117,
    // //         "function": "__construct",
    // //         "class" :"PDO",
    // //         "type": "->",
    // //         "args": [
    // //             "mysql:host=localhost;dbname=hhneweng;charset=utf8",
    // //             "root",
    // //             "데이터베이스비밀번호",
    // //             {
    // //                 "3": 2,
    // //                 "20": false
    // //             }
    // //         ]
    // //     }
    // // ]
}

response_handling:

// if($db_conn) mysqli_close($db_conn);
if( isset($response['debug']) && _server_type != "dev") unset($response['debug']);

header('Content-type: application/json');
echo json_encode($response);