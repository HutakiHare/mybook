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

// 檢查接收到POST請求
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 取得表單輸入的值
    $usrname = $_POST['usr_name'];
    $email = $_POST['email_name'];
    $password = $_POST['password'];
    $double_check_psswd = $_POST['double_check_psswd'];

    // 查詢資料庫中的使用者
    $sql_name = "SELECT * FROM users WHERE username='$usrname'";
    $name_result = $conn->query($sql_name);
    $sql_email = "SELECT * FROM users WHERE email='$email'";
    $email_result = $conn->query($sql_email);
    $new_page = "https://localhost/register.php";
    $color = "#FF3333";

    // 資料驗證
    if (empty($usrname) || empty($email) || empty($password) || empty($double_check_psswd)) {
        echo "所有欄位都是必填的！";
    } else if ($password != $double_check_psswd){
        $message = "輸入密碼不同!!\n請重新檢查輸入";
    } else if ($name_result->num_rows > 0){
        $message = '該使用者名稱已經被註冊了';
    } else if ($email_result->num_rows > 0){
        $message = '該電子郵件已經被註冊過了';
    } else {
        // 檢查連接
        $sql = "INSERT INTO users (username, email, password) VALUES ('$usrname', '$email', '$password')";
        if ($conn->query($sql) === TRUE) {
            // 動態切換資料庫
            if ($conn->select_db("user_data")) {
                $createTableQuery = "CREATE TABLE $usrname (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    file_name VARCHAR(255) NOT NULL,
                    file_size INT NOT NULL,
                    upload_date DATETIME DEFAULT NULL,
                    file_type VARCHAR(255) NOT NULL,
                    file_path VARCHAR(255) NOT NULL
                )";
                $directory = 'uploads/' . $usrname; // 這裡輸入要創建的資料夾路徑
                if ($conn->query($createTableQuery) === TRUE && mkdir($directory)) {
                    $message = "新記錄建立成功";
                    $color = "#42ff33";
                    $new_page = "https://localhost/login.php";
                } else {
                    $message = "創建資料庫失敗: " . $conn->error;
                }
            } else {
                die("連接失敗: " . $conn->connect_error);
            }
        } else {
            $message = "錯誤: " . $sql . "<br>" . $conn->error;
        }
        // 關閉連接
        $conn->close();
    }
    // 使用 header() 函數進行重定向
    header("Refresh: 3; URL=$new_page"); // 5秒後自動重定向到登入頁

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