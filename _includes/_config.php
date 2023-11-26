<?php
date_default_timezone_set("Asia/Seoul");

if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== "on") {
    $location = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . $location);
    exit;
}

if(!session_id()) {
    // id가 없을 경우 세션 시작
    session_start();
    if( !isset($_SESSION['login']) ) $_SESSION['login'] = false;
}

if($_SERVER["HTTP_HOST"] == "gdre.monstereng.com") {
    define("_server_type", "dev");
    define("_domain", "https://gdre.monstereng.com/");
    define("_db_address", "localhost");
    define("_db_id", "############");
    define("_db_pwd", "############");
    define("_db_name", "neweng");
    define("_tosspayments_clientkey", "test_ck_############################");
    define("_tosspayments_secretkey", "test_sk_############################");

    error_reporting( E_ALL );
    ini_set( "display_errors", 1 );
    mysqli_report(MYSQLI_REPORT_STRICT | MYSQLI_REPORT_ALL);
} else {
    define("_server_type", "product");
    define("_domain", "https://monstereng.com/");
    define("_db_address", "############.hosting.kr");
    define("_db_id", "############");
    define("_db_pwd", "############");
    define("_db_name", "monstereng");
    define("_tosspayments_clientkey", "live_ck_############################");
    define("_tosspayments_secretkey", "live_sk_############################");
}

define("_class_times", array(
    '13:00~13:25',
    '13:25~13:55',
    '14:00~14:25',
    '14:30~14:55',
    '15:00~15:25',
    '15:30~15:55',
    '16:00~16:25',
    '16:30~16:55',
    '17:00~17:25',
    '17:30~17:55',
    '18:00~18:25',
    '18:30~18:55',
    '19:00~19:25',
    '19:30~19:55',
    '20:00~20:25',
    '20:30~20:55',
    '21:00~21:25',
    '21:30~21:55',
    '22:00~22:25',
    '22:30~22:55'
));

define("_pay_status", array(
    "IN_PROGRESS" => array(
        "bs_class" => "text-warning",
        "korean" => "결제 대기",
        "explain_ko" => "결제수단 정보와 해당 결제수단의 소유자가 맞는지 인증을 마친 상태입니다. 결제 승인 API를 호출하면 결제가 완료됩니다."
    ),
    "WAITING_FOR_DEPOSIT" => array(
        "bs_class" => "text-warning",
        "korean" => "입금 대기",
        "explain_ko" => "가상계좌 결제 흐름에만 있는 상태로, 결제 고객이 발급된 가상계좌에 입금하는 것을 기다리고 있는 상태입니다."
    ),
    "DONE" => array(
        "bs_class" => "text-success",
        "korean" => "결제 승인",
        "explain_ko" => "인증된 결제수단 정보, 고객 정보로 요청한 결제가 승인된 상태입니다."
    ),
    "CANCELED" => array(
        "bs_class" => "text-danger",
        "korean" => "결제 취소",
        "explain_ko" => "승인된 결제가 취소된 상태입니다."
    ),
    "PARTIAL_CANCELED" => array(
        "bs_class" => "text-danger",
        "korean" => "결제 부분 취소",
        "explain_ko" => "승인된 결제가 부분 취소된 상태입니다."
    ),
    "ABORTED" => array(
        "bs_class" => "text-danger",
        "korean" => "결제 승인 실패",
        "explain_ko" => "결제 승인이 실패한 상태입니다."
    ),
    "EXPIRED" => array(
        "bs_class" => "text-warning",
        "korean" => "시간 초과",
        "explain_ko" => "결제 유효 시간 30분이 지나 거래가 취소된 상태입니다. IN_PROGRESS 상태에서 결제 승인 API를 호출하지 않으면 EXPIRED가 됩니다."
    ),
    "PARTIAL_REFUNDED" => array(
        "bs_class" => "text-danger",
        "korean" => "결제 부분 반환",
        "explain_ko" => "일부 수업이 쿠폰으로 반환된 상태."
    ),
    "REFUNDED" => array(
        "bs_class" => "text-danger",
        "korean" => "결제 반환",
        "explain_ko" => "전체 수업이 쿠폰으로 반환된 상태."
    )
));

