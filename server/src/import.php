<?php
use Dotenv\Dotenv;
use Carbon\Carbon;

require '../vendor/autoload.php';
require_once './utils.php';
require_once './const.php';
require_once './repository.php';

$dotenv = Dotenv::createImmutable(__DIR__. '/../');
$dotenv->load();

setCrossOriginHeaders();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  printLog($MESSAGE['invalidRequest']);
  exit;
}
if (!isset($_FILES['files'])) {
  printLog($MESSAGE['fileDoesNotExist']);
  exit;
}

try {
  $dbh = dbConnect('localhost', 'analysis-tool', 'root');
  $uploadDir = './files/';
  fileUpload($uploadDir, $_FILES['files']);
  $parseLogFiles = parseLogFiles($uploadDir);

  insertLogs($dbh, $parseLogFiles);
} catch (PDOException $e) {
  header('Content-Type: text/plain; charset=UTF-8', true, 500);
  printLog($e->getMessage());
}

//クロスドメイン対策用関数
function setCrossOriginHeaders(): void
{
  header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Methods: POST");
  header("Access-Control-Allow-Headers: Content-Type");
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
    $targetPage = $targetPage === '-' ? '' : $targetPage;
    $userAgent = str_replace('"', '', $parts[3] ?? '');
    $userAgent = $userAgent === '-' ? '' : $userAgent;

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
?>
