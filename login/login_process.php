<?php
session_start();
require_once('../db.php'); // 根據實際路徑引入你的資料庫設定

$account = $_POST['account'] ?? '';
$password = $_POST['password'] ?? '';

// 檢查是否輸入帳密
if (!$account || !$password) {
  echo "<script>alert('請輸入帳號與密碼'); history.back();</script>";
  exit;
}

// 查詢資料庫中是否有此帳號
$stmt = $pdo->prepare("SELECT * FROM users WHERE account = ?");
$stmt->execute([$account]);
$user = $stmt->fetch();

// 驗證密碼
if ($user && password_verify($password, $user['password'])) {
  // ✅ 正確設定 session
  $_SESSION["user"] = [
    "id" => $user["id"],
    "is_admin" => $user["is_admin"],
    "name" => $user["name"] ?: $user["account"],
    "email" => $user["email"] ?? ""
  ];

  echo "<script>alert('登入成功'); location.href = '/PLAYONE/index.html';</script>";
  exit;
} else {
  echo "<script>alert('帳號或密碼錯誤'); history.back();</script>";
  exit;
}
