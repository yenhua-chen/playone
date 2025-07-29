<?php
session_start();
header('Content-Type: application/json');
require_once('../db.php'); // 改成相對路徑

// 權限判斷
if (!isset($_SESSION['user']) || intval($_SESSION['user']['is_admin']) !== 1) {
  http_response_code(403);
  echo json_encode(["code" => 403, "msg" => "permission error"]);
  exit;
}

try {
  $stmt = $pdo->query("SELECT * FROM courts WHERE status = 'pending' ORDER BY created_at DESC");
  $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

  echo json_encode($data);
  exit;

} catch (PDOException $e) {
  http_response_code(500);
  echo json_encode([
    "code" => 500,
    "msg" => "查詢失敗：" . $e->getMessage()
  ]);
  exit;
}
?>
