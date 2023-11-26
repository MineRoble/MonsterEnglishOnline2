<?php
    $GLOBALS["page-title"] = "쿠폰 관리";

    include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_config.php";
    include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_header.php";
    include_once $_SERVER["DOCUMENT_ROOT"]."/dashboard/_dashboard_header.php";
?>
    <p class="h1 fw-bold mb-3">쿠폰 관리</p>
    <p class="text-muted mb-3">* 쿠폰을 사용한 적 없는 사용자는 나타나지 않습니다.</p>

    <a href="./issue/" class="btn btn-primary">쿠폰 발행</a>

    <form class="d-flex gap-3 my-3" id="searchForm">
        <select class="form-select flex-shrink-1 w-auto" name="queryType">
            <option value="id">사용자 아이디</option>
            <option value="name">사용자 이름</option>
            <option value="grade">Grade</option>
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
                    <th> <button class="btn btn-light w-100 text-start" id="sortby_idx"># <i class="bi bi-chevron-up" style="visibility: visible;"></i></button> </th>
                    <th> <button class="btn btn-light w-100 text-start" id="sortby_id">사용자 아이디 <i class="bi bi-chevron-up" style="visibility: hidden;"></i></button> </th>
                    <th> <button class="btn btn-light w-100 text-start" id="sortby_name">사용자 이름 <i class="bi bi-chevron-up" style="visibility: hidden;"></i></button> </th>
                    <th> <button class="btn btn-light w-100 text-start" id="sortby_grade">Grade <i class="bi bi-chevron-up" style="visibility: hidden;"></i></button> </th>
                    <th> <button class="btn btn-light w-100 text-start" id="sortby_usable_copons">사용 가능한 쿠폰 <i class="bi bi-chevron-up" style="visibility: hidden;"></i></button> </th>
                    <th> <button class="btn btn-light w-100 text-start" id="sortby_used_copons">사용한 쿠폰 <i class="bi bi-chevron-up" style="visibility: hidden;"></i></button> </th>
                    <th> <button class="btn btn-light w-100 text-start disabled">자세히</button> </th>
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
                    <td> <button class="btn btn-outline-secondary placeholder">자세히</button> </td>
                </tr>
                <tr class="align-middle placeholder-glow placeholder-wave">
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <button class="btn btn-outline-secondary placeholder">자세히</button> </td>
                </tr>
                <tr class="align-middle placeholder-glow placeholder-wave">
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <button class="btn btn-outline-secondary placeholder">자세히</button> </td>
                </tr>
                <tr class="align-middle placeholder-glow placeholder-wave">
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <button class="btn btn-outline-secondary placeholder">자세히</button> </td>
                </tr>
                <tr class="align-middle placeholder-glow placeholder-wave">
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <button class="btn btn-outline-secondary placeholder">자세히</button> </td>
                </tr>
                <tr class="align-middle placeholder-glow placeholder-wave">
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <button class="btn btn-outline-secondary placeholder">자세히</button> </td>
                </tr>
                <tr class="align-middle placeholder-glow placeholder-wave">
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <button class="btn btn-outline-secondary placeholder">자세히</button> </td>
                </tr>
                <tr class="align-middle placeholder-glow placeholder-wave">
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <button class="btn btn-outline-secondary placeholder">자세히</button> </td>
                </tr>
                <tr class="align-middle placeholder-glow placeholder-wave">
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <span class="placeholder col-12"></span> </td>
                    <td> <button class="btn btn-outline-secondary placeholder">자세히</button> </td>
                </tr>
                <tr class="align-middle placeholder-glow placeholder-wave">
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
        var sort = ["sortby_idx", "asc"]; // [sortKey, sortOrder]
        var page = [1, 1]; // [itemPerPage, page]
        var search = [null, null];

        function load() {
            document.getElementById("view").innerHTML = `<tr class="align-middle placeholder-glow placeholder-wave">
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <button class="btn btn-outline-secondary placeholder">자세히</button> </td>
            </tr>
            <tr class="align-middle placeholder-glow placeholder-wave">
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <button class="btn btn-outline-secondary placeholder">자세히</button> </td>
            </tr>
            <tr class="align-middle placeholder-glow placeholder-wave">
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <button class="btn btn-outline-secondary placeholder">자세히</button> </td>
            </tr>
            <tr class="align-middle placeholder-glow placeholder-wave">
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <button class="btn btn-outline-secondary placeholder">자세히</button> </td>
            </tr>
            <tr class="align-middle placeholder-glow placeholder-wave">
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <button class="btn btn-outline-secondary placeholder">자세히</button> </td>
            </tr>
            <tr class="align-middle placeholder-glow placeholder-wave">
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <button class="btn btn-outline-secondary placeholder">자세히</button> </td>
            </tr>
            <tr class="align-middle placeholder-glow placeholder-wave">
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <button class="btn btn-outline-secondary placeholder">자세히</button> </td>
            </tr>
            <tr class="align-middle placeholder-glow placeholder-wave">
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <button class="btn btn-outline-secondary placeholder">자세히</button> </td>
            </tr>
            <tr class="align-middle placeholder-glow placeholder-wave">
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <span class="placeholder col-12"></span> </td>
                <td> <button class="btn btn-outline-secondary placeholder">자세히</button> </td>
            </tr>
            <tr class="align-middle placeholder-glow placeholder-wave">
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

                    data['body']['members'].forEach(function(item,index,arr2){
                        let usable_coupons = data['body']['usable_coupons'][item['idx']];
                        if(typeof usable_coupons == "undefined") {
                            usable_coupons = {
                                "total_amount" : 0,
                                "count" : 0
                            };
                        }
                        let unusable_coupons = data['body']['unusable_coupons'][item['idx']];
                        if(typeof unusable_coupons == "undefined") {
                            unusable_coupons = {
                                "total_amount" : 0,
                                "count" : 0
                            };
                        }
                        
                        insertHTML += `<tr class="align-middle">
                                        <td>${item['idx']}</td>
                                        <td>${item['id']}</td>
                                        <td>${item['name']}</td>
                                        <td>${item['grade']}</td>
                                        <td><strong>${Number(usable_coupons['total_amount']).toLocaleString()}원 (${usable_coupons['count']}개)</strong></td>
                                        <td><strong>${Number(unusable_coupons['total_amount']).toLocaleString()}원 (${unusable_coupons['count']}개)</strong></td>
                                        <td> <a role="button" href="detail/?user_idx=${item['idx']}" class="btn btn-secondary">자세히</a> </td>
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
                document.querySelector("button#sortby_id i").style.visibility = "hidden";
                document.querySelector("button#sortby_name i").style.visibility = "hidden";
                document.querySelector("button#sortby_grade i").style.visibility = "hidden";
                document.querySelector("button#sortby_usable_copons i").style.visibility = "hidden";
                document.querySelector("button#sortby_used_copons i").style.visibility = "hidden";
                
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
        document.getElementById("sortby_id").addEventListener("click", (e) => { setSort(e); });
        document.getElementById("sortby_name").addEventListener("click", (e) => { setSort(e); });
        document.getElementById("sortby_grade").addEventListener("click", (e) => { setSort(e); });
        document.getElementById("sortby_usable_copons").addEventListener("click", (e) => { setSort(e); });
        document.getElementById("sortby_used_copons").addEventListener("click", (e) => { setSort(e); });


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