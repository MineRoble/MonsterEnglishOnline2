<?php
include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_config.php";

if(
    !isset($_POST['orderId']) || empty($_POST["orderId"])
) {
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

    $stmt = $pdo->prepare("SELECT COUNT(*) as cnt FROM schedule WHERE orderId = :orderId AND user_idx = :user_idx AND isCancelled = 0");
    $stmt->bindValue(':orderId', $_POST["orderId"]);
    $stmt->bindValue(':user_idx', $_SESSION["user_idx"]);
    $stmt->execute();
    $count = $stmt->fetchColumn();

    if($count <= 0) {
        $response = array(
            "header" => array(
                "result" => "error",
                "message" => "알 수 없는 수업입니다."
            )
        );
        goto response_handling;
    }

    $now = date("Y-m-d H:i:s");
    $memo = "사용자가 결제를 쿠폰으로 반환함. $now";
    $stmt = $pdo->prepare("UPDATE `schedule` SET `isCancelled`=1,`memo`=:memo WHERE orderId = :orderId AND user_idx = :user_idx AND isCancelled = 0");
    $stmt->bindValue(':memo', $memo);
    $stmt->bindValue(':orderId', $_POST["orderId"]);
    $stmt->bindValue(':user_idx', $_SESSION["user_idx"]);
    $stmt->execute();
    $updatedRowCount = $stmt->rowCount();
    if($updatedRowCount !== $count) {
        $response = array(
            "header" => array(
                "result" => "error",
                "message" => "수업 취소 과정에서 데이터베이스 오류가 발생하였습니다."
            )
        );
        goto response_handling;
    }

    $stmt = $pdo->prepare("UPDATE `tosspayments` SET `status` = 'REFUNDED' WHERE orderId = :orderId");
    $stmt->bindValue(':orderId', $_POST["orderId"]);
    $stmt->execute();
    $updatedRowCount = $stmt->rowCount();
    if($updatedRowCount != 1) {
        $response = array(
            "header" => array(
                "result" => "error",
                "message" => "결제 상태를 업데이트하는 과정에서 데이터베이스 오류가 발생하였습니다."
            )
        );
        goto response_handling;
    }
    
    $issuedCouponsCount = 0;
    for($i = 0; $i < $count; $i++) {
        $stmt = $pdo->prepare("SELECT date, start_time FROM schedule WHERE orderId = :orderId AND user_idx = :user_idx AND isCancelled = 1");
        $stmt->bindValue(':orderId', $_POST["orderId"]);
        $stmt->bindValue(':user_idx', $_SESSION["user_idx"]);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if( ( strtotime($result["date"]." ".$result["start_time"]) - strtotime(date("Ymd H:i:s")) ) / 3600 <= 12 ) continue;


        // 아래 코드는 issueCoupon 함수로 대체
        // $memo = "사용자가 주문번호 {$_POST["orderId"]}의 결제를 쿠폰으로 반환함. $now";
        // $stmt = $pdo->prepare("INSERT INTO `coupons`(`user_idx`, `amount`, `usable`, `created_at`, `memo`) VALUES (:user_idx, 8000, 1, :created_at, :memo)");
        // $stmt->bindValue(':user_idx', $_SESSION["user_idx"]);
        // $stmt->bindValue(':created_at', $now);
        // // $stmt->bindValue(':orderId', $_POST["orderId"]);
        // $stmt->bindValue(':memo', $memo);
        // $result = $stmt->execute();
        
        if(issueCoupon($_SESSION["user_idx"], "user", "orderId", $_POST["orderId"]) === false) {
            $response = array(
                "header" => array(
                    "result" => "error",
                    "message" => "쿠폰 생성 과정에서 데이터베이스 오류가 발생하였습니다."
                )
            );
            goto response_handling;
        } else $issuedCouponsCount++;
    }

    $response = array(
        "header" => array(
            "result" => "success",
            "message" => "수업 {$count}개 중 {$issuedCouponsCount}개가 정상적으로 쿠폰으로 반환되었습니다. (8,000원 × {$issuedCouponsCount}개)",
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