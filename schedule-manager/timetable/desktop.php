<?php
    $GLOBALS["page-title"] = "Timetable";
    $GLOBALS["page-language"] = "en";

    include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_config.php";
    include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_header.php";
    include_once $_SERVER["DOCUMENT_ROOT"]."/schedule-manager/_schedule-manager_header.php";
?>
    <!-- Modal for Cancelled Schedules -->
    <div class="modal fade" id="scheduleHistory" tabindex="-1">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">1970-01-01 00:00~00:00 Schedule History</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="vstack gap-3">
                        <div class="d-flex gap-3">
                            <hr class="flex-grow-1">
                            <span>1970-01-01 (Thu)</span>
                            <hr class="flex-grow-1">
                        </div>
                        <div class="vstack gap-1 bg-primary-subtle text-primary-emphasis border rounded-3 shadow-sm p-3 placeholder-glow placeholder-wave">
                            <span class="text-truncate d-flex gap-3"><i class="bi bi-hash"></i><span class="placeholder flex-grow-1" style="line-height: normal;">Loading . . .</span></span>
                            <span class="text-truncate d-flex gap-3"><i class="bi bi-person"></i><span class="placeholder flex-grow-1" style="line-height: normal;">Loading . . .</span></span>
                            <span class="text-truncate d-flex gap-3"><i class="bi bi-credit-card"></i><span class="placeholder flex-grow-1" style="line-height: normal;">Loading . . .</span></span>
                            <span class="text-truncate d-flex gap-3"><i class="bi bi-clock"></i><span class="placeholder flex-grow-1" style="line-height: normal;">Loading . . .</span></span>
                        </div>
                        <div class="vstack gap-1 bg-secondary-subtle text-secondary-emphasis fst-italic border rounded-3 shadow-sm p-3 text-decoration-line-through placeholder-glow placeholder-wave">
                            <span class="text-truncate d-flex gap-3"><i class="bi bi-hash"></i><span class="placeholder flex-grow-1" style="line-height: normal;">Loading . . .</span></span>
                            <span class="text-truncate d-flex gap-3"><i class="bi bi-person"></i><span class="placeholder flex-grow-1" style="line-height: normal;">Loading . . .</span></span>
                            <span class="text-truncate d-flex gap-3"><i class="bi bi-credit-card"></i><span class="placeholder flex-grow-1" style="line-height: normal;">Loading . . .</span></span>
                            <span class="text-truncate d-flex gap-3"><i class="bi bi-clock"></i><span class="placeholder flex-grow-1" style="line-height: normal;">Loading . . .</span></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <main>
        <p class="h1 fw-bold mb-3">Timetable (Desktop)</p>
        <p class="text-muted mb-1">* Timetable (Desktop) and Timetable (Mobile) have only different UI, but the contents are the same.</p>
        <p class="text-muted mb-1">* Click the lock icon to ban a class time. (The lock icon is in the top right corner or in the blank space of the timetable.)</p>
        <p class="text-muted mb-3">* Click the red lock icon to unban a banned class time.</p>

        <div id="unexpectedDataAlerts"></div>
        <div class="overflow-x-scroll">
            <table class="table table-fixed text-nowrap">
                <colgroup>
                    <col style="width: 72px;">
                    <col>
                    <col>
                    <col>
                    <col>
                    <col>
                </colgroup>
                <thead class="text-center">
                    <tr>
                        <th colspan="6" id="teacherSelectTh" class="placeholder-glow placeholder-wave">
                            <select id="teacher" class="form-select w-auto mx-auto placeholder">
                                <option>Loading . . .</option>
                            </select>
                        </th>
                    </tr>
                    <tr>
                        <th colspan="6">
                            <div class="d-flex justify-content-center gap-3">
                                <button class="btn btn-outline-primary" onclick="addDate(-7);"><i class="bi bi-caret-left-fill"></i></button>
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
                                <button class="btn btn-outline-primary" onclick="addDate(7);"><i class="bi bi-caret-right-fill"></i></button>
                            </div>
                        </th>
                    </tr>
                    <tr id="tableYoil">
                        <th></th>
                        <th>Mon</th>
                        <th>Tue</th>
                        <th>Wed</th>
                        <th>Thu</th>
                        <th>Fri</th>
                    </tr>
                </thead>
                <tbody id="view">
                    <?php foreach(_class_times as $time) { ?>
                        <tr class="placeholder-glow placeholder-wave">
                            <td class="text-center align-middle"><div class="d-flex flex-column gap-1"><span><?php echo explode("~", $time)[0]; ?></span><span>~</span><span><?php echo explode("~", $time)[1]; ?></span></td>
                            <td>
                                <div class="d-flex flex-column gap-1">
                                    <span class="text-truncate d-flex gap-2"><i class="bi bi-hash"></i> <span class="placeholder flex-grow-1" style="line-height: normal;">Loading . . .</span> </span>
                                    <span class="text-truncate d-flex gap-2"><i class="bi bi-person"></i> <span class="placeholder flex-grow-1" style="line-height: normal;">Loading . . .</span> </span>
                                    <span class="text-truncate d-flex gap-2"><i class="bi bi-credit-card"></i> <span class="placeholder flex-grow-1" style="line-height: normal;">Loading . . .</span> </span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex flex-column gap-1">
                                    <span class="text-truncate d-flex gap-2"><i class="bi bi-hash"></i> <span class="placeholder flex-grow-1" style="line-height: normal;">Loading . . .</span> </span>
                                    <span class="text-truncate d-flex gap-2"><i class="bi bi-person"></i> <span class="placeholder flex-grow-1" style="line-height: normal;">Loading . . .</span> </span>
                                    <span class="text-truncate d-flex gap-2"><i class="bi bi-credit-card"></i> <span class="placeholder flex-grow-1" style="line-height: normal;">Loading . . .</span> </span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex flex-column gap-1">
                                    <span class="text-truncate d-flex gap-2"><i class="bi bi-hash"></i> <span class="placeholder flex-grow-1" style="line-height: normal;">Loading . . .</span> </span>
                                    <span class="text-truncate d-flex gap-2"><i class="bi bi-person"></i> <span class="placeholder flex-grow-1" style="line-height: normal;">Loading . . .</span> </span>
                                    <span class="text-truncate d-flex gap-2"><i class="bi bi-credit-card"></i> <span class="placeholder flex-grow-1" style="line-height: normal;">Loading . . .</span> </span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex flex-column gap-1">
                                    <span class="text-truncate d-flex gap-2"><i class="bi bi-hash"></i> <span class="placeholder flex-grow-1" style="line-height: normal;">Loading . . .</span> </span>
                                    <span class="text-truncate d-flex gap-2"><i class="bi bi-person"></i> <span class="placeholder flex-grow-1" style="line-height: normal;">Loading . . .</span> </span>
                                    <span class="text-truncate d-flex gap-2"><i class="bi bi-credit-card"></i> <span class="placeholder flex-grow-1" style="line-height: normal;">Loading . . .</span> </span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex flex-column gap-1">
                                    <span class="text-truncate d-flex gap-2"><i class="bi bi-hash"></i> <span class="placeholder flex-grow-1" style="line-height: normal;">Loading . . .</span> </span>
                                    <span class="text-truncate d-flex gap-2"><i class="bi bi-person"></i> <span class="placeholder flex-grow-1" style="line-height: normal;">Loading . . .</span> </span>
                                    <span class="text-truncate d-flex gap-2"><i class="bi bi-credit-card"></i> <span class="placeholder flex-grow-1" style="line-height: normal;">Loading . . .</span> </span>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </main>

    <script>
        function load() {
            document.getElementById("view").innerHTML = `
                    <?php foreach(_class_times as $time) { ?>
                        <tr class="placeholder-glow placeholder-wave">
                            <td class="text-center align-middle"><div class="d-flex flex-column gap-1"><span><?php echo explode("~", $time)[0]; ?></span><span>~</span><span><?php echo explode("~", $time)[1]; ?></span></td>
                            <td>
                                <div class="d-flex flex-column gap-1">
                                    <span class="text-truncate d-flex gap-2"><i class="bi bi-hash"></i> <span class="placeholder flex-grow-1" style="line-height: normal;">Loading . . .</span> </span>
                                    <span class="text-truncate d-flex gap-2"><i class="bi bi-person"></i> <span class="placeholder flex-grow-1" style="line-height: normal;">Loading . . .</span> </span>
                                    <span class="text-truncate d-flex gap-2"><i class="bi bi-credit-card"></i> <span class="placeholder flex-grow-1" style="line-height: normal;">Loading . . .</span> </span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex flex-column gap-1">
                                    <span class="text-truncate d-flex gap-2"><i class="bi bi-hash"></i> <span class="placeholder flex-grow-1" style="line-height: normal;">Loading . . .</span> </span>
                                    <span class="text-truncate d-flex gap-2"><i class="bi bi-person"></i> <span class="placeholder flex-grow-1" style="line-height: normal;">Loading . . .</span> </span>
                                    <span class="text-truncate d-flex gap-2"><i class="bi bi-credit-card"></i> <span class="placeholder flex-grow-1" style="line-height: normal;">Loading . . .</span> </span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex flex-column gap-1">
                                    <span class="text-truncate d-flex gap-2"><i class="bi bi-hash"></i> <span class="placeholder flex-grow-1" style="line-height: normal;">Loading . . .</span> </span>
                                    <span class="text-truncate d-flex gap-2"><i class="bi bi-person"></i> <span class="placeholder flex-grow-1" style="line-height: normal;">Loading . . .</span> </span>
                                    <span class="text-truncate d-flex gap-2"><i class="bi bi-credit-card"></i> <span class="placeholder flex-grow-1" style="line-height: normal;">Loading . . .</span> </span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex flex-column gap-1">
                                    <span class="text-truncate d-flex gap-2"><i class="bi bi-hash"></i> <span class="placeholder flex-grow-1" style="line-height: normal;">Loading . . .</span> </span>
                                    <span class="text-truncate d-flex gap-2"><i class="bi bi-person"></i> <span class="placeholder flex-grow-1" style="line-height: normal;">Loading . . .</span> </span>
                                    <span class="text-truncate d-flex gap-2"><i class="bi bi-credit-card"></i> <span class="placeholder flex-grow-1" style="line-height: normal;">Loading . . .</span> </span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex flex-column gap-1">
                                    <span class="text-truncate d-flex gap-2"><i class="bi bi-hash"></i> <span class="placeholder flex-grow-1" style="line-height: normal;">Loading . . .</span> </span>
                                    <span class="text-truncate d-flex gap-2"><i class="bi bi-person"></i> <span class="placeholder flex-grow-1" style="line-height: normal;">Loading . . .</span> </span>
                                    <span class="text-truncate d-flex gap-2"><i class="bi bi-credit-card"></i> <span class="placeholder flex-grow-1" style="line-height: normal;">Loading . . .</span> </span>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>`;

            const formData = new FormData();

            let date = new Date(document.getElementById("year").value, document.getElementById("month").value-1, document.getElementById("date").value);
            let start_date = new Date(date.setDate( date.getDate() - date.getDay() ));
            let end_date = new Date(date.setDate( date.getDate() + 6 ));

            formData.append("teacher", document.getElementById("teacher").value);
            formData.append("start_date", `${start_date.getFullYear()}-${String(start_date.getMonth()+1).padStart(2, '0')}-${String(start_date.getDate()).padStart(2, '0')}`);
            formData.append("end_date", `${end_date.getFullYear()}-${String(end_date.getMonth()+1).padStart(2, '0')}-${String(end_date.getDate()).padStart(2, '0')}`);

            let tmp_date = new Date(start_date);
            let dates = [];
            for(let i=0; i<7; i++) {
                dates.push(`${String(tmp_date.getMonth()+1).padStart(2, '0')}/${String(tmp_date.getDate()).padStart(2, '0')}`);
                tmp_date.setDate(tmp_date.getDate() + 1);
            }

            document.getElementById("tableYoil").innerHTML = `<th></th><th>Mon (${dates[1]})</th><th>Tue (${dates[2]})</th><th>Wed (${dates[3]})</th><th>Thu (${dates[4]})</th><th>Fri (${dates[5]})</th>`;

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
                    const times = <?php echo json_encode(_class_times); ?>;
                    let insertHTML =[ 
                    <?php foreach(_class_times as $time) { ?>
                        ["<td class=\"text-center align-middle\"><div class=\"d-flex flex-column gap-1\"><?php echo explode("~", $time)[0]; ?></span><span>~</span><span><?php echo explode("~", $time)[1]; ?></td>", "<td></td>", "<td></td>", "<td></td>", "<td></td>", "<td></td>"],
                    <?php } ?>];

                    let cancelledItem = [[],[],[],[],[],[],[],[],[],[],[],[],[],[],[],[],[],[]];
                    let unexpectedData = [];

                    tmp_date = new Date(start_date);
                    dates = [];
                    for(let i=0; i<5; i++) {
                        dates.push(`${String(tmp_date.getFullYear())}-${String(tmp_date.getMonth()+1).padStart(2, '0')}-${String(tmp_date.getDate()).padStart(2, '0')}`);
                        tmp_date.setDate(tmp_date.getDate() + 1);
                    }
                    for(i=0;i<18;i++) {
                        for(j=0; j<5; j++) {
                            tmp_date.setDate(tmp_date.getDate() + 1);
                            insertHTML[i][j+1] = `<td class="align-middle" style="height: 1px;">
                                                    <button class="btn btn-outline-light w-100 h-100" onclick="toggleBanClass(this);" data-bs-date="${dates[j]}" data-bs-start-time="${times[i].split("~")[0]}" data-bs-end-time="${times[i].split("~")[1]}" data-bs-teacher="${document.getElementById("teacher").value}"><i class="bi bi-lock"></i></button>
                                                </td>`;
                        }
                    }

                    let schedules_str = data['body']['schedule'].map((item)=>{
                        return `${item["date"]} ${item["start_time"]}~${item["end_time"]}`;
                    });
                    let duplicatedClass = {};
                    schedules_str.map((item)=>{
                        if(schedules_str.indexOf(item) !== schedules_str.lastIndexOf(item)) {
                            if(typeof duplicatedClass[item] != "number") duplicatedClass[item] = 1;
                            else duplicatedClass[item]++;
                        }
                    });

                    data['body']['schedule'].forEach(function(item,index,arr2){
                        let yoilNum = new Date(item["date"]).getDay();
                        
                        if(times.indexOf(item["start_time"]+"~"+item["end_time"]) >= 0 && yoilNum>=1 && yoilNum<=5) {
                            if(item["user"]["id"] == "Banned Class Time") {
                                insertHTML[times.indexOf(item["start_time"]+"~"+item["end_time"])][yoilNum] = `
                                    <td class="align-middle" style="height: 1px;">
                                        <button class="btn btn-outline-danger w-100 h-100" onclick="toggleBanClass(this);" data-bs-date="${item["date"]}" data-bs-start-time="${item["start_time"]}" data-bs-end-time="${item["end_time"]}" data-bs-teacher="${item["teacher"]["idx"]}">
                                            <i class="bi bi-lock"></i>
                                        </button>
                                    </td>`;
                            } else {
                                let thisDuplicatedClassCnt = Number(duplicatedClass[`${item["date"]} ${item["start_time"]}~${item["end_time"]}`]);
                                if(isNaN(thisDuplicatedClassCnt)) thisDuplicatedClassCnt = 0;
                                
                                insertHTML[times.indexOf(item["start_time"]+"~"+item["end_time"])][yoilNum] = `
                                    <td class="${item["isCancelled"]?"bg-secondary-subtle text-secondary-emphasis":"bg-primary-subtle text-primary-emphasis"}">
                                        <div class="d-flex flex-column gap-1">
                                            <span class="text-truncate d-flex gap-2">
                                                <i class="bi bi-hash"></i>
                                                <span class="flex-grow-1 d-flex justify-content-between">
                                                    <span>${item["idx"]}${item["isCancelled"]?" (Cancelled)":""}</span>
                                                    <span class="d-flex gap-1 justify-content-end">
                                                        <a role="button" class="text-muted fst-normal ${thisDuplicatedClassCnt>0?"":"visually-hidden"}" style="font-family: bootstrap-icons;" data-bs-toggle="modal" data-bs-target="#scheduleHistory" data-bs-date="${item["date"]}" data-bs-start-time="${item["start_time"]}" data-bs-end-time="${item["end_time"]}" data-bs-teacher="${item["teacher"]["idx"]}">
                                                            &#xF292; ${thisDuplicatedClassCnt}
                                                        </a>
                                                        <a role="button" class="text-muted fst-normal ${item["isCancelled"]?"":"visually-hidden"}" style="font-family: bootstrap-icons;" data-bs-toggle="modal" onclick="toggleBanClass(this);" data-bs-date="${item["date"]}" data-bs-start-time="${item["start_time"]}" data-bs-end-time="${item["end_time"]}" data-bs-teacher="${item["teacher"]["idx"]}">
                                                            &#xF47B;
                                                        </a>
                                                    </span>
                                                </span>
                                            </span>
                                            <span class="text-truncate d-flex gap-2"><i class="bi bi-person"></i> <span>${item["user"]["name"]} (${item["user"]["id"]})</span> </span>
                                            <span class="text-truncate d-flex gap-2"><i class="bi bi-credit-card"></i> <?php if($_SESSION['user']['permission'] >= 3) { ?><a href="/dashboard/billing-records/detail/?orderId=${item['orderId']}"><?php } ?>${item["orderId"]}<?php if($_SESSION['user']['permission'] >= 3) { ?></a><?php } ?> </span>
                                        </div>
                                    </td>`;
                            }
                        } else {
                            let errMsg = [];
                            if(times.indexOf(item["start_time"]+"~"+item["end_time"]) < 0) errMsg.push("Invalid class time.");
                            if(yoilNum<1 || yoilNum>5) errMsg.push("Invalid day of the week.");

                            unexpectedData.push(`<div class="alert alert-danger" role="alert">
                                                    <h4 class="alert-heading">Unknown schedule found.</h4>
                                                    <p>${errMsg.join(" ")}</p>
                                                    <hr>

                                                    <div class="d-flex flex-column gap-1">
                                                        <span class="text-truncate d-flex gap-2"><i class="bi bi-hash"></i> <span>${item["idx"]}</span> </span>
                                                        <span class="text-truncate d-flex gap-2"><i class="bi bi-person"></i> <span>${item["user"]["name"]} (${item["user"]["id"]})</span> </span>
                                                        <span class="text-truncate d-flex gap-2"><i class="bi bi-credit-calendar"></i><span>${item["date"]}</span></span>
                                                        <span class="text-truncate d-flex gap-2"><i class="bi bi-credit-card"></i><span>${item["orderId"]}</span></span>
                                                        <span class="text-truncate d-flex gap-2"><i class="bi bi-clock"></i><span>${item["start_time"]}~${item["end_time"]}</span></span>
                                                    </div>
                                                </div>`);
                        }
                    });

                    document.getElementById("view").innerHTML = "<tr>"+insertHTML[0].join("")+"</tr><tr>"+insertHTML[1].join("")+"</tr><tr>"+insertHTML[2].join("")+"</tr><tr>"+insertHTML[3].join("")+"</tr><tr>"+insertHTML[4].join("")+"</tr><tr>"+insertHTML[5].join("")+"</tr><tr>"+insertHTML[6].join("")+"</tr><tr>"+insertHTML[7].join("")+"</tr><tr>"+insertHTML[8].join("")+"</tr><tr>"+insertHTML[9].join("")+"</tr><tr>"+insertHTML[10].join("")+"</tr><tr>"+insertHTML[11].join("")+"</tr><tr>"+insertHTML[12].join("")+"</tr><tr>"+insertHTML[13].join("")+"</tr><tr>"+insertHTML[14].join("")+"</tr><tr>"+insertHTML[15].join("")+"</tr><tr>"+insertHTML[16].join("")+"</tr><tr>"+insertHTML[17].join("")+"</tr>";
                    document.getElementById("unexpectedDataAlerts").innerHTML = unexpectedData.join("");
                } else {
                    alert(data['header']['message']);
                }
            })
            .catch(error => {
                alert("An error occurred while communicating with the server.");
                console.log(error);
            });
        }


        function toggleBanClass(ele) {
            const formData = new FormData();

            formData.append("teacher", ele.getAttribute('data-bs-teacher'));
            formData.append("date", ele.getAttribute("data-bs-date"));
            formData.append("start_time", ele.getAttribute("data-bs-start-time"));
            formData.append("end_time", ele.getAttribute("data-bs-end-time"));

            fetch("./processes/toggleBanClass.php", {
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
                if(data['header']['result'] != "success") {
                    alert(data['header']['message']);
                }
                load();
            })
            .catch(error => {
                alert("An error occurred while communicating with the server.");
                console.log(error);
            });
        }
        

        const scheduleHistory = document.getElementById('scheduleHistory');
        if (scheduleHistory) {
            scheduleHistory.addEventListener('show.bs.modal', event => {
                const date = event.relatedTarget.getAttribute('data-bs-date');
                const start_time = event.relatedTarget.getAttribute('data-bs-start-time');
                const end_time = event.relatedTarget.getAttribute('data-bs-end-time');
                
                const modalTitle = scheduleHistory.querySelector('.modal-title');
                const modalBody = scheduleHistory.querySelector('.modal-body');

                modalTitle.textContent = `${date} ${start_time}~${end_time} Schedule History`;
                modalBody.innerHTML = `<div class="vstack gap-3">
                                            <div class="d-flex gap-3 visually-hidden">
                                                <hr class="flex-grow-1">
                                                <span>${date} (${["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"][new Date(date).getDay()]})</span>
                                                <hr class="flex-grow-1">
                                            </div>
                                            <div class="vstack gap-1 bg-primary-subtle text-primary-emphasis border rounded-3 shadow-sm p-3 placeholder-glow placeholder-wave">
                                                <span class="text-truncate d-flex gap-3"><i class="bi bi-hash"></i><span class="placeholder flex-grow-1" style="line-height: normal;">Loading . . .</span></span>
                                                <span class="text-truncate d-flex gap-3"><i class="bi bi-person"></i><span class="placeholder flex-grow-1" style="line-height: normal;">Loading . . .</span></span>
                                                <span class="text-truncate d-flex gap-3"><i class="bi bi-credit-card"></i><span class="placeholder flex-grow-1" style="line-height: normal;">Loading . . .</span></span>
                                                <span class="text-truncate d-flex gap-3"><i class="bi bi-clock"></i><span class="placeholder flex-grow-1" style="line-height: normal;">Loading . . .</span></span>
                                            </div>
                                            <div class="vstack gap-1 bg-secondary-subtle text-secondary-emphasis fst-italic border rounded-3 shadow-sm p-3 text-decoration-line-through placeholder-glow placeholder-wave">
                                                <span class="text-truncate d-flex gap-3"><i class="bi bi-hash"></i><span class="placeholder flex-grow-1" style="line-height: normal;">Loading . . .</span></span>
                                                <span class="text-truncate d-flex gap-3"><i class="bi bi-person"></i><span class="placeholder flex-grow-1" style="line-height: normal;">Loading . . .</span></span>
                                                <span class="text-truncate d-flex gap-3"><i class="bi bi-credit-card"></i><span class="placeholder flex-grow-1" style="line-height: normal;">Loading . . .</span></span>
                                                <span class="text-truncate d-flex gap-3"><i class="bi bi-clock"></i><span class="placeholder flex-grow-1" style="line-height: normal;">Loading . . .</span></span>
                                            </div>
                                        </div>`;

                const formData = new FormData();

                formData.append("teacher", event.relatedTarget.getAttribute('data-bs-teacher'));
                formData.append("start_date", date);
                formData.append("end_date", date);

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
                        let insertHTML = "";
                        data['body']['schedule'].forEach(function(item,index,arr2){
                            if( start_time == item["start_time"] && end_time == item["end_time"]) {
                                insertHTML += `<div class="vstack gap-1 bg-${item["isCancelled"]?"secondary":"primary"}-subtle text-${item["isCancelled"]?"secondary":"primary"}-emphasis border rounded-3 shadow-sm p-3 placeholder-glow placeholder-wave">
                                                <span class="text-truncate d-flex gap-3"><i class="bi bi-hash"></i><span>${item["idx"]}${item["isCancelled"]?" (Cancelled)":""}</span></span>
                                                <span class="text-truncate d-flex gap-3"><i class="bi bi-person"></i><span>${item["user"]["name"]} (${item["user"]["id"]})</span></span>
                                                <span class="text-truncate d-flex gap-3"><i class="bi bi-credit-card"></i><span>${item["date"]}</span></span>
                                                <span class="text-truncate d-flex gap-3"><i class="bi bi-clock"></i><span>${item["start_time"]}~${item["end_time"]}</span></span>
                                            </div>`;
                            }
                        });

                        modalBody.innerHTML = `<div class="vstack gap-3">
                                <div class="d-flex gap-3 visually-hidden">
                                    <hr class="flex-grow-1">
                                    <span>${date} (${["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"][new Date(date).getDay()]})</span>
                                    <hr class="flex-grow-1">
                                </div>
                                ${insertHTML}
                            </div>`;
                    } else {
                        alert(data['header']['message']);
                    }
                })
                .catch(error => {
                    alert("An error occurred while communicating with the server.");
                    console.log(error);
                });
            });
        }

        function renderChooseDate() {
            let choosedDate = new Date(document.getElementById("year").value, document.getElementById("month").value-1, 1);
            // let yoil_ko = ["일", "월", "화", "수", "목", "금", "토"];
            let yoil_en = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
            let date_en = ["0th", "1st","2nd","3th","4th","5th","6th","7th","8th","9th","10th","11th","12th","13th","14th","15th","16th","17th","18th","19th","20th","21st","22nd","23rd","24th","25th","26th","27th","28th","29th","30th","31st","32nd"];

            let insertHTML = "";
            do {
                // insertHTML += `<option value="${String(choosedDate.getDate()).padStart(2, '0')}">${String(choosedDate.getDate()).padStart(2, '0')}일 (${yoil_en[choosedDate.getDay()]})</option>`;
                insertHTML += `<option value="${String(choosedDate.getDate()).padStart(2, '0')}">${date_en[choosedDate.getDate()]} (${yoil_en[choosedDate.getDay()]})</option>`;
                choosedDate.setDate( choosedDate.getDate()+1 );
            } while (choosedDate.getDate() != 1);

            document.getElementById("date").innerHTML = insertHTML;
        }

        function addDate(amount) {
            let date = new Date(document.getElementById("year").value, document.getElementById("month").value-1, document.getElementById("date").value);
            date.setDate(date.getDate() + amount);

            if(document.getElementById("month").value != String(date.getMonth()+1).padStart(2, "0")) renderChooseDate();

            document.getElementById("year").value = date.getFullYear();
            document.getElementById("month").value = String(date.getMonth()+1).padStart(2, "0");
            document.getElementById("date").value = String(date.getDate()).padStart(2, "0");

            load();
        }


        document.getElementById("year").addEventListener("change", load);
        document.getElementById("month").addEventListener("change", () => {
            renderChooseDate();
            load();
        });
        document.getElementById("date").addEventListener("change", load);
        document.getElementById("teacher").addEventListener("change", load);


        window.addEventListener('load', () => {
            document.getElementById("year").value = "<?php echo date("Y"); ?>";
            document.getElementById("month").value = "<?php echo date("m"); ?>";
            renderChooseDate();
            
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
                    document.getElementById("teacherSelectTh").classList.remove("placeholder-glow");
                    document.getElementById("teacherSelectTh").classList.remove("placeholder-wave");
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