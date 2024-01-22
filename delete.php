<?php
require_once('config.php');
require_once('functions.php');

// 存在・形式チェック
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location:index.php');
    exit();
}

$pdo = connectDB();

$sql = 'DELETE FROM schedules WHERE schedule_id = :schedule_id';
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':schedule_id', $_GET['id'], PDO::PARAM_INT);
$stmt->execute();

// 前の画面に移動
header('Location:' . $_SERVER['HTTP_REFERER']);
exit();
?>