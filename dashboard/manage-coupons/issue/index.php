<?php
    $GLOBALS["page-title"] = "쿠폰 발행";

    include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_config.php";
    include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_header.php";
    include_once $_SERVER["DOCUMENT_ROOT"]."/dashboard/_dashboard_header.php";
?>

    <main>
        <p class="h1 fw-bold">쿠폰 발행</p>

        <br>

        <label for="user_idx" class="form-label">사용자 <span id="found-member-cnt"></span></label>

        <form class="d-flex gap-3 mb-3" id="searchForm">
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

        <form id="issuingForm">
            <div class="mb-3">
                <!-- <input type="text" class="form-control" id="username" aria-describedby="howToChooseUser" disabled>
                <div id="howToChooseUser" class="form-text">상단에서 사용자를 검색하여 선택</div> -->
                <select class="form-select" id="user_idx">
                    <option selected>상단에서 사용자를 검색하여 선택</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="amount" class="form-label">금액</label>
                <input type="number" class="form-control" id="amount" value="8000" required>
            </div>

            <div class="mb-3">
                <label for="count" class="form-label">개수</label>
                <input type="number" class="form-control" id="count" value="1" required>
            </div>

            <p class="text-muted">* <span id="preview-amount" class="fw-bold">8,000</span>원 쿠폰 <span id="preview-count" class="fw-bold">1</span>장은 총 <span id="preview-total" class="fw-bold">8,000</span>원 입니다.</p>

            <button class="btn btn-primary" type="submit">발행하기</button>
        </form>
    </main>
    
    <script>
        document.getElementById("searchForm").addEventListener("submit", (e) => {
            e.preventDefault();

            const formData = new FormData();
            formData.append("queryType", document.getElementById("searchForm").querySelector("[name=queryType]").value);
            formData.append("searchQuery", document.getElementById("searchForm").querySelector("[name=searchQuery]").value);

            fetch("search.php", {
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
                    document.getElementById("user_idx").innerHTML = "";

                    let insertHTML = "";

                    data['body']['members'].forEach(function(item,index,arr2){
                        insertHTML += `<option value="${item["idx"]}">${item["name"]} (${item["id"]})</option>`;
                    });

                    document.getElementById("user_idx").innerHTML = insertHTML;
                    document.getElementById("found-member-cnt").textContent = `(${data['body']['members'].length}명 검색됨)`;
                } else {
                    alert(data['header']['message']);
                }
            })
            .catch(error => {
                alert("서버와 통신 중에 오류가 발생했습니다.");
                console.log(error);
            });
        });

        
        document.getElementById("issuingForm").addEventListener("submit", (e) => {
            e.preventDefault();

            const formData = new FormData();
            formData.append("user_idx", document.getElementById("user_idx").value);
            formData.append("amount", document.getElementById("amount").value);
            formData.append("count", document.getElementById("count").value);

            fetch("issue.php", {
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
                    if(confirm(data['header']['message'])) window.location.href = "https://gdre.monstereng.com/dashboard/manage-coupons/detail/?user_idx="+document.getElementById("user_idx").value;
                } else {
                    alert(data['header']['message']);
                }
            })
            .catch(error => {
                alert("서버와 통신 중에 오류가 발생했습니다.");
                console.log(error);
            });
        });

        document.getElementById("amount").addEventListener("change", (e) => {
            document.getElementById("preview-amount").textContent = Number(document.getElementById("amount").value).toLocaleString();
            document.getElementById("preview-total").textContent = Number(Number(document.getElementById("amount").value)*Number(document.getElementById("count").value)).toLocaleString();
        });
        document.getElementById("count").addEventListener("change", (e) => {
            document.getElementById("preview-count").textContent = document.getElementById("count").value;
            document.getElementById("preview-total").textContent = Number(Number(document.getElementById("amount").value)*Number(document.getElementById("count").value)).toLocaleString();
        });
    </script>

<?php
    include_once $_SERVER["DOCUMENT_ROOT"]."/dashboard/_dashboard_footer.php";
    include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_footer.php";
?>