<?php
include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_config.php";

exit();

if( !isset($_POST['orderId']) || empty($_POST["orderId"]) ) {
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

    $stmt = $pdo->prepare("SELECT paymentKey FROM tosspayments WHERE orderId = :orderId");
    $stmt->bindValue(':orderId', $_POST['orderId']);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // 결제 취소 API 호출
    $curlHandle = curl_init();
    curl_setopt_array($curlHandle, [
        CURLOPT_URL => "https://api.tosspayments.com/v1/payments/{$result['paymentKey']}/cancel",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "{\"cancelReason\":\"고객이 취소를 원함\"}",
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
        $response = array(
            "header" => array(
                "result" => "success",
                "message" => "결제가 정상적으로 취소되었습니다.",
            )
        );
    } else {
        $response = array(
            "header" => array(
                "result" => "error",
                "message" => "API 호출 실패했습니다."
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