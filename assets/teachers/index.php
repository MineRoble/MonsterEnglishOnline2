<?
    include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_config.php";
    
    if( isset($_GET["idx"]) ) {
        try {
            $pdo = new PDO(
                "mysql:host="._db_address.";dbname="._db_name.";charset=utf8",
                _db_id,_db_pwd,
                array(
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_EMULATE_PREPARES => false
                )
            );
    
            $stmt = $pdo->prepare("SELECT img FROM `teachers` WHERE idx = :idx");
            $stmt->bindValue(':idx', $_GET["idx"]);
            $stmt->execute();
            $img = $stmt->fetchColumn();
    
            if($img !== false && is_file($img)) {
                $fp = fopen($img, 'rb');
                $output = fread($fp, filesize($img));
                fclose($fp);
            }
            
        } catch (PDOException $e) {
            echo "데이터베이스 오류가 발생하였습니다.";
        }
    }
    
    // if( !isset($output) || $output == "" || $output == null ) echo "<noscript>해당 파일이 없습니다.</noscript><script>alert('해당 파일이 없습니다.');window.close();</script>";
    // if( !isset($output) || $output == "" || $output == null ) header("Location: https://icons.getbootstrap.com/assets/icons/person.svg");
    if( !isset($output) || $output == "" || $output == null ) {
        header('Content-type: image/svg+xml');
        echo '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="gray" class="bi bi-person" viewBox="0 0 16 16"><path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0Zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4Zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10Z"/></svg>';
    }
    else echo $output;
?>