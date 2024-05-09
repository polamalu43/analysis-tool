<?php
use Carbon\Carbon;

// envの設定を取得
function env(string $string): string
{
  return $_ENV[$string];
}

//値が入力されているかチェック
function isRequireError(array $datas): bool
{
  foreach ($datas as $data) {
    if (empty($data)) {
        return true;
    }
  }

  return false;
}

//デバッグ用関数
function printLog($value)
{
  $jsonData = json_encode($value, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

  return error_log($jsonData, 3, env('LOG_PATH'));
}

//出力確認用の関数
function printPre($value): void
{
  echo '<pre>';
  print_r($value);
  echo '</pre>';
}

//データベースへ接続
function dbConnect(
  string $host,
  string $dbName,
  string $userName='',
  string $password=''
): PDO
{
  $dsn = 'mysql:host='. $host. '; dbname='. $dbName. '; charset=utf8';
  $dbh = new PDO($dsn, $userName, $password);
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  return $dbh;
}
