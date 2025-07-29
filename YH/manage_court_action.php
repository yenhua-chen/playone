<?php
session_start();
header('Content-Type: application/json');
require_once('../db.php'); // 改成相對路徑

if (!isset($_SESSION['user']) || intval($_SESSION['user']['is_admin']) !== 1) {
  http_response_code(403);
  echo json_encode(["success" => false, "message" => "無權限"]);
  exit;
}

$id = $_POST['court_id'] ?? '';
$action = $_POST['action'] ?? '';

if (!$id || !in_array($action, ['approve', 'reject'])) {
  echo json_encode(["success" => false, "message" => "參數錯誤"]);
  exit;
}

try {
  if ($action === 'approve') {
    $stmt = $pdo->prepare("UPDATE courts SET status = 'approved' WHERE id = ?");
    $stmt->execute([$id]);
  } else {
    $stmt = $pdo->prepare("DELETE FROM courts WHERE id = ?");
    $stmt->execute([$id]);
  }

  echo json_encode(["success" => true]);
  exit;

} catch (PDOException $e) {
  http_response_code(500);
  echo json_encode([
    "success" => false,
    "message" => "資料庫錯誤：" . $e->getMessage()
  ]);
  exit;
}
