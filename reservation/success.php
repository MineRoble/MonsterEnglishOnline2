<?php
include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_config.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/reservation/processes/_reservationFunctions.php";

$response = array(
    "header" => array(
        "result" => null,
        "code" => null
    ),
    "body" => array(

    )
);

if( !( isset($_GET["paymentType"]) && $_GET["paymentType"] == "SELF_COUPON" && isset($_GET["orderId"]) && isset($_GET["amount"]) && is_numeric($_GET["amount"]) ) ) {
    if( !isset($_GET["paymentType"]) || !isset($_GET["paymentKey"]) || !isset($_GET["orderId"]) || !isset($_GET["amount"]) || !is_numeric($_GET["amount"]) ) {
        $response = array(
            "header" => array(
                "result" => "error",
                "code" => null
            ),
            "body" => array(
                "에러 메시지" => "필수 파라미터가 누락되었습니다."
            )
        );
        goto response_handling;
    }
    $paymentKey = $_GET['paymentKey'];
    
    if($_GET["paymentType"] != "NORMAL") {
        $response = array(
            "header" => array(
                "result" => "error",
                "code" => null
            ),
            "body" => array(
                "에러 메시지" => "paymentType이 올바르지 않습니다."
            )
        );
        goto response_handling;
    }
}

$orderId = $_GET['orderId'];
$amount = $_GET['amount'];

