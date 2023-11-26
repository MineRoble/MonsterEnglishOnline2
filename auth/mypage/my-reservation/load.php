<?php
include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_config.php";

if( isset($_POST['sortKey']) && isset($_POST['sortOrder']) && isset($_POST['itemPerPage']) && isset($_POST['page']) && isset($_POST['hideCancelledClass']) && isset($_POST['hideEndedClass']) ) {
    $sortKey = $_POST['sortKey'];
    $sortOrder = $_POST['sortOrder'];

    if(is_numeric($_POST['itemPerPage'])) $itemPerPage = intval($_POST['itemPerPage']);
    else goto handleValidationFailure;
    if(is_numeric($_POST['page'])) $page = intval($_POST['page']);
    else goto handleValidationFailure;

    if( $sortKey == "sortby_idx" ) $sortKey = "idx";
    elseif( $sortKey == "sortby_date" ) $sortKey = "date";
    elseif( $sortKey == "sortby_teacher" ) $sortKey = "teacher";
    elseif( $sortKey == "sortby_time" ) $sortKey = "start_time";
    elseif( $sortKey == "sortby_orderId" ) $sortKey = "orderId";
    // elseif( $sortKey == "sortby_status" ) $sortKey = "status";
    else goto handleValidationFailure;

    if($sortOrder == "asc") $sortOrder = "ASC";
    elseif($sortOrder == "desc") $sortOrder = "DESC";
    else goto handleValidationFailure;

    if($_POST["hideCancelledClass"] == "true") $hideCancelledClass = true;
    elseif($_POST["hideCancelledClass"] == "false") $hideCancelledClass = false;
    else goto handleValidationFailure;
    if($_POST["hideEndedClass"] == "true") $hideEndedClass = true;
    elseif($_POST["hideEndedClass"] == "false") $hideEndedClass = false;
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

    // $orderBySyntax = "ORDER BY $sortKey $sortOrder";
    // $limitSyntax = "LIMIT ".($itemPerPage*($page-1)).", ".$itemPerPage;
    // // $stmt = $pdo->prepare("SELECT method, paymentKey, teacher FROM tosspayments WHERE user_idx = :user_idx AND status IN ('IN_PROGRESS', 'WAITING_FOR_DEPOSIT', 'DONE', 'CANCELED', 'PARTIAL_CANCELED', 'ABORTED', 'EXPIRED') $orderBySyntax $limitSyntax");
    // $stmt = $pdo->prepare("SELECT * FROM tosspayments WHERE user_idx = :user_idx AND status IN ('IN_PROGRESS', 'WAITING_FOR_DEPOSIT', 'DONE', 'CANCELED', 'PARTIAL_CANCELED', 'ABORTED', 'EXPIRED', 'PARTIAL_REFUNDED', 'REFUNDED') $orderBySyntax $limitSyntax");
    // $stmt->bindValue(':user_idx', $_SESSION["user_idx"]);
    // $stmt->execute();
    // $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // $stmt = $pdo->prepare("SELECT COUNT(*) as cnt FROM tosspayments WHERE user_idx = :user_idx AND status IN ('IN_PROGRESS', 'WAITING_FOR_DEPOSIT', 'DONE', 'CANCELED', 'PARTIAL_CANCELED', 'ABORTED', 'EXPIRED', 'PARTIAL_REFUNDED', 'REFUNDED')");
    // $stmt->bindValue(':user_idx', $_SESSION["user_idx"]);
    // $stmt->execute();
    // $count = $stmt->fetchColumn();
    
    $whereSyntax = "WHERE user_idx = :user_idx";
    if($hideCancelledClass) $whereSyntax .= " AND isCancelled = 0";
    if($hideEndedClass) {
        if($hideCancelledClass) $whereSyntax .= " AND ( `date` > CURRENT_DATE OR (`date` = CURRENT_DATE AND `start_time` > CURRENT_TIME) )";
        else $whereSyntax .= " AND ( ( `date` > CURRENT_DATE OR (`date` = CURRENT_DATE AND `start_time` > CURRENT_TIME) ) OR ( (date < CURRENT_DATE OR (date = CURRENT_DATE AND start_time < CURRENT_TIME)) AND isCancelled = 1 ) )";
    }

    $orderBySyntax = "ORDER BY $sortKey $sortOrder";
    $limitSyntax = "LIMIT ".($itemPerPage*($page-1)).", ".$itemPerPage;
    $stmt = $pdo->prepare("SELECT idx, teacher_idx as teacher, date, DATE_FORMAT(start_time, '%H:%i') as start_time, DATE_FORMAT(end_time, '%H:%i') as end_time, isCancelled, orderId FROM schedule $whereSyntax $orderBySyntax $limitSyntax");
    $stmt->bindValue(':user_idx', $_SESSION["user_idx"]);
    $stmt->execute();
    $schedules = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare("SELECT COUNT(*) as cnt FROM schedule $whereSyntax");
    $stmt->bindValue(':user_idx', $_SESSION["user_idx"]);
    $stmt->execute();
    $count = $stmt->fetchColumn();

    foreach($schedules as $schedule_key => $schedule) {
        $stmt = $pdo->prepare("SELECT name FROM teachers WHERE idx = :idx");
        $stmt->bindValue(':idx', $schedule["teacher"]);
        $stmt->execute();
        $schedules[$schedule_key]["teacher"] = $stmt->fetchColumn();
    }
    
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
                "message" => "",
            ),
            "body" => array(
                "schedules" => $schedules,
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