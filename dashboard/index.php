<?php
    $GLOBALS["page-title"] = "Dashboard";

    include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_config.php";
    include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_header.php";
    include_once $_SERVER["DOCUMENT_ROOT"]."/dashboard/_dashboard_header.php";
?>

<main>
    <p class="h1 fw-bold mb-3">Dashboard</p>
    <div class="alert alert-primary" role="alert">
        메뉴를 선택하세요.
    </div>
</main>

<?php
    include_once $_SERVER["DOCUMENT_ROOT"]."/dashboard/_dashboard_footer.php";
    include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_footer.php";
?>