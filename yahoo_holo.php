<?php

/**
 * yahoo!12星座占いをスクレイピングしてメールで送るAPI
 * author irimo
 * created on 20141207
 * 免責: 自分用スクリプトなので、エラー処理がゆるいです(特にGETまわり)
 */

require './simple_html_dom.php';
header('Content-Type: text/html; charset=utf-8');

// 送りたいメールアドレスを書く(私はケータイのメアドを書いています)
$mail_address = 'mail@example.com';
// あなたの星座。公式の URL からよしなに引っ張ってきて
$holo = 'scorpio';


$url = 'http://fortune.yahoo.co.jp/12astro/'.$holo;
$day = $_GET['day'];
if ($day && strlen($day)===8 && is_numeric($day)){
  $url = 'http://fortune.yahoo.co.jp/12astro/'.$day.'/'.$holo.'.html';
}

$html = file_get_html($url);

// 基本情報(メールタイトル)
foreach($html->find('div.yftn-md20 div > div > div > p') as $element) {
  $status = $element->plaintext;
}

// 順位(メールタイトル)
foreach($html->find('div.yftn-md20 table tbody tr td strong') as $element) {
  $rank = $element->plaintext;
}

// 総合運
foreach($html->find('div[id=lnk01]') as $element) {
  $total_text = $element->plaintext;
}

// 恋愛運
foreach($html->find('div[id=lnk02] p') as $element) {
  $love_text = $element->plaintext;
}

// 金運
foreach($html->find('div[id=lnk03] p') as $element) {
  $money_text = $element->plaintext;
}

// 仕事運
foreach($html->find('div[id=lnk04] p') as $element) {
  $work_text = $element->plaintext;
}

// ラッキーシンボル
foreach($html->find('div.yftn12a-md24') as $element) {
  $simbol_text = $element->plaintext;
}
$html->clear();

// タイトルを整形
$subject = $status.' '.$rank;

// 本文を整形
$text = '総合運: '.$total_text."\r\n".'恋愛運: '.$love_text."\r\n".'金運: '.$money_text."\r\n";
$text .= '仕事運: '.$work_text."\r\n".'ラッキーシンボル'.$simbol_text;

// メールを送る
if(mail($mail_address, $subject, $text)) {
  print "メール送信成功:D";
} else {
  print "メール送信失敗X(";
}

