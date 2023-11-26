<?php
include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_config.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_PHPExcel/PHPExcel.php";

try {
    $pdo = new PDO(
        "mysql:host="._db_address.";dbname="._db_name.";charset=utf8",
        _db_id,_db_pwd,
        array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false
        )
    );
    
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
    // $headers = array(
    //     "학생 idx" => "string",
    //     "학생 이름" => "string",
    //     "학생 아이디" => "string",
    //     "수업 idx" => "string",
    //     "수업일" => "string",
    //     "수업 시간" => "string",
    //     "수업 선생님" => "string",
    //     "결제일시" => "string",
    //     "주문번호" => "string",
    //     "쿠폰 idx" => "string",
    //     "쿠폰 생성일" => "string",
    //     "쿠폰 사용일" => "string"
    // );
    $headers = array("학생 idx","학생 이름","학생 아이디","수업 idx","수업일","수업 시간","수업 선생님","결제일시","주문번호","쿠폰 idx","쿠폰 생성일","쿠폰 사용일");
    $widths = array(8,20,15,8,11,12,45,19,14,8,19,19);
    
    // $excel = new PHPExcel();
    // foreach($widths as $i => $w) $excel->setActiveSheetIndex(0)->getColumnDimension( chr( 65 + $i) )->setWidth($w);
    // $excel->getActiveSheet()->fromArray($output,NULL,'A1');
    // $excel->getActiveSheet()->setTitle("1번시트제목"); 
    
    // $excel->setActiveSheetIndex(0);

    // header("Content-Type: application/octet-stream");
    // header("Content-Disposition: attachment; filename=\"파일명.xlsx\"");
    // header("Cache-Control: max-age=0");

    // $writer = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
    // $writer->save('php://output');
    
    function column_char($i) { return chr( 65 + $i ); }

    $rows = $output;
    $data = array_merge(array($headers), $rows);
    $header_bgcolor = 'FFABCDEF';
    
    // 엑셀 생성
    $last_char = column_char( count($headers) - 1 );
    
    $excel = new PHPExcel();
    $excel->setActiveSheetIndex(0)->getStyle( "A1:${last_char}1" )->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($header_bgcolor);
    $excel->setActiveSheetIndex(0)->getStyle( "A:$last_char" )->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setWrapText(true);
    foreach($widths as $i => $w) $excel->setActiveSheetIndex(0)->getColumnDimension( column_char($i) )->setWidth($w);
    $excel->getActiveSheet()->fromArray($data,NULL,'A1');
    $writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
    $writer->save('php://output');
    echo "Done...\n";
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