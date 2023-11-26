<?php
include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_config.php";

if(
    !isset($_POST["year"]) || !isset($_POST["month"]) ||
    !is_numeric($_POST["year"]) || !is_numeric($_POST["month"])
) {
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

    $stmt = $pdo->prepare("SELECT idx, name FROM `teachers`");
    $stmt->execute();
    $teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if($teachers === false) {
        $response = array(
            "header" => array(
                "result" => "error",
                "message" => "데이터베이스 오류가 발생하였습니다."
            )
        );
        goto response_handling;
    }
    
    $response = array(
        "header" => array(
            "result" => "success",
            "message" => ""
        ),
        "body" => array(
            "counts" => array()
        )
    );

    foreach($teachers as $item) {
        $stmt = $pdo->prepare("SELECT count(*) as cnt FROM `schedule` WHERE teacher_idx = :teacher_idx AND iscancelled = 0 AND user_idx != -1 AND date BETWEEN :start_date AND :end_date");
        $stmt->bindValue(':teacher_idx', $item["idx"]);
        $stmt->bindValue(':start_date', date('Y-m-d', strtotime("{$_POST["year"]}-{$_POST["month"]}-01")));
        $stmt->bindValue(':end_date', date('Y-m-t', strtotime("{$_POST["year"]}-{$_POST["month"]}-01")));
        $stmt->execute();
        $count = $stmt->fetch(PDO::FETCH_ASSOC);

        if($count === false) {
            $response = array(
                "header" => array(
                    "result" => "error",
                    "message" => "데이터베이스 오류가 발생하였습니다."
                )
            );
            goto response_handling;
        }

        $response["body"]["counts"][$item["idx"]] = array(
            "name" => $item["name"],
            "total" => $count["cnt"]
        );

        for($i=1; $i<=intval(date('t', strtotime("{$_POST["year"]}-{$_POST["month"]}-01"))); $i++) {
            $stmt = $pdo->prepare("SELECT count(*) as cnt FROM `schedule` WHERE teacher_idx = :teacher_idx AND iscancelled = 0 AND user_idx != -1 AND date = :date");
            $stmt->bindValue(':teacher_idx', $item["idx"]);
            $stmt->bindValue(':date', date('Y-m-d', strtotime("{$_POST["year"]}-{$_POST["month"]}-{$i}")));
            $stmt->execute();
            $count = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if($count === false) {
                $response = array(
                    "header" => array(
                        "result" => "error",
                        "message" => "데이터베이스 오류가 발생하였습니다."
                    )
                );
                goto response_handling;
            }

            $response["body"]["counts"][$item["idx"]][$i] = $count["cnt"];
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