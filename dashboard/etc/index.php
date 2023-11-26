<?php
    $GLOBALS["page-title"] = "기타";

    include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_config.php";
    include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_header.php";
    include_once $_SERVER["DOCUMENT_ROOT"]."/dashboard/_dashboard_header.php";
?>
    <main>
        <p class="h1 fw-bold mb-3">기타</p>

        <ul>
            <li><a href="monthlyCouponRefunderList">월별 수업 반환 목록 (개발중)</a></li>
        </ul>
    </main>
<?php
    include_once $_SERVER["DOCUMENT_ROOT"]."/dashboard/_dashboard_footer.php";
    include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_footer.php";
?>