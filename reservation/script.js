var year = parseInt(document.getElementById("chooseYear").value);
var month = parseInt(document.getElementById("chooseMonth").value);
var checkoutObj = {
    "year": year,
    "month": month,
    "datetime": [],
    "coupons": [],
    "totalAmount": 0
};

// ------  결제위젯 초기화 ------ 
// 비회원 결제에는 customerKey 대신 ANONYMOUS를 사용하세요.
const paymentWidget = PaymentWidget(clientKey, customerKey); // 회원 결제
// const paymentWidget = PaymentWidget(clientKey, PaymentWidget.ANONYMOUS) // 비회원 결제

// ------  결제위젯 렌더링 ------ 
// 결제위젯이 렌더링될 DOM 요소를 지정하는 CSS 선택자 및 결제 금액을 넣어주세요. 
// https://docs.tosspayments.com/reference/widget-sdk#renderpaymentmethods선택자-결제-금액-옵션
const paymentMethods = paymentWidget.renderPaymentMethods("#payment-method", { value: 0 });

// ------  이용약관 렌더링 ------
// 이용약관이 렌더링될 DOM 요소를 지정하는 CSS 선택자를 넣어주세요.
// https://docs.tosspayments.com/reference/widget-sdk#renderagreement선택자
paymentWidget.renderAgreement('#agreement');


function renderCheckout() {
    let checkedDates = document.querySelectorAll("input[name=dates]:checked");
    let choosedTimes = {
        1: document.getElementById("chooseTime1"),
        2: document.getElementById("chooseTime2"),
        3: document.getElementById("chooseTime3"),
        4: document.getElementById("chooseTime4"),
        5: document.getElementById("chooseTime5")
    }
    let checkedCoupons = document.querySelectorAll("input[name=coupons]:checked");
    let insertHTML = "";
    let totalAmount = 0;

    checkoutObj = {
        "year": year,
        "month": month,
        "datetime": [],
        "coupons": [],
        "totalAmount": 0
    };

    let btnDisabled = false;
    for(item of checkedDates) {
        let yoilNum = new Date(year, month-1, item.dataset.date).getDay();
        let yoilKo = ["일", "월", "화", "수", "목", "금", "토"];

        if(choosedTimes[yoilNum].value == "0") {
            btnDisabled = true;
            continue;
        }

        checkoutObj['datetime'].push({
            "date": item.dataset.date,
            "time": choosedTimes[yoilNum].value
        });

        insertHTML += `<li class="list-group-item d-flex justify-content-between lh-sm">
                            <div>
                                <h6 class="my-0">${year}. ${month}. ${item.dataset.date}. (${yoilKo[yoilNum]})</h6>
                                <small class="text-body-secondary">${choosedTimes[yoilNum][choosedTimes[yoilNum].selectedIndex].dataset.displayText}</small>
                            </div>
                            <span class="text-body-secondary">₩8,000</span>
                        </li>`;
    }
    document.getElementById("checkoutBtn").disabled = btnDisabled;

    checkedCoupons.forEach(function(item,index,arr2) {
        checkoutObj['coupons'].push(parseInt(item.dataset.idx));
        totalAmount -= parseInt(item.value);

        insertHTML += `<li class="list-group-item d-flex justify-content-between bg-body-tertiary">
                            <div class="text-success">
                                <h6 class="my-0">할인쿠폰</h6>
                                <small class="text-body-secondary">할인쿠폰이 적용됨.</small>
                            </div>
                            <span class="text-success">−₩${parseInt(item.value).toLocaleString()}</span>
                        </li>`;
    });

    totalAmount += 8000*checkoutObj['datetime'].length;
    // if(totalAmount < 0) totalAmount = 0;
    document.querySelectorAll("input[type=checkbox][name=coupons]:not(:checked)").forEach((item)=>{item.disabled = (totalAmount <= 0);})

    checkoutObj["totalAmount"] = totalAmount;

    if(insertHTML == "") insertHTML = '<li class="list-group-item text-muted">선택된 수업 없음.</li>';
    insertHTML += `<li class="list-group-item d-flex justify-content-between">
                        <span>총합 (KRW)</span>
                        <strong ${totalAmount>=0?'':'class="text-danger"'}>₩${totalAmount.toLocaleString()}</strong>
                    </li>`;

    document.getElementById("checkout").innerHTML = insertHTML;
    paymentMethods.updateAmount(totalAmount);

    if(totalAmount > 0) {
        document.getElementById("payment-method").style.display = "block";
        document.getElementById("agreement").style.display = "block";
    } else {
        document.getElementById("payment-method").style.display = "none";
        document.getElementById("agreement").style.display = "none";
    }
}

