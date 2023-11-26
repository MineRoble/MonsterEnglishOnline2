<?php include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_config.php"; ?>
<!DOCTYPE html>
<html lang="<?php
    if( isset($GLOBALS["page-language"]) ) echo $GLOBALS["page-language"];
    else echo "ko";
?>">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php if(isset($GLOBALS["page-title"])) echo $GLOBALS["page-title"]." - "; ?>Monster English Online</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        .scoll-without-visual {
            -ms-overflow-style: none; /* 인터넷 익스플로러 */
            scrollbar-width: none; /* 파이어폭스 */
        }

        .scoll-without-visual::-webkit-scrollbar {
            display: none; /* 크롬, 사파리, 오페라, 엣지 */
        }

        .table-fixed {
            table-layout: fixed;
        }
    </style>

    <link rel="apple-touch-icon" sizes="180x180" href="/assets/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="194x194" href="/assets/favicon/favicon-194x194.png">
    <link rel="icon" type="image/png" sizes="192x192" href="/assets/favicon/android-chrome-192x192.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/assets/favicon/favicon-16x16.png">
    <link rel="manifest" href="/assets/favicon/site.webmanifest">
    <link rel="mask-icon" href="/assets/favicon/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
    
    <?php
        if($_SERVER['SCRIPT_FILENAME'] != $_SERVER["DOCUMENT_ROOT"]."/error/noscript.php") {
            echo '<noscript><meta http-equiv="refresh" content="0;url=/error/noscript.php?from='.urlencode($_SERVER['REQUEST_URI']).'"></noscript>';
        }
    ?>
</head>
<body>
    <nav class="navbar navbar-expand-md bg-body-tertiary bg-dark user-select-none" data-bs-theme="dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">Monster English Online<?php
                if(_server_type == "dev") echo ' <span class="badge bg-danger" title="운영 서버와 독립된 서버로 실결제가 이루어지지 않습니다.">Dev</span>';
            ?></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <?php if($_SESSION['login']) { ?>
                        <!-- <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="#">Home</a>
                        </li> -->
                        <li class="nav-item">
                            <a class="nav-link" href="/reservation/">Class Reservation</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/homeworks/">Homeworks</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <?php echo $_SESSION['user']['name'] ?> 님
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="/auth/mypage/">Mypage</a></li>
                                <?php if($_SESSION['user']['permission'] >= 2) { ?>
                                    <li><a class="dropdown-item" href="/schedule-manager/">Schedule Manager</a></li>
                                <?php } ?>
                                <?php if($_SESSION['user']['permission'] >= 3) { ?>
                                    <li><a class="dropdown-item" href="/dashboard">Dashboard</a></li>
                                <?php } ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="/auth/signout/">Sign Out</a></li>
                            </ul>
                        </li>
                    <?php } else { ?>
                        <!-- <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="#">Home</a>
                        </li> -->
                        <li class="nav-item">
                            <a class="nav-link" href="/auth/signin/">Sign In</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/auth/signup/">Sign Up</a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </nav>