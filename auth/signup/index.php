<?php
    $GLOBALS["page-title"] = "회원가입";

    include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_config.php";

    if( $_SESSION['login'] ) {
        $redirect = "/";
        if( isset($_GET['redirect']) ) $redirect = $_GET['redirect'];
        header("Location: ".$redirect);
        exit();
    }
    
    include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_header.php";
?>
<main class="mx-auto py-5 px-3" style="max-width: 550px;">
    <form>
        <h1 class="h3 mb-3 fw-normal">회원가입하기</h1>

        <div class="mb-3">
            <label for="user_id" class="form-label">아이디</label>
            <input type="text" class="form-control" id="user_id" aria-describedby="idRequirements" minlength="4" required>
            <div id="idRequirements" class="form-text">영문과 숫자로만 이루어진 4자리 이상 아이디</div>
        </div>
        <div class="mb-3">
            <label for="user_pwd" class="form-label">비밀번호</label>
            <input type="password" class="form-control" id="user_pwd" aria-describedby="passwordRequirements" minlength="8" required>
            <div id="passwordRequirements" class="form-text">대소문자, 숫자, 특수기호(!@#$%^&*?)로만 이루어진 8자리 이상 암호</div>
        </div>
        <div class="mb-3">
            <label for="user_repwd" class="form-label">비밀번호 재입력</label>
            <input type="password" class="form-control" id="user_repwd" required>
        </div>
        <div class="mb-3">
            <label for="user_name" class="form-label">학생 이름</label>
            <input type="text" class="form-control" id="user_name" required>
        </div>
        <div class="mb-3">
            <label for="user_phone" class="form-label">전화번호</label>
            <input type="text" class="form-control" id="user_phone" aria-describedby="phoneDiscription" pattern="^[0-9]{11,12}$" required>
            <div id="phoneDiscription" class="form-text">'-' 없이 11~12자리 숫자만 입력</div>
        </div>
        <div class="mb-3">
            <label for="user_email" class="form-label">이메일 주소 (선택)</label>
            <input type="email" class="form-control" id="user_email">
        </div>

        <div class="checkbox">
            <label class="user-select-none">
                <input type="checkbox" id="privacy" required> 개인정보 수집 및 이용 동의 <a href="/terms/privacy/" target="_blank">[보기]</a>
            </label>
        </div>
        <div class="checkbox mb-3">
            <label class="user-select-none">
                <input type="checkbox" id="user-agreement" required> 서비스 이용약관 동의 <a href="/terms/user-agreement/" target="_blank">[보기]</a>
            </label>
        </div>

        <button class="w-100 btn btn-lg btn-primary mb-3" type="submit">로그인</button>

        <p class="text-muted">* 아이디, 비밀번호 분실시 문의바랍니다.</p>
        <p class="text-muted">* 회원가입시 관리자의 승인 이후 로그인 가능합니다.</p>
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

        fetch("process.php", {
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
            if(data['header']['status'] == "success") {
                alert(data['header']['message']);
                window.location.href = "welcome.php";
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
    include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_footer.php";
?>