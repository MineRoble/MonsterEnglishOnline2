<?php
include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_config.php";

try {
    $pdo = new PDO(
        "mysql:host="._db_address.";dbname="._db_name.";charset=utf8",
        _db_id,_db_pwd,
        array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false
        )
    );

    $stmt = $pdo->prepare("UPDATE `login_sessions` SET `is_available`=0 WHERE token = :token");
    $stmt->bindValue(':token', $_COOKIE['login_token']);
    $stmt->execute();
    
} catch (PDOException $e) {
    die("데이터베이스에 연결할 수 없습니다.");
}

setcookie("login_token", null, 0, "/");
$_SESSION = array();
session_destroy();
header("Location: /");
exit();