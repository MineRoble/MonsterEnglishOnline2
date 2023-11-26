<?php
include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_config.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_header.php";
?>
<div class="d-flex align-items-center justify-content-center py-5">
    <div class="text-center">
        <h1 class="display-1 fw-bold">404</h1>
        <p class="fs-3"> <span class="text-danger">Opps!</span> 요청한 리소스를 찾을 수 없습니다.</p>
        <p class="lead">
            The page you’re looking for has some problem.
        </p>
        <div class="d-flex justify-content-center gap-3">
            <a href="/" class="btn btn-primary">Go Home</a>
            <a role="btn" class="btn btn-secondary" onclick="history.back();">Go Back</a>
        </div>
    </div>
</div>
<?php
include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_footer.php";