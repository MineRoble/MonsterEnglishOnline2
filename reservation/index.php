<?php
    $GLOBALS["page-title"] = "수업 예약";

    include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_config.php";

    try {
        $pdo = new PDO(
            "mysql:host="._db_address.";dbname="._db_name.";charset=utf8",
            _db_id,_db_pwd,
            array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false
            )
        );

        if($_SESSION['user']['teacher_idx'] == -1) {
            die('<script>alert("지정된 담당 선생님이 없어 수업 예약을 진행할 수 없습니다. 문의바랍니다.");history.back();</script>');
        }
        
        $stmt = $pdo->prepare("SELECT idx, name, description FROM teachers WHERE idx = :idx");
        $stmt->bindValue(':idx', $_SESSION['user']['teacher_idx']);
        $stmt->execute();
        $teacher = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "<script>alert('데이터베이스에 연결할 수 없습니다.');history.back();</script>";
        include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_footer.php";
    }

    include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_header.php";

?>

<main class="container py-5">
    <div class="mb-5">
        <p class="h2 fw-bold mb-3">Step 1. 수업 일자 선택</p>
        <table class="table table-fixed">
            <thead>
                <tr>
                    <td colspan="7">
                        <div class="d-flex justify-content-center gap-3">
                            <select class="form-select w-auto" id="chooseYear">
                                <?php
                                    $thisYear = intval(date("Y"));
                                    $nextYear = intval(date("Y"))+1;
                                    echo "<option value=\"$thisYear\" selected>$thisYear 년</option>";
                                    echo "<option value=\"$nextYear\">$nextYear 년</option>";
                                ?>
                            </select>
                            <select class="form-select w-auto" id="chooseMonth">
                                <option value="01" <?php if( (date('m') == '01' && date('t') != date('d')) || ( date('m') == '12' && date('t') == date('d') ) ) echo "selected"; ?>>01 월</option>
                                <option value="02" <?php if( (date('m') == '02' && date('t') != date('d')) || ( date('m') == '01' && date('t') == date('d') ) ) echo "selected"; ?>>02 월</option>
                                <option value="03" <?php if( (date('m') == '03' && date('t') != date('d')) || ( date('m') == '02' && date('t') == date('d') ) ) echo "selected"; ?>>03 월</option>
                                <option value="04" <?php if( (date('m') == '04' && date('t') != date('d')) || ( date('m') == '03' && date('t') == date('d') ) ) echo "selected"; ?>>04 월</option>
                                <option value="05" <?php if( (date('m') == '05' && date('t') != date('d')) || ( date('m') == '04' && date('t') == date('d') ) ) echo "selected"; ?>>05 월</option>
                                <option value="06" <?php if( (date('m') == '06' && date('t') != date('d')) || ( date('m') == '05' && date('t') == date('d') ) ) echo "selected"; ?>>06 월</option>
                                <option value="07" <?php if( (date('m') == '07' && date('t') != date('d')) || ( date('m') == '06' && date('t') == date('d') ) ) echo "selected"; ?>>07 월</option>
                                <option value="08" <?php if( (date('m') == '08' && date('t') != date('d')) || ( date('m') == '07' && date('t') == date('d') ) ) echo "selected"; ?>>08 월</option>
                                <option value="09" <?php if( (date('m') == '09' && date('t') != date('d')) || ( date('m') == '08' && date('t') == date('d') ) ) echo "selected"; ?>>09 월</option>
                                <option value="10" <?php if( (date('m') == '10' && date('t') != date('d')) || ( date('m') == '09' && date('t') == date('d') ) ) echo "selected"; ?>>10 월</option>
                                <option value="11" <?php if( (date('m') == '11' && date('t') != date('d')) || ( date('m') == '10' && date('t') == date('d') ) ) echo "selected"; ?>>11 월</option>
                                <option value="12" <?php if( (date('m') == '12' && date('t') != date('d')) || ( date('m') == '11' && date('t') == date('d') ) ) echo "selected"; ?>>12 월</option>
                            </select>
                        </div>
                    </td>
                </tr>
                <tr class="text-center">
                    <th class="px-0"><button class="btn btn-light w-100 fw-bold px-0 disabled" onclick="toggleWholeDay(0);">Sun</button></th>
                    <th class="px-0"><button class="btn btn-light w-100 fw-bold px-0" onclick="toggleWholeDay(1);">Mon</button></th>
                    <th class="px-0"><button class="btn btn-light w-100 fw-bold px-0" onclick="toggleWholeDay(2);">Tue</button></th>
                    <th class="px-0"><button class="btn btn-light w-100 fw-bold px-0" onclick="toggleWholeDay(3);">Wed</button></th>
                    <th class="px-0"><button class="btn btn-light w-100 fw-bold px-0" onclick="toggleWholeDay(4);">Thu</button></th>
                    <th class="px-0"><button class="btn btn-light w-100 fw-bold px-0" onclick="toggleWholeDay(5);">Fri</button></th>
                    <th class="px-0"><button class="btn btn-light w-100 fw-bold px-0 disabled" onclick="toggleWholeDay(6);">Sat</button></th>
                </tr>
            </thead>
            <tbody id="calendar" class="placeholder-glow placeholder-wave">
                <tr>
                    <td class="px-1">
                        <button class="btn btn-outline-secondary px-0 w-100 placeholder"></button>
                    </td>
                    <td class="px-1">
                        <button class="btn btn-outline-primary px-0 w-100 placeholder"></button>
                    </td>
                    <td class="px-1">
                        <button class="btn btn-outline-primary px-0 w-100 placeholder"></button>
                    </td>
                    <td class="px-1">
                        <button class="btn btn-outline-primary px-0 w-100 placeholder"></button>
                    </td>
                    <td class="px-1">
                        <button class="btn btn-outline-primary px-0 w-100 placeholder"></button>
                    </td>
                    <td class="px-1">
                        <button class="btn btn-outline-primary px-0 w-100 placeholder"></button>
                    </td>
                    <td class="px-1">
                        <button class="btn btn-outline-secondary px-0 w-100 placeholder"></button>
                    </td>
                </tr>
                <tr>
                    <td class="px-1">
                        <button class="btn btn-outline-secondary px-0 w-100 placeholder"></button>
                    </td>
                    <td class="px-1">
                        <button class="btn btn-outline-primary px-0 w-100 placeholder"></button>
                    </td>
                    <td class="px-1">
                        <button class="btn btn-outline-primary px-0 w-100 placeholder"></button>
                    </td>
                    <td class="px-1">
                        <button class="btn btn-outline-primary px-0 w-100 placeholder"></button>
                    </td>
                    <td class="px-1">
                        <button class="btn btn-outline-primary px-0 w-100 placeholder"></button>
                    </td>
                    <td class="px-1">
                        <button class="btn btn-outline-primary px-0 w-100 placeholder"></button>
                    </td>
                    <td class="px-1">
                        <button class="btn btn-outline-secondary px-0 w-100 placeholder"></button>
                    </td>
                </tr>
                <tr>
                    <td class="px-1">
                        <button class="btn btn-outline-secondary px-0 w-100 placeholder"></button>
                    </td>
                    <td class="px-1">
                        <button class="btn btn-outline-primary px-0 w-100 placeholder"></button>
                    </td>
                    <td class="px-1">
                        <button class="btn btn-outline-primary px-0 w-100 placeholder"></button>
                    </td>
                    <td class="px-1">
                        <button class="btn btn-outline-primary px-0 w-100 placeholder"></button>
                    </td>
                    <td class="px-1">
                        <button class="btn btn-outline-primary px-0 w-100 placeholder"></button>
                    </td>
                    <td class="px-1">
                        <button class="btn btn-outline-primary px-0 w-100 placeholder"></button>
                    </td>
                    <td class="px-1">
                        <button class="btn btn-outline-secondary px-0 w-100 placeholder"></button>
                    </td>
                </tr>
                <tr>
                    <td class="px-1">
                        <button class="btn btn-outline-secondary px-0 w-100 placeholder"></button>
                    </td>
                    <td class="px-1">
                        <button class="btn btn-outline-primary px-0 w-100 placeholder"></button>
                    </td>
                    <td class="px-1">
                        <button class="btn btn-outline-primary px-0 w-100 placeholder"></button>
                    </td>
                    <td class="px-1">
                        <button class="btn btn-outline-primary px-0 w-100 placeholder"></button>
                    </td>
                    <td class="px-1">
                        <button class="btn btn-outline-primary px-0 w-100 placeholder"></button>
                    </td>
                    <td class="px-1">
                        <button class="btn btn-outline-primary px-0 w-100 placeholder"></button>
                    </td>
                    <td class="px-1">
                        <button class="btn btn-outline-secondary px-0 w-100 placeholder"></button>
                    </td>
                </tr>
                <tr>
                    <td class="px-1">
                        <button class="btn btn-outline-secondary px-0 w-100 placeholder"></button>
                    </td>
                    <td class="px-1">
                        <button class="btn btn-outline-primary px-0 w-100 placeholder"></button>
                    </td>
                    <td class="px-1">
                        <button class="btn btn-outline-primary px-0 w-100 placeholder"></button>
                    </td>
                    <td class="px-1">
                        <button class="btn btn-outline-primary px-0 w-100 placeholder"></button>
                    </td>
                    <td class="px-1">
                        <button class="btn btn-outline-primary px-0 w-100 placeholder"></button>
                    </td>
                    <td class="px-1">
                        <button class="btn btn-outline-primary px-0 w-100 placeholder"></button>
                    </td>
                    <td class="px-1">
                        <button class="btn btn-outline-secondary px-0 w-100 placeholder"></button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div id="timeSelectors" class="mb-5 placeholder-glow placeholder-wave">
        <p class="h2 fw-bold mb-3">Step 2. 요일별 시간</p>
        <p class="text-muted" id="pleaseSelectDateFirst">수업할 날짜를 먼저 선택해 주세요.</p>
        <div class="mb-3" style="display: none;" id="timeDay1">
            <label for="chooseTime1" class="form-label">월요일</label>
            <select class="form-select" id="chooseTime1" onchange="renderCheckout();"></select>
        </div>
        <div class="mb-3" style="display: none;" id="timeDay2">
            <label for="chooseTime2" class="form-label">화요일</label>
            <select class="form-select" id="chooseTime2" onchange="renderCheckout();"></select>
        </div>
        <div class="mb-3" style="display: none;" id="timeDay3">
            <label for="chooseTime3" class="form-label">수요일</label>
            <select class="form-select" id="chooseTime3" onchange="renderCheckout();"></select>
        </div>
        <div class="mb-3" style="display: none;" id="timeDay4">
            <label for="chooseTime4" class="form-label">목요일</label>
            <select class="form-select" id="chooseTime4" onchange="renderCheckout();"></select>
        </div>
        <div class="mb-3" style="display: none;" id="timeDay5">
            <label for="chooseTime5" class="form-label">금요일</label>
            <select class="form-select" id="chooseTime5" onchange="renderCheckout();"></select>
        </div>
    </div>
    <div class="mb-5">
        <p class="h2 fw-bold mb-3">Step 3. 결제 정보 확인</p>
        <div class="row g-3">
            <ul class="list-group col-sm-6" id="checkout">
                <li class="list-group-item text-muted">선택된 수업 없음.</li>
                <li class="list-group-item d-flex justify-content-between"><span>총합 (KRW)</span><strong>₩0</strong></li>
            </ul>
            <ul class="list-group col-sm-6 placeholder-glow placeholder-wave" id="couponList">
                <li class="list-group-item fw-bold d-flex justify-content-between"><span>할인쿠폰</span> <a role="button" onclick="loadCoupons();"><i class="bi bi-arrow-clockwise"></i></a></li>
                <li class="list-group-item"><span class="placeholder w-100">Loading ....</span></li>
                <li class="list-group-item"><span class="placeholder w-100">Loading ....</span></li>
            </ul>
        </div>
    </div>
    
    <div id="payment-method" style="display: none;"></div>
    
    <p class="text-muted mb-1">* <span class="fw-bold"><?php echo $_SESSION['user']['name']; ?></span> 학생과 수업을 진행하게 될 선생님은 <span class="fw-bold"><?php echo $teacher["name"]; ?></span> 입니다. <a class="text-muted" role="button" data-bs-toggle="collapse" data-bs-target="#collapseTeacherInfo" aria-expanded="false" aria-controls="collapseTeacherInfo">[자세히 보기]</a></p>

    <div class="collapse mb-3" id="collapseTeacherInfo">
        <div class="card card-body">
            <div class="row gap-3">
                <div class="col-sm-4">
                    <div class="ratio ratio-1x1 border" style="background-image: url('/assets/teachers/?idx=<?php echo $teacher["idx"]; ?>'); background-position: center; background-size: contain; background-repeat: no-repeat;"></div>
                </div>
                <div class="col-sm">
                    <p class="h5"><?php echo $teacher["name"]; ?></p>
                    <div style="white-space: pre-wrap; line-height: 32px;" id="content"><?php echo $teacher["description"]; ?></div>
                </div>
            </div>
        </div>
    </div>

    <p class="text-muted mb-1">* 서비스 제공기간은 최대 1개월 입니다.</p>
    <p class="text-muted mb-1">* 결제 금액이 0원보다 작아질 경우 0원으로 계산되며 결제 과정은 생략됩니다.</p>
    <p class="text-muted mb-1">
        * 결제 진행시 서비스이용약관 및 개인정보처리방침에 동의한 것으로 간주됩니다.
        <a onclick="window.open('/terms/', '_blank', 'width=700, height=700'); return false;" role="button" class="text-muted" target="_blank">[자세히 보기]</a>
    </p>
    
    <div id="agreement" style="display: none;"></div>

    <button id="checkoutBtn" class="btn btn-primary btn-lg w-100 mt-3" onclick="requestPay();" disabled>결제하기</button>
</main>


<script>
    const clientKey = "<?php echo _tosspayments_clientkey; ?>";
    const customerKey = "<?php echo hash('md5', $_SESSION['user_idx']); ?>"; // 내 상점의 고객을 식별하는 고유한 키
</script>

<!-- 결제위젯 SDK 추가 -->
<script src="https://js.tosspayments.com/v1/payment-widget"></script>

<script src="script.js?t=<?php echo time(); ?>"></script>

<?php
    include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_footer.php";