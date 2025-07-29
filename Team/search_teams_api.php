<?php
require_once('../db.php');
header('Content-Type: application/json');

// 取得搜尋條件
$city    = trim($_POST['city'] ?? '');
$day     = trim($_POST['day'] ?? '');
$court   = trim($_POST['court'] ?? '');
$level   = trim($_POST['level'] ?? '');
$has_ac  = $_POST['has_ac'] ?? '';
$has_water = $_POST['has_water'] ?? '';
$has_parking = $_POST['has_parking'] ?? '';
$is_friendly = $_POST['is_friendly'] ?? '';
$is_free_play = $_POST['is_free_play'] ?? '';
$is_queue_play = $_POST['is_queue_play'] ?? '';
$is_insert_play = $_POST['is_insert_play'] ?? '';

// 組合 WHERE 條件
$conditions = [];
$params = [];

if ($city !== '') {
  $conditions[] = "courts.city LIKE ?";
  $params[] = "%$city%";
}

if ($day !== '') {
  $conditions[] = "teams.practice_day = ?";
  $params[] = $day;
}

if ($court !== '') {
  $conditions[] = "courts.name LIKE ?";
  $params[] = "%$court%";
}

// ✅ 修改：只查 level_min 等於使用者選項
if ($level !== '') {
  $conditions[] = "teams.level_min = ?";
  $params[] = $level;
}

if ($has_ac !== '') {
  $conditions[] = "courts.has_ac = 1";
}
if ($has_water !== '') {
  $conditions[] = "courts.has_water = 1";
}
if ($has_parking !== '') {
  $conditions[] = "courts.has_parking = 1";
}
if ($is_friendly !== '') {
  $conditions[] = "teams.is_friendly = 1";
}
if ($is_free_play !== '') {
  $conditions[] = "teams.is_free_play = 1";
}
if ($is_queue_play !== '') {
  $conditions[] = "teams.is_queue_play = 1";
}
if ($is_insert_play !== '') {
  $conditions[] = "teams.is_insert_play = 1";
}

// 建立 SQL（加入 level_min, level_max）
$sql = "
  SELECT
    teams.id,
    teams.name,
    teams.practice_day,
    teams.practice_start,
    teams.practice_end,
    teams.level_min,
    teams.level_max,
    teams.fee,
    teams.is_friendly,
    teams.is_free_play,
    teams.is_queue_play,
    teams.is_insert_play,
    courts.name AS court_name,
    courts.city,
    courts.district,
    courts.has_ac,
    courts.has_water,
    courts.has_parking
  FROM teams
  JOIN courts ON teams.court_id = courts.id
";

if (!empty($conditions)) {
  $sql .= " WHERE " . implode(" AND ", $conditions);
}

$sql .= " ORDER BY teams.id DESC";

// 執行查詢
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 回傳 JSON
echo json_encode($data);
