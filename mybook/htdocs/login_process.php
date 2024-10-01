<?php
$servername = "localhost";
$username = "root"; // MySQL 用戶名
$password = ""; // MySQL 密碼
$dbname = "user_registration";

// 建立連接
$conn = new mysqli($servername, $username, $password, $dbname);

// 檢查連接
if ($conn->connect_error) {
    die("連接失敗: " . $conn->connect_error);
}

// 開啟 session
session_start();

// 檢查POST資料
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usr_name = $_POST['usr_name'];
    $password = $_POST['password'];

    // 查詢資料庫中的使用者
    $sql = "SELECT * FROM users WHERE username='$usr_name'";
    $result = $conn->query($sql);
    $new_page = "https://localhost/login.php";
    $color = "#FF3333";

    if ($result->num_rows > 0) {
        // 取得使用者的資料
        $row = $result->fetch_assoc();

        // 驗證密碼是否正確
        if ($password == $row['password']) {
            // 登入成功，重定向到歡迎頁面
            $message = "登入成功！歡迎, " . $usr_name;
            $new_page = "https://localhost/home.php";
            $color = "#42ff33";
            
            $_SESSION['username'] = $usr_name;
            $_SESSION['selected_file_path'] = NULL;
            $_SESSION['fileSelect'] = NULL;

        } else {
            $message = "密碼錯誤！";
        }
    } else {
        $message = "找不到該使用者！";
    }
    // 使用 header() 函數進行重定向
    header("Refresh: 3; URL=$new_page"); // 3秒後自動重定向到登入頁
    ?>
    <!DOCTYPE html>
    <html lang="zh_TW">
    <head>
        <meta charset="UTF-8">
        <style>
            #message {
                display: none; /* 預設隱藏 */
                background-color: <?php echo htmlspecialchars($color); ?>; /* 背景 */
                color: white; /* 白色文字 */
                padding: 15px; /* 內邊距 */
                position: fixed; /* 固定位置 */
                top: 20px; /* 距上方20px */
                left: 50%;
                transform: translate(-50%, -50%); /* 調整位置使其正好居中 */
                z-index: 1000; /* 確保在最上層 */
            }
        </style>
    </head>
    <body>

    <div id="message"><?php echo $message; ?></div>
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

$conn->close();
?>
