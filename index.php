<?php
require_once('config.php');
require_once('functions.php');

$title = SITE_NAME;

// 前月・次月リンクが押された場合は、GETパラメータから年月を取得
if (isset($_GET['ym'])) {
    $ym = $_GET['ym'];
} else {
    // 今月の年月を表示
    $ym = date('Y-m');
}

// タイムスタンプを作成し、フォーマットをチェックする
$timestamp = strtotime($ym . '-01');
if ($timestamp === false) {
    $ym = date('Y-m');
    $timestamp = strtotime($ym . '-01');
}

// 該当月の日数を取得
$day_count = date('t', $timestamp);

// 1日が何曜日か 1:月 2:火 ... 7:日
$youbi = date('w', $timestamp);

// カレンダーのタイトルを作成　例）2024年3月
$html_title = date('Y年n月', $timestamp);

// 前月・次月の年月を取得
$prev = date('Y-m', strtotime('-1 month', $timestamp));
$next = date('Y-m', strtotime('+1 month', $timestamp));

// 今日の日付　例）2024-03-05
$today = date('Y-m-d');

// カレンダー作成の準備
$weeks = [];
$week = '';

// 第１週目：空のセルを追加
// 例）１日が木曜日だった場合、月曜日から水曜日の３つ分の空セルを追加する
$week .= str_repeat('<td></td>', $youbi);

// データベースに接続
$pdo = connectDB();

// カレンダー作成
for ($day = 1; $day <= $day_count; $day++, $youbi++) {
    
    $date = $ym . '-' . sprintf('%02d', $day);
    
    // 予定を取得
    $rows = getSchedulesByDate($pdo, $date);

    // HTML作成
    if ($date == $today) {
        $week .= '<td class="today">';
    } else {
        $week .= '<td>';
    }

    $week .= '<a href="detail.php?ymd=' . $date . '">' . $day;

    if (!empty($rows)) {
        $week .= '<div class="badges">';
            foreach ($rows as $row) {
                $task = date('H:i', strtotime($row['start_datetime'])) . ' ' . h($row['task']);
                $week .= '<span class="badge text-wrap ' . $row['color'] . '">' . $task . '</span>';
            }
        $week .= '</div>';
    }

    $week .= '</a></td>';

    // 日曜日、または、最終日の場合
     if ($youbi % 7 == 6 || $day == $day_count) {

        if ($day == $day_count) {
            // 月の最終日の場合、空セルを追加
            // 例）最終日が金曜日の場合、土曜日の空セルを追加
            $week .= str_repeat('<td></td>', 6 - $youbi % 7);
        }
        
        // weeks配列にtrと$weekを追加する
        $weeks[] = '<tr>' . $week . '</tr>';

        // $weekをリセット
        $week = '';
     }
}
?>
<!DOCTYPE html>
<html lang="ja" class="h-100">
<head>
    <?php require_once('elements/head.php'); ?>
</head>
<body class="d-flex flex-column h-100">

    <?php require_once('elements/navbar.php'); ?>

<main>
    <div>
        test
    </div>
    <div class="container">
        <table class="table table-bordered calendar">
            <thead>
                <tr class="head-cal fs-4">
                    <th colspan="1" class="text-start"><a href="index.php?ym=<?= $prev; ?>"><i class="fa-solid fa-chevron-left"></i></a></th>
                    <th colspan="5"><?= $html_title; ?></th>
                    <th colspan="1" class="text-end"><a href="index.php?ym=<?= $next; ?>"><i class="fa-solid fa-chevron-right"></i></a></th>
                </tr>
                <tr class="head-week">
                    <th>日</th>
                    <th>月</th>
                    <th>火</th>
                    <th>水</th>
                    <th>木</th>
                    <th>金</th>
                    <th>土</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($weeks as $week) { echo $week; }?>
            </tbody>
        </table>
    </div>
</main>

<?php require_once('elements/footer.php'); ?>
</body>
</html>