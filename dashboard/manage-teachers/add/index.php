<?php
    $GLOBALS["page-title"] = "교사 추가";

    include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_config.php";
    include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_header.php";
    include_once $_SERVER["DOCUMENT_ROOT"]."/dashboard/_dashboard_header.php";

?>

    <main>
        <p class="h1 fw-bold">교사 추가</p>

        <form>            
            <div class="mb-3">
                <label for="teacher_name" class="form-label">이름</label>
                <input type="text" class="form-control" id="teacher_name" required>
            </div>
            
            <div class="mb-3">
                <label for="teacher_description" class="form-label">소개</label>
                <textarea class="form-control" rows="10" id="teacher_description" required></textarea>
            </div>
            
            <div class="mb-3">
                <label for="teacher_img" class="form-label">이미지</label>
                <input class="form-control mb-3" type="file" id="teacher_img" onchange="previewImg(this);">

                <div class="d-flex gap-3 mb-3">
                    <button type="button" class="btn btn-primary" onclick="setDefaultImg();">기본 이미지로 설정하기</button>
                </div>

                <div class="ratio ratio-1x1 border" id="profileImg" style="resize: horizontal; overflow: auto; width: 512px; background-image: url('/assets/teachers/'); background-position: center; background-size: contain; background-repeat: no-repeat;"></div>
            </div>

            <div class="row gap-3 p-3">
                <button class="btn btn-lg btn-primary col-sm" type="submit">추가하기</button>
            </div>
        </form>
    </main>
    
    <script>
        document.querySelector("form").addEventListener("submit", (e) => {
            e.preventDefault();

            const formData = new FormData();
            formData.append("teacher_name", document.getElementById("teacher_name").value);
            formData.append("teacher_description", document.getElementById("teacher_description").value);
            if(
                document.getElementById("teacher_img").files &&
                document.getElementById("teacher_img").files[0] &&
                document.getElementById("teacher_img").files[0].type.match("image/.*")
            ) formData.append("teacher_img", document.getElementById("teacher_img").files[0], document.getElementById("teacher_img").files[0].name);
            else formData.append("teacher_img", "default");

            fetch("add.php", {
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
                    window.location.href = "../modify/?idx="+data["body"]["idx"];
                } else {
                    alert(data['header']['message']);
                }
            })
            .catch(error => {
                alert("서버와 통신 중에 오류가 발생했습니다.");
                console.log(error);
            });
        });


        function previewImg(ele) {
            let files = document.getElementById("teacher_img").files;

            // 인풋 태그에 파일이 있는 경우
            if(files && files[0]) {
                // 이미지 파일인지 검사
                if (!files[0].type.match("image/.*")) {
                    document.getElementById("teacher_img").value = '';
                    alert('이미지 파일만 업로드가 가능합니다.');
                    return false;
                }

                // FileReader 인스턴스 생성
                const reader = new FileReader();

                // 이미지가 로드가 된 경우
                reader.onload = e => {
                    document.getElementById("profileImg").style.backgroundImage = `url('${e.target.result}')`;
                }

                // reader가 이미지 읽도록 하기
                reader.readAsDataURL(files[0]);
            } else {
                setDefaultImg();
            }
        }

        function setDefaultImg() {
            document.getElementById("teacher_img").value = '';
            document.getElementById("profileImg").style.backgroundImage = "url('/assets/teachers/')";
        }
    </script>

<?php
    include_once $_SERVER["DOCUMENT_ROOT"]."/dashboard/_dashboard_footer.php";
    include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_footer.php";
?>