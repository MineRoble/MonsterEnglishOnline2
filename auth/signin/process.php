<?php
include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_config.php";

if( $_SESSION['login'] ) {
    header("Location: /");
    exit();
}

$user_id = strtolower($_POST['user_id']);
$user_pwd = $_POST['user_pwd'];

try {
    $pdo = new PDO(
        "mysql:host="._db_address.";dbname="._db_name.";charset=utf8",
        _db_id,_db_pwd,
        array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false
        )
    );

    $stmt = $pdo->prepare("SELECT idx, pwd, permission FROM members WHERE id = :user_id");
    $stmt->bindValue(':user_id', $user_id);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if (isset($result['pwd']) && password_verify($user_pwd, $result['pwd'])) {
        if($result["permission"] <= 0)  {
            $response = array(
                "header" => array(
                    "result" => "error",
                    "message" => "로그인 권한이 없습니다. 문의 바랍니다."
                )
            );
            goto response_handling;
        }

        $_SESSION['login'] = true;
        $_SESSION['user_idx'] = $result['idx'];


        // Create Login Session
        $fp = fopen('/dev/urandom', 'rb');
        $token = bin2hex(fread($fp, 1024));
        fclose($fp);

        $stmt = $pdo->prepare("INSERT INTO `login_sessions`(`user_idx`, `ipaddress`, `user_agent`, `created_at`, `expired_at`, `is_available`, `token`) VALUES (:user_idx, :ipaddress, :user_agent, :created_at, :expired_at, 1, :token)");
        $stmt->bindValue('user_idx', $result['idx']);
        $stmt->bindValue('ipaddress', $_SERVER["REMOTE_ADDR"]);
        $stmt->bindValue('user_agent', $_SERVER['HTTP_USER_AGENT']);
        $stmt->bindValue('created_at', date("Y-m-d H:i:s"));
        $stmt->bindValue('expired_at', date("Y-m-d H:i:s", strtotime("now + 7 days")));
        $stmt->bindValue('token', $token);

        $result = $stmt->execute();

        if($result !== false) setcookie("login_token", $token, time() + 604800, "/");


        $response = array(
            "header" => array(
                "result" => "success",
                "message" => "로그인에 성공했습니다."
            )
        );
    } else {
        $response = array(
            "header" => array(
                "result" => "error",
                "message" => "아이디 혹은 비밀번호가 올바르지 않습니다."
            )
        );
    }
} catch (PDOException $e) {
    $response = array(
        "header" => array(
            "result" => "error",
            "message" => "회원가입 중 오류가 발생하였습니다."
        )
    );
}

response_handling:
header('Content-type: application/json');
echo json_encode($response);