try {
    $pdo = new PDO(
        "mysql:host="._db_address.";dbname="._db_name.";charset=utf8",
        _db_id,_db_pwd,
        array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false
        )
    );


    // $stmt = $pdo->prepare("SELECT checkout FROM `tosspayments` WHERE user_idx = :user_idx AND orderId = :orderId AND status = 'READY' AND created_at >= DATE_ADD(NOW(), INTERVAL -10 MINUTE)");
    $stmt = $pdo->prepare("SELECT * FROM `tosspayments` WHERE user_idx = :user_idx AND orderId = :orderId AND created_at >= DATE_ADD(NOW(), INTERVAL -10 MINUTE)");
    $stmt->bindValue(':user_idx', $_SESSION["user_idx"]);
    $stmt->bindValue(':orderId', $orderId);
    $stmt->execute();
    $resultCount = $stmt->rowCount();

    if ($resultCount != 1) {
        $response = array(
            "header" => array(
                "result" => "error",
                "code" => null
            ),
            "body" => array(
                "에러 메시지" => "결제 정보가 올바르지 않습니다."
            )
        );
        goto response_handling;
    }
    
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if($result["status"] != "READY") {
        if($result["status"] == "DONE") {
            $response = array(
                "header" => array(
                    "result" => "success",
                    "code" => null
                ),
                "body" => array(
                    "상품명" => $result["orderName"],
                    "결제수단" => $result["method"]." ({$result['method_info']})",
                )
            );
        } else {
            $response = array(
                "header" => array(
                    "result" => "error",
                    "code" => null
                ),
                "body" => array(
                    "에러 메시지" => "결제 정보가 올바르지 않습니다."
                )
            );
        }
        goto response_handling;
    }
    
    $checkout = json_decode($result["checkout"], true);
    if(isCheckoutValid($checkout) === false) {
        $response = array(
            "header" => array(
                "result" => "error",
                "code" => null
            ),
            "body" => array(
                "에러 메시지" => "결제 정보가 올바르지 않습니다. 다시 시도 바랍니다."
            )
        );
        goto response_handling;
    }

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

    
    // 결제 승인 API 호출
    // $data = ['paymentKey' => $paymentKey, 'orderId' => $orderId, 'amount' => $amount];
    // $curlHandle = curl_init("https://api.tosspayments.com/v1/payments/confirm");
    // curl_setopt_array($curlHandle, [
    //     CURLOPT_POST => TRUE,
    //     CURLOPT_RETURNTRANSFER => TRUE,
    //     CURLOPT_HTTPHEADER => [
    //         'Authorization: Basic ' . base64_encode(_tosspayments_secretkey . ':'),
    //         'Content-Type: application/json'
    //     ],
    //     CURLOPT_POSTFIELDS => json_encode($data)
    // ]);
    $curlHandle = curl_init();
    $data = ['paymentKey' => $paymentKey, 'orderId' => $orderId, 'amount' => $amount];
    curl_setopt_array($curlHandle, [
        CURLOPT_URL => "https://api.tosspayments.com/v1/payments/confirm",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => [
            "Authorization: Basic ".base64_encode(_tosspayments_secretkey . ':'),
            "Content-Type: application/json"
        ],
    ]);

    $responseCURL = curl_exec($curlHandle);
    $httpCode = curl_getinfo($curlHandle, CURLINFO_HTTP_CODE);
    $isSuccess = $httpCode == 200;
    $responseJson = json_decode($responseCURL);

    if ($isSuccess) {
        // 카드, 가상계좌, 간편결제, 휴대폰, 계좌이체, 문화상품권, 도서문화상품권, 게임문화상품권
        $banks = array(
            "39" => "경남은행",
            "34" => "광주은행",
            "S8" => "교보증권",
            "12" => "단위농협(지역농축협)",
            "SE" => "대신증권",
            "SK" => "메리츠증권",
            "S5" => "미래에셋증권",
            "SM" => "부국증권",
            "32" => "부산은행",
            "S3" => "삼성증권",
            "45" => "새마을금고",
            "64" => "산림조합",
            "SN" => "신영증권",
            "S2" => "신한금융투자",
            "88" => "신한은행",
            "48" => "신협",
            "27" => "씨티은행",
            "20" => "우리은행",
            "71" => "우체국예금보험",
            "S0" => "유안타증권",
            "SJ" => "유진투자증권",
            "50" => "저축은행중앙회",
            "37" => "전북은행",
            "35" => "제주은행",
            "90" => "카카오뱅크",
            "SQ" => "카카오페이증권",
            "89" => "케이뱅크",
            "SB" => "키움증권",
            "92" => "토스뱅크",
            "ST" => "토스증권",
            "SR" => "펀드온라인코리아(한국포스증권)",
            "SH" => "하나금융투자",
            "81" => "하나은행",
            "S6" => "한국투자증권",
            "SG" => "한화투자증권",
            "SA" => "현대차증권",
            "54" => "홍콩상하이은행",
            "SI" => "DB금융투자",
            "31" => "DGB대구은행",
            "03" => "IBK기업은행",
            "06" => "KB국민은행",
            "S4" => "KB증권",
            "02" => "KDB산업은행",
            "SP" => "KTB투자증권(다올투자증권)",
            "SO" => "LIG투자증권",
            "11" => "NH농협은행",
            "SL" => "NH투자증권",
            "23" => "SC제일은행",
            "07" => "Sh수협은행",
            "SD" => "SK증권"
        );
        if ($responseJson->method === "카드") {
            $method_info = $responseJson->card->number;
        } elseif ($responseJson->method === "가상계좌") {
            $method_info = $banks[$responseJson->bankCode]." ".$responseJson->virtualAccount->accountNumber;
        } elseif ($responseJson->method === "간편결제") {
            $method_info = $responseJson->easyPay->provider;
        } elseif ($responseJson->method === "휴대폰") {
            $method_info = $responseJson->mobilePhone->customerMobilePhone;
        } elseif ($responseJson->method === "계좌이체") {
            $method_info = $banks[$responseJson->transfer->bankCode];
        }
        $approvedAt = date("Y-m-d H:i:s", strtotime($responseJson->approvedAt));
        
        $stmt = $pdo->prepare("UPDATE `tosspayments` SET `status` = 'DONE', `method` = :method, `paymentKey` = :paymentKey, `method_info` = :method_info, `approved_at` = :approved_at WHERE orderId = :orderId");
        $stmt->bindValue(':method', $responseJson->method);
        $stmt->bindValue(':paymentKey', $paymentKey);
        $stmt->bindValue(':method_info', $method_info);
        $stmt->bindValue(':approved_at', $approvedAt);
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


        $response = array(
            "header" => array(
                "result" => "success",
                "code" => null
            ),
            "body" => array(
                "상품명" => $responseJson->orderName,
                "결제수단" => $responseJson->method." ({$method_info})",
            )
        );
        goto response_handling;
    } else {
        $memo = json_encode(array(
            "에러메시지" => $responseJson->message,
            "에러코드" => $responseJson->code
        ));
        $stmt = $pdo->prepare("UPDATE `tosspayments` SET `status` = 'ERROR', paymentKey = :paymentKey, memo = :memo WHERE orderId = :orderId");
        $stmt->bindValue(':paymentKey', $paymentKey);
        $stmt->bindValue(':memo', $memo);
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

        
        // 스케줄 취소
        $memo = "PayError";
        $stmt = $pdo->prepare("UPDATE `schedule` SET `isCancelled` = 1, memo = :memo WHERE orderId = :orderId");
        $stmt->bindValue(':memo', $memo);
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

        
        // 쿠폰 사용 여부 업데이트 (usable = 1)
        $stmt = $pdo->prepare("UPDATE `coupons` SET `usable` = 1, orderId = NULL WHERE idx = :idx");
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


        $response = array(
            "header" => array(
                "result" => "error",
                "code" => null
            ),
            "body" => array(
                "에러메시지" => $responseJson->message,
                "에러코드" => $responseJson->code
            )
        );

        goto response_handling;
    }

    // if ($err) {
    // //   echo "cURL Error #:" . $err;
    //     $response = array(
    //         "result" => "error",
    //         "code" => null,
    //         "output" => array(
    //             "에러 메시지" => "cURL 에러",
    //             "cURL 에러" => $err
    //         )
    //     );
    //     goto response_handling;
    // }

    // $response = json_decode($response);
    
} catch (PDOException $e) {
    $response = array(
        "header" => array(
            "result" => "error",
            "code" => null
        ),
        "body" => array(
            "에러 메시지" => "데이터베이스 오류가 발생하였습니다."
        )
    );
}


