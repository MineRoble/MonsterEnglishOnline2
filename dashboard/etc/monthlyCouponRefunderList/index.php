<?php
    $GLOBALS["page-title"] = "월별 수업 반환 목록";

    include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_config.php";
    include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_header.php";
    include_once $_SERVER["DOCUMENT_ROOT"]."/dashboard/_dashboard_header.php";
?>
    <div class="alert alert-danger" role="alert">
        아직 개발중인 기능으로 기능이 작동하지 않을 수 있습니다.
    </div>
    
    <p class="h1 fw-bold mb-3">월별 수업 반환 목록</p>
    <p class="text-muted mb-3">* 선택한 기간에 진행되었어야 하는 수업 중 쿠폰으로 반환된 수업 목록을 출력합니다.</p>

    <div class="d-flex justify-content-between mb-3">
        <div class="d-flex justify-content-center flex-grow-1 gap-3">
            <select id="year" class="form-select w-auto">
                <?php
                    for($i=date("Y"); $i>=2023; $i--) {
                        echo "<option value=\"{$i}\">{$i}년</option>";
                    }
                ?>
            </select>
            <select id="month" class="form-select w-auto">
                <option value="01">01월</option>
                <option value="02">02월</option>
                <option value="03">03월</option>
                <option value="04">04월</option>
                <option value="05">05월</option>
                <option value="06">06월</option>
                <option value="07">07월</option>
                <option value="08">08월</option>
                <option value="09">09월</option>
                <option value="10">10월</option>
                <option value="11">11월</option>
                <option value="12">12월</option>
            </select>
        </div>
        <a role="button" class="btn btn-primary" href="" download="test.csv">Download as Excel</a>
    </div>

    <div class="overflow-x-scroll mb-3">
        <table class="table table-striped text-nowrap">
            <thead>
                <tr>
                    <th>학생 idx</th>
                    <th>학생 이름</th>
                    <th>학생 아이디</th>
                    <th>수업 idx</th>
                    <th>수업일</th>
                    <th>수업 시간</th>
                    <th>수업 선생님</th>
                    <th>결제일시</th>
                    <th>주문번호</th>
                    <th>반환된 쿠폰 idx</th>
                    <th>반환된 쿠폰 생성일</th>
                    <th>반환된 쿠폰 사용일</th>
                </tr>
            </thead>
            <tbody id="view">
                <tr class="align-middle placeholder-glow placeholder-wave">
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                </tr>
                <tr class="align-middle placeholder-glow placeholder-wave">
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                </tr>
                <tr class="align-middle placeholder-glow placeholder-wave">
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                </tr>
                <tr class="align-middle placeholder-glow placeholder-wave">
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                </tr>
                <tr class="align-middle placeholder-glow placeholder-wave">
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                </tr>
                <tr class="align-middle placeholder-glow placeholder-wave">
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                </tr>
                <tr class="align-middle placeholder-glow placeholder-wave">
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                </tr>
                <tr class="align-middle placeholder-glow placeholder-wave">
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                </tr>
                <tr class="align-middle placeholder-glow placeholder-wave">
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                </tr>
                <tr class="align-middle placeholder-glow placeholder-wave">
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                </tr>
            </tbody>
        </table>
    </div>

    <script>
        function load() {
            document.getElementById("view").innerHTML = `
                <tr class="align-middle placeholder-glow placeholder-wave">
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                </tr>
                <tr class="align-middle placeholder-glow placeholder-wave">
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                </tr>
                <tr class="align-middle placeholder-glow placeholder-wave">
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                </tr>
                <tr class="align-middle placeholder-glow placeholder-wave">
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                </tr>
                <tr class="align-middle placeholder-glow placeholder-wave">
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                </tr>
                <tr class="align-middle placeholder-glow placeholder-wave">
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                </tr>
                <tr class="align-middle placeholder-glow placeholder-wave">
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                </tr>
                <tr class="align-middle placeholder-glow placeholder-wave">
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                </tr>
                <tr class="align-middle placeholder-glow placeholder-wave">
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                </tr>
                <tr class="align-middle placeholder-glow placeholder-wave">
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                </tr>`;

            const formData = new FormData();

            formData.append("year", document.getElementById("year").value);
            formData.append("month", document.getElementById("month").value);

            fetch("load.php", {
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
                    let insertHTML = "";

                    data['body']['result'].forEach(function(item,index,arr2){
                        insertHTML += `
                            <tr class="align-middle">
                                <td>${item[0]}</td>
                                <td>${item[1]}</td>
                                <td>${item[2]}</td>
                                <td>${item[3]}</td>
                                <td>${item[4]}</td>
                                <td>${item[5]}</td>
                                <td>${item[6]}</td>
                                <td>${item[7]}</td>
                                <td>${item[8]}</td>
                                <td>${item[9]}</td>
                                <td>${item[10]}</td>
                                <td>${item[11]}</td>
                            </tr>`;
                    });
                    document.getElementById("view").innerHTML = insertHTML;
                } else {
                    alert(data['header']['message']);
                }
            })
            .catch(error => {
                alert("서버와 통신 중에 오류가 발생했습니다.");
                console.log(error);
            });
        }
        
        document.getElementById("year").addEventListener("change", load);
        document.getElementById("month").addEventListener("change", load);

        window.addEventListener('load', () => {
            load();
        });
    </script>
<?php
    include_once $_SERVER["DOCUMENT_ROOT"]."/dashboard/_dashboard_footer.php";
    include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_footer.php";
?>