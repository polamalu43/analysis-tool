<?php
use Dotenv\Dotenv;

require '../vendor/autoload.php';
require_once './utils.php';
require_once './const.php';
require_once './repository.php';

$dotenv = Dotenv::createImmutable(__DIR__. '/../');
$dotenv->load();

setCrossOriginHeaders();
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
  printLog($MESSAGE['invalidRequest']);
  exit;
}

try {
  $dbh = dbConnect('localhost', 'analysis-tool', 'root');
  $logs = [];
  $logs['today'] = getTodayLogs($dbh);
  $logs['groupThisMonths'] = getGroupThisMonthsLogs($dbh);

  echo json_encode($logs);
} catch (PDOException $e) {
  header('Content-Type: text/plain; charset=UTF-8', true, 500);
  printLog($e->getMessage());
}

//クロスドメイン対策用関数
function setCrossOriginHeaders(): void
{
  header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Methods: GET");
  header("Access-Control-Allow-Headers: Content-Type");
  header('Content-Type: application/json');
}
?>
