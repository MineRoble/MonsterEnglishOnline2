<?php
include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_config.php";

if( !isset($_POST["year"]) || !isset($_POST["month"]) || !is_int($_POST["year"]) > !is_int($_POST["month"]) ) {
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

    $periods = array(date('Y-m-d', strtotime($_POST["year"]."-".$_POST["month"]."-1")), date('Y-m-t', strtotime($_POST["year"]."-".$_POST["month"]."-1")));

    $stmt = $pdo->prepare("SELECT * FROM `schedules` WHERE `date` BETWEEN :period_start AND :period_end");
    $stmt->bindValue(':period_start', $periods[0]);
    $stmt->bindValue(':period_end', $periods[1]);
    $stmt->execute();
    $schedules = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if ($schedules === false) {
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
            "result" => array()
        )
    );
    
    foreach($schedules as $schedule) {
        $stmt = $pdo->prepare("SELECT * FROM `members` WHERE `idx` = :idx");
        $stmt->bindValue(':idx', $schedule["user_idx"]);
        $stmt->execute();
        $member = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $stmt = $pdo->prepare("SELECT * FROM `teachers` WHERE `idx` = :idx");
        $stmt->bindValue(':idx', $schedule['teacher_idx']);
        $stmt->execute();
        $teacher = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $stmt = $pdo->prepare("SELECT * FROM `tosspayments` WHERE `orderId` = :orderId");
        $stmt->bindValue(':orderId', $schedule["orderId"]);
        $stmt->execute();
        $tosspayment = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt = $pdo->prepare("SELECT * FROM `schedule` WHERE `` LIKE :memo");
        $stmt->bindValue(':memo', $memo);
        $stmt->execute();
        array_merge($coupons, $stmt->fetchAll(PDO::FETCH_ASSOC));

        array_push($response["body"]["result"], array(
            $member['idx'],
            $member['name'],
            $member['id'],
            $schedule['idx'],
            $schedule['date'],
            date("H:i", strtotime($schedule['date'].' '.$schedule['start_time']))."~".date("H:i", strtotime($schedule['date'].' '.$schedule['end_time'])),
            $teacher['name'],
            $tosspayment['approved_at'],
            $tosspayment['orderId']
        ));
    }
    
    // $filename = date('Y년 m월 수업 쿠폰반환', strtotime("{$period}-01")).".xlsx";
    $header = array(
        "학생 idx" => "string",
        "학생 이름" => "string",
        "학생 아이디" => "string",
        "수업 idx" => "string",
        "수업일" => "string",
        "수업 시간" => "string",
        "수업 선생님" => "string",
        "결제일시" => "string",
        "주문번호" => "string",
        "쿠폰 idx" => "string",
        "쿠폰 생성일" => "string",
        "쿠폰 사용일" => "string"
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