<?php
    $GLOBALS["page-title"] = "교사 수정";

    include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_config.php";
    include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_header.php";
    include_once $_SERVER["DOCUMENT_ROOT"]."/dashboard/_dashboard_header.php";

    if(isset($_GET["idx"])) {
        try {
            $pdo = new PDO(
                "mysql:host="._db_address.";dbname="._db_name.";charset=utf8",
                _db_id,_db_pwd,
                array(
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_EMULATE_PREPARES => false
                )
            );

            $stmt = $pdo->prepare("SELECT * FROM teachers WHERE idx = :user_idx");
            $stmt->bindValue(':user_idx', $_GET["idx"]);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if($result === false) {
                $response = array(
                    "header" => array(
                        "result" => "error",
                        "message" => "데이터가 없습니다."
                    )
                );
            } else {
                $response = array(
                    "header" => array(
                        "result" => "success"
                    ),
                    "body" => array(
                        "teacher" => $result
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
    } else {
        $response = array(
            "header" => array(
                "result" => "error",
                "message" => "데이터가 없습니다."
            )
        );
    }
?>

    <main>
        <p class="h1 fw-bold">교사 수정</p>

        <form>
            <input type="hidden" id="idx" value="<?php echo $response["body"]["teacher"]["idx"]; ?>">
            
            <div class="mb-3">
                <label for="teacher_name" class="form-label">이름</label>
                <input type="text" class="form-control" id="teacher_name" value="<?php echo $response["body"]["teacher"]["name"]; ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="teacher_description" class="form-label">소개</label>
                <textarea class="form-control" rows="10" id="teacher_description" required><?php echo $response["body"]["teacher"]["description"]; ?></textarea>
            </div>
            
            <div class="mb-3">
                <label for="teacher_img" class="form-label">이미지</label>
                <input class="form-control mb-3" type="file" id="teacher_img" onchange="previewImg(this);">

                <div class="d-flex gap-3 mb-3">
                    <button type="button" class="btn btn-primary" onclick="backToOriginImg();">기존 이미지로 돌아가기</button>
                    <button type="button" class="btn btn-primary" onclick="setDefaultImg();">기본 이미지로 설정하기</button>
                </div>

                <div class="ratio ratio-1x1 border" id="profileImg" style="resize: horizontal; overflow: auto; width: 512px; background-image: url('/assets/teachers/?idx=<?php echo $response["body"]["teacher"]["idx"]; ?>'); background-position: center; background-size: contain; background-repeat: no-repeat;"></div>
            </div>

            <div class="row gap-3 p-3">
                <button class="btn btn-lg btn-primary col-sm" type="submit">수정하기</button>
                <button class="btn btn-lg btn-danger col-sm" id="delTeacherBtn" type="button">삭제하기</button>
            </div>
        </form>
    </main>
    
    <script>
        document.querySelector("form").addEventListener("submit", (e) => {
            e.preventDefault();

            const formData = new FormData();
            formData.append("idx", document.getElementById("idx").value);
            formData.append("teacher_name", document.getElementById("teacher_name").value);
            formData.append("teacher_description", document.getElementById("teacher_description").value);
            if(
                document.getElementById("teacher_img").files &&
                document.getElementById("teacher_img").files[0] &&
                document.getElementById("teacher_img").files[0].type.match("image/.*")
            ) formData.append("teacher_img", document.getElementById("teacher_img").files[0], document.getElementById("teacher_img").files[0].name);
            else formData.append("teacher_img", "default");

            fetch("update.php", {
                method: "POST",
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                throw new Error('에러 발생: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                if(data['header']['result'] == "success") {
                    alert(data['header']['message']);
                    window.location.reload();
                } else {
                    alert(data['header']['message']);
                }
            })
            .catch(error => {
                alert("서버와 통신 중에 오류가 발생했습니다.");
                console.log(error);
            });
        });

        document.getElementById("delTeacherBtn").addEventListener("click", () => {
            const formData = new FormData();
            formData.append("idx", document.getElementById("idx").value);

            fetch("delete.php", {
                method: "POST",
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                throw new Error('에러 발생: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                if(data['header']['result'] == "success") {
                    alert(data['header']['message']);
                    window.location.href = "../";
                } else {
                    alert(data['header']['message']);
                }
            })
            .catch(error => {
                alert("서버와 통신 중에 오류가 발생했습니다.");
                console.log(error);
            });
        });


        function previewImg(ele) {
            let files = document.getElementById("teacher_img").files;

            // 인풋 태그에 파일이 있는 경우
            if(files && files[0]) {
                // 이미지 파일인지 검사
                if (!files[0].type.match("image/.*")) {
                    document.getElementById("teacher_img").value = '';
                    alert('이미지 파일만 업로드가 가능합니다.');
                    return false;
                }

                // FileReader 인스턴스 생성
                const reader = new FileReader();

                // 이미지가 로드가 된 경우
                reader.onload = e => {
                    document.getElementById("profileImg").style.backgroundImage = `url('${e.target.result}')`;
                }

                // reader가 이미지 읽도록 하기
                reader.readAsDataURL(files[0]);
            } else {
                backToOriginImg();
                // document.getElementById("profileImg").style.backgroundImage = "url('/assets/teachers/?idx=<?php echo $response["body"]["teacher"]["idx"]; ?>')";
                // document.getElementById("profileImg").style.backgroundImage = "url(https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460__480.png)";
            }
        }

        function backToOriginImg() {
            document.getElementById("teacher_img").value = '';
            document.getElementById("profileImg").style.backgroundImage = "url('/assets/teachers/?idx=<?php echo $response["body"]["teacher"]["idx"]; ?>')";
        }
        
        function setDefaultImg() {
            document.getElementById("teacher_img").value = '';
            document.getElementById("profileImg").style.backgroundImage = "url('/assets/teachers/')";
        }
    </script>

<?php
    include_once $_SERVER["DOCUMENT_ROOT"]."/dashboard/_dashboard_footer.php";
    include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_footer.php";
?>