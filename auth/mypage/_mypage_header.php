<?php
    include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_config.php";

    $tabs = array(
        array(
            "name" => "account",
            "display-name" => "계정 정보",
            "href" => "/auth/mypage/account/",
            "disabled" => false
        ),
        array(
            "name" => "billing-records",
            "display-name" => "결제 기록",
            "href" => "/auth/mypage/billing-records/",
            "disabled" => false
        ),
        array(
            "name" => "my-reservation",
            "display-name" => "수업 목록",
            "href" => "/auth/mypage/my-reservation/",
            "disabled" => false
        )
    );
?>
<div class="container d-flex flex-column justify-content-center gap-3 my-5">
    <div class="overflow-x-scroll scoll-without-visual">
        <ul class="nav nav-tabs w-100 flex-nowrap text-nowrap">
            <?php
                foreach($tabs as $tab) {
                    if(isset($tab["subtabs"])) {
                        echo '<li class="nav-item dropdown"><a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="'.$tab["href"].'" role="button" aria-expanded="false">'.$tab["display-name"].'</a><ul class="dropdown-menu">';

                        foreach($tab["subtabs"] as $subtab) {
                            if($subtab === "div") echo '<li><hr class="dropdown-divider"></li>';
                            else echo '<li><a class="dropdown-item" href="'.$subtab["href"].'">'.$subtab["display-name"].'</a></li>';
                        }

                        echo '</ul></li>';
                    } else {
                        echo '<li class="nav-item"><a class="nav-link';

                        if($_SERVER["DOCUMENT_ROOT"].$tab["href"]."index.php" == $_SERVER['SCRIPT_FILENAME']) echo ' active';
                        elseif($_SERVER["DOCUMENT_ROOT"].$tab["href"] == $_SERVER['SCRIPT_FILENAME']) echo ' active';

                        if($tab["disabled"]) echo ' disabled';

                        echo '" aria-current="page" href="'.$tab["href"].'">'.$tab["display-name"].'</a></li>';
                    }
                }
            ?>
        </ul>
    </div>