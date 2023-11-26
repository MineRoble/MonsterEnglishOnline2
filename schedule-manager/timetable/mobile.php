<?php
    $GLOBALS["page-title"] = "Timetable";
    $GLOBALS["page-language"] = "en";

    include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_config.php";
    include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_header.php";
    include_once $_SERVER["DOCUMENT_ROOT"]."/schedule-manager/_schedule-manager_header.php";
?>
    <main>
        <p class="h1 fw-bold mb-3">Timetable (Mobile)</p>
        <p class="text-muted mb-1">* Timetable (Desktop) and Timetable (Mobile) have only different UI, but the contents are the same.</p>
        <p class="text-muted mb-1">* Banning a class time is avalible on the desktop page.</p>
        <p class="text-muted mb-1">* This page shows the schedule for 7 days from the selected date.</p>
        <p class="text-muted mb-3">* You can view the full schedule for selected month by selecting "All" for the date.</p>

        <div class="d-flex flex-column justify-content-center gap-1 mb-1">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" role="switch" id="hideCancelledClass" checked>
                <label class="form-check-label" for="hideCancelledClass">Hide cancelled classes</label>
            </div>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" role="switch" id="hideBannedClass" checked>
                <label class="form-check-label" for="hideBannedClass">Hide banned Classes</label>
            </div>
        </div>
        <div id="teacherSelectDiv" class="d-flex justify-content-center mb-1">
            <select id="teacher" class="form-select w-auto placeholder">
                <option>Loading . . .</option>
            </select>
        </div>
        <div class="d-flex justify-content-center gap-3 mb-3">
            <select id="month" class="form-select w-auto">
                <option value="01">January</option>
                <option value="02">February</option>
                <option value="03">March</option>
                <option value="04">April</option>
                <option value="05">May</option>
                <option value="06">June</option>
                <option value="07">July</option>
                <option value="08">August</option>
                <option value="09">September</option>
                <option value="10">October</option>
                <option value="11">November</option>
                <option value="12">December</option>
            </select>
            <select id="date" class="form-select w-auto">
            </select>
            <select id="year" class="form-select w-auto">
                <option value="<?php echo date("Y"); ?>"><?php echo date("Y"); ?></option>
                <option value="<?php echo date("Y")+1; ?>"><?php echo date("Y")+1; ?></option>
            </select>
        </div>

        <div class="vstack gap-3" id="view">
            <div class="d-flex gap-3">
                <hr class="flex-grow-1">
                <span class="placeholder" style="line-height: normal;">1970-01-01 (Thu)</span>
                <hr class="flex-grow-1">
            </div>
            <div class="vstack gap-1 bg-primary-subtle text-primary-emphasis border rounded-3 shadow-sm p-3 placeholder-glow placeholder-wave">
                <span class="text-truncate d-flex gap-3"><i class="bi bi-hash"></i><span class="placeholder flex-grow-1" style="line-height: normal;">Loading . . .</span></span>
                <span class="text-truncate d-flex gap-3"><i class="bi bi-person"></i><span class="placeholder flex-grow-1" style="line-height: normal;">Loading . . .</span></span>
                <span class="text-truncate d-flex gap-3"><i class="bi bi-calendar"></i><span class="placeholder flex-grow-1" style="line-height: normal;">Loading . . .</span></span>
                <span class="text-truncate d-flex gap-3"><i class="bi bi-clock"></i><span class="placeholder flex-grow-1" style="line-height: normal;">Loading . . .</span></span>
            </div>
            <div class="vstack gap-1 bg-primary-subtle text-primary-emphasis border rounded-3 shadow-sm p-3 placeholder-glow placeholder-wave">
                <span class="text-truncate d-flex gap-3"><i class="bi bi-hash"></i><span class="placeholder flex-grow-1" style="line-height: normal;">Loading . . .</span></span>
                <span class="text-truncate d-flex gap-3"><i class="bi bi-person"></i><span class="placeholder flex-grow-1" style="line-height: normal;">Loading . . .</span></span>
                <span class="text-truncate d-flex gap-3"><i class="bi bi-calendar"></i><span class="placeholder flex-grow-1" style="line-height: normal;">Loading . . .</span></span>
                <span class="text-truncate d-flex gap-3"><i class="bi bi-clock"></i><span class="placeholder flex-grow-1" style="line-height: normal;">Loading . . .</span></span>
            </div>
            <div class="vstack gap-1 bg-secondary-subtle text-secondary-emphasis fst-italic border rounded-3 shadow-sm p-3 text-decoration-line-through placeholder-glow placeholder-wave">
                <span class="text-truncate d-flex gap-3"><i class="bi bi-hash"></i><span class="placeholder flex-grow-1" style="line-height: normal;">Loading . . .</span></span>
                <span class="text-truncate d-flex gap-3"><i class="bi bi-person"></i><span class="placeholder flex-grow-1" style="line-height: normal;">Loading . . .</span></span>
                <span class="text-truncate d-flex gap-3"><i class="bi bi-calendar"></i><span class="placeholder flex-grow-1" style="line-height: normal;">Loading . . .</span></span>
                <span class="text-truncate d-flex gap-3"><i class="bi bi-clock"></i><span class="placeholder flex-grow-1" style="line-height: normal;">Loading . . .</span></span>
            </div>
        </div>
    </main>

    <script>
        function load() {
            document.getElementById("view").innerHTML = `
                <div class="d-flex gap-3">
                    <hr class="flex-grow-1">
                    <span class="placeholder" style="line-height: normal;">1970-01-01 (Thu)</span>
                    <hr class="flex-grow-1">
                </div>
                <div class="vstack gap-1 bg-primary-subtle text-primary-emphasis border rounded-3 shadow-sm p-3 placeholder-glow placeholder-wave">
                    <span class="text-truncate d-flex gap-3"><i class="bi bi-hash"></i><span class="placeholder flex-grow-1" style="line-height: normal;">Loading . . .</span></span>
                    <span class="text-truncate d-flex gap-3"><i class="bi bi-person"></i><span class="placeholder flex-grow-1" style="line-height: normal;">Loading . . .</span></span>
                    <span class="text-truncate d-flex gap-3"><i class="bi bi-calendar"></i><span class="placeholder flex-grow-1" style="line-height: normal;">Loading . . .</span></span>
                    <span class="text-truncate d-flex gap-3"><i class="bi bi-clock"></i><span class="placeholder flex-grow-1" style="line-height: normal;">Loading . . .</span></span>
                </div>
                <div class="vstack gap-1 bg-primary-subtle text-primary-emphasis border rounded-3 shadow-sm p-3 placeholder-glow placeholder-wave">
                    <span class="text-truncate d-flex gap-3"><i class="bi bi-hash"></i><span class="placeholder flex-grow-1" style="line-height: normal;">Loading . . .</span></span>
                    <span class="text-truncate d-flex gap-3"><i class="bi bi-person"></i><span class="placeholder flex-grow-1" style="line-height: normal;">Loading . . .</span></span>
                    <span class="text-truncate d-flex gap-3"><i class="bi bi-calendar"></i><span class="placeholder flex-grow-1" style="line-height: normal;">Loading . . .</span></span>
                    <span class="text-truncate d-flex gap-3"><i class="bi bi-clock"></i><span class="placeholder flex-grow-1" style="line-height: normal;">Loading . . .</span></span>
                </div>
                <div class="vstack gap-1 bg-secondary-subtle text-secondary-emphasis fst-italic border rounded-3 shadow-sm p-3 text-decoration-line-through placeholder-glow placeholder-wave">
                    <span class="text-truncate d-flex gap-3"><i class="bi bi-hash"></i><span class="placeholder flex-grow-1" style="line-height: normal;">Loading . . .</span></span>
                    <span class="text-truncate d-flex gap-3"><i class="bi bi-person"></i><span class="placeholder flex-grow-1" style="line-height: normal;">Loading . . .</span></span>
                    <span class="text-truncate d-flex gap-3"><i class="bi bi-calendar"></i><span class="placeholder flex-grow-1" style="line-height: normal;">Loading . . .</span></span>
                    <span class="text-truncate d-flex gap-3"><i class="bi bi-clock"></i><span class="placeholder flex-grow-1" style="line-height: normal;">Loading . . .</span></span>
                </div>`;

            const formData = new FormData();

            let start_date;
            let end_date;
            if(document.getElementById("date").value == "all") {
                start_date = new Date(document.getElementById("year").value, document.getElementById("month").value-1, 1);
                end_date = new Date(document.getElementById("year").value, document.getElementById("month").value, 0);
            } else {
                start_date = new Date(document.getElementById("year").value, document.getElementById("month").value-1, document.getElementById("date").value);
                end_date = new Date(start_date.setDate(start_date.getDate() + 7));
                start_date.setDate(start_date.getDate() - 7);
            }

            formData.append("teacher", document.getElementById("teacher").value);
            formData.append("start_date", `${start_date.getFullYear()}-${String(start_date.getMonth()+1).padStart(2, '0')}-${String(start_date.getDate()).padStart(2, '0')}`);
            formData.append("end_date", `${end_date.getFullYear()}-${String(end_date.getMonth()+1).padStart(2, '0')}-${String(end_date.getDate()).padStart(2, '0')}`);

            fetch("./processes/loadSchedules.php", {
                method: "POST",
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                throw new Error('Error occured: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                if(data['header']['result'] == "success") {
                    let schedules = [];
                    let tmp_schedules = [];

                    data['body']['schedule'].forEach(function(item,index,arr2){
                        if(typeof tmp_schedules[item["date"]] == "undefined") tmp_schedules[item["date"]] = [];
                        tmp_schedules[item["date"]].push(item);
                    });
                    
                    Object.keys(tmp_schedules).sort().forEach(function(key) {
                        schedules[key] = tmp_schedules[key];
                    });

                    for(date in schedules) {
                        schedules[date].sort((a, b) => {
                            if (a.start_time < b.start_time) return -1;
                            if (a.start_time > b.start_time) return 1;
                            return 0;
                        });
                    }

                    // const yoil_ko = ["일", "월", "화", "수", "목", "금", "토"];
                    const yoil_en = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
                    let insertHTML = "";

                    for(date in schedules) {
                        let tmp_insertHTML = "";

                        schedules[date].forEach(function(item,index,arr2){
                            if(item["user"]["id"] == "Banned Class Time") {
                                // let hideBanClassStyle = document.getElementById("hideBannedClass").checked?`style="display: none;"`:"";
                                if(document.getElementById("hideBannedClass").checked) return;

                                tmp_insertHTML += `
                                    <div class="vstack gap-1 bg-danger-subtle text-danger-emphasis border rounded-3 shadow-sm p-3 bannedClass">
                                        <span class="text-truncate d-flex gap-3"><i class="bi bi-hash"></i><span>${item["idx"]}${item["isCancelled"]?" (Cancelled)":""}</span></span>
                                        <span class="text-truncate d-flex gap-3"><i class="bi bi-person"></i><span>${item["user"]["name"]}</span></span>
                                        <span class="text-truncate d-flex gap-3"><i class="bi bi-clock"></i><span>${item["start_time"]} ~ ${item["end_time"]}</span></span>
                                    </div>`;
                            } else {
                                // let hideCancelledClassStyle = (document.getElementById("hideCancelledClass").checked&&item["isCancelled"])?`style="display: none;"`:"";
                                if(document.getElementById("hideCancelledClass").checked&&item["isCancelled"]) return;

                                tmp_insertHTML += `
                                    <div class="vstack gap-1 ${item["isCancelled"]?"bg-secondary-subtle text-secondary-emphasis cancelledClass":"bg-primary-subtle text-primary-emphasis"} border rounded-3 shadow-sm p-3">
                                        <span class="text-truncate d-flex gap-3"><i class="bi bi-hash"></i><span>${item["idx"]}${item["isCancelled"]?" (Cancelled)":""}</span></span>
                                        <span class="text-truncate d-flex gap-3"><i class="bi bi-person"></i><span>${item["user"]["name"]} (${item["user"]["id"]})</span></span>
                                        <span class="text-truncate d-flex gap-3"><i class="bi bi-clock"></i><span>${item["start_time"]} ~ ${item["end_time"]}</span></span>
                                        <span class="text-truncate d-flex gap-3"><i class="bi bi-credit-card"></i><span> <?php if($_SESSION['user']['permission'] >= 3) { ?><a href="/dashboard/billing-records/detail/?orderId=${item['orderId']}"><?php } ?>${item["orderId"]}<?php if($_SESSION['user']['permission'] >= 3) { ?></a><?php } ?> </span></span>
                                    </div>`;
                            }
                        });

                        if(tmp_insertHTML != "") {
                            insertHTML += `
                                <div class="d-flex gap-3">
                                    <hr class="flex-grow-1">
                                    <span>${date} (${yoil_en[new Date(date).getDay()]})</span>
                                    <hr class="flex-grow-1">
                                </div> ${tmp_insertHTML}`;
                        }
                    }

                    document.getElementById("view").innerHTML = insertHTML;
                } else {
                    alert(data['header']['message']);
                }
            })
            .catch(error => {
                alert("An error occurred while communicating with the server.");
                console.log(error);
            });
        }
        
        // document.getElementById("hideCancelledClass").addEventListener("change", (e)=>{
        //     let display = e.target.checked?"none":"";
        //     Object.entries(document.querySelectorAll(".cancelledClass")).map((item)=>{return item[1].style.display = display;})
        // });
        // document.getElementById("hideBannedClass").addEventListener("change", (e)=>{
        //     let display = e.target.checked?"none":"";
        //     Object.entries(document.querySelectorAll(".bannedClass")).map((item)=>{return item[1].style.display = display;})
        // });
        document.getElementById("hideCancelledClass").addEventListener("change", (e)=>{
            load();
        });
        document.getElementById("hideBannedClass").addEventListener("change", (e)=>{
            load();
        });
        
        document.getElementById("year").addEventListener("change", load);
        document.getElementById("month").addEventListener("change", () => {
            let choosedDate = new Date(document.getElementById("year").value, document.getElementById("month").value-1, 1);
            // let yoil_kr = ["일", "월", "화", "수", "목", "금", "토"];
            let yoil_en = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];

            let insertHTML = '<option value="all">전체</option>';
            do {
                insertHTML += `<option value="${String(choosedDate.getDate()).padStart(2, '0')}">${String(choosedDate.getDate()).padStart(2, '0')}일 (${yoil_en[choosedDate.getDay()]})</option>`;
                choosedDate.setDate( choosedDate.getDate()+1 );
            } while (choosedDate.getDate() != 1);

            document.getElementById("date").innerHTML = insertHTML;

            load();
        });
        document.getElementById("date").addEventListener("change", load);
        document.getElementById("teacher").addEventListener("change", load);


        window.addEventListener('load', () => {
            document.getElementById("year").value = "<?php echo date("Y"); ?>";
            document.getElementById("month").value = "<?php echo date("m"); ?>";

            let choosedDate = new Date(document.getElementById("year").value, document.getElementById("month").value-1, 1);
            // let yoil_kr = ["일", "월", "화", "수", "목", "금", "토"];
            let yoil_en = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
            let date_en = ["0th", "1st","2nd","3th","4th","5th","6th","7th","8th","9th","10th","11th","12th","13th","14th","15th","16th","17th","18th","19th","20th","21st","22nd","23rd","24th","25th","26th","27th","28th","29th","30th","31st","32nd"];

            let insertHTML = '<option value="all">All</option>';
            do {
                // insertHTML += `<option value="${String(choosedDate.getDate()).padStart(2, '0')}">${String(choosedDate.getDate()).padStart(2, '0')}일 (${yoil_en[choosedDate.getDay()]})</option>`;
                insertHTML += `<option value="${String(choosedDate.getDate()).padStart(2, '0')}">${date_en[choosedDate.getDate()]} (${yoil_en[choosedDate.getDay()]})</option>`;
                choosedDate.setDate( choosedDate.getDate()+1 );
            } while (choosedDate.getDate() != 1);

            document.getElementById("date").innerHTML = insertHTML;
            
            document.getElementById("date").value = "<?php echo date("d"); ?>";

            
            fetch("./processes/loadTeachers.php")
            .then(response => {
                if (!response.ok) {
                throw new Error('Error occured: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                if(data['header']['result'] == "success") {          
                    let insertHTML = "";
                    data['body']['teachers'].forEach(function(item,index,arr2){
                        insertHTML += `<option value="${item["idx"]}">${item["name"]}</option>`;
                    });

                    document.getElementById("teacher").innerHTML = insertHTML;
                    document.getElementById("teacherSelectDiv").classList.remove("placeholder-glow");
                    document.getElementById("teacherSelectDiv").classList.remove("placeholder-wave");
                    document.getElementById("teacher").classList.remove("placeholder");
                    
                    load();
                } else {
                    alert(data['header']['message']);
                }
            })
            .catch(error => {
                alert("An error occurred while communicating with the server.");
                console.log(error);
            });
        });
    </script>
<?php
    include_once $_SERVER["DOCUMENT_ROOT"]."/schedule-manager/_schedule-manager_footer.php";
    include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_footer.php";
?>