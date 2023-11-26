<?php
    $GLOBALS["page-title"] = "교사 관리";

    include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_config.php";
    include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_header.php";
    include_once $_SERVER["DOCUMENT_ROOT"]."/dashboard/_dashboard_header.php";
?>
    <p class="h1 fw-bold mb-3">교사 관리</p>

    <div class="overflow-x-scroll mb-3">
        <table class="table table-striped text-nowrap">
            <thead>
                <tr>
                    <th> <button class="btn btn-light w-100 text-start" id="sortby_idx"># <i class="bi bi-chevron-down" style="visibility: visible;"></i></button> </th>
                    <th> <button class="btn btn-light w-100 text-start" id="sortby_name">이름 <i class="bi bi-chevron-up" style="visibility: hidden;"></i></button> </th>
                    <th> <button class="btn btn-light w-100 text-start disabled">수정</button> </th>
                    <th> <button class="btn btn-light w-100 text-start disabled">삭제</button> </th>
                </tr>
            </thead>
            <tbody id="view">
                <tr class="placeholder-glow placeholder-wave">
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <button class="btn btn-outline-secondary placeholder">수정</button> </td>
                    <td> <button class="btn btn-outline-secondary placeholder">삭제</button> </td>
                </tr>
                <tr class="placeholder-glow placeholder-wave">
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <button class="btn btn-outline-secondary placeholder">수정</button> </td>
                    <td> <button class="btn btn-outline-secondary placeholder">삭제</button> </td>
                </tr>
                <tr class="placeholder-glow placeholder-wave">
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <button class="btn btn-outline-secondary placeholder">수정</button> </td>
                    <td> <button class="btn btn-outline-secondary placeholder">삭제</button> </td>
                </tr>
                <tr class="placeholder-glow placeholder-wave">
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <button class="btn btn-outline-secondary placeholder">수정</button> </td>
                    <td> <button class="btn btn-outline-secondary placeholder">삭제</button> </td>
                </tr>
                <tr class="placeholder-glow placeholder-wave">
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <button class="btn btn-outline-secondary placeholder">수정</button> </td>
                    <td> <button class="btn btn-outline-secondary placeholder">삭제</button> </td>
                </tr>
                <tr class="placeholder-glow placeholder-wave">
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <button class="btn btn-outline-secondary placeholder">수정</button> </td>
                    <td> <button class="btn btn-outline-secondary placeholder">삭제</button> </td>
                </tr>
                <tr class="placeholder-glow placeholder-wave">
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <button class="btn btn-outline-secondary placeholder">수정</button> </td>
                    <td> <button class="btn btn-outline-secondary placeholder">삭제</button> </td>
                </tr>
                <tr class="placeholder-glow placeholder-wave">
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <button class="btn btn-outline-secondary placeholder">수정</button> </td>
                    <td> <button class="btn btn-outline-secondary placeholder">삭제</button> </td>
                </tr>
                <tr class="placeholder-glow placeholder-wave">
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <button class="btn btn-outline-secondary placeholder">수정</button> </td>
                    <td> <button class="btn btn-outline-secondary placeholder">삭제</button> </td>
                </tr>
                <tr class="placeholder-glow placeholder-wave">
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <button class="btn btn-outline-secondary placeholder">수정</button> </td>
                    <td> <button class="btn btn-outline-secondary placeholder">삭제</button> </td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <div class="d-flex justify-content-center gap-3 mb-3">
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

    <button class="btn btn-outline-secondary mb-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePayStatusExplain" aria-expanded="false" aria-controls="collapsePayStatusExplain">
        결제상태 설명 보기
    </button>
    <div class="collapse" id="collapsePayStatusExplain">
        <div class="card card-body">
            <div class="d-flex flex-column gap-1">
                <p class="text-muted mb-0">* <span class="text-warning">결제 대기(IN_PROGRESS)</span>: 결제수단 정보와 해당 결제수단의 소유자가 맞는지 인증을 마친 상태입니다. 결제 승인 API를 호출하면 결제가 완료됩니다.</p>
                <p class="text-muted mb-0">* <span class="text-warning">입금 대기(WAITING_FOR_DEPOSIT)</span>: 가상계좌 결제 흐름에만 있는 상태로, 결제 고객이 발급된 가상계좌에 입금하는 것을 기다리고 있는 상태입니다.</p>
                <p class="text-muted mb-0">* <span class="text-success">결제 승인(DONE)</span>: 인증된 결제수단 정보, 고객 정보로 요청한 결제가 승인된 상태입니다.</p>
                <p class="text-muted mb-0">* <span class="text-danger">결제 취소(CANCELED)</span>: 승인된 결제가 취소된 상태입니다.</p>
                <p class="text-muted mb-0">* <span class="text-danger">결제 부분 취소(PARTIAL_CANCELED)</span>: 승인된 결제가 부분 취소된 상태입니다.</p>
                <p class="text-muted mb-0">* <span class="text-danger">결제 승인 실패(ABORTED)</span>: 결제 승인이 실패한 상태입니다.</p>
                <p class="text-muted mb-0">* <span class="text-warning">시간 초과(EXPIRED)</span>: 결제 유효 시간 30분이 지나 거래가 취소된 상태입니다. IN_PROGRESS 상태에서 결제 승인 API를 호출하지 않으면 EXPIRED가 됩니다.</p>
                <p class="text-muted mb-0">* <span class="text-danger">결제 부분 반환(PARTIAL_REFUNDED)</span>: 일부 수업이 쿠폰으로 반환된 상태.</p>
            </div>
        </div>
    </div>

    <script>
        function load() {
            document.getElementById("view").innerHTML = `
                <tr class="placeholder-glow placeholder-wave">
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <button class="btn btn-outline-secondary placeholder">수정</button> </td>
                    <td> <button class="btn btn-outline-secondary placeholder">삭제</button> </td>
                </tr>            
                <tr class="placeholder-glow placeholder-wave">
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <button class="btn btn-outline-secondary placeholder">수정</button> </td>
                    <td> <button class="btn btn-outline-secondary placeholder">삭제</button> </td>
                </tr>            
                <tr class="placeholder-glow placeholder-wave">
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <button class="btn btn-outline-secondary placeholder">수정</button> </td>
                    <td> <button class="btn btn-outline-secondary placeholder">삭제</button> </td>
                </tr>            
                <tr class="placeholder-glow placeholder-wave">
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <button class="btn btn-outline-secondary placeholder">수정</button> </td>
                    <td> <button class="btn btn-outline-secondary placeholder">삭제</button> </td>
                </tr>            
                <tr class="placeholder-glow placeholder-wave">
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <button class="btn btn-outline-secondary placeholder">수정</button> </td>
                    <td> <button class="btn btn-outline-secondary placeholder">삭제</button> </td>
                </tr>            
                <tr class="placeholder-glow placeholder-wave">
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <button class="btn btn-outline-secondary placeholder">수정</button> </td>
                    <td> <button class="btn btn-outline-secondary placeholder">삭제</button> </td>
                </tr>            
                <tr class="placeholder-glow placeholder-wave">
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <button class="btn btn-outline-secondary placeholder">수정</button> </td>
                    <td> <button class="btn btn-outline-secondary placeholder">삭제</button> </td>
                </tr>            
                <tr class="placeholder-glow placeholder-wave">
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <button class="btn btn-outline-secondary placeholder">수정</button> </td>
                    <td> <button class="btn btn-outline-secondary placeholder">삭제</button> </td>
                </tr>            
                <tr class="placeholder-glow placeholder-wave">
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <button class="btn btn-outline-secondary placeholder">수정</button> </td>
                    <td> <button class="btn btn-outline-secondary placeholder">삭제</button> </td>
                </tr>            
                <tr class="placeholder-glow placeholder-wave">
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <button class="btn btn-outline-secondary placeholder">수정</button> </td>
                    <td> <button class="btn btn-outline-secondary placeholder">삭제</button> </td>
                </tr>`;

            fetch("load.php", {
                method: "POST"
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
                    data['body']['teachers'].forEach(function(item,index,arr2){
                        insertHTML += `<tr class="align-middle">
                                        <td>${item["idx"]}</td>
                                        <td>${item["name"]}</td>
                                        <td>${item["description"]}</td>
                                        <td> <a role="button" class="btn btn-primary" href="detail/?orderId=${item['idx']}">수정</a> </td>
                                        <td> <a role="button" class="btn btn-danger" href="detail/?orderId=${item['idx']}">삭제</a> </td>
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

        document.querySelector("#searchForm select[name=queryType]").addEventListener("change", (e) => {
            if(e.target.value == "period") {
                document.getElementById("searchQueryDiv").innerHTML = `
                    <input type="date" class="form-control w-100" name="startDate" placeholder="시작일" autocomplete="off">
                    <input type="date" class="form-control w-100" name="endDate" placeholder="종료일" autocomplete="off">
                `;
            } else document.getElementById("searchQueryDiv").innerHTML = `<input type="text" class="form-control w-100" name="searchQuery" placeholder="검색어 입력" autocomplete="off">`
        });

        document.querySelector("#searchForm").addEventListener("submit", (e) => {
            e.preventDefault();

            let queryType = e.target.querySelector("[name=queryType]").value;
            let searchQuery = "";

            if(queryType == "period") {
                if(new Date(e.target.querySelector("[name=startDate]").value) > new Date(e.target.querySelector("[name=endDate]").value)) return alert("시작일이 종료일보다 과거여야 합니다.");
                if(e.target.querySelector("[name=startDate]").value != "" || e.target.querySelector("[name=endDate]").value != "") searchQuery = e.target.querySelector("[name=startDate]").value+"~"+e.target.querySelector("[name=endDate]").value;
            } else searchQuery = e.target.querySelector("[name=searchQuery]").value;

            search = [queryType, searchQuery];
            load();
        });
        
        document.querySelectorAll("[id^=sortby_]").forEach(function(item,index,arr2){
            item.addEventListener("click", setSort);
        });

        document.getElementById("itemPerPage").addEventListener("input", (e) => {
            page[0] = e.target.value;
            load();
        });
        document.getElementById("page").addEventListener("input", (e) => {
            page[1] = e.target.value;
            load();
        });
    </script>
<?php
    include_once $_SERVER["DOCUMENT_ROOT"]."/dashboard/_dashboard_footer.php";
    include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_footer.php";
?>