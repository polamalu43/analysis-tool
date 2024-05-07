<?php
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;

require '../vendor/autoload.php';
require_once './utils.php';
require_once './const.php';

$dotenv = Dotenv::createImmutable(__DIR__. '/../');
$dotenv->load();

setCrossOriginHeaders();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  printLog($MESSAGE['invalidRequest']);
}

try {
  // $dbh = dbConnect('localhost', 'analysis-tools', 'root');

  $uploadDir = './files/';
  fileUpload($uploadDir, $_FILES['files']);

  $parseLogFiles = parseLogFiles($uploadDir);


} catch (PDOException $e) {
  header('Content-Type: text/plain; charset=UTF-8', true, 500);
  printLog($e->getMessage());
}

// $statement = null;
// $dbh = null;
?>
