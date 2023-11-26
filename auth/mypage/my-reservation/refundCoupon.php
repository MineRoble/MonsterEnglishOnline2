<?php
include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_config.php";

if(
    !isset($_POST['idx']) || empty($_POST["idx"])
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


    $stmt = $pdo->prepare("SELECT COUNT(*) as cnt, orderId FROM schedule WHERE idx = :idx AND user_idx = :user_idx AND isCancelled = 0");
    $stmt->bindValue(':idx', $_POST["idx"]);
    $stmt->bindValue(':user_idx', $_SESSION["user_idx"]);
    $stmt->execute();
    $init = $stmt->fetch(PDO::FETCH_ASSOC);
    $count = $init["cnt"];
    if($init === false || $init["cnt"] <= 0) {
        $response = array(
            "header" => array(
                "result" => "error",
                "message" => "알 수 없는 수업입니다."
            )
        );
        goto response_handling;
    }



    $now = date("Y-m-d H:i:s");
    $memo = "사용자가 수업을 쿠폰으로 반환함. $now";
    $stmt = $pdo->prepare("UPDATE `schedule` SET `isCancelled`=1,`memo`=:memo WHERE idx = :idx AND user_idx = :user_idx AND isCancelled = 0");
    $stmt->bindValue(':memo', $memo);
    $stmt->bindValue(':idx', $_POST["idx"]);
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



    $stmt = $pdo->prepare("SELECT COUNT(*) as cnt FROM schedule WHERE orderId = :orderId AND user_idx = :user_idx AND isCancelled = 0");
    $stmt->bindValue(':orderId', $init["orderId"]);
    $stmt->bindValue(':user_idx', $_SESSION["user_idx"]);
    $stmt->execute();
    $leftClassesCnt = $stmt->fetchColumn();
    
    $stmt = $pdo->prepare("SELECT status FROM tosspayments WHERE orderId = :orderId");
    $stmt->bindValue(':orderId', $init["orderId"]);
    $stmt->execute();
    $lastPayStatus = $stmt->fetchColumn();

    if($leftClassesCnt <= 0) $pay_status = "REFUNDED";
    else $pay_status = "PARTIAL_REFUNDED";
    
    if($lastPayStatus != $pay_status) {
        $stmt = $pdo->prepare("UPDATE `tosspayments` SET `status` = :status WHERE orderId = :orderId");
        $stmt->bindValue(':status', $pay_status);
        $stmt->bindValue(':orderId', $init["orderId"]);
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
    }


    // 아래 코드는 issueCoupon 함수로 대체
    // $memo = "사용자가 수업번호 {$_POST["idx"]}을(를) 쿠폰으로 반환함. $now";
    // $stmt = $pdo->prepare("INSERT INTO `coupons`(`user_idx`, `amount`, `usable`, `created_at`, `memo`) VALUES (:user_idx, 8000, 1, :created_at, :memo)");
    // $stmt->bindValue(':user_idx', $_SESSION["user_idx"]);
    // $stmt->bindValue(':created_at', $now);
    // $stmt->bindValue(':memo', $memo);
    // $result = $stmt->execute();
    
    if(issueCoupon($_SESSION["user_idx"], "user", "schedule", $_POST["idx"]) === false) {
        $response = array(
            "header" => array(
                "result" => "error",
                "message" => "쿠폰 생성 과정에서 데이터베이스 오류가 발생하였습니다."
            )
        );
        goto response_handling;
    }



    $response = array(
        "header" => array(
            "result" => "success",
            "message" => "수업 {$count}개가 정상적으로 쿠폰으로 반환되었습니다. (8,000원 × {$count}개)",
        )
    );
    
} catch (PDOException $e) {
    $response = array(
        "header" => array(
            "result" => "error",
            "message" => "데이터베이스 오류가 발생하였습니다.",
            "e" => $e
        )
    );
}

response_handling:
header('Content-type: application/json');
echo json_encode($response);