function renderCalendar() {
    document.getElementById("calendar").classList.add("placeholder-glow");
    document.getElementById("calendar").classList.add("placeholder-wave");
    document.querySelectorAll("#calendar tr td label").forEach((item)=>{item.classList.add("placeholder")})
    document.querySelectorAll("#calendar tr td label").forEach((item)=>{item.classList.remove("disabled")})

    let date = new Date(year, month-1, 1);

    let calendarHTML = "";

    while(date.getMonth() == month-1) {

        // 1일이거나 일요일일 경우 새로운 행
        if(date.getDate() == 1 || date.getDay() == 0) {
            calendarHTML += "<tr>";
        }

        // 1일이라면 앞에 공란 채우기
        if(date.getDate() == 1) {
            for(let i=0; i<date.getDay(); i++) {
                calendarHTML += "<td></td>";
            }
        }

        calendarHTML += '<td class="px-1">';
        if(date.getDay() == 0 || date.getDay() == 6 || date <= new Date()) { // 토요일, 일요일이거나 오늘이거나 과거라면 비활성화
            calendarHTML += `<input type="checkbox" class="btn-check" name="dates" id="d${date.getDate()}" data-date="${date.getDate()}" autocomplete="off" disabled>
                                <label class="btn btn-outline-secondary px-0 w-100 disabled" for="d${date.getDate()}">${date.getDate()}</label>`;
        } else {
            calendarHTML += `<input type="checkbox" class="btn-check" name="dates" id="d${date.getDate()}" data-date="${date.getDate()}" autocomplete="off" onchange="renderTimeSelector();">
                                <label class="btn btn-outline-primary px-0 w-100" for="d${date.getDate()}">${date.getDate()}</label>`;
        }
        calendarHTML += "</td>";

        // 마지막일일 경우 공란 채우고 행 종료
        if(date.getDate() == new Date(year, date.getMonth()+1, 0).getDate()) {
            for(let i=0; i<6-date.getDay(); i++) {
                calendarHTML += "<td></td>";
            }
            calendarHTML += "</tr>\n";
        }
        // 토요일일 경우 행 종료
        if(date.getDay() == 6) {
            calendarHTML += "</tr>\n";
        }

        // date를 다음날로 변경
        date.setDate(date.getDate() + 1);
    }

    document.getElementById("calendar").innerHTML = calendarHTML;

    document.getElementById("calendar").classList.remove("placeholder-glow");
    document.getElementById("calendar").classList.remove("placeholder-wave");

    renderTimeSelector();
    renderCheckout();
}

function toggleWholeDay(day) {
    let date = new Date(year, month-1, 1);
    
    while(date.getMonth() == month-1) {
        if(date.getDay() == day && document.getElementById("d"+date.getDate()).disabled === false) document.getElementById("d"+date.getDate()).checked = !document.getElementById("d"+date.getDate()).checked
        date.setDate(date.getDate() + 1);
    }

    renderTimeSelector();
}

