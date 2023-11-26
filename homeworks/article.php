<?php
    include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_config.php";
    
    function file_size($size) {
        $sizes = array(" Bytes", " KB", " MB", " GB", " TB", " PB", " EB", " ZB", " YB");
        if ($size == 0) {
            return('n/a');
        } else {
            return (round($size/pow(1024, ($i = floor(log($size, 1024)))), 2) . $sizes[$i]);
        }
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

        $stmt = $pdo->prepare("SELECT * FROM homeworks_article WHERE is_del = 0 AND idx = :idx");
        $stmt->bindValue(':idx', $_GET["idx"]);
        $stmt->execute();
        $homework_article = $stmt->fetch(PDO::FETCH_ASSOC);

        if($homework_article === false) {
            $response = array(
                "header" => array(
                    "result" => "error",
                    "message" => "존재하지 않는 글입니다."
                )
            );
        } else {
            $stmt = $pdo->prepare("SELECT * FROM homeworks_files WHERE article = :article");
            $stmt->bindValue(':article', $_GET["idx"]);
            $stmt->execute();
            $homeworks_files = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            $stmt = $pdo->prepare("SELECT * FROM members WHERE idx = :idx");
            $stmt->bindValue(':idx', $homework_article["writer"]);
            $stmt->execute();
            $member = $stmt->fetch(PDO::FETCH_ASSOC);
    
            $response = array(
                "header" => array(
                    "result" => "success"
                ),
                "body" => array(
                    "article" => array(
                        "title" => $homework_article["title"],
                        "writer" => $member,
                        "created_at" => date("Y-m-d H:i:s", strtotime($homework_article["created_at"])),
                        "body" => $homework_article["body"],
                        "files" => $homeworks_files
                    )
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

    if($response["header"]["result"] == "success") {
        $GLOBALS["page-title"] = $response["body"]["article"]["title"];
        include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_header.php";
?>

<main class="container-md py-5">
    <div class="d-flex flex-column">
        <p class="h1"><?php echo $response["body"]["article"]["title"]; ?></p>
        <div class="d-flex gap-3">
            <p class="fw-bold"><?php echo $response["body"]["article"]["writer"]["name"]; ?></p>
            <p class="text-muted"><?php echo $response["body"]["article"]["created_at"]; ?></p>
        </div>
    </div>

    <hr>

    <div style="white-space: pre-wrap; line-height: 32px;" id="content"><?php echo $response["body"]["article"]["body"]; ?></div>

    <?php
        if(count($response["body"]["article"]["files"]) > 0) {
            echo '<hr> <p class="h4 d-flex gap-3 mb-3"> <i class="bi bi-files"></i> <span>첨부파일 '.count($response["body"]["article"]["files"]).'개</span> </p> <div class="list-group mb-3">';
            foreach($response["body"]["article"]["files"] as $file) {
                ?>
                        <a href="download.php?idx=<?php echo $file["idx"]; ?>" class="list-group-item list-group-item-action d-flex w-100 justify-content-between" target="_blank">
                            <span class="d-flex gap-3">
                                <i class="bi bi-download"></i>
                                <span><?php echo $file["name"]; ?></span>
                            </span>
                            <span class="text-muted"><?php echo file_size($file["size"]); ?></span>
                        </a>
                <?php
            }
            echo '</div>';
        }
    ?>

    <hr>

    <div class="d-flex gap-3">
        <a href="/homeworks/" role="button" class="btn btn-secondary">목록으로</a>
        <?php
            if($_SESSION['user']['permission'] >= 2) {
        ?>
            <a href="edit.php?idx=<?php echo $_GET["idx"]; ?>" role="button" class="btn btn-primary">글 수정</a>
            <button class="btn btn-danger" onclick="deleteArticle();">글 삭제</button>
        <?php
            }
        ?>
    </div>
</main>

<script>
    function deleteArticle() {
        if(confirm("정말로 글을 삭제하시겠습니까?")) {
            fetch("./processes/deleteArticle.php?idx=<?php echo $_GET["idx"]; ?>&areyousure=sure")
            .then(response => {
                if (!response.ok) {
                throw new Error('에러 발생: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                if(data['header']['result'] == "success") {
                    alert(data['header']['message']);
                    window.location = "/homeworks/";
                } else {
                    alert(data['header']['message']);
                }
            })
            .catch(error => {
                alert("서버와 통신 중에 오류가 발생했습니다.");
                console.log(error);
            });
        }
    }
</script>

<?php
    } else {
        $GLOBALS["page-title"] = "존재하지 않는 게시글";
        include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_header.php";

        echo '<main class="container-md py-5"> <h1>';
        echo $response["header"]["message"];
        echo '</h1> <hr> <a href="/homeworks/" role="button" class="btn btn-secondary">목록으로</a> </main>';
    }
    include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_footer.php";