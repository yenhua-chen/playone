<?php
// Render 上的 PDO MySQL 資料庫連線設定

$host = 'mysql.railway.internal';
$dbname = 'railway';
$user = 'root';
$pass = 'VPZllwhVbgWetYUtKTEeYDIPbcWcfGKy';  // 建議你上線後再用環境變數取代這裡

try {
  $pdo = new PDO("mysql:host=$host;port=3306;dbname=$dbname;charset=utf8mb4", $user, $pass);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  die("資料庫連線失敗：" . $e->getMessage());
}
?>
