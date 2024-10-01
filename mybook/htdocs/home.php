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

        /* 圖片容器，用於垂直排列圖片 */
        .img-container {
            display: flex;
            flex-direction: column; /* 垂直排列 */
            align-items: center; /* 水平居中 */
            margin-top: 20px; /* 與上方元素的間隙 */
        }

        .img-container img {
            max-width: 100%;
            height: auto;
            margin-bottom: 20px; /* 每張圖片之間的距離 */
            border: 5px solid #ddd;
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
    <a href="register.php" class = "logout">Logout</a>
</div>

<!-- 內容區域 -->
<div class="content">
    <h1>Real-Time Detection of Traffic Violation</h1>
    <div class="img-container">
        <img src="pictures/system.png" alt="system">
        <img src="pictures/tutorial.png" alt="tutorial">
    </div>
</div>

</body>
</html>
