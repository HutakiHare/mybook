<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST["fileSelect"])) {
        // 將選擇的檔案路徑存到 session
        $_SESSION['selected_file_path'] = $_POST["fileSelect"];
    }
}

// 回到 run.php 頁面
header("Location: run.php");
exit();
?>

