<?php
session_start();
require_once '../db.php'; // 請根據實際路徑修改資料庫連線

// 取得使用者輸入
$account = $_POST['account'] ?? '';
$password = $_POST['password'] ?? '';
$email = $_POST['email'] ?? '';
$name = $_POST['name'] ?? '';
$address = $_POST['address'] ?? '';

// 基本欄位驗證（你也可以加更細的規則）
if (!$account || !$password || !$email) {
  echo "<script>alert('請完整填寫帳號、密碼與信箱'); history.back();</script>";
  exit;
}

// 檢查帳號是否已存在
$stmt = $pdo->prepare("SELECT * FROM users WHERE account = ?");
$stmt->execute([$account]);
if ($stmt->fetch()) {
  echo "<script>alert('此帳號已被註冊，請使用其他帳號'); history.back();</script>";
  exit;
}

// 加密密碼
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// 寫入資料庫
$sql = "INSERT INTO users (account, password, name, email, address, created_at, updated_at)
        VALUES (?, ?, ?, ?, ?, NOW(), NOW())";
$stmt = $pdo->prepare($sql);
$result = $stmt->execute([$account, $hashedPassword, $name, $email, $address]);

if ($result) {
  echo "<script>alert('註冊成功，請登入'); window.location.href = '../login.html';</script>";
} else {
  echo "<script>alert('註冊失敗，請稍後再試'); history.back();</script>";
}
