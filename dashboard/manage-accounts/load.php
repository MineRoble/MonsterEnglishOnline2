<?php
include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_config.php";

if( isset($_POST['sortKey']) && isset($_POST['sortOrder']) && isset($_POST['itemPerPage']) && isset($_POST['page']) ) {
    $sortKey = $_POST['sortKey'];
    $sortOrder = $_POST['sortOrder'];

    if(is_numeric($_POST['itemPerPage'])) $itemPerPage = intval($_POST['itemPerPage']);
    else goto handleValidationFailure;
    if(is_numeric($_POST['page'])) $page = intval($_POST['page']);
    else goto handleValidationFailure;

    if( $sortKey == "user_idx" ) $sortKey = "idx";
    elseif( $sortKey == "user_id" ) $sortKey = "id";
    elseif( $sortKey == "user_name" ) $sortKey = "name";
    elseif( $sortKey == "user_teacher" ) $sortKey = "teacher_idx";
    elseif( $sortKey == "user_grade" ) $sortKey = "grade";
    elseif( $sortKey == "user_permission" ) $sortKey = "permission";
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
        elseif($_POST['queryType'] == "permission") $whereSyntax .= " AND permission = ".$pdo->quote($_POST["searchQuery"]);
        elseif($_POST['queryType'] == "teacher") {
            $stmt = $pdo->prepare("SELECT idx FROM teachers where name LIKE {$searchQuery}");
            $stmt->execute();
            $searchResult = $stmt->fetchAll(PDO::FETCH_COLUMN);

            if(count($searchResult) <= 0 ) $whereSyntax .= " AND 0";
            else $whereSyntax .= " AND teacher_idx IN (".implode(',', $searchResult).")";
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
    // die("SELECT idx, id, name, teacher_idx, grade, permission FROM members $whereSyntax $orderBySyntax $limitSyntax");
    $stmt = $pdo->prepare("SELECT idx, id, name, teacher_idx, grade, permission FROM members $whereSyntax $orderBySyntax $limitSyntax");
    $stmt->execute();
    $members = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare("SELECT COUNT(*) as cnt FROM members $whereSyntax");
    $stmt->execute();
    $count = $stmt->fetchColumn();
    
    $stmt = $pdo->prepare("SELECT idx, name FROM teachers");
    $stmt->execute();
    $teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
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
                "members" => $members,
                "teachers" => array(
                    "-1" => "선생님 미정"
                ),
                "count" => $count
            )
        );

        foreach($teachers as $teacher) {
            $response["body"]["teachers"][strval($teacher["idx"])] = $teacher["name"];
        }

        // foreach($result as $item) {
        //     $stmt = $pdo->prepare("SELECT name FROM teachers WHERE `idx` = :idx");
        //     $stmt->bindValue(':idx', $item["teacher_idx"]);
        //     $stmt->execute();
        //     $teacher = $stmt->fetch(PDO::FETCH_ASSOC);

        //     array_push($response["body"]["members"], array(
        //         "idx" => $item["idx"],
        //         "id" => $item["id"],
        //         "name" => $item["name"],
        //         "teacher" => array(
        //             "idx" => $item["teacher_idx"],
        //             "name" => $teacher["name"]
        //         ),
        //         "grade" => $item["grade"],
        //         "permission" => $item["permission"]
        //     ));
        // }
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
if( isset($response['debug']) && _server_type != "dev") unset($response['debug']);
header('Content-type: application/json');
echo json_encode($response);