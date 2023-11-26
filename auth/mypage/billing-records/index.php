<?php
    $GLOBALS["page-title"] = "결제 기록";

    include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_config.php";
    include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_header.php";
    include_once $_SERVER["DOCUMENT_ROOT"]."/auth/mypage/_mypage_header.php";
?>
    <main>
        <p class="h1 fw-bold mb-3">결제 기록</p>
        <p class="text-muted mb-1">* 수업 시작까지 남은 시간이 12시간 이하일 경우 결제를 취소하셔도 환불은 진행되지 않습니다.</p>
        <p class="text-muted mb-3">* 반환시, 결제 금액은 쿠폰으로 지급됩니다. 퇴원 등의 이유로 환불을 원할 경우 유선으로 문의 바랍니다.</p>

        <div class="overflow-x-scroll">
            <table class="table table-striped text-nowrap">
                <thead>
                    <tr>
                        <th> <button class="btn btn-light w-100 text-start" id="sortby_approvedAt">결제일시 <i class="bi bi-chevron-down" style="visibility: visible;"></i></button> </th>
                        <th> <button class="btn btn-light w-100 text-start" id="sortby_teacher">담당 선생님 (수업일수) <i class="bi bi-chevron-up" style="visibility: hidden;"></i></button> </th>
                        <th> <button class="btn btn-light w-100 text-start" id="sortby_orderId">주문번호 <i class="bi bi-chevron-up" style="visibility: hidden;"></i></button> </th>
                        <th> <button class="btn btn-light w-100 text-start" id="sortby_status">결제상태 <i class="bi bi-chevron-up" style="visibility: hidden;"></i></button> </th>
                        <th> <button class="btn btn-light w-100 text-start" id="sortby_amount">결제금액 <i class="bi bi-chevron-up" style="visibility: hidden;"></i></button> </th>
                        <th> <button class="btn btn-light w-100 text-start" id="sortby_method">결제수단 <i class="bi bi-chevron-up" style="visibility: hidden;"></i></button> </th>
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
        var sort = [document.querySelectorAll("[id^=sortby_]")[0].id, "desc"]; // [sortKey, sortOrder]
        var page = [1, 1]; // [itemPerPage, page]
        const pay_status = <?php echo json_encode(_pay_status); ?>;

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
                    data['body']['billing-records'].forEach(function(item,index,arr2){
                        if(pay_status[item['status']] === undefined) status = `<span class="text-secondary">${item['status']}</span>`;
                        else status = `<span class="${pay_status[item['status']]["bs_class"]}">${pay_status[item['status']]["korean"]}</span>`;

                        approvedDate = new Date(item['approvedAt']);
                        approved_Year = approvedDate.getFullYear();
                        approved_Month = String(approvedDate.getMonth()+1).padStart(2, '0');
                        approved_Date = String(approvedDate.getDate()).padStart(2, '0');
                        approved_Hours = String(approvedDate.getHours()).padStart(2, '0');
                        approved_Minutes = String(approvedDate.getMinutes()).padStart(2, '0');
                        approved_Seconds = String(approvedDate.getSeconds()).padStart(2, '0');

                        approvedAt = `${approved_Year}-${approved_Month}-${approved_Date} ${approved_Hours}:${approved_Minutes}:${approved_Seconds}`;

                        insertHTML += `<tr class="align-middle">
                                        <td>${approvedAt}</td>
                                        <td>${item['teacher']} (${item['createdClassCnt']}회)</td>
                                        <td>${item['orderId']}</td>
                                        <td>${status}</td>
                                        <td>${Number(item['amount']).toLocaleString()}원</td>
                                        <td>${item['method']}</td>
                                        <td> <button ${(item['status'] == "DONE" || item['status'] == "PARTIAL_CANCELED")?"":"disabled"} class="btn btn-danger" onclick="refundCoupon('${item['orderId']}', ${item['createdClassCnt']}, ${item['refundableClassCnt']});">반환</button> </td>
                                    </tr>`;
                    })
                    document.getElementById("view").innerHTML = insertHTML;

                    insertHTML = ""
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
        
        function refundCoupon(orderId, totalClassCnt, refundableClassCnt) {
            if(!confirm(`정말로 수업을 반환하시겠습니까? 반환시 ${totalClassCnt}개의 수업이 취소되며 8,000원 쿠폰 ${refundableClassCnt}개가 발급됩니다.`)) return;

            const formData = new FormData();

            formData.append("orderId", orderId);
            
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