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
        $GLOBALS["page-title"] = "게시글 수정";
        include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_header.php";
?>

<form class="container-md py-5" enctype="multipart/form-data">
    <div class="d-flex flex-column gap-3">
        <input class="form-control form-control-lg" type="text" placeholder="제목 입력" id="article_title" value="<?php echo $response["body"]["article"]["title"]; ?>">
        <div class="d-flex gap-3">
            <span class="fw-bold"><?php echo $_SESSION["user"]["name"]; ?></span>
            <span class="text-muted"><?php echo date("Y-m-d H:i:s"); ?></span>
        </div>
    </div>
    
    <hr>

    <div class="mb-3">
        <div class="d-flex gap-3 mb-3 user-select-none">
            <button type="button" class="btn btn-secondary" onclick="document.execCommand('bold', false, null);">Bold</button>
            <button type="button" class="btn btn-secondary" onclick="document.execCommand('italic', false, null);">Italic</button>
            <button type="button" class="btn btn-secondary" onclick="document.execCommand('underline', false, null);">Underline</button>
            <button type="button" class="btn btn-secondary" onclick="document.execCommand('strikeThrough', false, null);">Strike</button>

            <div class="btn-group">
                <button type="button" class="btn btn-secondary" onclick="addLink('inherit');">Link</button>
                <button type="button" class="btn btn-secondary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="visually-hidden">Toggle Dropdown</span>
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" role="button" onclick="addLink('#0d3efd');" style="color: #0d3efd;"><u>Primary</u></a></li>
                    <li><a class="dropdown-item" role="button" onclick="addLink('#198754');" style="color: #198754;"><u>Success</u></a></li>
                    <li><a class="dropdown-item" role="button" onclick="addLink('#dc3545');" style="color: #dc3545;"><u>Danger</u></a></li>
                    <li><a class="dropdown-item" role="button" onclick="addLink('#ffc107');" style="color: #ffc107;"><u>Warning</u></a></li>
                    <li><a class="dropdown-item" role="button" onclick="addLink('#0dcaf0');" style="color: #0dcaf0;"><u>Info</u></a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><button type="button" class="dropdown-item" onclick="addLink('default');"> <a href="#" onclick="return false;">Default</a> </button></li>
                </ul>
            </div>

            <div class="btn-group">
                <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                Highlight
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" role="button" onclick="document.execCommand('backColor', false, '#cfe2ff');" style="background-color: #cfe2ff;">Primary</a></li>
                    <li><a class="dropdown-item" role="button" onclick="document.execCommand('backColor', false, '#d1e7dd');" style="background-color: #d1e7dd;">Success</a></li>
                    <li><a class="dropdown-item" role="button" onclick="document.execCommand('backColor', false, '#f8d7da');" style="background-color: #f8d7da;">Danger</a></li>
                    <li><a class="dropdown-item" role="button" onclick="document.execCommand('backColor', false, '#fff3cd');" style="background-color: #fff3cd;">Warning</a></li>
                    <li><a class="dropdown-item" role="button" onclick="document.execCommand('backColor', false, '#cff4fc');" style="background-color: #cff4fc;">Info</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" role="button" onclick="document.execCommand('backColor', false, 'transparent');">Transparent</a></li>
                </ul>
            </div>

            <div class="btn-group">
                <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                Font Color
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" role="button" onclick="document.execCommand('foreColor', false, '#0d3efd');" style="color: #0d3efd;">Primary</a></li>
                    <li><a class="dropdown-item" role="button" onclick="document.execCommand('foreColor', false, '#198754');" style="color: #198754;">Success</a></li>
                    <li><a class="dropdown-item" role="button" onclick="document.execCommand('foreColor', false, '#dc3545');" style="color: #dc3545;">Danger</a></li>
                    <li><a class="dropdown-item" role="button" onclick="document.execCommand('foreColor', false, '#ffc107');" style="color: #ffc107;">Warning</a></li>
                    <li><a class="dropdown-item" role="button" onclick="document.execCommand('foreColor', false, '#0dcaf0');" style="color: #0dcaf0;">Info</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" role="button" onclick="document.execCommand('foreColor', false, '#000000');">Black</a></li>
                </ul>
            </div>

            <div class="btn-group">
                <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    Font Size
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" role="button" onclick="document.execCommand('fontSize', false, '1');" style="font-size: 10px;">10px</a></li>
                    <li><a class="dropdown-item" role="button" onclick="document.execCommand('fontSize', false, '2');" style="font-size: 13px;">13px</a></li>
                    <li><a class="dropdown-item" role="button" onclick="document.execCommand('fontSize', false, '3');" style="font-size: 16px;">16px (Default)</a></li>
                    <li><a class="dropdown-item" role="button" onclick="document.execCommand('fontSize', false, '4');" style="font-size: 18px;">18px</a></li>
                    <li><a class="dropdown-item" role="button" onclick="document.execCommand('fontSize', false, '5');" style="font-size: 24px;">24px</a></li>
                    <li><a class="dropdown-item" role="button" onclick="document.execCommand('fontSize', false, '6');" style="font-size: 32px;">32px</a></li>
                    <li><a class="dropdown-item" role="button" onclick="document.execCommand('fontSize', false, '7');" style="font-size: 48px;">48px</a></li>
                </ul>
            </div>
        </div>

        <div id="article_body" class="form-control overflow-y-scroll" style="height: 512px; line-height: 32px;" contenteditable="true" spellcheck="false"><?php echo $response["body"]["article"]["body"]; ?></div>
    </div>

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
            echo '</div> <p class="text-muted">* 첨부파일은 수정하실 수 없습니다.</p>';
        }
    ?>

    <hr>

    <div class="d-flex gap-3">
        <button type="submit" class="btn btn-primary">수정하기</button>
        <a href="/homeworks/" role="button" class="btn btn-secondary">목록으로</a>
    </div>
</form>

<script>
    let attachedFiles = [];
    let leftMessage = function (event) {
        event.returnValue = "변경사항이 저장되지 않을 수 있습니다.";
    };


    function addLink(colorType) {
        var range = window.getSelection().getRangeAt(0);
        var selectedText = range.toString();
        var link = document.createElement("a");
        link.href = selectedText;
        link.textContent = selectedText;
        if(colorType != "default") link.style.color = colorType;
        range.deleteContents();
        range.insertNode(link);
    }

    
    document.querySelector("form").addEventListener("submit", function(event) {
        event.preventDefault();

        let formData = new FormData();
        formData.append("article_title", document.getElementById("article_title").value);
        formData.append("article_body", document.getElementById("article_body").innerHTML);
        formData.append("idx", <?php echo $_GET["idx"]; ?>);
        
        fetch('./processes/editing.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(response.statusText);
            }
            return response.json();
        })
        .then(data => {
            if(data["header"]["result"] == "success") {
                window.removeEventListener("beforeunload", leftMessage);

                window.location = "article.php?idx=<?php echo $_GET["idx"]; ?>";
            }
            else alert(data["header"]["message"]);
        })
        .catch(error => {
            alert("Fech API 에러 발생");
        });
    });

    window.addEventListener("beforeunload", leftMessage);
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