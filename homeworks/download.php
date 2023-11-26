<?
    include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_config.php";
    
    try {
        $pdo = new PDO(
            "mysql:host="._db_address.";dbname="._db_name.";charset=utf8",
            _db_id,_db_pwd,
            array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false
            )
        );

        $stmt = $pdo->prepare("SELECT article, location, name FROM homeworks_files WHERE idx = :idx");
        $stmt->bindValue(':idx', $_GET["idx"]);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if($result !== false) {
            $stmt = $pdo->prepare("SELECT COUNT(*) as cnt FROM homeworks_article WHERE idx = :idx AND is_del = 0");
            $stmt->bindValue(':idx', $result["article"]);
            $stmt->execute();
            $articleCnt = $stmt->fetchColumn();
    
            if( $articleCnt > 0 && is_file($result['location']) ) {
                header("Content-type: application/octet-stream"); 
                header("Content-Length: ".filesize($result['location']));
                header('Content-Disposition: attachment; filename="'.$result['name'].'"'); // 다운로드되는 파일명 (실제 파일명과 별개로 지정 가능)
                header("Content-Transfer-Encoding: binary"); 
                header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
                header("Pragma: public"); 
                header("Expires: 0");
                $fp = fopen($result['location'], 'rb');
                $output = fread($fp, filesize($result['location']));
                fclose($fp);
            }
        }

        if( !isset($output) || $output == "" || $output == null ) echo "<noscript>해당 파일이 없습니다.</noscript><script>alert('해당 파일이 없습니다.');window.close();</script>";
        else echo $output;
        
    } catch (PDOException $e) {
        echo "데이터베이스 오류가 발생하였습니다.";
    }
?>