response_handling:

if( $response["header"]["result"] == "success" ) $GLOBALS["page-title"] = "결제 완료";
else $GLOBALS["page-title"] = "결제 실패";

include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_header.php";

if( $response["header"]["result"] == "success" ) { ?>
    <main class="container">
        <div class="alert alert-success my-5" role="alert">
            <h4 class="alert-heading"> <i class="bi bi-check2-circle"></i> 결제가 완료되었습니다!</h4>
            <?php
                // echo '<p class="mb-0">'.$response["message"].'</p>';
                if(is_array($response["body"])) {
                    foreach($response["body"] as $key => $value) {
                        echo '<p class="mb-0"><strong>'.$key.':</strong> '.$value.'</p>';
                    }
                }
            ?>
            <hr>
            <p class="mb-0">수업 예약 현황 및 결제 내역은 <a href="/auth/mypage/billing-records/" class="alert-link">마이페이지</a>에서 확인하실 수 있습니다.</p>
        </div>
    </main>
<? } else { ?>
<main class="container">
    <div class="alert alert-danger my-5" role="alert">
        <h4 class="alert-heading"> <i class="bi bi-exclamation-circle"></i> 결제에 실패했습니다!</h4>
        <?php
            // echo '<p class="mb-0">'.$response["message"].'</p>';
            if(is_array($response["body"])) {
                foreach($response["body"] as $key => $value) {
                    echo '<p class="mb-0"><strong>'.$key.':</strong> '.$value.'</p>';
                }
            }
        ?>
        <hr>
        <p class="mb-0">해당 화면을 캡처하여 관리자에게 문의 바랍니다.</p>
        <code><?php if(isset($response["header"]["code"])) echo $response["code"]; ?></code>
    </div>
</main>
<? }

include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_footer.php";