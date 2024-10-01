<?php

$servername = "localhost";
$username = "root"; // MySQL 用戶名
$password = ""; // MySQL 密碼
$dbname = "user_data";

// 建立連接
$conn = new mysqli($servername, $username, $password, $dbname);

// 檢查連接
if ($conn->connect_error) {
    die("連接失敗: " . $conn->connect_error);
}

// 開啟 session
session_start();
// 設定時區，以獲取正確的當地時間
date_default_timezone_set('Asia/Taipei');

// 檢查接收到POST請求
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 取得表單輸入的值
    $files = $_FILES['my_file'];
    $uploadedFiles = [];
    $usrname = $_SESSION['username'];

    foreach ($files['name'] as $index => $fileName) {
        $fileTmpName = $files['tmp_name'][$index];
        $fileSize = $files['size'][$index];
        $upload_date = date('Y-m-d H:i:s');
        $fileType = strtolower($files['type'][$index]);
        
        // 確認檔案是否已存在於資料庫
        $query = "SELECT * FROM $usrname WHERE file_name = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $fileName);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            // 如果檔案重複，提示用戶選擇是否替換
            $self_tmp_store = 'uploads/tmp/' . basename($fileName);
            move_uploaded_file($fileTmpName, $self_tmp_store);
?>
            <!DOCTYPE html>
            <html lang="zh_TW">
            <head>
                <meta charset="UTF-8">
            </head>
            <body>
                <form id="replaceForm" method="POST" action="replace.php">
                    <input type="hidden" name="fileName" id="fileNameInput">
                    <input type="hidden" name="tmp_name" id="file_tmp_Input" value=<?php echo $self_tmp_store;?>>
                    <input type="hidden" name="fileSize" id="fileSizeInput" value=<?php echo $fileSize; ?>>
                    <input type="hidden" name="fileType" id="fileTypeInput" value=<?php echo $fileType; ?>>
                </form>
            </body>
            </html>
<?php   
            echo "<script>
                document.getElementById('fileNameInput').value = '" . addslashes($fileName) . "';
                if (confirm('檔案 \"" . addslashes($fileName) . "\" 已存在，是否要取代?')) {
                    document.getElementById('replaceForm').submit();
                }else{
                    alert('檔案未被取代。');
                    // Optionally redirect or handle the case where the user cancels
                    window.location.href = 'upload.php'; // Redirect back to upload page
                }
            </script>";
        } else {
            // 如果檔案不重複，則進行上傳
            $uploadPath = 'uploads/' . $usrname . '/' . basename($fileName);
            if (move_uploaded_file($fileTmpName, $uploadPath)) {
                // 將檔案資訊寫入資料庫
                $insertQuery = "INSERT INTO $usrname (file_name, file_size, upload_date, file_type, file_path) VALUES (?, ?, ?, ?, ?)";
                $insertStmt = $conn->prepare($insertQuery);
                $insertStmt->bind_param("sisss", $fileName, $fileSize, $upload_date, $fileType, $uploadPath);
                $insertStmt->execute();
                
                $message = "上傳完成!";
                $color = "#42ff33";
                $uploadedFiles[] = $fileName;
            }else{
                $message = "上傳失敗\n請重新上傳";
                $color = "#FF3333";
            }
        }
    }
    
    $conn->close();
    
    $new_page = 'https://localhost/upload.php';

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
                background-color: <?php echo htmlspecialchars($color);?>; /* 背景 */
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