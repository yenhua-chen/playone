<?php
session_start();
require_once __DIR__ . '/../db.php';

// 取得表單資料
$name = $_POST['name'] ?? '';
$city = $_POST['city'] ?? '';
$district = $_POST['district'] ?? '';
$address = $_POST['address'] ?? '';

// 處理 checkbox
$has_ac = isset($_POST['has_ac']) ? 1 : 0;
$has_water = isset($_POST['has_water']) ? 1 : 0;
$has_parking = isset($_POST['has_parking']) ? 1 : 0;
$has_shower = isset($_POST['has_shower']) ? 1 : 0;

// 圖片上傳
$image_path = null;
if (!empty($_FILES['image']['name'])) {
  $upload_dir = 'uploads/';
  if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

  $filename = uniqid() . '_' . basename($_FILES['image']['name']);
  $target_path = $upload_dir . $filename;

  if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
    $image_path = $target_path;
  }
}

// 基本驗證
if (!$name || !$city || !$district || !$address) {
  echo "<script>alert('請填寫所有必填欄位'); history.back();</script>";
  exit;
}

// 寫入資料庫（含狀態）
$stmt = $pdo->prepare("
  INSERT INTO courts (name, city, district, has_ac, has_water, has_parking, has_shower, address, image_path, status)
  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')
");

try {
  $stmt->execute([
    $name, $city, $district,
    $has_ac, $has_water, $has_parking, $has_shower,
    $address, $image_path
  ]);
  echo "<script>alert('球場新增成功，請等待站長審核'); location.href='../index.html';</script>";

} catch (PDOException $e) {
  echo "<script>alert('資料寫入失敗：" . $e->getMessage() . "'); history.back();</script>";
}
?>