if(
    !(
        $_SERVER['SCRIPT_FILENAME'] == "{$_SERVER["DOCUMENT_ROOT"]}/index.php" ||

        $_SERVER['SCRIPT_FILENAME'] == "{$_SERVER["DOCUMENT_ROOT"]}/auth/signin/index.php" ||
        $_SERVER['SCRIPT_FILENAME'] == "{$_SERVER["DOCUMENT_ROOT"]}/auth/signin/process.php" ||

        $_SERVER['SCRIPT_FILENAME'] == "{$_SERVER["DOCUMENT_ROOT"]}/auth/signup/index.php" ||
        $_SERVER['SCRIPT_FILENAME'] == "{$_SERVER["DOCUMENT_ROOT"]}/auth/signup/process.php" ||
        
        $_SERVER['SCRIPT_FILENAME'] == "{$_SERVER["DOCUMENT_ROOT"]}/terms/privacy/index.php" ||
        $_SERVER['SCRIPT_FILENAME'] == "{$_SERVER["DOCUMENT_ROOT"]}/terms/user-agreement/index.php"
    ) && $_SESSION['login'] !== true
) { //로그인 안한 경우 접근 불가
    // header("Location: /");
    $redirectURL = '/auth/signin/?redirect=' . urlencode($_SERVER['REQUEST_URI']);
    // die('<script>alert("로그인이 필요합니다.");window.location.href="'.$redirectURL.'"</script><noscript><meta http-equiv="refresh" content="0;url=' . $redirectURL . '"></noscript>');
    die('<script>alert("로그인이 필요합니다.");window.location.href="'.$redirectURL.'"</script>');
}

if( substr(basename($_SERVER['PHP_SELF']), 0, 1) == "_" ) { //파일명이 언더바로 시작할 경우 접근 불가
    header("Location: /");
    exit();
}

if( $_SESSION['login'] === true ) {
    try {
        $pdo = new PDO(
            "mysql:host="._db_address.";dbname="._db_name.";charset=utf8",
            _db_id,_db_pwd,
            array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false
            )
        );

        if(isset($_COOKIE['login_token'])) {
            $stmt = $pdo->prepare("SELECT user_idx FROM login_sessions WHERE user_idx = :user_idx AND token = :token AND expired_at > DATE(NOW()) AND is_available != 0");
            $stmt->bindValue(':user_idx', $_SESSION['user_idx']);
            $stmt->bindValue(':token', $_COOKIE['login_token']);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if($result) {
                $stmt = $pdo->prepare("SELECT * FROM members WHERE idx = :user_idx");
                $stmt->bindValue(':user_idx', $result['user_idx']);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
                if($result) {
                    $_SESSION['user_idx'] = $result['idx'];
                    if(isset($result['pwd'])) unset($result['pwd']);
                    $_SESSION['user'] = $result;
                } else {
                    header("Location: /auth/signout");
                }
            } else {
                header("Location: /auth/signout");
            }
        } else {
            $stmt = $pdo->prepare("SELECT * FROM members WHERE idx = :user_idx");
            $stmt->bindValue(':user_idx', $_SESSION['user_idx']);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if($result) {
                if(isset($result['pwd'])) unset($result['pwd']);
                $_SESSION['user'] = $result;
            } else {
                header("Location: /auth/signout");
            }
        }

    } catch (PDOException $e) {
        die("데이터베이스에 연결할 수 없습니다.");
    }
} elseif( isset($_COOKIE["login_token"]) && !empty($_COOKIE["login_token"]) ) {
    try {
        $pdo = new PDO(
            "mysql:host="._db_address.";dbname="._db_name.";charset=utf8",
            _db_id,_db_pwd,
            array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false
            )
        );
    
        $stmt = $pdo->prepare("SELECT user_idx FROM login_sessions WHERE token = :token AND expired_at > DATE(NOW()) AND is_available != 0");
        $stmt->bindValue(':token', $_COOKIE['login_token']);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if($result) {
            $stmt = $pdo->prepare("SELECT * FROM members WHERE idx = :user_idx");
            $stmt->bindValue(':user_idx', $result['user_idx']);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if($result) {
                $_SESSION['login'] = true;
                if(isset($result['pwd'])) unset($result['pwd']);
                $_SESSION['user'] = $result;
            } else {
                header("Location: /auth/signout");
            }
        } else {
            setcookie("login_token", null, 0, "/");
        }
        
    } catch (PDOException $e) {
        die("데이터베이스에 연결할 수 없습니다.");
    }
}

