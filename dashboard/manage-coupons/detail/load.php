<?php
include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_config.php";

if( isset($_POST['sortKey']) && isset($_POST['sortOrder']) && isset($_POST['itemPerPage']) && isset($_POST['page']) && isset($_POST['user_idx']) ) {
    $sortKey = $_POST['sortKey'];
    $sortOrder = $_POST['sortOrder'];

    if(is_numeric($_POST['user_idx'])) $user_idx = intval($_POST['user_idx']);
    else goto handleValidationFailure;

    if(is_numeric($_POST['itemPerPage'])) $itemPerPage = intval($_POST['itemPerPage']);
    else goto handleValidationFailure;
    if(is_numeric($_POST['page'])) $page = intval($_POST['page']);
    else goto handleValidationFailure;

    if( $sortKey == "sortby_idx" ) $sortKey = "idx";
    elseif( $sortKey == "sortby_amount" ) $sortKey = "amount";
    elseif( $sortKey == "sortby_usable" ) $sortKey = "usable";
    elseif( $sortKey == "sortby_createdAt" ) $sortKey = "createdAt";
    elseif( $sortKey == "sortby_orderId" ) $sortKey = "orderId";
    elseif( $sortKey == "sortby_fromKey" ) $sortKey = "from_key";
    elseif( $sortKey == "sortby_fromValue" ) $sortKey = "from_value";
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

    $whereSyntax = "WHERE user_idx = :user_idx";
    if( isset($_POST['queryType']) && isset($_POST['searchQuery']) ) {
        $searchQuery = $pdo->quote("%".str_replace("%", "\\%", $_POST['searchQuery'])."%");
        
        if($_POST['queryType'] == "period") {
            $search_dates = explode("~", $_POST['searchQuery']);
            
            $search_date = array();
            $search_date[0] = $pdo->quote($search_dates[0]." 00:00:00");
            $search_date[1] = $pdo->quote($search_dates[1]." 23:59:59");
            
            if($search_dates[0] == "") $whereSyntax .= " AND created_at <= {$search_date[1]}";
            elseif($search_dates[1] == "") $whereSyntax .= " AND created_at >= {$search_date[0]}";
            else $whereSyntax .= " AND created_at BETWEEN {$search_date[0]} AND {$search_date[1]}";
        }
        elseif($_POST['queryType'] == "memo") $whereSyntax .= " AND memo LIKE {$searchQuery}";
        elseif($_POST['queryType'] == "orderId") {
            $stmt = $pdo->prepare("SELECT idx FROM `tosspayments` WHERE orderId LIKE {$searchQuery}");
            $stmt->execute();
            $searchResult = $stmt->fetchAll(PDO::FETCH_COLUMN);
            if(count($searchResult) <= 0 ) $whereSyntax .= " AND 0";
            else $whereSyntax .= " AND teacher_idx IN (".implode(',', $searchResult).")";
        }
        else {
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
    $stmt = $pdo->prepare("SELECT * FROM `coupons` $whereSyntax $orderBySyntax $limitSyntax");
    $stmt->bindValue(':user_idx', $user_idx);
    $stmt->execute();
    $coupons = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare("SELECT COUNT(*) as cnt FROM `coupons` $whereSyntax");
    $stmt->bindValue(':user_idx', $user_idx);
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
                "message" => ""
            ),
            "body" => array(
                "coupons" => $coupons,
                "count" => $count
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