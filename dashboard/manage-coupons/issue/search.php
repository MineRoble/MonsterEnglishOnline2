<?php
include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_config.php";

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

    $stmt = $pdo->prepare("SELECT idx, id, name, teacher_idx, grade, permission FROM members $whereSyntax LIMIT 100");
    $stmt->execute();
    $members = $stmt->fetchAll(PDO::FETCH_ASSOC);

    
    $response = array(
        "header" => array(
            "result" => "success",
            "message" => ""
        ),
        "body" => array(
            "members" => $members
        )
    );
    
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