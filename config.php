<?php
// タイムゾーンの設定
date_default_timezone_set('Asia/Tokyo');

// サイト名
define('SITE_NAME', 'My Calendar');

// データベース接続
define('DB_HOST', 'localhost');
define('DB_NAME', 'my_calendar');
define('DB_USER', 'root');
define('DB_PASS', '');

// カラーリスト
$colorList = [
    'bg-light' => 'デフォルト',
    'bg-danger' => '赤',
    'bg-warning' => 'オレンジ',
    'bg-primary' => '青',
    'bg-info' => '水色',
    'bg-success' => '緑',
    'bg-dark' => '黒',
    'bg-secondary' => 'グレー'
];
define('COLOR_LIST', $colorList);
?>