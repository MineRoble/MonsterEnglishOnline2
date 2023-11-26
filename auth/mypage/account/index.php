<?php
    $GLOBALS["page-title"] = "계정 정보";

    include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_config.php";
    include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_header.php";
    include_once $_SERVER["DOCUMENT_ROOT"]."/auth/mypage/_mypage_header.php";

    if(isset($_POST["verify"])) {
        try {
            $pdo = new PDO(
                "mysql:host="._db_address.";dbname="._db_name.";charset=utf8",
                _db_id,_db_pwd,
                array(
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_EMULATE_PREPARES => false
                )
            );

            $stmt = $pdo->prepare("SELECT idx, pwd FROM members WHERE idx = :user_idx");
            $stmt->bindValue(':user_idx', $_SESSION["user_idx"]);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if (password_verify($_POST["verify"], $result['pwd'])) {
                $response = array(
                    "header" => array(
                        "result" => "success",
                        "message" => "비밀번호 인증에 성공했습니다."
                    )
                );
            } else {
                $response = array(
                    "header" => array(
                        "result" => "error",
                        "message" => "아이디 혹은 비밀번호가 올바르지 않습니다."
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
                "message" => "계속하려면 비밀번호를 입력하세요."
            )
        );
    }

    if($response["header"]["result"] == "success") {
?>

    <main>
        <p class="h1 fw-bold mb-3">계정 정보</p>

        <form>
            <div class="mb-3">
                <label for="user_id" class="form-label">아이디</label>
                <input type="text" class="form-control" id="user_id" aria-describedby="idRequirements" minlength="4" value="<?php echo $_SESSION["user"]['id'] ?>" required>
                <div id="idRequirements" class="form-text">영문과 숫자로만 이루어진 4자리 이상 아이디</div>
            </div>
            <div class="mb-3">
                <label for="user_pwd" class="form-label">현재 비밀번호</label>
                <input type="password" class="form-control" id="user_pwd" required>
            </div>
            <div class="mb-3">
                <label for="user_repwd" class="form-label">새 비밀번호 입력 (선택)</label>
                <input type="password" class="form-control" id="user_newpwd" aria-describedby="passwordRequirements" minlength="8">
                <div id="passwordRequirements" class="form-text">대소문자, 숫자, 특수기호(!@#$%^&*?)로만 이루어진 8자리 이상 암호</div>
            </div>
            <div class="mb-3">
                <label for="user_repwd" class="form-label">새 비밀번호 재입력 (선택)</label>
                <input type="password" class="form-control" id="user_renewpwd" minlength="8">
            </div>
            <div class="mb-3">
                <label for="user_name" class="form-label">학생 이름</label>
                <input type="text" class="form-control" id="user_name" value="<?php echo $_SESSION["user"]['name'] ?>" required>
            </div>
            <div class="mb-3">
                <label for="user_phone" class="form-label">전화번호</label>
                <input type="text" class="form-control" id="user_phone" aria-describedby="phoneDiscription" pattern="^[0-9]{11,12}$" value="<?php echo $_SESSION["user"]['phone'] ?>" required>
                <div id="phoneDiscription" class="form-text">'-' 없이 11~12자리 숫자만 입력</div>
            </div>
            <div class="mb-3">
                <label for="user_email" class="form-label">이메일 주소 (선택)</label>
                <input type="email" class="form-control" id="user_email" value="<?php echo $_SESSION["user"]['email'] ?>">
            </div>

            <div class="checkbox">
                <label class="user-select-none">
                    <input type="checkbox" id="privacy" checked required> 개인정보 수집 및 이용 동의 <a href="/terms/privacy/" target="_blank">[보기]</a>
                </label>
            </div>
            <div class="checkbox mb-3">
                <label class="user-select-none">
                    <input type="checkbox" id="user-agreement" checked required> 서비스 이용약관 동의 <a href="/terms/user-agreement/" target="_blank">[보기]</a>
                </label>
            </div>

            <button class="w-100 btn btn-lg btn-primary mb-3" type="submit">수정하기</button>

            <p class="text-muted">* 비밀번호 분실시 문의바랍니다.</p>
        </form>
    </main>
    
    <script>
        document.querySelector("form").addEventListener("submit", (e) => {
            e.preventDefault();

            const formData = new FormData(e.target);
            const inputElements = document.querySelector("form").querySelectorAll("input");
            
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
    } else {
?>
    <main class="py-5 px-3">
        <form class="mx-auto w-100" style="max-width: 550px;" method="post">
        
            <?php if($response["header"]["message"] != "계속하려면 비밀번호를 입력하세요.") { ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $response["header"]["message"]; ?>
                </div>
            <?php } ?>

            <p class="h4 mb-3 fw-normal">계속하려면 비밀번호를 입력하세요.</p>
            <div class="form-floating mb-3">
                <input type="password" class="form-control" id="floatingPassword" name="verify" placeholder="비밀번호" autofocus>
                <label for="floatingPassword">비밀번호</label>
            </div>

            <button class="w-100 btn btn-lg btn-primary mb-3" type="submit">확인</button>

            <p class="text-muted">* 비밀번호 분실시 문의바랍니다.</p>
        </form>
    </main>
<?php
    }

    include_once $_SERVER["DOCUMENT_ROOT"]."/dashboard/_dashboard_footer.php";
    include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_footer.php";
?>