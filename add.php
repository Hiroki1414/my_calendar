<?php
require_once('config.php');
require_once('functions.php');

$title = '予定の追加 | ' . SITE_NAME;

// 変数を用意
$err = [];
$start_datetime = '';
$end_datetime = '';
$task = '';
$color = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $start_datetime = $_POST['start_datetime'];
    $end_datetime =  $_POST['end_datetime'];
    $task = $_POST['task'];
    $color = $_POST['color'];

    // 入力チェック
    if ($start_datetime == '') {
        $err['start_datetime'] = '開始日時を入力して下さい。';
    }

    if ($end_datetime == '') {
        $err['end_datetime'] = '終了日時を入力して下さい。';
    }

    if ($task == '') {
        $err['task'] = '予定を入力してください。';
    } else if (mb_strlen($task) > 32) {
        $err['task'] = '32文字以内で入力してください。';
    }

    if ($color == '') {
        $err['color'] = 'カラーを選択してください。';
    }

    // エラーが無ければデータベースに保存
    if (empty($err)) {
        // 1. データベースに接続
        $pdo = connectDB();

        // 2. SQL文の作成
        $sql = 'INSERT INTO schedules(start_datetime, end_datetime, task, color, created_at, modified_at)
        VALUES(:start_datetime, :end_datetime, :task, :color, now(), now())';
    
        // 3. SQL文を実行する準備
        $stmt = $pdo->prepare($sql);

        // 4. 値をセット
        $stmt->bindValue(':start_datetime', $start_datetime, PDO::PARAM_STR);
        $stmt->bindValue(':end_datetime', $end_datetime, PDO::PARAM_STR);
        $stmt->bindValue(':task', $task, PDO::PARAM_STR);
        $stmt->bindValue(':color', $color, PDO::PARAM_STR);
    
        // 5. ステートメントを実行
        $stmt->execute();
        
        // 6. 予定詳細画面に遷移
        header('Location:detail.php?ymd='.date('Y-m-d', strtotime($start_datetime)));
        exit();
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
    <div class="container">
        <div class="row">
            <div class="col-lg-6 offset-lg-3">
                <h4 class="text-center">予定の追加</h4>

                <form method="post">
                    <div class="mb-4 dp-parent">
                        <label for="inputStartDateTime" class="form-label">開始日時</label>
                        <input type="text" name="start_datetime" id="inputStartDateTime" class="form-control task-datetime <?php if (!empty($err['start_datetime'])) echo 'is-invalid'; ?>" placeholder="開始日時を選択して下さい。" value="<?= $start_datetime; ?>">
                        <?php if (!empty($err['start_datetime'])): ?>
                            <div id="inputStartDateTimeFeedback" class="invalid-feedback">
                                * <?= $err['start_datetime']; ?>
                            </div>
                        <?php endif; ?>
                    </div>
    
                    <div class="mb-4 dp-parent">
                        <label for="inputEndDateTime" class="form-label">終了日時</label>
                        <input type="text" name="end_datetime" id="inputEndDateTime" class="form-control task-datetime <?php if (!empty($err['end_datetime'])) echo 'is-invalid'; ?>" placeholder="終了日時を選択して下さい。" value="<?= $end_datetime; ?>">
                        <?php if (!empty($err['end_datetime'])): ?>
                            <div id="inputEndDateTimeFeedback" class="invalid-feedback">
                                * <?= $err['end_datetime']; ?>
                            </div>
                        <?php endif; ?>
                    </div>
    
                    <div class="mb-4">
                        <label for="inputTask" class="form-label">予定</label>
                        <input type="text" name="task" id="inputTask" class="form-control <?php if (!empty($err['task'])) echo 'is-invalid'; ?>" placeholder="予定を入力して下さい。" value="<?= $task; ?>">
                        <?php if (!empty($err['task'])): ?>
                            <div id="inputTaskFeedback" class="invalid-feedback">
                                * <?= $err['task']; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-5">
                        <label for="selectColor" class="form-label">カラー</label>
                        <select name="color" id="selectColor" class="form-select <?= $color; ?> <?php if (!empty($err['color'])) echo 'is-invalid'; ?>">
                            <?php foreach(COLOR_LIST as $key => $val):?>
                                <option value="<?= $key; ?>" <?php if ($color == $key) echo 'selected'; ?>><?= $val; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (!empty($err['color'])): ?>
                            <div id="selectColorFeedback" class="invalid-feedback">
                                * <?= $err['color']; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">登録</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</main>

<?php require_once('elements/footer.php'); ?>
</body>
</html>