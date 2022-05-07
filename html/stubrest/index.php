<?php
// これはsampleなので、用途に合わせて書き換えること。
// このままでも、受け取ったリクエストを出力して確認するのには使える。

// php.iniで設定する項目
// php.iniファイルをマウントする手はあるが、実行時に上書きで済むならこれでも
// と思ったが、php.iniをイメージ作るときに書き換えたので以下不要
// ini_set('log_errors', 'On');
// ini_set('date.timezone', 'Asia/Tokyo');
// ini_set('error_log', '/dev/stderr');

// ログを標準エラー出力する。
// コンテナの標準出力・標準エラー出力はdocker logs コンテナIDで見るか、以下のファイルを見る
// /var/lib/docker/containers/＜コンテナID＞/＜コンテナID＞-json.log
// apacheのアクセスログ(stdout)・エラーログ(stderr)も同じように見る
// 確認用のログ
error_log('stubrest: start');
foreach(array_keys($_GET) as $key) {
  error_log('GET: ' . $key . ': ' . $_GET[$key]);
}
foreach(array_keys($_POST) as $key) {
  error_log('POST: ' . $key . ': ' . $_POST[$key]);
}
$serverEnvKeys = ['REQUEST_URI','REQUEST_METHOD','HTTP_USER_AGENT','REMOTE_ADDR','HTTP_COOKIE','QUERY_STRING'];
foreach($serverEnvKeys as $key) {
  if (isset($_SERVER[$key])) {
    error_log('Srv: ' . $key . ': ' . $_SERVER[$key]);
  }
}
$header = getallheaders();
foreach(array_keys($header) as $key) {
  error_log('HEAD: ' . $key . ': ' . $header[$key]);
}
// パスパラメータを扱うときは$_SERVER['REQUEST_URI']から取得する

$contents = NULL;
// json形式の入力は$ret = json_decode($json)でパースする
if (isset($header['Content-Type']) && substr($header['Content-Type'], 0, 16) == 'application/json') {
  $json = file_get_contents("php://input"); // POSTでのデータ入力を受け取る
  error_log('JSON_STRING: ' . $json);
  $contents = json_decode($json, true);
  error_log('JSON_REQ: ' . var_export($contents, true));
}
if (!is_array($contents)) {
  $contents = array();
}
// 呼び出し側の例:
// curl -X POST -H "Content-Type: application/json" -d '{"id":"1001", "name":"abc01"}' "http://localhost:8080/stubrest/a14"


header('Content-Type: application/json'); // 他の出力より前に必要
if (isset($contents['id']) && substr($contents['id'], 0, 1) == '9') {
  // 9で始まるidを指定されたら、404 NotFound応答を返す
  header("HTTP/1.1 404 Not Found") ;
  $message['errcode'] = '1234';
  $message['msg'] = 'id must not 9.';
  print(json_encode($message));
} else {
  $contents['retid'] = '1001';
  $contents['age'] = 18;
  $contents['location'] = 'Tokyo';
  // 2021-01-02T03:04:05+09:00 形式
  $contents['reg_dttm'] = date(DATE_RFC3339);
  // 2021-01-02 03:04:05.006 形式
  $contents['upd_dttm'] = date("Y-m-d H:i:s") . "." . substr(explode(".", (microtime(true) . ""))[1], 0, 3);
  print(json_encode($contents));
}
// 応答をjson形式で出力するときはjson_encode($value)をprintする
?>