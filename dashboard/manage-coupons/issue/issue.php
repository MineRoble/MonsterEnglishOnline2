<?php
include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_config.php";

if( isset($_POST['user_idx']) && isset($_POST['amount']) && isset($_POST['count']) ) {
    if(is_numeric($_POST['user_idx'])) $user_idx = intval($_POST['user_idx']);
    else goto handleValidationFailure;
    
    if(is_numeric($_POST['amount'])) $amount = intval($_POST['amount']);
    else goto handleValidationFailure;
    
    if(is_numeric($_POST['count'])) $count = intval($_POST['count']);
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

    $now = date("Y-m-d H:i:s");
    $memo = "관리자#{$_SESSION['user_idx']}에 의해 쿠폰이 생성됨. $now";

    for($i=0; $i<$count; $i++) {
        // 아래 코드는 issueCoupon 함수로 대체
        // $stmt = $pdo->prepare("INSERT INTO `coupons`(`user_idx`, `amount`, `usable`, `created_at`, `memo`) VALUES (:user_idx, :amount, 1, :created_at, :memo)");
        // $stmt->bindValue(':user_idx', $user_idx);
        // $stmt->bindValue(':amount', $amount);
        // $stmt->bindValue(':created_at', $now);
        // $stmt->bindValue(':memo', $memo);
        // $result = $stmt->execute();
        $result = issueCoupon($user_idx, "admin", "dashboard", "/dashboard/manage-coupons/issue/", $amount);

        if ($result === false) {
            $response = array(
                "header" => array(
                    "status" => "error",
                    "message" => "쿠폰 발급 과정 중 데이터베이스 오류가 발생했습니다.",
                    "result" => $result
                )
            );
            goto response_handling;
        }
    }

    
    $response = array(
        "header" => array(
            "result" => "success",
            "message" => number_format($amount)."원 쿠폰 {$count}장 발급이 완료되었습니다. 사용자 쿠폰 상세 페이지로 이동하시겠습니까?"
        )
    );
    
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