<?php
include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_config.php";

// try {
//     $pdo = new PDO(
//         "mysql:host="._db_address.";dbname="._db_name.";charset=utf8",
//         _db_id,_db_pwd,
//         array(
//             PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
//             PDO::ATTR_EMULATE_PREPARES => false
//         )
//     );

//     $stmt = $pdo->prepare("SELECT * FROM `tosspayments` WHERE status = 'ready' AND created_at >= DATE_ADD(NOW(), INTERVAL -10 MINUTE);");

//     $stmt->bindParam(':teacher_idx', $_SESSION['user']['teacher_idx']);

//     for($i=1; $i<=count($dates); $i++) {
//         $stmt->bindValue(":dates{$i}", $dates[$i-1]);
//     }

//     $stmt->execute();
//     // $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
//     $result = array();
//     while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
//         array_push($result, array(
//             "date" => $row['date'],
//             "start" => $row["DATE_FORMAT(start_time, '%H:%i')"],
//             "end" => $row["DATE_FORMAT(end_time, '%H:%i')"]
//         ));
//     }
    
//     $response = array(
//         "status" => "success",
//         "message" => "",
//         "schedule" => $result
//     );
    
// } catch (PDOException $e) {
//     $response = array(
//         "status" => "error",
//         "message" => "데이터베이스 오류가 발생하였습니다.",
//         "debug" => $e->getMessage()
//     );
// }

// response_handling:
// if( isset($response['debug']) && _server_type != "dev") unset($response['debug']);
// header('Content-type: application/json');
// echo json_encode($response);



function isCheckoutValid($checkout) {
    // $checkout = array(
    //     "year" => $reciveData["year"],
    //     "month" => $reciveData["month"],
    //     "datetime" => array(),
    //     "coupons" => array()
    // );

    
    // $checkout 구조 검사
    if( (
        is_array($checkout) && array_values($checkout) !== $checkout
        &&
        array_key_exists("year", $checkout) && is_numeric($checkout["year"]) && intval($checkout["year"]) >= intval(date("Y")) && intval($checkout["year"]) <= intval(date("Y"))+1
        &&
        array_key_exists("month", $checkout) && is_numeric($checkout["month"]) && intval($checkout["month"]) >= 1 && intval($checkout["month"]) <= 12
        &&
        array_key_exists("datetime", $checkout) && is_array($checkout["datetime"]) && array_values($checkout["datetime"]) === $checkout["datetime"]
        &&
        array_key_exists("coupons", $checkout) && is_array($checkout["coupons"]) && array_values($checkout["coupons"]) === $checkout["coupons"]
        &&
        array_key_exists("totalAmount", $checkout) && is_numeric($checkout["totalAmount"]) 
    ) === false ) {
        return false;
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

        $totalAmount = 0;
        // $times = array(
        //     "14:00~14:25",
        //     "14:30~14:55",
        //     "15:00~15:25",
        //     "15:30~15:55",
        //     "16:00~16:25",
        //     "16:30~16:55",
        //     "17:00~17:25",
        //     "17:30~17:55",
        //     "18:00~18:25",
        //     "18:30~18:55",
        //     "19:00~19:25",
        //     "19:30~19:55",
        //     "20:00~20:25",
        //     "20:30~20:55",
        //     "21:00~21:25",
        //     "21:30~21:55",
        //     "22:00~22:25",
        //     "22:30~22:55"
        // );

        // 스케줄 검사
        $datetimes = array();
        foreach($checkout["datetime"] as $datetime) {
            if( (
                is_array($datetime) &&
                is_numeric($datetime["date"]) &&
                is_string($datetime["time"]) &&
                in_array($datetime["time"], _class_times)
            ) === false ) return false;

            $date = date("Y-m-d", strtotime( $checkout["year"] ."-". $checkout["month"] ."-". $datetime["date"] ));
            if(date('w', strtotime($date)) == 0 || date('w', strtotime($date)) == 6) return false;

            $start_time = explode("~", $datetime["time"])[0].":00";
            $end_time = explode("~", $datetime["time"])[1].":00";

            $query = "SELECT COUNT(*) AS count FROM schedule WHERE date = :input_date AND teacher_idx = :teacher_idx  AND isCancelled = 0 AND ( ( start_time <= :input_start_time_1 AND end_time >= :input_start_time_2 )  OR ( start_time <= :input_end_time_1 AND end_time >= :input_end_time_2 ) OR ( start_time >= :input_start_time_3 AND end_time <= :input_end_time_3 ) )";

            $stmt = $pdo->prepare($query);
            $stmt->bindValue(':teacher_idx', $_SESSION['user']['teacher_idx']);
            $stmt->bindValue(':input_date', $date);
            $stmt->bindValue(':input_start_time_1', $start_time);
            $stmt->bindValue(':input_start_time_2', $start_time);
            $stmt->bindValue(':input_start_time_3', $start_time);
            $stmt->bindValue(':input_end_time_1', $end_time);
            $stmt->bindValue(':input_end_time_2', $end_time);
            $stmt->bindValue(':input_end_time_3', $end_time);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result["count"] > 0) return false;

            $totalAmount += 8000;
            array_push($datetimes, array(
                "date" => intval($datetime["date"]),
                "time" => explode("~", $datetime["time"])[0]."~".explode("~", $datetime["time"])[1]
            ));
        }


        // 쿠폰 검사
        $coupons = array();
        foreach($checkout["coupons"] as $coupon) {
            if(!is_int($coupon)) return false;

            $stmt = $pdo->prepare("SELECT idx, amount FROM coupons WHERE idx = :coupon_idx AND user_idx = :user_idx AND usable = 1");
            $stmt->bindValue(':coupon_idx', $coupon);
            $stmt->bindValue(':user_idx', $_SESSION['user_idx']);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $resultCount = $stmt->rowCount();
            if($resultCount != 1) return false;

            $totalAmount -= $result["amount"];
            array_push($coupons, $result["idx"]);
        }
    } catch (PDOException $e) {
        return false;
    }

    // return $checkout["totalAmount"] === $totalAmount;
    // if($totalAmount < 0) $totalAmount = 0;
    if($checkout["totalAmount"] !== $totalAmount) return false;
    else return array(
            "year" => intval($checkout["year"]),
            "month" => intval($checkout["month"]),
            "datetime" => $datetimes,
            "coupons" => $coupons,
            "totalAmount" => $totalAmount
    );
}