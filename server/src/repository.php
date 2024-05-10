<?php
use Carbon\Carbon;

//logsテーブルにデータを挿入
function insertLogs(PDO $dbh, array $datas): void
{
  $sql = "INSERT INTO logs (address, date, request_method, request_resource, request_protocol, request_status, request_size, target_page, user_agent) VALUES ";
  $values = [];
  for ($i = 0; $i < count($datas); $i++) {
    $values[$i] = "(:address$i, :date$i, :request_method$i, :request_resource$i, :request_protocol$i, :request_status$i, :request_size$i, :target_page$i, :user_agent$i)";
  }
  $sql .= implode(", ", $values);
  $stmt = $dbh->prepare($sql);

  foreach ($datas as $idx => $data) {
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

//logsテーブルから月で集計したデータを取得
function getLogsGroupMonths(PDO $dbh): array
{
  $sql = 'SELECT DATE_FORMAT(date, \'%Y-%m\') as month, COUNT(DATE_FORMAT(date, \'%Y-%m\')) as count '
    .'FROM logs '
    .'WHERE date >= DATE_SUB(CURRENT_DATE(), INTERVAL 12 MONTH) '
    .'GROUP BY month '
    .'ORDER BY month';
  $stmt = $dbh->prepare($sql);
  $stmt->execute();
  $groupMonths = $stmt->fetchAll(PDO::FETCH_ASSOC);
  $latestDate = $groupMonths[count($groupMonths)-1]['month'];

  $formatedGroupMonths = [];
  for ($i=0; $i < 12; $i++) {
    $dt = Carbon::parse($latestDate);
    $month = $dt->subMonths($i)->format('Y年m月');
    $monthkey = array_search(
      $dt->format('Y-m'),
      array_column($groupMonths, 'month')
    );

    $formatedGroupMonths[] = [
      'month' => $month,
      'count' => $monthkey !== false ? $groupMonths[$monthkey]['count'] : null,
    ];
  }

  return $formatedGroupMonths;
}
?>
