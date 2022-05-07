# lamp

LAMP環境をdocker-composeで構築するサンプル。

環境: PHP8.0+apache+MySQL8.0+phpMyAdmin5.0

参考URL:

https://tt-computing.com/docker-php8_0-lamp

上記を元に、MySQL永続化、言語・タイムゾーン設定をプラスしています。

## 使い方

起動
```
docker-compose up -d
```

終了
```
docker-compose down
```

## PHP

php.iniはDockerfileでdate.timezoneとerror_logを変更しています。

PHPはAPIのスタブとして動作するようにしています。

### トップページ
http://localhost:8080/index.php
phpinfoのみ

### DBアクセスのサンプル
http://localhost:8080/dbtest.php
DEPT表を読み込んで出力するだけ。

### stubディレクトリ
http://localhost:8080/stub/以下

リクエストを受信して、内容を出力。

.htaccessファイルで、/stub以下のアクセスはすべて/stub/index.phpが処理するように指定しています。
（パスパラメータを受け取るAPIを想定）

### stubrestディレクトリ
http://localhost:8080/stubrest/以下

リクエストを受信して、内容をログ出力してサンプル応答を返す。

.htaccessファイルで、/stubrest以下のアクセスはすべて/stubrest/index.phpが処理するように指定しています。
（パスパラメータを受け取るAPIを想定）

application/jsonでJSONデータをPOSTしてjsonを返すAPIのスタブを作るためのサンプルを想定しています。

