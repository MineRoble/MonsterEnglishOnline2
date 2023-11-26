<?php
include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_config.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/reservation/processes/_reservationFunctions.php";

// $reciveData = json_decode(file_get_contents('php://input'), true, 512, JSON_THROW_ON_ERROR);

$checkout = isCheckoutValid(json_decode(file_get_contents('php://input'), true, 512, JSON_THROW_ON_ERROR));

if($checkout === false) {
    $response = array(
        "header" => array(
            "result" => "error",
            "message" => "파라미터가 올바르지 않습니다."
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

    $stmt = $pdo->prepare("SELECT name FROM teachers WHERE idx = :idx");
    $stmt->bindValue(':idx', $_SESSION['user']['teacher_idx']);
    $stmt->execute();
    $resultCount = $stmt->rowCount();
    if($resultCount != 1) {
        $response = array(
            "header" => array(
                "result" => "error",
                "message" => "알 수 없는 선생님입니다."
            )
        );
        goto response_handling;
    }
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $teacher_name = $result["name"];
    

    // orderId 생성 (타임스탬프 13자리)
    list($microtime, $timestamp) = explode(' ',microtime());
    $orderId = $timestamp . substr($microtime, 2, 3);
    
    $nowYmdHis = date("Y-m-d H:i:s");
        

    // tosspayments 테이블에 결제 정보 생성
    if(filter_var($_SESSION["user"]["email"], FILTER_VALIDATE_EMAIL)) $user_email = $_SESSION["user"]["email"];
    else $user_email = "";

    $orderName = $teacher_name." 선생님 수업 (".$checkout['year']."년 ".$checkout['month']."월, ".count($checkout["datetime"])."회)";

    if($user_email != "") {
        $is_user_email_1 = "`customerEmail`,";
        $is_user_email_2 = ":customerEmail,";
    } else {
        $is_user_email_1 = "";
        $is_user_email_2 = "";
    }

    
    if($checkout["totalAmount"] > 0) {
        $stmt = $pdo->prepare("INSERT INTO `tosspayments`(`user_idx`, `teacher`, `orderId`, {$is_user_email_1} `orderName`, `customerName`, `amount`, `status`, `checkout`, `created_at`) VALUES (:user_idx, :teacher_idx, :orderId, :orderName, {$is_user_email_2} :customerName, :amount, 'READY', :checkout, :created_at)");

        // $tmp_status = "READY";
        $tmp_checkout_str = json_encode($checkout);
        $stmt->bindValue(':user_idx', $_SESSION['user_idx']);
        $stmt->bindValue(':teacher_idx', $_SESSION['user']['teacher_idx']);
        $stmt->bindValue(':orderId', $orderId);
        $stmt->bindValue(':orderName', $orderName);
        if($user_email != "") $stmt->bindValue(':customerEmail', $user_email);
        $stmt->bindValue(':customerName', $_SESSION["user"]["name"]);
        $stmt->bindValue(':amount', $checkout["totalAmount"]);
        // $stmt->bindValue(':status', $tmp_status);
        $stmt->bindValue(':checkout', $tmp_checkout_str);
        $stmt->bindValue(':created_at', $nowYmdHis);

        $result = $stmt->execute();
        
        if ($result) {
            $response = array(
                "header" => array(
                    "result" => "success",
                    "message" => "READY"
                ),
                "body" => array(
                    "checkout" => array(
                        "orderId" => $orderId, // 13자리 타임스탬프 (https://yoshikixdrum.tistory.com/192)
                        "orderName" => $orderName,
                        "successUrl" => _domain."reservation/success.php", // 결제에 성공하면 이동하는 페이지(직접 만들어주세요)
                        "failUrl" => _domain."reservation/fail.php", // 결제에 실패하면 이동하는 페이지(직접 만들어주세요)
                        "customerEmail" => $user_email,
                        "customerName" => $_SESSION["user"]["name"],
                        "amount" => $checkout["totalAmount"]
                    )
                )
            );
            goto response_handling;
        } else {
            $errorInfo = $stmt->errorInfo();
            $errorMessage = $errorInfo[2];
            $response = array(
                "header" => array(
                    "result" => "error",
                    "message" => "결제 생성 중 오류가 발생하였습니다."
                )
            );
            goto response_handling;
        }
    } else {
        $stmt = $pdo->prepare("INSERT INTO `tosspayments`(`user_idx`, `teacher`, `orderId`, {$is_user_email_1} `orderName`, `customerName`, `amount`, `method`, `method_info`, `status`, `checkout`, `created_at`, `approved_at`) VALUES (:user_idx, :teacher_idx, :orderId, :orderName, {$is_user_email_2} :customerName, :amount, '쿠폰', :method_info, 'DONE', :checkout, :created_at, :approved_at)");

        $tmp_checkout_str = json_encode($checkout);
        $method_info = count($checkout["coupons"])."개";
        $stmt->bindValue(':user_idx', $_SESSION['user_idx']);
        $stmt->bindValue(':teacher_idx', $_SESSION['user']['teacher_idx']);
        $stmt->bindValue(':orderId', $orderId);
        $stmt->bindValue(':orderName', $orderName);
        if($user_email != "") $stmt->bindValue(':customerEmail', $user_email);
        $stmt->bindValue(':customerName', $_SESSION["user"]["name"]);
        $stmt->bindValue(':amount', $checkout["totalAmount"]);
        $stmt->bindValue(':method_info', $method_info);
        $stmt->bindValue(':checkout', $tmp_checkout_str);
        $stmt->bindValue(':created_at', $nowYmdHis);
        $stmt->bindValue(':approved_at', $nowYmdHis);

        $result = $stmt->execute();
        
        if ($result) {            
            // 스케줄 생성
            foreach($checkout["datetime"] as $datetime) {
                $date = date("Y-m-d", strtotime( $checkout["year"] ."-". $checkout["month"] ."-". $datetime["date"] ));
                $start_time = explode("~", $datetime["time"])[0].":00";
                $end_time = explode("~", $datetime["time"])[1].":00";
                
                $stmt = $pdo->prepare("INSERT INTO `schedule`(`teacher_idx`, `user_idx`, `date`, `start_time`, `end_time`, `orderId`) VALUES (:teacher_idx, :user_idx, :date, :start_time, :end_time, :orderId)");
                $stmt->bindValue(':teacher_idx', $_SESSION['user']['teacher_idx']);
                $stmt->bindValue(':user_idx', $_SESSION['user_idx']);
                $stmt->bindValue(':date', $date);
                $stmt->bindValue(':start_time', $start_time);
                $stmt->bindValue(':end_time', $end_time);
                $stmt->bindValue(':orderId', $orderId);

                $result = $stmt->execute();
                if (!$result) {
                    $errorInfo = $stmt->errorInfo();
                    $errorMessage = $errorInfo[2];
                    $response = array(
                        "header" => array(
                            "result" => "error",
                            "code" => null
                        ),
                        "body" => array(
                            "에러 메시지" => "결제 중 오류가 발생하였습니다."
                        )
                    );
                    goto response_handling;
                }
            }


            // 쿠폰 사용 여부 업데이트 (usable   = 0)
            foreach($checkout["coupons"] as $coupon) {
                $stmt = $pdo->prepare("UPDATE `coupons` SET `usable` = 0, orderId = :orderId WHERE idx = :idx");
                $stmt->bindValue(':idx', $coupon);
                $stmt->bindValue(':orderId', $orderId);

                $stmt->execute();
                $count = $stmt->rowCount();

                if($count != 1) {
                    $response = array(
                        "header" => array(
                            "result" => "error",
                            "code" => null
                        ),
                        "body" => array(
                            "에러 메시지" => "결제 중 오류가 발생하였습니다."
                        )
                    );
                    goto response_handling;
                }
            }


            $response = array(
                "header" => array(
                    "result" => "success",
                    "message" => "DONE"
                ),
                "body" => array(
                    "redirect" => "/reservation/success.php?orderId={$orderId}&amount={$checkout["totalAmount"]}&paymentType=SELF_COUPON"
                )
            );
            goto response_handling;
        } else {
            $errorInfo = $stmt->errorInfo();
            $errorMessage = $errorInfo[2];
            $response = array(
                "header" => array(
                    "result" => "error",
                    "message" => "결제 생성 중 오류가 발생하였습니다."
                )
            );
            goto response_handling;
        }
    }
} catch (PDOException $e) {
    $response = array(
        "header" => array(
            "result" => "error",
            "message" => "데이터베이스 오류가 발생하였습니다."
        )
    );
    goto response_handling;
}


response_handling:
if( isset($response['debug']) && _server_type != "dev") unset($response['debug']);
header('Content-type: application/json');
echo json_encode($response);