function renderTimeSelector() {
    document.getElementById("timeSelectors").classList.add("placeholder-glow");
    document.getElementById("timeSelectors").classList.add("placeholder-wave");

    let checkedDates = document.querySelectorAll("input[name=dates]:checked");
    let checkedDay = new Array("none","none","none","none","none","none","none");

    if(checkedDates.length > 0) {
        const formData = new FormData();

        formData.append("year", year);
        formData.append("month", month);
        
        checkedDates.forEach(function(item,index,arr2) {
            if(item.checked) {
                checkedDay[new Date(year, month-1, item.dataset.date).getDay()] = "block";
                formData.append("dates[]", item.dataset.date);
            }
        });
        
        document.getElementById("pleaseSelectDateFirst").style.display = "none";

        if(checkedDay[1] == "none") document.querySelector("#timeDay1 select").innerHTML = '';
        if(checkedDay[2] == "none") document.querySelector("#timeDay2 select").innerHTML = '';
        if(checkedDay[3] == "none") document.querySelector("#timeDay3 select").innerHTML = '';
        if(checkedDay[4] == "none") document.querySelector("#timeDay4 select").innerHTML = '';
        if(checkedDay[5] == "none") document.querySelector("#timeDay5 select").innerHTML = '';

        if(document.getElementById("timeDay1").style.display != checkedDay[1]) document.getElementById("timeDay1").style.display = checkedDay[1];
        if(document.getElementById("timeDay2").style.display != checkedDay[2]) document.getElementById("timeDay2").style.display = checkedDay[2];
        if(document.getElementById("timeDay3").style.display != checkedDay[3]) document.getElementById("timeDay3").style.display = checkedDay[3];
        if(document.getElementById("timeDay4").style.display != checkedDay[4]) document.getElementById("timeDay4").style.display = checkedDay[4];
        if(document.getElementById("timeDay5").style.display != checkedDay[5]) document.getElementById("timeDay5").style.display = checkedDay[5];

        document.getElementById("chooseTime1").classList.add("placeholder");
        document.getElementById("chooseTime2").classList.add("placeholder");
        document.getElementById("chooseTime3").classList.add("placeholder");
        document.getElementById("chooseTime4").classList.add("placeholder");
        document.getElementById("chooseTime5").classList.add("placeholder");

        fetch("./processes/loadSchedules.php", {
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
            if(data['header']['result'] != "success") {
                alert(data['header']['message']);
                return;
            }
            
            let insertHTML = "";
            let options_base = data['body']['options_base'];
            // let options_base = {
            //     '14:00~14:25': { displayText: "오후 02시 00분 ~ 02시 25분", available: true },
            //     '14:30~14:55': { displayText: "오후 02시 30분 ~ 02시 55분", available: true },
            //     '15:00~15:25': { displayText: "오후 03시 00분 ~ 03시 25분", available: true },
            //     '15:30~15:55': { displayText: "오후 03시 30분 ~ 03시 55분", available: true },
            //     '16:00~16:25': { displayText: "오후 04시 00분 ~ 04시 25분", available: true },
            //     '16:30~16:55': { displayText: "오후 04시 30분 ~ 04시 55분", available: true },
            //     '17:00~17:25': { displayText: "오후 05시 00분 ~ 05시 25분", available: true },
            //     '17:30~17:55': { displayText: "오후 05시 30분 ~ 05시 55분", available: true },
            //     '18:00~18:25': { displayText: "오후 06시 00분 ~ 06시 25분", available: true },
            //     '18:30~18:55': { displayText: "오후 06시 30분 ~ 06시 55분", available: true },
            //     '19:00~19:25': { displayText: "오후 07시 00분 ~ 07시 25분", available: true },
            //     '19:30~19:55': { displayText: "오후 07시 30분 ~ 07시 55분", available: true },
            //     '20:00~20:25': { displayText: "오후 08시 00분 ~ 08시 25분", available: true },
            //     '20:30~20:55': { displayText: "오후 08시 30분 ~ 08시 55분", available: true },
            //     '21:00~21:25': { displayText: "오후 09시 00분 ~ 09시 25분", available: true },
            //     '21:30~21:55': { displayText: "오후 09시 30분 ~ 09시 55분", available: true },
            //     '22:00~22:25': { displayText: "오후 10시 00분 ~ 10시 25분", available: true },
            //     '22:30~22:55': { displayText: "오후 10시 30분 ~ 10시 55분", available: true }
            // };
            // let options_tmp = {...options_base};
            let options = {
                1: JSON.parse(JSON.stringify(options_base)),
                2: JSON.parse(JSON.stringify(options_base)),
                3: JSON.parse(JSON.stringify(options_base)),
                4: JSON.parse(JSON.stringify(options_base)),
                5: JSON.parse(JSON.stringify(options_base))
            };

            data['body']['schedule'].forEach(function(item,index,arr2) {
                let time = item['start'] + "~" + item['end'];

                if(typeof options[new Date(item['date']).getDay()][time]['available'] !== "boolean") {
                    alert(`에러 발생: 알 수 없는 스케줄 시간. ${item['date']} ${time}`);
                    console.log(options[new Date(item['date']).getDay()][time])
                    throw new Error(`에러 발생: 알 수 없는 스케줄 시간. ${item['date']} ${time}`);
                } else {
                    options[new Date(item['date']).getDay()][time]['available'] = false;
                }
            });

            for(let i=1; i<=5; i++) {
                insertHTML = "";
                if(checkedDay[i] == "block") {
                    insertHTML += `<option value="0" selected>수업 시간을 선택해 주세요</option>`;
                    for(key in options[i]) {
                        if(options[i][key]['available']) insertHTML += `<option value="${key}" data-display-text="${options[i][key]['displayText']}">${options[i][key]['displayText']}</option>`;
                        else insertHTML += `<option value="${key}" data-display-text="${options[i][key]['displayText']}" disabled>${options[i][key]['displayText']} (선택 불가)</option>`;
                    };
                }
                document.getElementById("chooseTime"+i).innerHTML = insertHTML;
            }
    
            document.getElementById("timeSelectors").classList.remove("placeholder-glow");
            document.getElementById("timeSelectors").classList.remove("placeholder-wave");
            
            document.getElementById("chooseTime1").classList.remove("placeholder");
            document.getElementById("chooseTime2").classList.remove("placeholder");
            document.getElementById("chooseTime3").classList.remove("placeholder");
            document.getElementById("chooseTime4").classList.remove("placeholder");
            document.getElementById("chooseTime5").classList.remove("placeholder");
            
            renderCheckout();
        })
        .catch(error => {
            alert("서버와 통신 중에 오류가 발생했습니다.");
            console.log(error);
        });
    } else {
        document.getElementById("pleaseSelectDateFirst").style.display = "block";

        document.querySelector("#timeDay1 select").innerHTML = "";
        document.querySelector("#timeDay2 select").innerHTML = "";
        document.querySelector("#timeDay3 select").innerHTML = "";
        document.querySelector("#timeDay4 select").innerHTML = "";
        document.querySelector("#timeDay5 select").innerHTML = "";

        document.getElementById("timeDay1").style.display = "none";
        document.getElementById("timeDay2").style.display = "none";
        document.getElementById("timeDay3").style.display = "none";
        document.getElementById("timeDay4").style.display = "none";
        document.getElementById("timeDay5").style.display = "none";

        renderCheckout();
    }
}

