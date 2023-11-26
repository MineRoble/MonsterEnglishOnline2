<?php
include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_config.php";

if( isset($_POST['sortKey']) && isset($_POST['sortOrder']) && isset($_POST['itemPerPage']) && isset($_POST['page']) ) {
    $sortKey = $_POST['sortKey'];
    $sortOrder = $_POST['sortOrder'];

    if(is_numeric($_POST['itemPerPage'])) $itemPerPage = intval($_POST['itemPerPage']);
    else goto handleValidationFailure;
    if(is_numeric($_POST['page'])) $page = intval($_POST['page']);
    else goto handleValidationFailure;

    if( $sortKey == "sortby_idx" ) $sortKey = "idx";
    elseif( $sortKey == "sortby_id" ) $sortKey = "id";
    elseif( $sortKey == "sortby_name" ) $sortKey = "name";
    elseif( $sortKey == "sortby_grade" ) $sortKey = "grade";
    elseif( $sortKey == "sortby_usable_copons" ) $sortKey = "sortby_usable_copons";
    elseif( $sortKey == "sortby_used_copons" ) $sortKey = "sortby_used_copons";
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

    $whereSyntax = "WHERE 1";
    if( isset($_POST['queryType']) && isset($_POST['searchQuery']) ) {
        $searchQuery = $pdo->quote("%".str_replace("%", "\\%", $_POST['searchQuery'])."%");
        
        if($_POST['queryType'] == "id") $whereSyntax .= " AND id LIKE {$searchQuery}";
        elseif($_POST['queryType'] == "name") $whereSyntax .= " AND name LIKE {$searchQuery}";
        elseif($_POST['queryType'] == "grade") $whereSyntax .= " AND grade = ".$pdo->quote($_POST["searchQuery"]);
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

    $stmt = $pdo->prepare("SELECT DISTINCT user_idx FROM `coupons`");
    $stmt->execute();
    $existing_members = $stmt->fetchAll(PDO::FETCH_COLUMN);
    if(count($existing_members) <= 0 ) $whereSyntax .= " AND 0";
    else $whereSyntax .= " AND idx IN (".implode(',', $existing_members).")";

    $orderBySyntax = "ORDER BY $sortKey $sortOrder";
    $limitSyntax = "LIMIT ".($itemPerPage*($page-1)).", ".$itemPerPage;
    $stmt = $pdo->prepare("SELECT idx, id, name, grade, permission FROM members $whereSyntax $orderBySyntax $limitSyntax");
    $stmt->execute();
    $members = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare("SELECT COUNT(*) as cnt FROM members $whereSyntax");
    $stmt->execute();
    $count = $stmt->fetchColumn();
    
    $stmt = $pdo->prepare("SELECT user_idx, SUM(amount) as total_amount, COUNT(amount) as cnt FROM `coupons` WHERE usable = 1 GROUP BY user_idx");
    $stmt->execute();
    $tmp_usable_coupons = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $usable_coupons = array();
    foreach($tmp_usable_coupons as $usable_coupon) {
        $usable_coupons[$usable_coupon["user_idx"]] = array(
            "total_amount" => $usable_coupon["total_amount"],
            "count" => $usable_coupon["cnt"]
        );
    }
    
    $stmt = $pdo->prepare("SELECT user_idx, SUM(amount) as total_amount, COUNT(amount) as cnt FROM `coupons` WHERE usable = 0 GROUP BY user_idx");
    $stmt->execute();
    $tmp_unusable_coupons = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $unusable_coupons = array();
    foreach($tmp_unusable_coupons as $unusable_coupon) {
        $unusable_coupons[$unusable_coupon["user_idx"]] = array(
            "total_amount" => $unusable_coupon["total_amount"],
            "count" => $unusable_coupon["cnt"]
        );
    }

    
    if ($count === false || $usable_coupons === false || $unusable_coupons === false) {
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
                "members" => $members,
                "usable_coupons" => $usable_coupons,
                "unusable_coupons" => $unusable_coupons,
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