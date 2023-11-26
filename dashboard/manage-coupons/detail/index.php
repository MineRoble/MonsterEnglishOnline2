<?php
    $GLOBALS["page-title"] = "사용자 쿠폰 자세히";

    include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_config.php";
    include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_header.php";
    include_once $_SERVER["DOCUMENT_ROOT"]."/dashboard/_dashboard_header.php";
?>
    <p class="h1 fw-bold mb-3">사용자 쿠폰 자세히</p>
    <p class="text-muted mb-1">* 담당 선생님으로 검색시 선생님 미정인 학생은 검색 결과에서 제외됩니다.</p>

    <form class="d-flex gap-3 my-3" id="searchForm">
        <select class="form-select flex-shrink-1 w-auto" name="queryType">
            <option value="period">생성일자</option>
            <!-- <option value="memo">메모</option> -->
            <option value="orderId">주문 ID</option>
        </select>
        <div class="w-100 d-flex gap-3" id="searchQueryDiv">
                <input type="date" class="form-control w-100" name="startDate" placeholder="시작일" autocomplete="off">
                <input type="date" class="form-control w-100" name="endDate" placeholder="종료일" autocomplete="off">
        </div>
        <button type="submit" class="btn btn-primary flex-shrink-1 w-auto">Search</button>
    </form>

    <div class="overflow-x-scroll mb-3">
        <table class="table table-striped text-nowrap">
            <thead>
                <tr>
                    <th> <button class="btn btn-light w-100 text-start" id="sortby_idx"># <i class="bi bi-chevron-down" style="visibility: visible;"></i></button> </th>
                    <th> <button class="btn btn-light w-100 text-start" id="sortby_amount">금액 <i class="bi bi-chevron-up" style="visibility: hidden;"></i></button> </th>
                    <th> <button class="btn btn-light w-100 text-start" id="sortby_usable">사용 여부 <i class="bi bi-chevron-up" style="visibility: hidden;"></i></button> </th>
                    <th> <button class="btn btn-light w-100 text-start" id="sortby_createdAt">생성일자 <i class="bi bi-chevron-up" style="visibility: hidden;"></i></button> </th>
                    <th> <button class="btn btn-light w-100 text-start" id="sortby_orderId">사용한 주문 ID <i class="bi bi-chevron-up" style="visibility: hidden;"></i></button> </th>
                    <th> <button class="btn btn-light w-100 text-start" id="sortby_fromKey">from key <i class="bi bi-chevron-up" style="visibility: hidden;"></i></button> </th>
                    <th> <button class="btn btn-light w-100 text-start" id="sortby_fromValue">from value <i class="bi bi-chevron-up" style="visibility: hidden;"></i></button> </th>
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
                </tr>
                <tr class="align-middle placeholder-glow placeholder-wave">
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
                </tr>
                <tr class="align-middle placeholder-glow placeholder-wave">
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
                </tr>
                <tr class="align-middle placeholder-glow placeholder-wave">
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
                </tr>
                <tr class="align-middle placeholder-glow placeholder-wave">
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
                </tr>
                <tr class="align-middle placeholder-glow placeholder-wave">
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

    <script>
        var sort = ["sortby_idx", "desc"]; // [sortKey, sortOrder]
        var page = [1, 1]; // [itemPerPage, page]
        var search = [null, null];

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
                </tr>
                <tr class="align-middle placeholder-glow placeholder-wave">
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
                </tr>
                <tr class="align-middle placeholder-glow placeholder-wave">
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
                </tr>
                <tr class="align-middle placeholder-glow placeholder-wave">
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
                </tr>
                <tr class="align-middle placeholder-glow placeholder-wave">
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
                </tr>
                <tr class="align-middle placeholder-glow placeholder-wave">
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
            formData.append("user_idx", "<?php echo $_GET["user_idx"]; ?>");

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

                    data['body']['coupons'].forEach(function(item,index,arr2){
                        insertHTML += `<tr class="align-middle">
                                        <td>${item['idx']}</td>
                                        <td>${Number(item['amount']).toLocaleString()}원</td>
                                        <td>${item['usable']?'<span class="text-success">사용 가능</span>':'<span class="text-danger">사용 불가능</span>'}</td>
                                        <td>${item['created_at']}</td>
                                        <td>${item['orderId']==null?'<span class="text-muted">정보 없음</span>':'<a href="/dashboard/billing-records/detail/?orderId='+item['orderId']+'">'+item['orderId']+'</a>'}</td>
                                        <!-- <td class="text-wrap">${item['memo']==null?'<span class="text-muted">정보 없음</span>':item['memo']}</td> -->
                                        <td>${item['from_key']}</td>
                                        <td>${item['from_value']}</td>
                                    </tr>`;
                    });
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
                
                document.querySelector("button#sortby_idx i").style.visibility = "hidden";
                document.querySelector("button#sortby_amount i").style.visibility = "hidden";
                document.querySelector("button#sortby_usable i").style.visibility = "hidden";
                document.querySelector("button#sortby_createdAt i").style.visibility = "hidden";
                document.querySelector("button#sortby_orderId i").style.visibility = "hidden";
                document.querySelector("button#sortby_fromKey i").style.visibility = "hidden";
                document.querySelector("button#sortby_fromValue i").style.visibility = "hidden";
                
                event.target.querySelector("i").style.visibility = "visible"
                sortOrder = "asc";
            }
            sort[0] = event.target.id;
            sort[1] = sortOrder;

            load();
        }

        window.addEventListener('load', () => {
            page = [document.getElementById("itemPerPage").value, document.getElementById("page").value];
            load();
        });
        
        document.getElementById("sortby_idx").addEventListener("click", (e) => { setSort(e); });
        document.getElementById("sortby_amount").addEventListener("click", (e) => { setSort(e); });
        document.getElementById("sortby_usable").addEventListener("click", (e) => { setSort(e); });
        document.getElementById("sortby_createdAt").addEventListener("click", (e) => { setSort(e); });
        document.getElementById("sortby_orderId").addEventListener("click", (e) => { setSort(e); });
        document.getElementById("sortby_fromKey").addEventListener("click", (e) => { setSort(e); });
        document.getElementById("sortby_fromValue").addEventListener("click", (e) => { setSort(e); });


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


        document.querySelector("#searchForm").addEventListener("submit", (e) => {
            e.preventDefault();

            let queryType = e.target.querySelector("[name=queryType]").value;
            let searchQuery = e.target.querySelector("[name=searchQuery]").value;

            search = [queryType, searchQuery];
            load();
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