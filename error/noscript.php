<?php
    $GLOBALS["page-title"] = "자바스크립트 오류";

    include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_config.php";
    include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_header.php";
?>
<main class="container py-5">
    <p class="mb-3"><span class="fw-bold">고객님의 브라우저에서 자바스크립트가 비활성화 되어있습니다.</span> 브라우저에서 자바스크립트가 비활성화 상태일 경우 해당 서비스를 정상적으로 이용하실 수 없습니다. 아래 설명에 따라 브라우저 설정을 변경해주세요.</p>
    
    <span class="fw-bold">Chrome (Windows 11, 버전 115.0.5790.171 기준.)</span>
    <ol class="mb-3">
        <li>우측 상단에 <code>Chrome 맞춤설정 및 제어 <i class="bi bi-three-dots-vertical"></i></code> 클릭.</li>
        <li><code>설정</code> 클릭.</li>
        <li>좌측 메뉴에서 <code>개인 정보 보호 및 보안</code> 클릭.</li>
        <li>하단으로 스크롤 후 <code>콘텐츠</code>에서 <code>자바스크립트</code> 클릭.</li>
        <li><code>기본 동작</code>에서 <code>사이트에서 JavaScript를 사용할 수 있음</code> 클릭.</li>
    </ol>
    
    <span class="fw-bold">Edge (Windows 11, 버전 115.0.1901.188 기준.)</span>
    <ol class="mb-3">
        <li>우측 상단에 <code>설정 및 기타 (Alt + F) <i class="bi bi-three-dots"></i></code> 클릭.</li>
        <li><code>설정(S) </code> 클릭.</li>
        <li>좌측 메뉴에서 <code>쿠키 및 사이트 권한</code> 클릭.</li>
        <li>하단으로 스크롤 후 <code>JavaSCript</code> 클릭.</li>
        <li><code>허용됨(권장)</code> 활성화.</li>
    </ol>

    <p class="mb-3 text-muted">* 자바스크립트가 활성화되지 않거나 그 외 브라우저는 문의 바랍니다.</p>

    <?php
        $refer = "";
        if( isset($_GET["from"]) && !empty($_GET["from"]) ) $refer = $_GET["from"];
        elseif( isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER']) ) $refer = $_SERVER['HTTP_REFERER'];
        
        if( $refer != "" ) echo '<a class="btn btn-primary" role="button" href="'.$refer.'">이전 페이지로 돌아가기</a>';
    ?>
</main>
<?php
    include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_footer.php";
?>