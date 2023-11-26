<?php
    $GLOBALS["page-title"] = "계정 수정";

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

            $stmt = $pdo->prepare("SELECT * FROM members WHERE idx = :user_idx");
            $stmt->bindValue(':user_idx', $_GET["idx"]);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            $stmt = $pdo->prepare("SELECT idx, name FROM teachers");
            $stmt->execute();
            $teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
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
                        "user" => $result,
                        "teachers" => array_merge(
                            array(
                                -1 => 
                                array(
                                    "idx" => -1,
                                    "name" => "선생님 미정"
                                )
                            ), $teachers
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
        <p class="h1 fw-bold">계정 수정</p>

        <form>
            <input type="hidden" id="user_idx" value="<?php echo $response["body"]["user"]["idx"]; ?>">
            <div class="mb-3">
                <label for="user_id" class="form-label">아이디</label>
                <input type="text" class="form-control" id="user_id" aria-describedby="idRequirements" minlength="4" value="<?php echo $response["body"]["user"]["id"]; ?>" required>
                <div id="idRequirements" class="form-text">영문과 숫자로만 이루어진 4자리 이상 아이디</div>
            </div>
            <div class="mb-3">
                <label for="user_name" class="form-label">학생 이름</label>
                <input type="text" class="form-control" id="user_name" value="<?php echo $response["body"]["user"]["name"]; ?>" required>
            </div>
            <div class="mb-3">
                <label for="user_teacher" class="form-label">Teacher</label>
                <select class="form-select" id="user_teacher">
                    <?php
                        foreach($response["body"]["teachers"] as $teacher) {
                            $selected = $teacher["idx"] == $response["body"]["user"]["teacher_idx"] ? " selected" : "";
                            echo "<option value=\"{$teacher['idx']}\"{$selected}>{$teacher['name']}</option>";
                        }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="user_phone" class="form-label">전화번호</label>
                <input type="text" class="form-control" id="user_phone" aria-describedby="phoneDiscription" pattern="^[0-9]{11,12}$" value="<?php echo $response["body"]["user"]["phone"]; ?>" required>
                <div id="phoneDiscription" class="form-text">'-' 없이 11~12자리 숫자만 입력</div>
            </div>
            <div class="mb-3">
                <label for="user_email" class="form-label">이메일 주소 (선택)</label>
                <input type="email" class="form-control" id="user_email" value="<?php echo $response["body"]["user"]["email"]; ?>">
            </div>
            <div class="mb-3">
                <label for="user_permission" class="form-label">Permission</label>
                <select class="form-select" id="user_permission">
                    <option value="0" <?php echo $response["body"]["user"]["permission"]==0?"selected":""; ?>>미승인 (로그인할 수 없음)</option>
                    <option value="1" <?php echo $response["body"]["user"]["permission"]==1?"selected":""; ?>>학생/학부모</option>
                    <option value="2" <?php echo $response["body"]["user"]["permission"]==2?"selected":""; ?>>교사 (스케줄 메니저 이용 가능)</option>
                    <option value="3" <?php echo $response["body"]["user"]["permission"]==3?"selected":""; ?>>관리자 (대시보드 이용 가능)</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="user_grade" class="form-label">Grade</label>
                <input type="number" class="form-control" id="user_grade" value="<?php echo $response["body"]["user"]["grade"]; ?>">
            </div>

            <div class="row gap-3 p-3">
                <button class="btn btn-lg btn-primary col-sm" type="submit">수정하기</button>
                <button class="btn btn-lg btn-danger col-sm" id="resetPwdBtn" type="button">비밀번호 재설정</button>
            </div>

            <p class="text-muted">* 비밀번호 재설정시 임시 비밀번호가 발급됩니다.</p>
        </form>
    </main>
    
    <script>
        document.querySelector("form").addEventListener("submit", (e) => {
            e.preventDefault();

            // const formData = new FormData(e.target);
            const formData = new FormData();
            const inputElements = [
                ...document.querySelector("form").querySelectorAll("input"),
                ...document.querySelector("form").querySelectorAll("select")
            ];
            
            inputElements.forEach((ele) => {
                if (ele.type === "checkbox") {
                    if (ele.checked) formData.append(ele.id, "checked");
                } else {
                    formData.append(ele.id, ele.value);
                }
            });

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

        document.getElementById("resetPwdBtn").addEventListener("click", () => {
            const formData = new FormData();
            formData.append("user_idx", document.getElementById("user_idx").value);

            fetch("resetPwd.php", {
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
                    prompt(data['header']['message'], data["body"]["newPwd"]);
                } else {
                    alert(data['header']['message']);
                }
            })
            .catch(error => {
                alert("서버와 통신 중에 오류가 발생했습니다.");
                console.log(error);
            });
        });
    </script>

<?php
    include_once $_SERVER["DOCUMENT_ROOT"]."/dashboard/_dashboard_footer.php";
    include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_footer.php";
?>