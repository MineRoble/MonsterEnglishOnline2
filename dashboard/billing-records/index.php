<?php
    $GLOBALS["page-title"] = "결제 기록";

    include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_config.php";
    include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_header.php";
    include_once $_SERVER["DOCUMENT_ROOT"]."/dashboard/_dashboard_header.php";
?>
    <p class="h1 fw-bold mb-3">결제 기록</p>

    <form class="d-flex gap-3 my-3" id="searchForm">
        <select class="form-select flex-shrink-1 w-auto" name="queryType">
            <option value="id">사용자 아이디</option>
            <option value="name">사용자 이름</option>
            <option value="teacher">수업 선생님</option>
            <option value="orderId">주문번호</option>
            <option value="period">기간</option>
        </select>
        <div class="w-100 d-flex gap-3" id="searchQueryDiv">
            <input type="text" class="form-control w-100" name="searchQuery" placeholder="검색어 입력" autocomplete="off">
        </div>
        <button type="submit" class="btn btn-primary flex-shrink-1 w-auto">Search</button>
    </form>

    <div class="overflow-x-scroll mb-3">
        <table class="table table-striped text-nowrap">
            <thead>
                <tr>
                    <th> <button class="btn btn-light w-100 text-start" id="sortby_idx"># <i class="bi bi-chevron-down" style="visibility: visible;"></i></button> </th>
                    <th> <button class="btn btn-light w-100 text-start" id="sortby_username">이름 (아이디) <i class="bi bi-chevron-up" style="visibility: hidden;"></i></button> </th>
                    <th> <button class="btn btn-light w-100 text-start" id="sortby_teacher">담당 선생님 (수업횟수) <i class="bi bi-chevron-up" style="visibility: hidden;"></i></button> </th>
                    <th> <button class="btn btn-light w-100 text-start" id="sortby_orderId">주문번호 <i class="bi bi-chevron-up" style="visibility: hidden;"></i></button> </th>
                    <th> <button class="btn btn-light w-100 text-start" id="sortby_status">결제상태 <i class="bi bi-chevron-up" style="visibility: hidden;"></i></button> </th>
                    <th> <button class="btn btn-light w-100 text-start" id="sortby_amount">결제금액 <i class="bi bi-chevron-up" style="visibility: hidden;"></i></button> </th>
                    <th> <button class="btn btn-light w-100 text-start" id="sortby_method">결제수단 <i class="bi bi-chevron-up" style="visibility: hidden;"></i></button> </th>
                    <th> <button class="btn btn-light w-100 text-start" id="sortby_approvedAt">결제일시 <i class="bi bi-chevron-up" style="visibility: hidden;"></i></button> </th>
                    <th> <button class="btn btn-light w-100 text-start disabled">자세히</button> </th>
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
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <button class="btn btn-outline-secondary placeholder">자세히</button> </td>
                </tr>
                <tr class="placeholder-glow placeholder-wave">
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
                    <td> <button class="btn btn-outline-secondary placeholder">자세히</button> </td>
                </tr>
                <tr class="placeholder-glow placeholder-wave">
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
                    <td> <button class="btn btn-outline-secondary placeholder">자세히</button> </td>
                </tr>
                <tr class="placeholder-glow placeholder-wave">
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
                    <td> <button class="btn btn-outline-secondary placeholder">자세히</button> </td>
                </tr>
                <tr class="placeholder-glow placeholder-wave">
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
                    <td> <button class="btn btn-outline-secondary placeholder">자세히</button> </td>
                </tr>
                <tr class="placeholder-glow placeholder-wave">
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
                    <td> <button class="btn btn-outline-secondary placeholder">자세히</button> </td>
                </tr>
                <tr class="placeholder-glow placeholder-wave">
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
                    <td> <button class="btn btn-outline-secondary placeholder">자세히</button> </td>
                </tr>
                <tr class="placeholder-glow placeholder-wave">
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
                    <td> <button class="btn btn-outline-secondary placeholder">자세히</button> </td>
                </tr>
                <tr class="placeholder-glow placeholder-wave">
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
                    <td> <button class="btn btn-outline-secondary placeholder">자세히</button> </td>
                </tr>
                <tr class="placeholder-glow placeholder-wave">
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
                    <td> <button class="btn btn-outline-secondary placeholder">자세히</button> </td>
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
                <?php
                    foreach(_pay_status as $key => $value) {
                        echo '<p class="text-muted mb-0">* <span class="'.$value["bs_class"].'">'.$value["korean"].'('.$key.')</span>: '.$value["explain_ko"].'</p>';
                    }
                ?>
            </div>
        </div>
    </div>

    <script>
        var sort = [document.querySelectorAll("[id^=sortby_]")[0].id, "desc"]; // [sortKey, sortOrder]
        var page = [1, 1]; // [itemPerPage, page]
        var search = [null, null];
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
                <td> <span class="placeholder col-12"></span> </td>
                <td> <button class="btn btn-outline-secondary placeholder">자세히</button> </td>
            </tr>
            <tr class="placeholder-glow placeholder-wave">
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <button class="btn btn-outline-secondary placeholder">자세히</button> </td>
            </tr>
            <tr class="placeholder-glow placeholder-wave">
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <button class="btn btn-outline-secondary placeholder">자세히</button> </td>
            </tr>
            <tr class="placeholder-glow placeholder-wave">
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <button class="btn btn-outline-secondary placeholder">자세히</button> </td>
            </tr>
            <tr class="placeholder-glow placeholder-wave">
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <button class="btn btn-outline-secondary placeholder">자세히</button> </td>
            </tr>
            <tr class="placeholder-glow placeholder-wave">
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <button class="btn btn-outline-secondary placeholder">자세히</button> </td>
            </tr>
            <tr class="placeholder-glow placeholder-wave">
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <button class="btn btn-outline-secondary placeholder">자세히</button> </td>
            </tr>
            <tr class="placeholder-glow placeholder-wave">
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <button class="btn btn-outline-secondary placeholder">자세히</button> </td>
            </tr>
            <tr class="placeholder-glow placeholder-wave">
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <button class="btn btn-outline-secondary placeholder">자세히</button> </td>
            </tr>
            <tr class="placeholder-glow placeholder-wave">
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <button class="btn btn-outline-secondary placeholder">자세히</button> </td>
            </tr>`;

            const formData = new FormData();

            formData.append("sortKey", sort[0]);
            formData.append("sortOrder", sort[1]);
            formData.append("itemPerPage", page[0]);
            formData.append("page", page[1]);

            if(
                search[0] !== null && search[0] !== undefined  && search[0] !== ""
                &&
                search[1] !== null && search[1] !== undefined  && search[1] !== ""
            ) {
                formData.append("queryType", search[0]);
                formData.append("searchQuery", search[1]);
            }

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
                                        <td>${item["idx"]}</td>
                                        <td>${item["user"]["name"]} (${item["user"]["id"]})</td>
                                        <td>${item['teacher']} (${item['createdClassCnt']}회)</td>
                                        <td>${item['orderId']}</td>
                                        <td>${status}</td>
                                        <td>${Number(item['amount']).toLocaleString()}원</td>
                                        <td>${item['method']}</td>
                                        <td>${approvedAt}</td>
                                        <td> <a role="button" class="btn btn-primary" href="detail/?orderId=${item['orderId']}">자세히</a> </td>
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