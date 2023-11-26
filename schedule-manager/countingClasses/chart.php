<?php
    $GLOBALS["page-title"] = "Class Counter (Chart)";
    $GLOBALS["page-language"] = "en";

    include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_config.php";
    include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_header.php";
    include_once $_SERVER["DOCUMENT_ROOT"]."/schedule-manager/_schedule-manager_header.php";
?>
    <main>
        <p class="h1 fw-bold mb-3">Class Counter (Chart)</p>
        <p class="text-muted mb-1">* Cancelled classes are excluded.</p>
        
        <div class="d-flex justify-content-center gap-3 mb-3">
            <button class="btn btn-outline-primary" onclick="addMonth(-1);"><i class="bi bi-caret-left-fill"></i></button>
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
            <select id="year" class="form-select w-auto">
                <?php
                    for($i=2022; $i<=intval(date("Y"))+1; $i++) {
                        if($i == intval(date("Y"))) echo "<option value=\"{$i}\" selected>{$i}</option>";
                        else echo "<option value=\"{$i}\">{$i}</option>";
                    }
                ?>
            </select>
            <button class="btn btn-outline-primary" onclick="addMonth(1);"><i class="bi bi-caret-right-fill"></i></button>
        </div>
        
        <canvas id="myChart"></canvas>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.3.3/dist/chart.umd.min.js"></script>
    
    <script>
        let myChart = null;

        function load() {

            const formData = new FormData();

            formData.append("year", document.getElementById("year").value);
            formData.append("month", document.getElementById("month").value);

            fetch("load.php", {
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

                    if(myChart === null) {
                        myChart = new Chart(document.getElementById('myChart'), {
                            type: 'bar',
                            data: {
                                labels: Object.entries(data['body']['counts']).map((data) => { return data[1]["name"] }),
                                datasets: [{
                                    // axis: 'y',
                                    label: 'The number of classes',
                                    data: Object.entries(data['body']['counts']).map((data) => { return data[1]["total"] }),
                                    borderWidth: 1,
                                    barThickness: 100,
                                }]
                            },
                            options: {
                                scales: {
                                    y: {
                                        beginAtZero: true
                                    }
                                },
                                // indexAxis: 'y'
                            }
                        });
                    } else {
                        myChart.data.datasets[0].data = Object.entries(data['body']['counts']).map((data) => { return data[1]["total"] });
                        myChart.update();
                    };

                } else {
                    alert(data['header']['message']);
                }
            })
            .catch(error => {
                alert("An error occurred while communicating with the server.");
                console.log(error);
            });
        }

        function addMonth(amount) {
            let date = new Date(document.getElementById("year").value, document.getElementById("month").value-1, 1);
            date.setMonth(date.getMonth() + amount);

            document.getElementById("year").value = date.getFullYear();
            document.getElementById("month").value = String(date.getMonth()+1).padStart(2, "0");

            load();
        }


        document.getElementById("year").addEventListener("change", load);
        document.getElementById("month").addEventListener("change", load);


        window.addEventListener('load', () => {
            document.getElementById("year").value = "<?php echo date("Y"); ?>";
            document.getElementById("month").value = "<?php echo date("m"); ?>";
            load();
        });
    </script>
<?php
    include_once $_SERVER["DOCUMENT_ROOT"]."/schedule-manager/_schedule-manager_footer.php";
    include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_footer.php";
?>