<?php
include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_config.php";

$joinSyntax = "";
if( isset($_POST['sortKey']) && isset($_POST['sortOrder']) && isset($_POST['itemPerPage']) && isset($_POST['page']) ) {
    $sortKey = $_POST['sortKey'];
    $sortOrder = $_POST['sortOrder'];

    if(is_numeric($_POST['itemPerPage'])) $itemPerPage = intval($_POST['itemPerPage']);
    else goto handleValidationFailure;
    if(is_numeric($_POST['page'])) $page = intval($_POST['page']);
    else goto handleValidationFailure;

    if( $sortKey == "sortby_idx" ) $sortKey = "idx";
    elseif( $sortKey == "sortby_username" ) {
        $joinSyntax = "JOIN members ON tosspayments.user_idx = members.idx";
        $sortKey = "members.name";
    } elseif( $sortKey == "sortby_teacher" ) {
        $joinSyntax = "JOIN teachers ON tosspayments.teacher = teachers.idx";
        $sortKey = "teachers.name";
    } 
    elseif( $sortKey == "sortby_orderId" ) $sortKey = "orderId";
    elseif( $sortKey == "sortby_status" ) $sortKey = "status";
    elseif( $sortKey == "sortby_amount" ) $sortKey = "amount";
    elseif( $sortKey == "sortby_method" ) $sortKey = "method";
    elseif( $sortKey == "sortby_approvedAt" ) $sortKey = "approved_at";
    else goto handleValidationFailure;

    if($sortOrder == "asc") $sortOrder = "ASC";
    elseif($sortOrder == "desc") $sortOrder = "DESC";
    else goto handleValidationFailure;
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

    $bindValues = array();
    $whereSyntax = "WHERE status IN ('IN_PROGRESS', 'WAITING_FOR_DEPOSIT', 'DONE', 'CANCELED', 'PARTIAL_CANCELED', 'ABORTED', 'EXPIRED', 'PARTIAL_REFUNDED', 'REFUNDED')";
    if( isset($_POST['queryType']) && isset($_POST['searchQuery']) ) {
        $searchQuery = $pdo->quote("%".str_replace("%", "\\%", $_POST['searchQuery'])."%");
        if($_POST['queryType'] == "period") {
            $search_dates = explode("~", $_POST['searchQuery']);

            $search_date = array();
            $search_date[0] = $pdo->quote($search_dates[0]." 00:00:00");
            $search_date[1] = $pdo->quote($search_dates[1]." 23:59:59");
            
            if($search_dates[0] == "") $whereSyntax .= " AND approved_at <= {$search_date[1]}";
            elseif($search_dates[1] == "") $whereSyntax .= " AND approved_at >= {$search_date[0]}";
            else $whereSyntax .= " AND approved_at BETWEEN {$search_date[0]} AND {$search_date[1]}";
        } elseif($_POST['queryType'] == "id") {
            $stmt = $pdo->prepare("SELECT idx FROM members where id LIKE {$searchQuery}");
            $stmt->execute();
            $searchResult = $stmt->fetchAll(PDO::FETCH_COLUMN);

            if(count($searchResult) <= 0 ) $whereSyntax .= " AND 0";
            else $whereSyntax .= " AND user_idx IN (".implode(',', $searchResult).")";
        } elseif($_POST['queryType'] == "name") {
            $stmt = $pdo->prepare("SELECT idx FROM members where name LIKE {$searchQuery}");
            $stmt->execute();
            $searchResult = $stmt->fetchAll(PDO::FETCH_COLUMN);

            if(count($searchResult) <= 0 ) $whereSyntax .= " AND 0";
            else $whereSyntax .= " AND user_idx IN (".implode(',', $searchResult).")";
        } elseif($_POST['queryType'] == "teacher") {
            $stmt = $pdo->prepare("SELECT idx FROM teachers where name LIKE {$searchQuery}");
            $stmt->execute();
            $searchResult = $stmt->fetchAll(PDO::FETCH_COLUMN);

            if(count($searchResult) <= 0 ) $whereSyntax .= " AND 0";
            else $whereSyntax .= " AND teacher IN (".implode(',', $searchResult).")";
        } elseif($_POST['queryType'] == "orderId") {
            $whereSyntax .= " AND orderId LIKE {$searchQuery}";
        } else {
            $response = array(
                "header" => array(
                    "result" => "error",
                    "message" => "파라미터가 올바르지 않습니다."
                )
            );
            goto response_handling;
        }
    }
    $orderBySyntax = "ORDER BY $sortKey $sortOrder";
    $limitSyntax = "LIMIT ".($itemPerPage*($page-1)).", ".$itemPerPage;
    $stmt = $pdo->prepare("SELECT tosspayments.* FROM tosspayments $joinSyntax $whereSyntax $orderBySyntax $limitSyntax");
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare("SELECT COUNT(*) as cnt FROM tosspayments $whereSyntax");
    $stmt->execute();
    $count = $stmt->fetchColumn();
    
    if ($count === false) {
        $response = array(
            "header" => array(
                "result" => "error",
                "message" => "레코드 개수를 가져오는 중 오류가 발생했습니다."
            )
        );
    } else {
        $response = array(
            "header" => array(
                "result" => "success",
                "message" => null,
            ),
            "body" => array(
                "billing-records" => array(),
                "count" => $count
            )
        );

        foreach($result as $item) {
            $stmt = $pdo->prepare("SELECT COUNT(*) as cnt FROM schedule WHERE orderId = :orderId AND user_idx = :user_idx");
            $stmt->bindValue(':orderId', $item["orderId"]);
            $stmt->bindValue(':user_idx', $_SESSION["user_idx"]);
            $stmt->execute();
            $createdClassCnt = $stmt->fetchColumn();

            $stmt = $pdo->prepare("SELECT COUNT(*) as cnt FROM schedule WHERE orderId = :orderId AND user_idx = :user_idx AND isCancelled = 0");
            $stmt->bindValue(':orderId', $item["orderId"]);
            $stmt->bindValue(':user_idx', $_SESSION["user_idx"]);
            $stmt->execute();
            $leftClassCnt = $stmt->fetchColumn();

            if($item["method"] == "쿠폰") {
                $stmt = $pdo->prepare("SELECT name FROM teachers WHERE idx = :idx");
                $stmt->bindValue(':idx', $item["teacher"]);
                $stmt->execute();
                $teacher = $stmt->fetch(PDO::FETCH_ASSOC);
                
                $stmt = $pdo->prepare("SELECT id, name FROM members WHERE idx = :idx");
                $stmt->bindValue(':idx', $item["user_idx"]);
                $stmt->execute();
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                array_push($response["body"]["billing-records"], array(
                    "idx" => $item["idx"],
                    "user" => array(
                        "idx" => $item["user_idx"],
                        "id" => $user["id"],
                        "name" => $user["name"]
                    ),
                    "approvedAt" => $item["approved_at"],
                    "teacher" => $teacher["name"],
                    "orderId" => $item["orderId"],
                    "status" => $item["status"],
                    "amount" => $item["amount"],
                    "method" => $item["method"]." (".$item["method_info"].")",
                    "createdClassCnt" => $createdClassCnt,
                    "leftClassCnt" => $leftClassCnt
                ));

                continue;
            }

            // 결제 조회 API 호출
            $curlHandle = curl_init();
            curl_setopt_array($curlHandle, [
                CURLOPT_URL => "https://api.tosspayments.com/v1/payments/".$item['paymentKey'],
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
                
                $stmt = $pdo->prepare("SELECT name FROM teachers WHERE idx = :idx");
                $stmt->bindValue(':idx', $item["teacher"]);
                $stmt->execute();
                $teacher = $stmt->fetch(PDO::FETCH_ASSOC);
                
                $stmt = $pdo->prepare("SELECT id, name FROM members WHERE idx = :idx");
                $stmt->bindValue(':idx', $item["user_idx"]);
                $stmt->execute();
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if($item["status"] == $responseJson->status) $status = $responseJson->status;
                else $status = $item["status"];

                array_push($response["body"]["billing-records"], array(
                    "idx" => $item["idx"],
                    "user" => array(
                        "idx" => $item["user_idx"],
                        "id" => $user["id"],
                        "name" => $user["name"]
                    ),
                    "approvedAt" => $responseJson->approvedAt,
                    "teacher" => $teacher["name"],
                    "orderId" => $responseJson->orderId,
                    "status" => $status,
                    "amount" => $responseJson->totalAmount,
                    "method" => $responseJson->method.$method_info,
                    "createdClassCnt" => $createdClassCnt,
                    "leftClassCnt" => $leftClassCnt
                ));
            } else {
                $response = array(
                    "header" => array(
                        "result" => "error",
                        "message" => "결제 정보 조회에 실패했습니다."
                    )
                );
                goto response_handling;
            }
        }
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