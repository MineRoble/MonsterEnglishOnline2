<?php
    $GLOBALS["page-title"] = "예약 현황";

    include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_config.php";
    include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_header.php";
    include_once $_SERVER["DOCUMENT_ROOT"]."/auth/mypage/_mypage_header.php";
?>
    <main>
        <p class="h1 fw-bold mb-3">예약 현황</p>
        <p class="text-muted mb-1">* 수업 시작까지 남은 시간이 12시간 이하일 경우 결제를 취소하셔도 환불은 진행되지 않습니다.</p>
        <p class="text-muted mb-3">* 반환시, 결제 금액은 쿠폰으로 지급됩니다. 퇴원 등의 이유로 환불을 원할 경우 유선으로 문의 바랍니다.</p>

        <div class="d-flex flex-column justify-content-center gap-1 mb-1">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" role="switch" id="hideCancelledClass" checked>
                <label class="form-check-label" for="hideCancelledClass">취소된 수업 숨기기</label>
            </div>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" role="switch" id="hideEndedClass" checked>
                <label class="form-check-label" for="hideEndedClass">종료된 수업 숨기기</label>
            </div>
        </div>
        
        <div class="overflow-x-scroll">
            <table class="table table-striped text-nowrap">
                <thead>
                    <tr>
                        <th> <button class="btn btn-light w-100 text-start" id="sortby_idx"># <i class="bi bi-chevron-up" style="visibility: visible;"></i></button> </th>
                        <th> <button class="btn btn-light w-100 text-start" id="sortby_date">수업일 <i class="bi bi-chevron-up" style="visibility: hidden;"></i></button> </th>
                        <th> <button class="btn btn-light w-100 text-start" id="sortby_teacher">수업 선생님 <i class="bi bi-chevron-up" style="visibility: hidden;"></i></button> </th>
                        <th> <button class="btn btn-light w-100 text-start" id="sortby_time">수업 시간 <i class="bi bi-chevron-up" style="visibility: hidden;"></i></button> </th>
                        <th> <button class="btn btn-light w-100 text-start" id="sortby_orderId">주문번호 <i class="bi bi-chevron-up" style="visibility: hidden;"></i></button> </th>
                        <th> <button class="btn btn-light w-100 text-start disabled">상태</button> </th>
                        <th> <button class="btn btn-light w-100 text-start disabled">반환</button> </th>
                    </tr>
                </thead>
                <tbody id="view">
                    <tr class="placeholder-glow placeholder-wave">
                        <td> <span class="placeholder col-12"></span> </td>
                        <td> <span class="placeholder col-12"></span> </td>
                        <td> <span class="placeholder col-12"></span> </td>
                        <td> <span class="placeholder col-12"></span> </td>
                        <td> <span class="placeholder col-12"></span> </td>
                        <td> <span class="placeholder col-12"></span> </td>
                        <td> <span class="placeholder col-12"></span> </td>
                    </tr>
                    <tr class="placeholder-glow placeholder-wave">
                        <td> <span class="placeholder col-12"></span> </td>
                        <td> <span class="placeholder col-12"></span> </td>
                        <td> <span class="placeholder col-12"></span> </td>
                        <td> <span class="placeholder col-12"></span> </td>
                        <td> <span class="placeholder col-12"></span> </td>
                        <td> <span class="placeholder col-12"></span> </td>
                        <td> <span class="placeholder col-12"></span> </td>
                    </tr>
                    <tr class="placeholder-glow placeholder-wave">
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
        
        <div class="d-flex justify-content-center gap-3">
            <select class="form-select w-auto" id="itemPerPage">
                <option value="10">10 개씩</option>
                <option value="20">20 개씩</option>
                <option value="30">30 개씩</option>
                <option value="40">40 개씩</option>
                <option value="50">50 개씩</option>
                <option value="60">60 개씩</option>
                <option value="70">70 개씩</option>
                <option value="80">80 개씩</option>
                <option value="90">90 개씩</option>
                <option value="100">100 개씩</option>
            </select>
            <select class="form-select w-auto" id="page">
                <option value="1">1 Page</option>
            </select>
        </div>
    </main>

    <script>
        var sort = [document.querySelectorAll("[id^=sortby_]")[0].id, "asc"]; // [sortKey, sortOrder]
        var page = [1, 1]; // [itemPerPage, page]

        function load() {
            document.getElementById("view").innerHTML = `<tr class="placeholder-glow placeholder-wave">
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
            </tr>
            <tr class="placeholder-glow placeholder-wave">
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
            </tr>
            <tr class="placeholder-glow placeholder-wave">
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
            </tr>`;

            const formData = new FormData();

            formData.append("sortKey", sort[0]);
            formData.append("sortOrder", sort[1]);
            formData.append("itemPerPage", page[0]);
            formData.append("page", page[1]);
            formData.append("hideCancelledClass", document.getElementById("hideCancelledClass").checked);
            formData.append("hideEndedClass", document.getElementById("hideEndedClass").checked);

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
                    data['body']['schedules'].forEach(function(item,index,arr2){
                        // let date = new Date(...item["date"].split("-"), ...String(item['start_time']).split(":"), 0);
                        // let date = new Date(...[...item["date"].split("-"), ...String(item['start_time']).split(":"), 0].map(item=>{return Number(item)}));
                        let date = new Date(`${item["date"]} ${item['start_time']}:00`);
                        let status = "<span class=\"text-muted\">알 수 없음</span>";
                        let refundBtnDisabled = "disabled";
                        let refundable = "true";

                        if(item["isCancelled"] != false) status = "<span class=\"text-danger\">취소됨</span>";
                        else if(date <= new Date()) status = "<span class=\"text-muted\">수업시간종료</span>";
                        else {
                            refundBtnDisabled = "";
                            if(( date.getTime() - new Date().getTime() ) / ( 1000 * 60 * 60 ) <= 12) refundable = "false";
                            status = "<span class=\"text-success\">수업예정</span>";
                        }

                        // if(document.getElementById("hideCancelledClass").checked && item["isCancelled"] != false) return;
                        // if(document.getElementById("hideEndedClass").checked && date <= new Date()) return;

                        insertHTML += `<tr class="align-middle">
                                        <td>${item["idx"]}</td>
                                        <td>${date.getFullYear()}-${String(date.getMonth()+1).padStart(2, '0')}-${String(date.getDate()).padStart(2, '0')} (${["일","월","화","수","목","금","토"][date.getDay()]})</td>
                                        <td>${item["teacher"]}</td>
                                        <td>${item['start_time']}~${item['end_time']}</td>
                                        <td>${item["orderId"]}원</td>
                                        <td>${status}</td>
                                        <td> <button class="btn btn-danger" onclick="refundCoupon('${item['idx']}', ${refundable});" ${refundBtnDisabled}>반환</button> </td>
                                    </tr>`;
                    })
                    document.getElementById("view").innerHTML = insertHTML;

                    insertHTML = "";
                    for(let i=1; i<=Math.ceil(data['body']['count']/page[0]); i++) {
                        let isSelected = "";
                        if(i == page[1]) isSelected = " selected";
                        insertHTML += `<option value="${i}"${isSelected}>${i} Page</option>`;
                        document.getElementById("page").innerHTML = insertHTML;
                    }
                } else {
                    alert(data['header']['message']);
                }
            })
            .catch(error => {
                alert("서버와 통신 중에 오류가 발생했습니다.");
                console.log(error);
            });
        }
        
        function refundCoupon(idx, refundable) {
            let confirmMsg = "";

            if(refundable) confirmMsg = `정말로 수업을 반환하시겠습니까? 수업이 취소되며 8,000원 쿠폰 1개가 발급됩니다.`;
            else confirmMsg = `정말로 수업을 반환하시겠습니까? 남은 시간이 12시간 이하이므로 쿠폰이 발급되지 않습니다.`;

            if(!confirm(confirmMsg)) return;

            const formData = new FormData();

            formData.append("idx", idx);
            
            fetch("refundCoupon.php", {
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
                    alert(data['header']['message']);
                    load();
                } else {
                    alert(data['header']['message']);
                }
            })
            .catch(error => {
                alert("서버와 통신 중에 오류가 발생했습니다.");
                console.log(error);
            });
        }

        function setSort(event) {
            let sortOrder = "asc";
            if(event.target.querySelector("i").style.visibility == "visible") {
                if(event.target.querySelector("i").classList.contains("bi-chevron-up")) {
                    event.target.querySelector("i").classList.remove("bi-chevron-up");
                    event.target.querySelector("i").classList.add("bi-chevron-down");
                    sortOrder = "desc";
                } else {
                    event.target.querySelector("i").classList.add("bi-chevron-up");
                    event.target.querySelector("i").classList.remove("bi-chevron-down");
                    sortOrder = "asc";
                }
            } else {
                event.target.querySelector("i").classList.remove("bi-chevron-up");
                event.target.querySelector("i").classList.remove("bi-chevron-down");
                event.target.querySelector("i").classList.add("bi-chevron-up");
                
                document.querySelectorAll("[id^=sortby_] i").forEach(function(item,index,arr2){
                    item.style.visibility = "hidden";
                });
                
                event.target.querySelector("i").style.visibility = "visible"
                sortOrder = "asc";
            }
            sort[0] = event.target.id;
            sort[1] = sortOrder;

            load();
        }

        window.addEventListener('load', () => {
            page = [document.getElementById("itemPerPage").value, document.getElementById("page").value]
            load();
        });
        
        document.getElementById("hideCancelledClass").addEventListener("change", (e)=>{
            load();
        });
        document.getElementById("hideEndedClass").addEventListener("change", (e)=>{
            load();
        });
        
        document.querySelectorAll("[id^=sortby_]").forEach(function(item,index,arr2){
            item.addEventListener("click", setSort);
        });

        document.getElementById("itemPerPage").addEventListener("input", (e) => {
            page[0] = e.target.value;
            load();
        })
        document.getElementById("page").addEventListener("input", (e) => {
            page[1] = e.target.value;
            load();
        })
    </script>
<?php
    include_once $_SERVER["DOCUMENT_ROOT"]."/dashboard/_dashboard_footer.php";
    include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_footer.php";
?>