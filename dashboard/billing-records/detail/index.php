<?php
    $GLOBALS["page-title"] = "주문번호 ".$_GET['orderId']."의 상세 정보";

    include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_config.php";
    include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_header.php";
    include_once $_SERVER["DOCUMENT_ROOT"]."/dashboard/_dashboard_header.php";

    $orderId = $_GET['orderId'];
    
    $stmt = $pdo->prepare("SELECT * FROM tosspayments WHERE orderId = :orderId");
    $stmt->bindValue(':orderId', $_GET["orderId"]);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if($result !== false) {
        $stmt = $pdo->prepare("SELECT name FROM teachers WHERE idx = :idx");
        $stmt->bindValue(':idx', $result["teacher"]);
        $stmt->execute();
        $teacherName = $stmt->fetchColumn();

        $stmt = $pdo->prepare("SELECT id, name FROM members WHERE idx = :idx");
        $stmt->bindValue(':idx', $result["user_idx"]);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $stmt = $pdo->prepare("SELECT idx, amount, memo FROM coupons WHERE orderId = :orderId");
        $stmt->bindValue(':orderId', $_GET["orderId"]);
        $stmt->execute();
        $coupons = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $stmt = $pdo->prepare("SELECT idx, memo, date, DATE_FORMAT(start_time, '%H:%i') as start_time, DATE_FORMAT(end_time, '%H:%i') as end_time, isCancelled FROM schedule WHERE orderId = :orderId");
        $stmt->bindValue(':orderId', $orderId);
        $stmt->execute();
        $schedules = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if($result["method"] == "쿠폰") {
            $response = array(
                "header" => array(
                    "result" => "success",
                    "message" => ""
                ),
                "body" => array(
                    "pay-info" => array(
                        "idx" => $result["idx"],
                        "학생" => "{$user["id"]} ({$user["name"]})",
                        "승인일시" => date("Y-m-d H:i:s", strtotime($result["approved_at"])),
                        "선생님" => $teacherName,
                        "주문번호" => $result["orderId"],
                        "결제상태" => $result["status"],
                        "결제금액" => number_format($result["amount"]),
                        "결재수단" => $result["method"]." (".$result["method_info"].")",
                        "영수증" => '<span class="text-muted">없음.</span>'
                    ),
                    "schedules" => $schedules,
                    "coupons" => $coupons,
                    "apiResponse" => null
                )
            );
        } else {
            // 결제 조회 API 호출
            $curlHandle = curl_init();
            curl_setopt_array($curlHandle, [
                CURLOPT_URL => "https://api.tosspayments.com/v1/payments/".$result['paymentKey'],
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
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
                if(isset($method_info)) $method_info = " ({$method_info})";
                else $method_info = "";

                $response = array(
                    "header" => array(
                        "result" => "success",
                        "message" => ""
                    ),
                    "body" => array(
                        "pay-info" => array(
                            "idx" => $result["idx"],
                            "학생" => "{$user["id"]} ({$user["name"]})",
                            "승인일시" => date("Y-m-d H:i:s", strtotime($responseJson->approvedAt)),
                            "선생님" => $teacherName,
                            "주문번호" => $responseJson->orderId,
                            "결제상태" => $responseJson->status,
                            "결제금액" => number_format($responseJson->totalAmount),
                            "결재수단" => $responseJson->method.$method_info,
                            "영수증" => "<a href=\"".$responseJson->receipt->url."\" class=\"user-select-none\">".$responseJson->receipt->url."</a>"
                        ),
                        "schedules" => $schedules,
                        "coupons" => $coupons,
                        "apiResponse" => $responseJson
                    )
                );
            } else {
                $response = array(
                    "header" => array(
                        "result" => "error",
                        "message" => "결제 정보 조회에 실패했습니다."
                    )
                );
            }
        }
    } else {
        $response = array(
            "header" => array(
                "result" => "error",
                "message" => "알 수 없는 주문번호 입니다. "
            )
        );
    }
?>
    <p class="h1 fw-bold mb-3">주문번호 <?php echo $orderId; ?></p>
<?php
    if($response["header"]["result"] == "success") {
?>
    <main>
        <table class="table">
            <colgroup>
                <col style="width: 128px;">
            </colgroup>
            <tbody>
                <?php
                    foreach($response["body"]["pay-info"] as $key => $value) {
                        echo "<tr>";
                        echo "<th><span class=\"float-end\">{$key}</span></th>";
                        echo "<td><span>{$value}</span></td>";
                        echo "</tr>";
                    }
                    echo '<tr> <th> <span class="float-end">사용된 쿠폰</span> </th> <td> <ul class="list-group">';
                    foreach($response["body"]["coupons"] as $coupon) {
                        echo '<li class="list-group-item">';
                        echo "<script>document.write(Number({$coupon["amount"]}).toLocaleString());</script>원 쿠폰 #{$coupon["idx"]} ({$coupon["memo"]})";
                        echo '</li>';
                    }
                    echo '</ul> </td> </tr>';
                    echo '<tr> <th> <span class="float-end">수업</span> </th> <td> <ul class="list-group">';
                    foreach($response["body"]["schedules"] as $schedule) {
                        $disabled = "";
                        if($schedule["isCancelled"] != 0) $disabled ="disabled";

                        $memo = "";
                        if($schedule["memo"] != "") $memo = " ({$schedule["memo"]})";

                        echo '<li class="list-group-item '.$disabled.'">';
                        echo '<input class="form-check-input me-1" type="checkbox" value="'.$schedule["idx"].'" name="classes" id="'.$schedule["idx"].'" '.$disabled.'>'."\n";
                        echo '<label class="form-check-label stretched-link" for="'.$schedule["idx"].'">';
                        echo "#". $schedule["idx"]." ".$schedule["date"].' (';
                        echo array("일","월","화","수","목","금","토")[date("w", strtotime($schedule['date']))];
                        echo ') '.$schedule["start_time"].'~'.$schedule["end_time"].$memo.'</label>';
                        echo '</li>';
                    }
                    echo '</ul> </td> </tr>';
                ?>
            </tbody>
        </table>

        <div class="d-flex gap-3 mb-3">
            <!-- <button class="btn btn-danger">결제 취소(환불)</button> -->
            <button class="btn btn-danger" onclick="cancel();">선택된 수업을 취소하기</button>
            <button class="btn btn-danger" onclick="cancel('coupon');">선택된 수업을 쿠폰으로 반환하기</button>
            <a class="btn btn-primary" data-bs-toggle="collapse" href="#rawJSON" role="button">
                JSON 보기
            </a>
        </div>
        <div class="collapse" id="rawJSON" readonly>
            <textarea rows="25" class="w-100 form-control" readonly><?php echo json_encode($response["body"]["apiResponse"], JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES); ?></textarea>
        </div>
    </main>

    <script>
        function cancel(refund = null) {
            const formData = new FormData();
            formData.append("user_idx", "<?php echo $result["user_idx"]; ?>");
            formData.append("orderId", "<?php echo $result["orderId"]; ?>");
            formData.append("refund", refund);
            Object.entries(document.querySelectorAll("[name=classes]:checked")).map((item)=>{return item[1].value;}).forEach((item)=>{
                formData.append("idx[]", item);
            });

            fetch("cancel.php", {
                method: "POST",
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                throw new Error('에러 발생: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                if(data['header']['result'] == "success") {
                    alert(data['header']['message']);
                    window.location.reload();
                } else {
                    alert(data['header']['message']);
                }
            })
            .catch(error => {
                alert("서버와 통신 중에 오류가 발생했습니다.");
                console.log(error);
            });
        }
    </script>

<?php } else { ?>

    <div class="alert alert-danger" role="alert">
        <?php echo $response["header"]["message"]; ?>
    </div>

<? }
    include_once $_SERVER["DOCUMENT_ROOT"]."/dashboard/_dashboard_footer.php";
    include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_footer.php";
?>