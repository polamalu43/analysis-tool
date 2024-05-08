<?php
use Goutte\Client;
use Symfony\Component\HttpClient\HttpClient;
require '../vendor/autoload.php';
require_once './utils.php';
require_once './const.php';

setCrossOriginHeaders();

// $sld = "hogehoge";
// $tld = "com";
// $pass = "hogehogepassword";
// $domain = "www.hogehoge.com";
// $analyzeurlGet = "https://user.lolipop.jp/?mode=analyze&exec=setting&id=LU16676381";
// $analyzeurlPost = "https://user.lolipop.jp/?mode=analyze&exec=download";

// $login = 'https://user.lolipop.jp/?mode=login&exec=1';

// $client = new Client(HttpClient::create(['timeout' => 60]));

// // Login form
// $form['domain_plan'] = 0;
// $form['account'] = 'yu-tachibana';
// $form['domain_id'] = 440;
// $form['passwd'] = 'Shoyutomo14*';
// $form['chkSetCookie'] = 1;
// $client->request('POST', 'https://user.lolipop.jp/', $form);

// print_r($client->getResponse()->getStatusCode());
// print_r($client->getResponse()->getHeaders());
// $form = $crawler->selectButton('ログイン')->form();
// $form['account'] = 'https://yu-tachibana.main.jp';
// $form['domain_plan'] = 0;
// $form['account'] = 'yu-tachibana';
// $form['domain_id'] = 440;
// $form['passwd'] = 'Shoyutomo14*';
// $form['chkSetCookie'] = 1;
// $crawler = $client->submit($form);
// $crawler->filter('.flash-error')->each(function ($node) {
//   print $node->text()."\n";
// });

// Domain login form
// $form = $crawler->selectButton('ログイン')->form();
// $form['domain_name_2'] = $sld;
// $form['domain_name_3'] = $tld;
// $form['passwd'] = $pass;
// $crawler = $client->submit($form);

// Log download form
$date = date('ymd', strtotime('-2 days'));
$form2['sltDate'] = $date;
$crawler = $client->request('POST', $analyzeurlGet, $form2);
print_r($crawler);
$form = $crawler->selectButton('ダウンロード')->form();
$date = date('ymd', strtotime('-2 days'));
$form['sltDate'] = $date;
$client->submit($form);
?>
