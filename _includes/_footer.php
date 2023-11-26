<?php include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_config.php"; ?>
    <footer class="bg-secondary-subtle py-5">
        <div class="container">
            <p class="fw-bold">몬스터어학원<?php
                if(_server_type == "dev") echo ' <span class="badge bg-danger" title="운영 서버와 독립된 서버로 실결제가 이루어지지 않습니다.">Development Server</span>';
            ?></p>

            <p class="text-muted my-0">몬스터어학원 대표 : 박은영</p>
            <p class="text-muted my-0">사업자등록번호 : 237-98-00399 <span class="badge rounded-pill text-bg-secondary"><a onclick="window.open(this.href, '_blank', 'width=700, height=700'); return false;" href="http://www.ftc.go.kr/bizCommPop.do?wrkr_no=2379800399" class="text-white text-decoration-none" target="_blank">[사업자정보확인]</a></span></p>
            <p class="text-muted my-0">통신판매 신고번호 : 2022-서울광진-2437</p>
            <p class="text-muted my-0">사업장주소 : 서울시 광진구 아차산로 459</p>
            <p class="text-muted my-0">문의 전화 : 02-6929-4299</p>
            
            <div class="my-3">
                <a class="text-muted" href="/terms/user-agreement/">서비스이용약관</a>
                <div class="vr"></div>
                <a class="text-muted" href="/terms/privacy/">개인정보처리방침</a>
            </div>

            <!-- <p class="text-muted">© <?php echo date("Y"); ?> 몬스터어학원</p> -->
            <p class="text-muted">Copyright © 2022-<?php echo date("Y"); ?> Monster English Online. All Rights Reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
</body>
</html>
<?php
exit();