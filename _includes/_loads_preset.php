<?php
include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_config.php";

function checkSortKey($sortKeys) {
    global $_POST;

    if( !is_array($sortKeys) ) {
        $response = array(
            "status" => "error",
            "message" => "정렬 키 설정이 올바르지 않습니다."
        );
    } else {
        if( isset($_POST['sortKey']) && isset($_POST['sortOrder']) ) {
            $sortKey = $_POST['sortKey'];
            $sortOrder = $_POST['sortOrder'];
        
            if( array_key_exists($sortKey, $sortKeys) ) $sortKey = $sortKeys[$sortKey];
            else {
                $response = array(
                    "status" => "error",
                    "message" => "필수 파라미터가 누락되었습니다."
                );
                return $response;
            }
        
            if($sortOrder == "asc") $sortOrder = "ASC";
            elseif($sortOrder == "desc") $sortOrder = "DESC";
            else {
                $response = array(
                    "status" => "error",
                    "message" => "필수 파라미터가 누락되었습니다."
                );
                return $response;
            }

            $response = array(
                "status" => "success",
                "sortKey" => $sortKey,
                "sortOrder" => $sortOrder
            );
        } else {
            $response = array(
                "status" => "error",
                "message" => "필수 파라미터가 누락되었습니다."
            );
        }
    }

    return $response;
}

function checkPagenation() {
    global $_POST;

    if( isset($_POST['itemPerPage']) && isset($_POST['page']) && is_numeric($_POST['itemPerPage']) && is_numeric($_POST['page']) ) {      
        $response = array(
            "status" => "success",
            "itemPerPage" => intval($_POST['itemPerPage']),
            "page" => intval($_POST['page']) //currentPage
        );
    } else {
        $response = array(
            "status" => "error",
            "message" => "필수 파라미터가 누락되었습니다."
        );
    }

    return $response;
}