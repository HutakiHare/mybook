<!DOCTYPE html>
<html lang="zh_TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>即時車輛偵測違規系統</title>
    <style>
        /* 整體佈局 */
        body {
            display: flex;
            margin: 0;
            font-family: Arial, sans-serif;
        }

        /* 左邊的側邊欄樣式 */
        .sidebar {
            width: 250px;
            height: 100vh;
            background-color: #333;
            color: white;
            padding: 20px 15px; /* 調整內邊距 */
            position: fixed;
            overflow: auto; /* 如果內容太多，可滾動 */
        }

        .sidebar h2, .sidebar a {
            margin-left: 10px; /* 增加左側邊距，避免貼邊 */
        }

        .sidebar a {
            padding: 10px;
            text-decoration: none;
            font-size: 18px;
            color: white;
            display: block;
        }

        .sidebar a:hover {
            background-color: #575757;
        }

        /* Logout連結固定在底部 */
        .logout {
            position: absolute;
            bottom: 70px; /* 與底部的距離 */
        }

        /* 右邊內容區域，包含圖片 */
        .content {
            margin-left: 270px; /* 增加側邊欄寬度 */
            padding: 20px;
            flex-grow: 1;
            
            /* 增加與底部的距離 */
            padding-bottom: 70px; /* 與底部的距離，根據需求調整 */

            /* 將內容居中 */
            display: flex;
            align-items: center; /* 垂直居中 */
            height: 100vh; /* 讓內容區域高度充滿頁面，便於垂直居中 */
            flex-direction: column; /* 垂直排列 */
            box-sizing: border-box;
        }

        select {
            width: 350px; /* 調整寬度 */
            height: 35px; /* 調整高度 */
            font-size: 15px; /* 調整文字大小 */
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        /* 容器，用於垂直排列 */
        .run-container {
            display: flex;
            font-size: 30px;
            align-items: left; /* 水平居中 */
            flex-direction: column; /* 垂直排列 */
            margin-top: 50px; /* 與上方元素的間隙 */
        }

        .func_button {
            margin-bottom: 70px; /* 調整影片與下方表單的距離 */
        }

    </style>
</head>
<body>

<!-- 側邊欄區域 -->
<div class="sidebar">
    <h2>RT-DTV</h2>
    <a href="home.php">Home</a>
    <a href="upload.php">Upload</a>
    <a href="run.php">Run</a>
    <a href="register.php" class="logout">Logout</a>
</div>

<!-- 內容區域 -->
<?php $show = False; ?>
<div class="content">
    <h1>Real-Time Detection of Traffic Violation</h1>
    <div class = run-container>
        <form action="run_process.php" method="post">
            <select name="fileSelect" id="fileSelect">
                <?php
                session_start();
                $servername = "localhost";
                $username = "root"; // MySQL 用戶名
                $password = ""; // MySQL 密碼
                $dbname = "user_data";
                $conn = new mysqli($servername, $username, $password, $dbname);

                $usrname = $_SESSION['username'];

                // 檢查連接
                if ($conn->connect_error) {
                    die("連接失敗: " . $conn->connect_error);
                }
                
                $sql_name = "SELECT * FROM $usrname";
                $result = $conn->query($sql_name);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . htmlspecialchars($row["file_path"]) . "'>" . htmlspecialchars($row["file_name"]) . "</option>";
                    }
                } else {
                    echo "<option value=''>沒有可用的檔案</option>";
                }
                ?>
            </select>
        <br><br>
        <input type="submit" value="Choose">
        </form>

        <!-- 顯示處理後的檔案 -->
        <?php
       
        if (isset($_SESSION['selected_file_path'])) {
            $show = True;
            $file_path = $_SESSION['selected_file_path'];
            echo "<h4>選擇的檔案：</h4>";
            echo "<video width='600' controls>
                    <source src='" . htmlspecialchars($file_path) . "' type='video/mp4'>
                  您的瀏覽器不支援影片播放。
                  </video>";
        
        ?>
        <br>
        <div class = func_button>
            <form action="run_program.php" method="post">
                <input type="submit" value="Run">
                <input type="submit" value="程式影片生成">
                <input type="submit" value="Result">
                <input type="submit" value="Download">
            </form>
        </div>

    <?php
        }
    ?>

    </div>
</div>

</body>
</html>
