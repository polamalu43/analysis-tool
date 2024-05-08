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

//クロスドメイン対策用関数
function setCrossOriginHeaders(): void
{
  header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Methods: POST");
  header("Access-Control-Allow-Headers: Content-Type");
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

//ファイルのアップロード
function fileUpload(string $uploadDir, array $files)
{
  foreach ($files['name'] as $idx => $name) {
    $uploadedFile = $uploadDir . basename($name);

    if (move_uploaded_file($files['tmp_name'][$idx], $uploadedFile)) {
        printLog('ファイルのアップロードに成功しました'. $name);
    } else {
        printLog('ファイルのアップロードに失敗しました');
    }
  }
}

//ファイルから必要な情報を抽出
function parseLogFiles(string $uploadDir): array
{
  $files = getFiles($uploadDir);
  $results = [];
  foreach ($files as $file) {
    if (pathinfo($file, PATHINFO_EXTENSION) !== 'log') {
      printLog('拡張子が「log」でないファイルです。');
      continue;
    }

    $contents = getLogContents($uploadDir. $file);
    $results = [...$results, ...formatLogContents($contents)];
  }

  return $results;
}

//フォルダー内のファイルの一覧を取得
function getFiles(string $uploadDir)
{
  return array_values(array_diff(scandir($uploadDir), array('.', '..') ));
}

//ログファイルの中身を配列に変換して出力
function getLogContents(string $dir): array
{
  $contents = file_get_contents($dir);
  return explode("\n", $contents);
}

//ログファイルの中身を整形して出力
function formatLogContents(array $contents): array
{
  if (empty($contents)) {
    return [];
  }

  $results = [];
  foreach ($contents as $key => $line) {
    $parts = explode(' "', $line);

    if (empty($parts[0])) {
      continue;
    }

    $address = getAddressLog($parts[0] ?? '');
    $date = getDateLog($parts[0] ?? '');
    $requestLines = getRequestLines(str_replace('"', '', $parts[1] ?? ''));
    $requestMethod = $requestLines[0] ?? '';
    $requestResource = $requestLines[1] === '/' || is_null($requestLines[1])
      ? ''
      : $requestLines[1];
    $requestProtocol = $requestLines[2] ?? '';
    $requestStatus = $requestLines[3] ?? '';
    $requestSize = $requestLines[4] ?? '';
    $targetPage = str_replace('"', '', $parts[2] ?? '');
    $userAgent = $parts[3] ?? '';

    $results[] = [
        'address' => $address,
        'date' => $date,
        'request_method' => $requestMethod,
        'request_resource' => $requestResource,
        'request_protocol' => $requestProtocol,
        'request_status' => $requestStatus,
        'request_size' => $requestSize,
        'target_page' => $targetPage,
        'user_agent' => $userAgent,
    ];
  }

  return $results;
}

//addressを取得
function getAddressLog(string $str): string
{
  return strtok($str, ' ');
}

//dateを取得
function getDateLog(string $str): string
{
  $startPos = strpos($str, '[');
  $endPos = strpos($str, ']');

  if ($startPos === false || $endPos === false) {
    return '';
  }
  $date = substr($str, $startPos + 1, $endPos - $startPos - 1);
  $carbonDate = Carbon::createFromFormat('d/M/Y:H:i:s O', $date);
  $formattedDate = $carbonDate->format('Y-m-d H:i:s');

  return $formattedDate;
}

//リクエストラインを配列で取得
function getRequestLines(string $str): array
{
  return explode(' ', $str);
}

//logsテーブルにデータを挿入
function insertLogs(PDO $dbh, array $data): void
{
  $sql = "INSERT INTO logs (address, date, request_method, request_resource, request_protocol, request_status, request_size, target_page, user_agent) VALUES ";
  $values = [];
  for ($i = 0; $i < count($data); $i++) {
    $values[$i] = "(:address$i, :date$i, :request_method$i, :request_resource$i, :request_protocol$i, :request_status$i, :request_size$i, :target_page$i, :user_agent$i)";
  }
  $sql .= implode(", ", $values);

  $stmt = $dbh->prepare($sql);
  foreach ($data as $idx => $data) {
    $stmt->bindValue(":address$idx", $data['address'], PDO::PARAM_STR);
    $stmt->bindValue(":date$idx", $data['date'], PDO::PARAM_STR);
    $stmt->bindValue(":request_method$idx", $data['request_method'], PDO::PARAM_STR);

    $stmt->bindValue(":request_resource$idx", $data['request_resource'], PDO::PARAM_STR);
    $stmt->bindValue(":request_protocol$idx", $data['request_protocol'], PDO::PARAM_STR);
    $stmt->bindValue(":request_status$idx", $data['request_status'], PDO::PARAM_INT);
    $stmt->bindValue(":request_size$idx", $data['request_size'], PDO::PARAM_INT);
    $stmt->bindValue(":target_page$idx", $data['target_page'], PDO::PARAM_STR);
    $stmt->bindValue(":user_agent$idx", $data['user_agent'], PDO::PARAM_STR);
  }
  $stmt->execute();

  $stmt = null;
  $dbh = null;
}
