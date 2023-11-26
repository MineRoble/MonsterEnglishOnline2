<?php
include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_config.php";

// echo '{"status":"success","message":"","schedule":[{"date":"2023-06-21","start":"14:00","end":"14:25"}]}';
// exit();
// sleep(5);

// die(json_encode($_POST, JSON_HEX_TAG | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

if( isset($_POST['year']) && isset($_POST['month']) && isset($_POST['dates']) ) {
    $year = $_POST['year'];
    $month = $_POST['month'];
    $dates = $_POST['dates'];
    $tmp_dates = array();

    if( intval($year) != date("Y") && intval($year) != date("Y")+1 ) goto handleValidationFailure;
    if( intval($month) < 1 && intval($month) > 12 ) goto handleValidationFailure;

    if(gettype($dates) != "array") goto handleValidationFailure;
    foreach($dates as $date) {
        if(intval($date) == 0) goto handleValidationFailure;
        if(
            date('Y', mktime(0, 0, 0, $month, $date, $year)) != $year ||
            date('m', mktime(0, 0, 0, $month, $date, $year)) != $month ||
            date('d', mktime(0, 0, 0, $month, $date, $year)) != $date ||
            date('w', mktime(0, 0, 0, $month, $date, $year)) == 0 ||
            date('w', mktime(0, 0, 0, $month, $date, $year)) == 6
        ) goto handleValidationFailure;
        array_push($tmp_dates, "{$year}-{$month}-{$date}");
    }
    $dates = $tmp_dates;
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

    // $dates_parms = array_map(function($i) { return ":dates{$i}"; }, range(1, count($dates)));
    // $in_syntax = implode(',', $dates_parms);
    $in_syntax = implode(',', array_map(function($i) { return ":dates{$i}"; }, range(1, count($dates))));
    $query = "SELECT date, DATE_FORMAT(start_time, '%H:%i'), DATE_FORMAT(end_time, '%H:%i') FROM schedule WHERE isCancelled = 0 AND teacher_idx = :teacher_idx AND date IN ({$in_syntax})";
    $stmt = $pdo->prepare($query);

    // $stmt->bindParam(':teacher_idx', $_SESSION['user']['teacher']);
    $stmt->bindParam(':teacher_idx', $_SESSION['user']['teacher_idx']);
    
    // foreach ($dates_parms as $key => $date_parms) {
    //     $stmt->bindValue(":dates{$key}", $date_parms);
    // }
    for($i=1; $i<=count($dates); $i++) {
        $stmt->bindValue(":dates{$i}", $dates[$i-1]);
    }

    $stmt->execute();
    // $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $result = array();
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        array_push($result, array(
            "date" => $row['date'],
            "start" => $row["DATE_FORMAT(start_time, '%H:%i')"],
            "end" => $row["DATE_FORMAT(end_time, '%H:%i')"]
        ));
    }

    $options_base = array();
    $ampm_ko = array(
        "am" => "오전",
        "AM" => "오전",
        "pm" => "오후",
        "PM" => "오후"
    );
    foreach(_class_times as $time) {
        $start_time = explode("~", $time)[0];
        $end_time = explode("~", $time)[1];
        $start_time_timestamp = strtotime(date("Y-m-d ").$start_time.":00");
        $end_time_timestamp = strtotime(date("Y-m-d ").$end_time.":00");

        $displayText  = $ampm_ko[date("a", $start_time_timestamp)] ." ". date("h시 i분", $start_time_timestamp);
        $displayText .= " ~ ";
        $displayText .= $ampm_ko[date("a", $end_time_timestamp)]   ." ". date("h시 i분", $end_time_timestamp);

        $options_base[$time] = array(
            // "displayText" => "오후 02시 00분 ~ 02시 25분",
            "displayText" => $displayText,
            "available" => true 
        );
    }

    $response = array(
        "header" => array(
            "result" => "success",
            "message" => ""
        ),
        "body" => array(
            "schedule" => $result,
            "options_base" => $options_base
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