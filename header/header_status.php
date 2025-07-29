<?php
session_start();
header("Content-Type: application/json");

if (isset($_SESSION["user"])) {
  echo json_encode([
    "loggedIn" => true,
    "name" => $_SESSION["user"]["name"],
    'is_admin' => $_SESSION['user']['is_admin'],
    "email" => $_SESSION["user"]["email"] ?? ""
  ]);
} else {
  // 不回傳 403，只回傳 loggedIn: false
  echo json_encode([
    "loggedIn" => false,
    "message" => "未登入"
  ]);
}
