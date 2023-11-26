<?php
include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_config.php";

if(
    !isset($_POST["teacher"]) || !isset($_POST["start_date"]) || !isset($_POST["end_date"]) || !is_numeric($_POST["teacher"]) ||
    !preg_match("/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/", $_POST["start_date"]) ||
    !preg_match("/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/", $_POST["end_date"])
) {
    $response = array(
        "header" => array(
            "result" => "error",
            "message" => "Required parameters are missing."
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

    $stmt = $pdo->prepare("SELECT idx, user_idx, teacher_idx, date, DATE_FORMAT(start_time, '%k:%i') AS start_time, DATE_FORMAT(end_time, '%k:%i') AS end_time, orderId, isCancelled FROM schedule WHERE `teacher_idx` = :teacher_idx AND `date` BETWEEN :start_date AND :end_date");
    $stmt->bindValue(':teacher_idx', $_POST["teacher"]);
    $stmt->bindValue(':start_date', $_POST["start_date"]);
    $stmt->bindValue(':end_date', $_POST["end_date"]);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $response = array(
        "header" => array(
            "result" => "success",
            "message" => ""
        ),
        "body" => array(
            "schedule" => array()
        )
    );

    foreach($result as $item) {
        if($item["user_idx"] == -1) {
            if($item["isCancelled"] !== 0) continue;
            $user["name"] = "Banned Class Time";
            $user["id"] = "Banned Class Time";
        } else {
            $stmt = $pdo->prepare("SELECT id, name FROM members WHERE `idx` = :idx");
            $stmt->bindValue(':idx', $item["user_idx"]);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        
        $stmt = $pdo->prepare("SELECT name FROM teachers WHERE `idx` = :idx");
        $stmt->bindValue(':idx', $item["teacher_idx"]);
        $stmt->execute();
        $teacher = $stmt->fetch(PDO::FETCH_ASSOC);

        array_push($response["body"]["schedule"], array(
            "idx" => $item["idx"],
            "user" => array(
                "idx" => $item["user_idx"],
                "name" => $user["name"],
                "id" => $user["id"]
            ),
            "teacher" => array(
                "idx" => $item["teacher_idx"],
                "name" => $teacher["name"]
            ),
            "date" => $item["date"],
            "start_time" => $item["start_time"],
            "end_time" => $item["end_time"],
            "orderId" =>  $item["orderId"],
            "isCancelled" => $item["isCancelled"] !== 0
        ));
    }
    
} catch (PDOException $e) {
    $response = array(
        "header" => array(
            "result" => "error",
            "message" => "A database error occurred."
        )
    );
}

response_handling:
header('Content-type: application/json');
echo json_encode($response);