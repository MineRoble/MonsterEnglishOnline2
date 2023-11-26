<?php
include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_config.php";

if(
    !isset($_POST['user_idx']) || empty($_POST["user_idx"]) ||
    !isset($_POST['orderId']) || empty($_POST["orderId"]) ||
    !isset($_POST['refund']) || empty($_POST["refund"]) ||
    !isset($_POST['idx']) || empty($_POST["idx"]) || !is_array($_POST["idx"])
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

    $idxs = array();
    foreach($_POST["idx"] as $idx) {
        // array_push($idxs, $pdo->quote($idx));
        if(is_numeric($idx)) array_push($idxs, intval($idx));
        else {
            $response = array(
                "header" => array(
                    "result" => "error",
                    "message" => "알 수 없는 수업입니다."
                )
            );
            goto response_handling;
        }
    }

    $stmt = $pdo->prepare("SELECT COUNT(*) as cnt FROM schedule WHERE idx IN (".implode(",", $idxs).") AND orderId = :orderId AND user_idx = :user_idx");
    $stmt->bindValue(':orderId', $_POST["orderId"]);
    $stmt->bindValue(':user_idx', $_POST["user_idx"]);
    $stmt->execute();
    $count = $stmt->fetchColumn();

    if($count !== count($idxs)) {
        $response = array(
            "header" => array(
                "result" => "error",
                "message" => "알 수 없는 수업입니다. ".$count."!==".count($idxs)
            )
        );
        goto response_handling;
    }

    $now = date("Y-m-d H:i:s");
    $memo = "관리자가 수업을 쿠폰으로 반환함. $now";
    $stmt = $pdo->prepare("UPDATE `schedule` SET `isCancelled`=1,`memo`=:memo WHERE idx IN (".implode(",", $idxs).") AND orderId = :orderId");
    $stmt->bindValue(':memo', $memo);
    $stmt->bindValue(':orderId', $_POST["orderId"]);
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
    $stmt->bindValue(':orderId', $_POST["orderId"]);
    $stmt->bindValue(':user_idx', $_POST["user_idx"]);
    $stmt->execute();
    $leftClassCount = $stmt->fetchColumn();

    if($leftClassCount > 0) $status = "PARTIAL_REFUNDED";
    else $status = "REFUNDED";

    $stmt = $pdo->prepare("UPDATE `tosspayments` SET `status` = :status WHERE orderId = :orderId");
    $stmt->bindValue(':status', $status);
    $stmt->bindValue(':orderId', $_POST["orderId"]);
    $stmt->execute();
    // $updatedRowCount = $stmt->rowCount();
    // if($updatedRowCount != 1) {
    //     $response = array(
    //         "header" => array(
    //             "result" => "error",
    //             "message" => "결제 상태를 업데이트하는 과정에서 데이터베이스 오류가 발생하였습니다. $updatedRowCount"
    //         )
    //     );
    //     goto response_handling;
    // }

    if(  $_POST["refund"] == "coupon" ) {
        foreach($idxs as $idx) {
            // 아래 코드는 issueCoupon 함수로 대체
            // $memo = "관리자가 수업번호 {$idx}을(를) 쿠폰으로 반환함. $now";
            // $stmt = $pdo->prepare("INSERT INTO `coupons`(`user_idx`, `amount`, `usable`, `created_at`, `memo`) VALUES (:user_idx, 8000, 1, :created_at, :memo)");
            // $stmt->bindValue(':user_idx', $_POST["user_idx"]);
            // $stmt->bindValue(':created_at', $now);
            // // $stmt->bindValue(':orderId', $_POST["orderId"]);
            // $stmt->bindValue(':memo', $memo);
            // $result = $stmt->execute();
            
            if(issueCoupon($_POST["user_idx"], "admin", "schedule", $idx) === false) {
                $response = array(
                    "header" => array(
                        "result" => "error",
                        "message" => "쿠폰 생성 과정에서 데이터베이스 오류가 발생하였습니다."
                    )
                );
                goto response_handling;
            }
        }
    
        $response = array(
            "header" => array(
                "result" => "success",
                "message" => "수업 {$count}개가 정상적으로 쿠폰으로 반환되었습니다. (8,000원 × {$count}개)"
            )
        );
    } else {
        $response = array(
            "header" => array(
                "result" => "success",
                "message" => "수업 {$count}개가 쿠폰 반환 없이 정상적으로 취소되었습니다."
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