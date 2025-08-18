<?php
// MySQL 資料庫連線設定
$host = 'localhost';          // 通常是 localhost
$dbname = 'playone';          // 你的資料庫名稱
$user = 'root';               // 資料庫帳號（預設為 root）
$pass = '';                   // 資料庫密碼（如果你沒設密碼則留空）

try {
  // 建立 PDO 連線
  $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
  
  // 錯誤模式設為例外，方便除錯
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  
  // 成功後可選擇顯示訊息（開發用，正式版可註解掉）
  // echo "資料庫連線成功";

} catch (PDOException $e) {
  die("資料庫連線失敗：" . $e->getMessage());
}
?>
