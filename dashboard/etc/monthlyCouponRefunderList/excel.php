<?php
include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_config.php";

if( !isset($_GET["start"]) || !isset($_GET["end"]) || strtotime($_GET["start"]) > strtotime($_GET["end"]) ) {
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


    $stmt = $pdo->prepare("SELECT * FROM `schedule` WHERE `date` BETWEEN :start AND :end AND `isCancelled` != 0 AND `user_idx` != -1");
    $stmt->bindValue(':start', $_GET["start"]);
    $stmt->bindValue(':end', $_GET["end"]);
    $stmt->execute();
    $cancelledSchedules = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    
    if ($cancelledSchedules === false) {
        $response = array(
            "header" => array(
                "result" => "error",
                "message" => "데이터베이스 오류가 발생하였습니다."
            )
        );
        goto response_handling;
    }


    $response = array(
        "header" => array(
            "result" => "success",
            "message" => ""
        ),
        "body" => array(
            "cancelledSchedules" => array()
        )
    );

    
    foreach($cancelledSchedules as $cancelledSchedule) {
        $stmt = $pdo->prepare("SELECT * FROM members WHERE idx = :idx");
        $stmt->bindValue(':idx', $cancelledSchedule["user_idx"]);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $stmt = $pdo->prepare("SELECT * FROM teachers WHERE idx = :idx");
        $stmt->bindValue(':idx', $cancelledSchedule["teacher_idx"]);
        $stmt->execute();
        $teacher = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $stmt = $pdo->prepare("SELECT * FROM tosspayments WHERE orderId = :orderId");
        $stmt->bindValue(':orderId', $cancelledSchedule["orderId"]);
        $stmt->execute();
        $billingRecord = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $memo = "%{$cancelledSchedule["idx"]}을(를) 쿠폰으로 반환함%";
        $stmt = $pdo->prepare("SELECT * FROM `coupons` WHERE memo LIKE :memo");
        $stmt->bindValue(':memo', $memo);
        $stmt->execute();
        $coupons = $stmt->fetch(PDO::FETCH_ASSOC);

        if($coupons === false) {
            $memo = "%사용자가 주문번호 {$cancelledSchedule["orderId"]}의 결제를 쿠폰으로 반환함%";
            $stmt = $pdo->prepare("SELECT * FROM `coupons` WHERE memo LIKE :memo");
            $stmt->bindValue(':memo', $memo);
            $stmt->execute();
            $coupons = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        
        if($coupons === false) die("%{$cancelledSchedule["idx"]}을(를) 쿠폰으로 반환함%"."\n"."%사용자가 주문번호 {$cancelledSchedule["orderId"]}의 결제를 쿠폰으로 반환함%");
        
        array_push($response["body"]["cancelledSchedules"], array(
            "user" => $user,
            "teacher" => $teacher,
            "schedule" => $cancelledSchedule,
            "billing-record" => $billingRecord,
            "coupons" => $coupons
        ));
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

if($response["header"]["result"] != "success") {
    header('Content-type: application/json');
    echo json_encode($response);
    exit();
}

$file_name = '엑셀 생성';
header("Content-Disposition: attachment; filename=$file_name.xls");
?>

<table>
    <?php
        foreach($response["body"]["cancelledSchedules"] as $cancelledSchedule) {    
            echo '<tr>';
            echo '<td>'.$cancelledSchedule['user']['idx'].'</td>';
            echo '<td>'.$cancelledSchedule['user']['name'].'</td>';
            echo '<td>'.$cancelledSchedule['user']['id'].'</td>';
            echo '<td>'.$cancelledSchedule['schedule']['idx'].'</td>';
            echo '<td>'.$cancelledSchedule['schedule']['date'].'</td>';
            echo '<td>'.$cancelledSchedule['schedule']['start_time'].' ~ '.$cancelledSchedule['schedule']['end_time'].'</td>';
            echo '<td>'.$cancelledSchedule['teacher']['name'].'</td>';
            echo '<td>'.$cancelledSchedule['billing-record']['approved_at'].'</td>';
            echo '<td>'.$cancelledSchedule['billing-record']['orderId'].'</td>';
            echo '<td>'.$cancelledSchedule['coupons']['idx'].'</td>';
            echo '<td>'.$cancelledSchedule['coupons']['created_at'].'</td>';
            echo '<td>'.$cancelledSchedule['coupons']['created_at'].'</td>';
            echo '</tr>';
        }
    ?>
</table>