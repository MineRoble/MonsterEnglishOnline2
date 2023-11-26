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

    include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_xlsxwriter.class.php";
    $writer = new XLSXWriter();
    
    $period = "2023-08";
    $periods = array(date('Y-m-d', strtotime("{$period}-01")), date('Y-m-t', strtotime("{$period}-01")));
    $output = array();

    $stmt = $pdo->prepare("SELECT * FROM `schedule` WHERE `date` BETWEEN :period_start AND :period_end AND `isCancelled` != 0 AND `user_idx` != -1");
    $stmt->bindValue(':period_start', $periods[0]);
    $stmt->bindValue(':period_end', $periods[1]);
    $stmt->execute();
    $schedules = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
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
        
        $coupons = array();

        $memo = "%관리자가 수업번호 {$schedule['idx']}을(를) 쿠폰으로 반환함.%";
        $stmt = $pdo->prepare("SELECT * FROM `coupons` WHERE `memo` LIKE :memo");
        $stmt->bindValue(':memo', $memo);
        $stmt->execute();
        array_merge($coupons, $stmt->fetchAll(PDO::FETCH_ASSOC));
        
        $memo = "%사용자가 주문번호 {$schedule['orderId']}의 결제를 쿠폰으로 반환함.%";
        $stmt = $pdo->prepare("SELECT * FROM `coupons` WHERE `memo` LIKE :memo");
        $stmt->bindValue(':memo', $memo);
        $stmt->execute();
        array_merge($coupons, $stmt->fetchAll(PDO::FETCH_ASSOC));
        
        $memo = "%사용자가 수업번호 {$schedule['idx']}을(를) 쿠폰으로 반환함.%";
        $stmt = $pdo->prepare("SELECT * FROM `coupons` WHERE `memo` LIKE :memo");
        $stmt->bindValue(':memo', $memo);
        $stmt->execute();
        array_merge($coupons, $stmt->fetchAll(PDO::FETCH_ASSOC));


        array_push($output, array(
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
    
    $filename = date('Y년 m월 수업 쿠폰반환', strtotime("{$period}-01")).".xlsx";
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
    $writer->writeSheetHeader("Sheet1",$header,$col_options = ['widths'=>[8,20,15,8,11,12,45,19,14,8,19,19]]);

    header("Content-Type: application/octet-stream");
    header("Content-Disposition: attachment; filename=\"$filename\"");
    header("Cache-Control: max-age=0");

    // echo '<!DOCTYPE html>
    // <html lang="ko">
    // <head>
    //     <meta charset="UTF-8">
    //     <meta http-equiv="X-UA-Compatible" content="IE=edge">
    //     <meta name="viewport" content="width=device-width, initial-scale=1.0">
    //     <title>'.$filename.'</title>
    //     <style>
    //         table {
    //             width: 100%;
    //             border: 1px solid #444444;
    //             border-collapse: collapse;
    //         }
    //         th, td {
    //             border: 1px solid #444444;
    //         }
    //     </style>
    // </head>
    // <body>';

    // echo '<a href="'.$filename.'" download>Click to Download</a> <br> <br> <table>';
    // echo '<th>학생 idx</th><th>학생 이름</th><th>학생 아이디</th><th>수업 idx</th><th>수업일</th><th>수업 시간</th><th>수업 선생님</th><th>결제일시</th><th>주문번호</th><th>쿠폰 idx</th><th>쿠폰 생성일</th><th>쿠폰 사용일</th>';
    // foreach($output as $row) {
    //     // $writer->writeSheetRow('Sheet1', $row);
    //     echo "<tr>";
    //     foreach ($row as $data) {
    //         echo "<td>$data</td>";
    //     }
    //     echo "</tr>";
    // }
    // echo "</table>\n</body>\n</html>";

    $writer->writeSheet($output);//엑셀에 해당 정보 입력
    // $writer->writeToFile($filename);//파일로 다운로드
    $writer->writeToFile('php://output');//파일로 다운로드
} catch (PDOException $e) {
    $response = array(
        "header" => array(
            "result" => "error",
            "message" => "데이터베이스 오류가 발생하였습니다.",
            "e" => $e
        )
    );
    
    // echo $response["header"]["message"];
    header('Content-type: application/json');
    echo json_encode($response);
}