$directoryPermission = array(
    "{$_SERVER["DOCUMENT_ROOT"]}/dashboard/" => 3,
    "{$_SERVER["DOCUMENT_ROOT"]}/schedule-manager/" => 2,
    "{$_SERVER["DOCUMENT_ROOT"]}/homeworks/processes/" => 2,
    "{$_SERVER["DOCUMENT_ROOT"]}/homeworks/write.php" => 2,
    "{$_SERVER["DOCUMENT_ROOT"]}/homeworks/edit.php" => 2
);
foreach($directoryPermission as $directory => $permission) {
    if( $directory === "" || strrpos($_SERVER['SCRIPT_FILENAME'], $directory, -strlen($_SERVER['SCRIPT_FILENAME'])) !== false) setPermission($permission);
}

function setPermission($permission) {
    // 0: 미승인
    // 1: 학생/학부모
    // 2: 교사
    // 3: 관리자
    if($_SESSION['login'] !== true) {
        $redirectURL = '/auth/signin/?redirect=' . urlencode($_SERVER['REQUEST_URI']);
        die('<script>alert("로그인이 필요합니다.");window.location.href="'.$redirectURL.'"</script>');
    }
    if($_SESSION["user"]["permission"] < $permission) {
        // die("<noscript>권한이 부족합니다.</noscript><script>alert('권한이 부족합니다.');history.back();</script>");

        // header('HTTP/1.0 403 Forbidden');
        header("Location: /error/403.php");
        exit();
    }
}

function issueCoupon($user_idx, $issued_by, $from_key, $from_value, $amount = 8000, $memo = null) {
    // issued_by
    // 0: 미승인
    // 1: 학생/학부모
    // 2: 교사
    // 3: 관리자
    try {
        $pdo = new PDO(
            "mysql:host="._db_address.";dbname="._db_name.";charset=utf8",
            _db_id,_db_pwd,
            array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false
            )
        );

        if(is_int($issued_by)) {
            if(
                $issued_by != 1 &&
                $issued_by != 2 &&
                $issued_by != 3
            ) return false;
        } else {
            if($issued_by == "user") $issued_by = 1;
            elseif($issued_by == "teacher") $issued_by = 2;
            elseif($issued_by == "admin") $issued_by = 3;
            else return false;
        }

        if($amount == null) $amount = 8000;
        $usable = 1;
        $created_at = date("Y-m-d H:i:s");
    
        $stmt = $pdo->prepare("INSERT INTO `coupons`(`user_idx`, `amount`, `usable`, `created_at`, `issued_by`, `from_key`, `from_value`, `memo`) VALUES (:user_idx, :amount, :usable, :created_at, :issued_by, :from_key, :from_value, :memo)");
        $stmt->bindValue(':user_idx', $user_idx);
        $stmt->bindValue(':amount', $amount);
        $stmt->bindValue(':usable', $usable);
        $stmt->bindValue(':created_at', $created_at);
        $stmt->bindValue(':issued_by', $issued_by);
        $stmt->bindValue(':from_key', $from_key);
        $stmt->bindValue(':from_value', $from_value);
        $stmt->bindValue(':memo', $memo);
        $result = $stmt->execute();

        return $result;
        
    } catch (PDOException $e) {
        die($e);
    }
}
?>