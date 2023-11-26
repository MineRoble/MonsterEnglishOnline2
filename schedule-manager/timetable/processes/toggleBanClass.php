<?php
include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_config.php";

if(
    !isset($_POST["teacher"]) || !isset($_POST["date"]) || !isset($_POST["start_time"]) || !isset($_POST["end_time"]) || !is_numeric($_POST["teacher"]) ||
    !preg_match("/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/", $_POST["date"]) ||
    !preg_match("/^([0-9]{2}):([0-9]{2})$/", $_POST["start_time"]) ||
    !preg_match("/^([0-9]{2}):([0-9]{2})$/", $_POST["end_time"])
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
    
    $stmt = $pdo->prepare("SELECT count(*) as cnt, isCancelled FROM `schedule` WHERE `teacher_idx` = :teacher_idx AND `user_idx` = -1 AND `date` = :date AND `start_time` = :start_time AND `end_time` = :end_time");
    $stmt->bindValue(':teacher_idx', $_POST["teacher"]);
    $stmt->bindValue(':date', $_POST["date"]);
    $stmt->bindValue(':start_time', $_POST["start_time"]);
    $stmt->bindValue(':end_time', $_POST["end_time"]);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result["cnt"] > 0) {
        
        if($result["isCancelled"] == 0) {
            $memo = "\n ".date("Y-m-d H:i:s")." {$_SESSION['user_idx']}에 의해 해제됨.";
            $stmt = $pdo->prepare("UPDATE `schedule` SET `isCancelled`=1 , `memo`= CONCAT(`memo`, :memo) WHERE `teacher_idx` = :teacher_idx AND `user_idx` = -1 AND `date` = :date AND `start_time` = :start_time AND `end_time` = :end_time");
            $stmt->bindValue(':memo', $memo);
            $stmt->bindValue(':teacher_idx', $_POST["teacher"]);
            $stmt->bindValue(':date', $_POST["date"]);
            $stmt->bindValue(':start_time', $_POST["start_time"]);
            $stmt->bindValue(':end_time', $_POST["end_time"]);
            $result = $stmt->execute();
            $updatedRowCount = $stmt->rowCount();
            
            if($updatedRowCount > 0) {
                $response = array(
                    "header" => array(
                        "result" => "success",
                        "message" => "수업 금지 시간 해제가 완료되었습니다."
                    )
                );
            } else {
                $response = array(
                    "header" => array(
                        "result" => "error",
                        // "message" => "수업 금지 시간 설정 해제 중 오류가 발생했습니다."
                        "message" => "An error occurred while unbanning the banned class time."
                    )
                );
            }
        } else {
            $memo = "\n ".date("Y-m-d H:i:s")." {$_SESSION['user_idx']}에 의해 설정됨.";
            $stmt = $pdo->prepare("UPDATE `schedule` SET `isCancelled`=0 , `memo`= CONCAT(`memo`, :memo) WHERE `teacher_idx` = :teacher_idx AND `user_idx` = -1 AND `date` = :date AND `start_time` = :start_time AND `end_time` = :end_time");
            $stmt->bindValue(':memo', $memo);
            $stmt->bindValue(':teacher_idx', $_POST["teacher"]);
            $stmt->bindValue(':date', $_POST["date"]);
            $stmt->bindValue(':start_time', $_POST["start_time"]);
            $stmt->bindValue(':end_time', $_POST["end_time"]);
            $result = $stmt->execute();
            $updatedRowCount = $stmt->rowCount();
            
            if($updatedRowCount > 0) {
                $response = array(
                    "header" => array(
                        "result" => "success",
                        "message" => "수업 금지 시간 설정이 완료되었습니다."
                    )
                );
            } else {
                $response = array(
                    "header" => array(
                        "result" => "error",
                        "message" => "수업 금지 시간 설정 중 오류가 발생했습니다."
                    )
                );

            }
        }
    } else {
        $memo = date("Y-m-d H:i:s")." {$_SESSION['user_idx']}에 의해 생성됨.";
        $stmt = $pdo->prepare("INSERT INTO `schedule`(`teacher_idx`, `user_idx`, `date`, `start_time`, `end_time`, `memo`) VALUES (:teacher_idx, -1, :date, :start_time, :end_time, :memo)");
        $stmt->bindValue(':teacher_idx', $_POST["teacher"]);
        $stmt->bindValue(':date', $_POST["date"]);
        $stmt->bindValue(':start_time', $_POST["start_time"]);
        $stmt->bindValue(':end_time', $_POST["end_time"]);
        $stmt->bindValue(':memo', $memo);
        $result = $stmt->execute();
        $updatedRowCount = $stmt->rowCount();

        if ($updatedRowCount > 0) {
            $response = array(
                "header" => array(
                    "result" => "success",
                    "message" => "수업 금지 시간 설정이 완료되었습니다."
                )
            );
        } else {
            $response = array(
                "header" => array(
                    "result" => "error",
                    "message" => "수업 금지 시간 생성 중 오류가 발생했습니다."
                )
            );
        }
    }
    
} catch (PDOException $e) {
    $response = array(
        "header" => array(
            "result" => "error",
            "message" => "데이터베이스 오류가 발생하였습니다.",
            "e" => $e
        )
    );
}

response_handling:
header('Content-type: application/json');
echo json_encode($response);