function loadCoupons() {
    document.getElementById("couponList").classList.add("placeholder-glow");
    document.getElementById("couponList").classList.add("placeholder-wave");
    document.getElementById("couponList").innerHTML = `<li class="list-group-item fw-bold d-flex justify-content-between">
                                                            <span>할인쿠폰</span> 
                                                            <div class="spinner-border spinner-border-sm text-primary my-auto" role="status">
                                                                <span class="visually-hidden">Loading...</span>
                                                            </div>
                                                        </li>
                                                        <li class="list-group-item"><span class="placeholder w-100">Loading ....</span></li>
                                                        <li class="list-group-item"><span class="placeholder w-100">Loading ....</span></li>`;

    fetch("./processes/loadCoupons.php")
    .then(response => {
        if (!response.ok) {
            throw new Error('에러 발생: ' + response.status);
        }
        return response.json();
    })
    .then(data => {
        if(data['header']['result'] != "success") {
            alert(data['header']['message']);
            return;
        }

        let insertHTML = "";
        let couponTypes = {};
        let insertCouponTypes = "";
        if(data['body']['coupons'].length == 0) insertHTML += '<li class="list-group-item text-muted">사용 가능한 쿠폰 없음.</li>';
        else {
            data['body']['coupons'].forEach(function(item,index,arr2) {
                if (typeof couponTypes[item['amount']] == "undefined") couponTypes[item['amount']] = 1;
                else couponTypes[item['amount']]++;

                insertHTML += `<li class="list-group-item">
                                    <input class="form-check-input me-1" type="checkbox" value="${item['amount']}" name="coupons" id="coupon-${item['idx']}" data-idx="${item['idx']}" onchange="renderCheckout();">
                                    <label class="form-check-label stretched-link user-select-none" for="coupon-${item['idx']}">${item['amount'].toLocaleString()}원 할인</label>
                                </li>`;
            });
        }
        for( key in couponTypes ) {
            if(insertCouponTypes == "") insertCouponTypes += `${key}원×${couponTypes[key]}개`;
            else insertCouponTypes += `, ${key}원×${couponTypes[key]}개`;
        }
        if(insertCouponTypes != "") insertCouponTypes = `(${insertCouponTypes})`;
        insertHTML = `<li class="list-group-item fw-bold d-flex justify-content-between"><span>할인쿠폰 ${insertCouponTypes}</span> <a role="button" onclick="loadCoupons();"><i class="bi bi-arrow-clockwise"></i></a></li>`+insertHTML;
        document.getElementById("couponList").innerHTML = insertHTML;

        document.getElementById("couponList").classList.remove("placeholder-glow");
        document.getElementById("couponList").classList.remove("placeholder-wave");

        document.querySelectorAll("input[type=checkbox][name=coupons]:not(:checked)").forEach((item)=>{item.disabled = (checkoutObj["totalAmount"] <= 0);})
    })
    .catch(error => {
        alert("서버와 통신 중에 오류가 발생했습니다.");
        console.log(error);
    });
    
    renderCheckout();
}

