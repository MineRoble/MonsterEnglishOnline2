<?php
    $GLOBALS["page-title"] = "로그인";

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
        <h1 class="h3 mb-3 fw-normal">로그인하기</h1>

        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="floatingID" name="user_id" placeholder="아이디">
            <label for="floatingID">아이디</label>
        </div>

        <div class="form-floating mb-3">
            <input type="password" class="form-control" id="floatingPassword" name="user_pwd" placeholder="비밀번호">
            <label for="floatingPassword">비밀번호</label>
        </div>

        <button class="w-100 btn btn-lg btn-primary mb-3" type="submit">로그인</button>

        <p class="text-muted">* 아이디, 비밀번호 분실시 문의바랍니다.</p>
    </form>
</main>

<script>
    document.querySelector("form").addEventListener("submit", (e) => {
        e.preventDefault();

        const formData = new FormData(e.target);

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
            if(data['header']['result'] == "success") {
                window.location.href = "<?php
                    if( isset($_GET['redirect']) ) echo $_GET['redirect'];
                    else echo "/";
                ?>";
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