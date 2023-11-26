<?php
    $GLOBALS["page-title"] = "Class Counter (Table)";
    $GLOBALS["page-language"] = "en";

    include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_config.php";
    include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_header.php";
    include_once $_SERVER["DOCUMENT_ROOT"]."/schedule-manager/_schedule-manager_header.php";
?>
    <main>
        <p class="h1 fw-bold mb-3">Class Counter (Table)</p>
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

        <div class="overflow-x-scroll">
            <table class="table table-striped text-nowrap text-center">
                <thead id="thead">
                    <tr class="placeholder-glow placeholder-wave">
                        <th style="width: 64px;"></th>
                        <th><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></th>
                        <th><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></th>
                        <th><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></th>
                        <th><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></th>
                        <th><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></th>
                        <th><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></th>
                    </tr>
                </thead>
                <tbody id="tbody">
                    <tr class="placeholder-glow placeholder-wave">
                        <th class="text-center align-middle"><div class="d-flex flex-column gap-1">Total</th>
                        <th><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></th>
                        <th><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></th>
                        <th><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></th>
                        <th><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></th>
                        <th><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></th>
                        <th><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></th>
                    </tr>
                    <tr class="placeholder-glow placeholder-wave">
                        <td class="text-center align-middle"><div class="d-flex flex-column gap-1">1st</td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                    </tr>
                    <tr class="placeholder-glow placeholder-wave">
                        <td class="text-center align-middle"><div class="d-flex flex-column gap-1">2nd</td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                    </tr>
                    <tr class="placeholder-glow placeholder-wave">
                        <td class="text-center align-middle"><div class="d-flex flex-column gap-1">3th</td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                    </tr>
                    <tr class="placeholder-glow placeholder-wave">
                        <td class="text-center align-middle"><div class="d-flex flex-column gap-1">4th</td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                    </tr>
                    <tr class="placeholder-glow placeholder-wave">
                        <td class="text-center align-middle"><div class="d-flex flex-column gap-1">5th</td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                    </tr>
                    <tr class="placeholder-glow placeholder-wave">
                        <td class="text-center align-middle"><div class="d-flex flex-column gap-1">6th</td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                    </tr>
                    <tr class="placeholder-glow placeholder-wave">
                        <td class="text-center align-middle"><div class="d-flex flex-column gap-1">7th</td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                    </tr>
                    <tr class="placeholder-glow placeholder-wave">
                        <td class="text-center align-middle"><div class="d-flex flex-column gap-1">8th</td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                    </tr>
                    <tr class="placeholder-glow placeholder-wave">
                        <td class="text-center align-middle"><div class="d-flex flex-column gap-1">9th</td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                    </tr>
                    <tr class="placeholder-glow placeholder-wave">
                        <td class="text-center align-middle"><div class="d-flex flex-column gap-1">10th</td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                    </tr>
                    <tr class="placeholder-glow placeholder-wave">
                        <td class="text-center align-middle"><div class="d-flex flex-column gap-1">11th</td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                    </tr>
                    <tr class="placeholder-glow placeholder-wave">
                        <td class="text-center align-middle"><div class="d-flex flex-column gap-1">12th</td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                    </tr>
                    <tr class="placeholder-glow placeholder-wave">
                        <td class="text-center align-middle"><div class="d-flex flex-column gap-1">13th</td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                    </tr>
                    <tr class="placeholder-glow placeholder-wave">
                        <td class="text-center align-middle"><div class="d-flex flex-column gap-1">14th</td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                    </tr>
                    <tr class="placeholder-glow placeholder-wave">
                        <td class="text-center align-middle"><div class="d-flex flex-column gap-1">15th</td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                    </tr>
                    <tr class="placeholder-glow placeholder-wave">
                        <td class="text-center align-middle"><div class="d-flex flex-column gap-1">16th</td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                    </tr>
                    <tr class="placeholder-glow placeholder-wave">
                        <td class="text-center align-middle"><div class="d-flex flex-column gap-1">17th</td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                    </tr>
                    <tr class="placeholder-glow placeholder-wave">
                        <td class="text-center align-middle"><div class="d-flex flex-column gap-1">18th</td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                    </tr>
                    <tr class="placeholder-glow placeholder-wave">
                        <td class="text-center align-middle"><div class="d-flex flex-column gap-1">19th</td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                    </tr>
                    <tr class="placeholder-glow placeholder-wave">
                        <td class="text-center align-middle"><div class="d-flex flex-column gap-1">20th</td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                    </tr>
                    <tr class="placeholder-glow placeholder-wave">
                        <td class="text-center align-middle"><div class="d-flex flex-column gap-1">21st</td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                    </tr>
                    <tr class="placeholder-glow placeholder-wave">
                        <td class="text-center align-middle"><div class="d-flex flex-column gap-1">22nd</td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                    </tr>
                    <tr class="placeholder-glow placeholder-wave">
                        <td class="text-center align-middle"><div class="d-flex flex-column gap-1">23rd</td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                    </tr>
                    <tr class="placeholder-glow placeholder-wave">
                        <td class="text-center align-middle"><div class="d-flex flex-column gap-1">24th</td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                    </tr>
                    <tr class="placeholder-glow placeholder-wave">
                        <td class="text-center align-middle"><div class="d-flex flex-column gap-1">25th</td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                    </tr>
                    <tr class="placeholder-glow placeholder-wave">
                        <td class="text-center align-middle"><div class="d-flex flex-column gap-1">26th</td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                    </tr>
                    <tr class="placeholder-glow placeholder-wave">
                        <td class="text-center align-middle"><div class="d-flex flex-column gap-1">27th</td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                    </tr>
                    <tr class="placeholder-glow placeholder-wave">
                        <td class="text-center align-middle"><div class="d-flex flex-column gap-1">28th</td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                    </tr>
                    <tr class="placeholder-glow placeholder-wave">
                        <td class="text-center align-middle"><div class="d-flex flex-column gap-1">29th</td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                    </tr>
                    <tr class="placeholder-glow placeholder-wave">
                        <td class="text-center align-middle"><div class="d-flex flex-column gap-1">30th</td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>
    
    <script>
        function load() {
            document.getElementById("thead").innerHTML = `
                <tr class="placeholder-glow placeholder-wave">
                    <th style="width: 64px;"></th>
                    <th><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></th>
                    <th><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></th>
                    <th><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></th>
                    <th><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></th>
                    <th><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></th>
                    <th><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></th>
                </tr>`
            document.getElementById("tbody").innerHTML = `
                    <tr class="placeholder-glow placeholder-wave">
                        <th class="text-center align-middle"><div class="d-flex flex-column gap-1">Total</th>
                        <th><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></th>
                        <th><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></th>
                        <th><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></th>
                        <th><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></th>
                        <th><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></th>
                        <th><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></th>
                    </tr>
                    <tr class="placeholder-glow placeholder-wave">
                        <td class="text-center align-middle"><div class="d-flex flex-column gap-1">1st</td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                    </tr>
                    <tr class="placeholder-glow placeholder-wave">
                        <td class="text-center align-middle"><div class="d-flex flex-column gap-1">2nd</td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                    </tr>
                    <tr class="placeholder-glow placeholder-wave">
                        <td class="text-center align-middle"><div class="d-flex flex-column gap-1">3th</td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                    </tr>
                    <tr class="placeholder-glow placeholder-wave">
                        <td class="text-center align-middle"><div class="d-flex flex-column gap-1">4th</td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                    </tr>
                    <tr class="placeholder-glow placeholder-wave">
                        <td class="text-center align-middle"><div class="d-flex flex-column gap-1">5th</td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                    </tr>
                    <tr class="placeholder-glow placeholder-wave">
                        <td class="text-center align-middle"><div class="d-flex flex-column gap-1">6th</td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                    </tr>
                    <tr class="placeholder-glow placeholder-wave">
                        <td class="text-center align-middle"><div class="d-flex flex-column gap-1">7th</td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                    </tr>
                    <tr class="placeholder-glow placeholder-wave">
                        <td class="text-center align-middle"><div class="d-flex flex-column gap-1">8th</td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                    </tr>
                    <tr class="placeholder-glow placeholder-wave">
                        <td class="text-center align-middle"><div class="d-flex flex-column gap-1">9th</td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                    </tr>
                    <tr class="placeholder-glow placeholder-wave">
                        <td class="text-center align-middle"><div class="d-flex flex-column gap-1">10th</td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                    </tr>
                    <tr class="placeholder-glow placeholder-wave">
                        <td class="text-center align-middle"><div class="d-flex flex-column gap-1">11th</td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                    </tr>
                    <tr class="placeholder-glow placeholder-wave">
                        <td class="text-center align-middle"><div class="d-flex flex-column gap-1">12th</td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                    </tr>
                    <tr class="placeholder-glow placeholder-wave">
                        <td class="text-center align-middle"><div class="d-flex flex-column gap-1">13th</td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                    </tr>
                    <tr class="placeholder-glow placeholder-wave">
                        <td class="text-center align-middle"><div class="d-flex flex-column gap-1">14th</td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                    </tr>
                    <tr class="placeholder-glow placeholder-wave">
                        <td class="text-center align-middle"><div class="d-flex flex-column gap-1">15th</td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                    </tr>
                    <tr class="placeholder-glow placeholder-wave">
                        <td class="text-center align-middle"><div class="d-flex flex-column gap-1">16th</td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                    </tr>
                    <tr class="placeholder-glow placeholder-wave">
                        <td class="text-center align-middle"><div class="d-flex flex-column gap-1">17th</td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                    </tr>
                    <tr class="placeholder-glow placeholder-wave">
                        <td class="text-center align-middle"><div class="d-flex flex-column gap-1">18th</td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                    </tr>
                    <tr class="placeholder-glow placeholder-wave">
                        <td class="text-center align-middle"><div class="d-flex flex-column gap-1">19th</td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                    </tr>
                    <tr class="placeholder-glow placeholder-wave">
                        <td class="text-center align-middle"><div class="d-flex flex-column gap-1">20th</td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                    </tr>
                    <tr class="placeholder-glow placeholder-wave">
                        <td class="text-center align-middle"><div class="d-flex flex-column gap-1">21st</td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                    </tr>
                    <tr class="placeholder-glow placeholder-wave">
                        <td class="text-center align-middle"><div class="d-flex flex-column gap-1">22nd</td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                    </tr>
                    <tr class="placeholder-glow placeholder-wave">
                        <td class="text-center align-middle"><div class="d-flex flex-column gap-1">23rd</td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                    </tr>
                    <tr class="placeholder-glow placeholder-wave">
                        <td class="text-center align-middle"><div class="d-flex flex-column gap-1">24th</td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                    </tr>
                    <tr class="placeholder-glow placeholder-wave">
                        <td class="text-center align-middle"><div class="d-flex flex-column gap-1">25th</td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                    </tr>
                    <tr class="placeholder-glow placeholder-wave">
                        <td class="text-center align-middle"><div class="d-flex flex-column gap-1">26th</td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                    </tr>
                    <tr class="placeholder-glow placeholder-wave">
                        <td class="text-center align-middle"><div class="d-flex flex-column gap-1">27th</td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                    </tr>
                    <tr class="placeholder-glow placeholder-wave">
                        <td class="text-center align-middle"><div class="d-flex flex-column gap-1">28th</td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                    </tr>
                    <tr class="placeholder-glow placeholder-wave">
                        <td class="text-center align-middle"><div class="d-flex flex-column gap-1">29th</td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                    </tr>
                    <tr class="placeholder-glow placeholder-wave">
                        <td class="text-center align-middle"><div class="d-flex flex-column gap-1">30th</td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                        <td><span class="placeholder w-100" style="line-height: normal;">Loading . . .</span></td>
                    </tr>`;
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
                    let insertHTML_thead = "";
                    let insertHTML_tbody = "";                    
                    let teachers = Object.keys(data['body']['counts']);
                    // let yoil_ko = ["일", "월", "화", "수", "목", "금", "토"];
                    let yoil_en = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
                    let date_en = ["0th", "1st","2nd","3th","4th","5th","6th","7th","8th","9th","10th","11th","12th","13th","14th","15th","16th","17th","18th","19th","20th","21st","22nd","23rd","24th","25th","26th","27th","28th","29th","30th","31st","32nd"];

                    for(let i = 0; i<=new Date(document.getElementById("year").value, document.getElementById("month").value, 0).getDate(); i++) {
                        insertHTML_tbody += "<tr>";
                        
                        if(i == 0) insertHTML_tbody += `<th>Total</th>`;
                        else insertHTML_tbody += `<td>${date_en[i]} (${yoil_en[new Date(document.getElementById("year").value, document.getElementById("month").value-1, i).getDay()]})</td>`;
                        // else insertHTML_tbody += `<td>${String(i).padStart(2, '0')}일 (${yoil_en[new Date(document.getElementById("year").value, document.getElementById("month").value-1, i).getDay()]})</td>`;

                        for(let j = 0; j<teachers.length; j++) {
                            if( i ==0 ) {
                                insertHTML_thead += `<th>${data['body']['counts'][teachers[j]]["name"]}</th>`;
                                insertHTML_tbody += `<td>${data['body']['counts'][teachers[j]]["total"]}</td>`;
                            } else insertHTML_tbody += `<td>${data['body']['counts'][teachers[j]][i]}</td>`;
                        }

                        insertHTML_tbody += "</tr>";
                    }

                    document.getElementById("thead").innerHTML = `<tr style="width: 64px;"><th></th>${insertHTML_thead}</tr>`;
                    document.getElementById("tbody").innerHTML = insertHTML_tbody;
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