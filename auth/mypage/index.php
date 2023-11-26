<?php
    $GLOBALS["page-title"] = "마이페이지";

    include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_config.php";

    include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_header.php";
    include_once $_SERVER["DOCUMENT_ROOT"]."/auth/mypage/_mypage_header.php";
?>

<main>
    <p class="h1 fw-bold mb-3">Mypage</p>
    <div class="alert alert-primary" role="alert">
        메뉴를 선택하세요.
    </div>
</main>

<?php
    include_once $_SERVER["DOCUMENT_ROOT"]."/auth/mypage/_mypage_footer.php";
    include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_footer.php";
?>