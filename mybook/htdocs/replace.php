<?php
// 連接 MySQL 資料庫
$servername = "localhost";
$username = "root"; // MySQL 用戶名
$password = ""; // MySQL 密碼
$dbname = "user_data";

// 建立連接
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("資料庫連接失敗: " . $conn->connect_error);
}

// 開啟 session
session_start();
// 設定時區，以獲取正確的當地時間
date_default_timezone_set('Asia/Taipei');

if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $fileName = $_POST['fileName']; // 接收檔案名稱
    $usrname = $_SESSION['username'];
    $uploadPath = 'uploads/' . $usrname . '/' . basename($fileName);
    
    if (file_exists($uploadPath)){
        unlink($uploadPath);
    }
    $fileTmpName = $_POST['tmp_name']; // 檔案臨時路徑
    $fixfileTmpName = addslashes($fileTmpName);
    $fileSize = $_POST['fileSize']; // 檔案大小
    $upload_date = date('Y-m-d H:i:s');
    $fileType = $_POST['fileType']; // 檔案類型
    
    if (!file_exists($fileTmpName)) {
        die("臨時檔案不存在: " . $fileTmpName);
    }
    if (empty($uploadPath)) {
        die("上傳路徑未正確設定.");
    }

    if (rename($fileTmpName, $uploadPath)) {
        // Update the database record
        $updateQuery = "UPDATE $usrname SET file_type = ?, file_size = ?, upload_date = ? , file_path = ? WHERE file_name = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param("sisss",  $fileType, $fileSize, $upload_date, $uploadPath, $fileName);

        if ($updateStmt->execute()) {
            $message = "檔案更新成功!";
        } else {
            $message = "檔案更新失敗: " . $updateStmt->error;
        }

        $updateStmt->close();
    } else {
        $message = "檔案 " . $fileName . " 更新失敗!";
    }
    // Move the uploaded file and replace the old one

    $conn->close();
    $new_page = 'https://localhost/upload.php';
    header("Refresh: 3; URL=$new_page"); // 2秒後自動重定向到上傳頁面
    ?>
    <!DOCTYPE html>
    <html lang="zh_TW">
    <head>
        <meta charset="UTF-8">
        <style>
            #message {
                display: none; /* 預設隱藏 */
                background-color: #42ff33; /* 背景 */
                color: white; /* 白色文字 */
                padding: 15px; /* 內邊距 */
                position: fixed; /* 固定位置 */
                top: 20px; /* 距上方20px */
                left: 50%; /* 水平置中 */
                transform: translate(-50%, -50%); /* 調整位置使其正好居中 */
                z-index: 1000; /* 確保在最上層 */
            }
        </style>
    </head>
    <body>

    <div id="message"><?php echo $message; ?></div> <!-- 顯示成功消息 -->

    <script>
        // 獲取消息元素
        var messageDiv = document.getElementById('message');
        // 顯示消息
        messageDiv.style.display = 'block';
        // 設定幾秒後隱藏
        setTimeout(function() {
            messageDiv.style.display = 'none';
        }, 5000); // 5000毫秒 = 5秒
    </script>

    </body>
    </html>
    <?php

    // 終止腳本的執行
    exit();
}
?>
