<?php
// これはsampleなので、用途に合わせて書き換えること。
// このままでも、受け取ったリクエストを出力して確認するのには使える。

// ログを標準エラー出力する。
// コンテナの標準出力・標準エラー出力はdocker logs コンテナIDで見るか、以下のファイルを見る
// /var/lib/docker/containers/＜コンテナID＞/＜コンテナID＞-json.log
// apacheのアクセスログ(stdout)・エラーログ(stderr)も同じように見る
ini_set('log_errors', 'On');
ini_set('error_log', '/dev/stderr');
// 確認用のログ
error_log('test_of_log');
foreach(array_keys($_GET) as $key) {
  echo 'GET: ' . $key . ': ' . $_GET[$key] . "<br/>\n";
}
foreach(array_keys($_POST) as $key) {
  echo 'POST: ' . $key . ': ' . $_POST[$key] . "<br/>\n";
}
$serverEnvKeys = ['REQUEST_URI','REQUEST_METHOD','HTTP_USER_AGENT','REMOTE_ADDR','HTTP_COOKIE','QUERY_STRING'];
foreach($serverEnvKeys as $key) {
  if (isset($_SERVER[$key])) {
    echo 'Srv: ' . $key . ': ' . $_SERVER[$key] . "<br/>\n";
  }
}
$header = getallheaders();
foreach(array_keys($header) as $key) {
  echo 'HEAD: ' . $key . ': ' . $header[$key] . "<br/>\n";
}
// パスパラメータを扱うときは$_SERVER['REQUEST_URI']から取得する

// json形式の入力は$ret = json_decode($json)でパースする
if (isset($header['Content-Type']) && substr($header['Content-Type'], 0, 16) == 'application/json') {
  $json = file_get_contents("php://input"); // POSTでのデータ入力を受け取る
  $contents = json_decode($json, true);
  var_dump($contents);
  error_log('JSON_REQ: ' . var_export($contents, true));
}
// 呼び出し側の例:
// curl -X POST -H "Content-Type: application/json" -d '{"id":"1001", "name":"abc01"}' "http://localhost:8080/stub/a14"

// 応答をjson形式で出力するときはjson_encode($value)をprintする
if ($header['Accept'] == 'application/json') {
  $out['id'] = '1001';
  $out['age'] = 18;
  $out['location'] = 'Tokyo';
  header('Content-Type: application/json'); // ほんとは他の出力より前に必要
  print(json_encode($out));
}
?>