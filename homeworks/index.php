<?php
    $GLOBALS["page-title"] = "숙제 게시판";

    include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_config.php";
    include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_header.php";
?>

<main class="container py-5">
    <?php
        if($_SESSION['user']['permission'] >= 2) {
    ?>
        <a href="write.php" role="button" class="btn btn-outline-primary mb-3 float-end">글 작성</a>
    <?php
        }
    ?>
    <table class="table table-fixed">
        <colgroup>
            <col style="width: 64px;">
            <col>
            <col style="width: 128px;">
            <col style="width: 200px;">
        </colgroup>
        <thead>
            <tr>
                <th>#</th>
                <th>제목</th>
                <th>작성자</th>
                <th>작성일시</th>
            </tr>
        </thead>
        <tbody id="view">
            <tr class="placeholder-wave">
                <td><span class="placeholder w-100 text-truncate">Loading . . .</span></td>
                <td><span class="placeholder w-100 text-truncate">Loading . . .</span></td>
                <td><span class="placeholder w-100 text-truncate">Loading . . .</span></td>
                <td><span class="placeholder w-100 text-truncate">Loading . . .</span></td>
            </tr>
            <tr class="placeholder-wave">
                <td><span class="placeholder w-100 text-truncate">Loading . . .</span></td>
                <td><span class="placeholder w-100 text-truncate">Loading . . .</span></td>
                <td><span class="placeholder w-100 text-truncate">Loading . . .</span></td>
                <td><span class="placeholder w-100 text-truncate">Loading . . .</span></td>
            </tr>
            <tr class="placeholder-wave">
                <td><span class="placeholder w-100 text-truncate">Loading . . .</span></td>
                <td><span class="placeholder w-100 text-truncate">Loading . . .</span></td>
                <td><span class="placeholder w-100 text-truncate">Loading . . .</span></td>
                <td><span class="placeholder w-100 text-truncate">Loading . . .</span></td>
            </tr>
            <tr class="placeholder-wave">
                <td><span class="placeholder w-100 text-truncate">Loading . . .</span></td>
                <td><span class="placeholder w-100 text-truncate">Loading . . .</span></td>
                <td><span class="placeholder w-100 text-truncate">Loading . . .</span></td>
                <td><span class="placeholder w-100 text-truncate">Loading . . .</span></td>
            </tr>
        </tbody>
    </table>
    
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
    var page = [1, 1]; // [itemPerPage, page]

    function load() {
        document.getElementById("view").innerHTML = `
            <tr class="placeholder-wave">
                <td><span class="placeholder w-100 text-truncate">Loading . . .</span></td>
                <td><span class="placeholder w-100 text-truncate">Loading . . .</span></td>
                <td><span class="placeholder w-100 text-truncate">Loading . . .</span></td>
                <td><span class="placeholder w-100 text-truncate">Loading . . .</span></td>
            </tr>
            <tr class="placeholder-wave">
                <td><span class="placeholder w-100 text-truncate">Loading . . .</span></td>
                <td><span class="placeholder w-100 text-truncate">Loading . . .</span></td>
                <td><span class="placeholder w-100 text-truncate">Loading . . .</span></td>
                <td><span class="placeholder w-100 text-truncate">Loading . . .</span></td>
            </tr>
            <tr class="placeholder-wave">
                <td><span class="placeholder w-100 text-truncate">Loading . . .</span></td>
                <td><span class="placeholder w-100 text-truncate">Loading . . .</span></td>
                <td><span class="placeholder w-100 text-truncate">Loading . . .</span></td>
                <td><span class="placeholder w-100 text-truncate">Loading . . .</span></td>
            </tr>
            <tr class="placeholder-wave">
                <td><span class="placeholder w-100 text-truncate">Loading . . .</span></td>
                <td><span class="placeholder w-100 text-truncate">Loading . . .</span></td>
                <td><span class="placeholder w-100 text-truncate">Loading . . .</span></td>
                <td><span class="placeholder w-100 text-truncate">Loading . . .</span></td>
            </tr>`;

        const formData = new FormData();

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
                data['body']['articles'].forEach(function(item,index,arr2){
                    let files_count = "";

                    if(item["files_count"] > 0) files_count = `<div class="d-flex gap-1 text-muted"><i class="bi bi-files"></i><span>${item["files_count"]}</span></div>`;

                    insertHTML += `
                        <tr class="position-relative">
                            <td class="text-truncate">${item["idx"]}</td>
                            <td class="text-truncate">
                                <a class="stretched-link text-reset text-decoration-none d-flex gap-3" href="article.php?idx=${item["idx"]}">
                                    ${item["title"]} ${files_count}
                                </a>
                            </td>
                            <td class="text-truncate">${item["writer"]["name"]}</td>
                            <td class="text-truncate">${item["created_at"]}</td>
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

    window.addEventListener('load', () => {
        page = [document.getElementById("itemPerPage").value, document.getElementById("page").value]
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
    include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_footer.php";