<?php
$dsn      = 'mysql:dbname=sampledb;host=myapp-db';
$user     = 'sampleuser';
$password = 'samplepasswd';

// DBへ接続
try{
    $dbh = new PDO($dsn, $user, $password);

    // クエリの実行
    $query = "SELECT * FROM DEPT";
    $stmt = $dbh->query($query);

    // 表示処理
    $cnt = 0;
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
      $cnt++;
      foreach(array_keys($row) as $key) {
        echo 'row' . $cnt .':' . $key . ': ' . $row[$key] . "<br/>";
      }
    }

}catch(PDOException $e){
    print("データベースの接続に失敗しました".$e->getMessage());
    die();
}

// 接続を閉じる
$dbh = null;
?>