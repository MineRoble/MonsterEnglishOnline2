<?php
include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_config.php";

$orderId = $_POST['orderId'];
if(!is_numeric($orderId)) {
    $response = array(
        "header" => array(
            "result" => "error",
            "message" => "알 수 없는 orderId 입니다."
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


    // tosspayments 테이블에서 checkout 가져오기
    $stmt = $pdo->prepare("SELECT checkout FROM `tosspayments` WHERE orderId = :orderId AND user_idx = :user_idx AND status = 'READY'");
    $stmt->bindValue(':orderId', $orderId);
    $stmt->bindValue(':user_idx', $_SESSION["user_idx"]);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $checkout = json_decode($result["checkout"], true);

    if (!is_array($checkout["coupons"])) {
        $response = array(
            "header" => array(
                "result" => "error",
                "message" => "알 수 없는 orderId 입니다."
            )
        );
        goto response_handling;
    }

    // 결제 상태 업데이트 "stopped"
    $memo = "SDK ERROR CODE: ".$_POST["errorcode"];
    $stmt = $pdo->prepare("UPDATE `tosspayments` SET `status` = 'STOPPED', `memo` = :memo WHERE orderId = :orderId");
    $stmt->bindValue(':orderId', $orderId);
    $stmt->bindValue(':memo', $memo);
    $stmt->execute();
    $count = $stmt->rowCount();
    if($count == 1) {
        $response = array(
            "header" => array(
                "result" => "success",
                "message" => "STOPPED"
            )
        );
        goto response_handling;
    } else {
        $response = array(
            "header" => array(
                "result" => "error",
                "message" => "오류 발생! {$count}개의 결제 상태가 수정되었습니다."
            )
        );
        goto response_handling;
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