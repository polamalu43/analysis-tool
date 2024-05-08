<?php
use Dotenv\Dotenv;

require '../vendor/autoload.php';
require_once './utils.php';
require_once './const.php';

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
?>