function requestPay() {
    let checkedDates = document.querySelectorAll("input[name=dates]:checked");
    let choosedTimes = {
        1: document.getElementById("chooseTime1"),
        2: document.getElementById("chooseTime2"),
        3: document.getElementById("chooseTime3"),
        4: document.getElementById("chooseTime4"),
        5: document.getElementById("chooseTime5")
    }
    for(item of checkedDates) {
        let yoilNum = new Date(year, month-1, item.dataset.date).getDay();
        if(choosedTimes[yoilNum].value == "0") {
            alert("수업 시간을 먼저 선택해 주세요.");
            return;
        }
    }

    if(checkoutObj['datetime'].length == 0) {
        alert("수업 일정을 먼저 선택해 주세요.");
        return;
    }

    fetch("./processes/requestPay.php", {
        method: "POST",
        body: JSON.stringify(checkoutObj)
    })
    .then(response => {
        if (!response.ok) {
        throw new Error('에러 발생: ' + response.status);
        }
        return response.json();
    })
    .then(data => {
        if(data['header']['result'] != "success") {
            alert(data['header']['message']);
            return;
        }
        
        if(data['header']['message'] == "DONE") {
            window.location.href = data['body']['redirect'];
            return;
        }

        paymentMethods.updateAmount(data['body']['checkout']['amount']);

        paymentWidget.requestPayment({
            orderId: data['body']['checkout']['orderId'],
            orderName: data['body']['checkout']['orderName'],
            successUrl: data['body']['checkout']['successUrl'],
            failUrl: data['body']['checkout']['failUrl'],
            customerEmail: data['body']['checkout']['customerEmail'],
            customerName: data['body']['checkout']['customerName']
        })
        .catch(function (error) {
            console.log(error);
            console.log(error.code);
            
            const formData = new FormData();
            formData.append("orderId", data['body']['checkout']['orderId']);
            formData.append("errorcode", error.code);

            fetch("./processes/breakPay.php", {
                method: "POST",
                body: formData
            })
            .then(response2 => {
                if (!response2.ok) {
                throw new Error('에러 발생: ' + response2.status);
                }
                return response2.json();
            })
            .then(data2 => {
                if(data2['header']['result'] != "success") {
                    alert(data2['header']['message']);
                    return;
                }
            })
            .catch(error2 => {
                alert("서버와 통신 중에 오류가 발생했습니다.");
                console.log(error2);
            });
        });
    })
    .catch(error => {
        alert("서버와 통신 중에 오류가 발생했습니다.");
        console.log(error);
    });
}

window.addEventListener('load', () => {
    renderCalendar();
    loadCoupons();
});
document.getElementById("chooseYear").addEventListener("change", () => {
    year = parseInt(document.getElementById("chooseYear").value);
    renderCalendar();
});
document.getElementById("chooseMonth").addEventListener("change", () => {
    month = parseInt(document.getElementById("chooseMonth").value);
    renderCalendar();
});