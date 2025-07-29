<?php
header('Content-Type: application/json');
require_once('../db.php');
session_start();

// ✅ 檢查是否登入
$captain_id = $_SESSION['user']['id'] ?? null;
if (!$captain_id) {
  echo json_encode([
    'success' => false,
    'message' => '請先登入才能新增球隊'
  ]);
  exit;
}

// ✅ 取得表單欄位資料
$name = trim($_POST['name'] ?? '');
$contact = trim($_POST['contact_info'] ?? '');
$court_id = intval($_POST['court_id'] ?? 0);
$note = trim($_POST['note'] ?? '');
$fee = trim($_POST['fee'] ?? '');

$practice_day = $_POST['practice_day'] ?? null;
$practice_start = $_POST['practice_start'] ?? null;
$practice_end = $_POST['practice_end'] ?? null;

// ✅ 重點：改為 level_min / level_max
$level_min = $_POST['level_min'] ?? null;
$level_max = $_POST['level_max'] ?? null;

$is_friendly = isset($_POST['is_friendly']) ? 1 : 0;
$is_queue_play = isset($_POST['is_queue_play']) ? 1 : 0;
$is_free_play = isset($_POST['is_free_play']) ? 1 : 0;
$is_insert_play = isset($_POST['is_insert_play']) ? 1 : 0;

// ✅ 基本欄位檢查
if (!$name || !$court_id) {
  echo json_encode([
    'success' => false,
    'message' => '請填寫球隊名稱與所屬球場'
  ]);
  exit;
}

// ✅ 寫入資料庫
try {
  $stmt = $pdo->prepare("
    INSERT INTO teams 
      (name, contact_info, court_id, note, fee, practice_day, practice_start, practice_end, 
       level_min, level_max,
       is_friendly, is_queue_play, is_free_play, is_insert_play, created_at, captain_id)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?)
  ");

  $stmt->execute([
    $name,
    $contact,
    $court_id,
    $note,
    $fee,
    $practice_day ?: null,
    $practice_start ?: null,
    $practice_end ?: null,
    $level_min ?: null,
    $level_max ?: null,
    $is_friendly,
    $is_queue_play,
    $is_free_play,
    $is_insert_play,
    $captain_id
  ]);

  echo json_encode([
    'success' => true,
    'message' => '球隊新增成功'
  ]);

} catch (PDOException $e) {
  echo json_encode([
    'success' => false,
    'message' => '新增失敗：' . $e->getMessage()
  ]);
}
