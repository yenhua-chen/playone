<?php
require_once(__DIR__ . '/../db.php');
header('Content-Type: application/json');

$city = $_GET['city'] ?? '';
$district = $_GET['district'] ?? '';

$sql = "SELECT * FROM courts";
$params = [];

if ($city || $district) {
    $sql .= " WHERE 1";
    if ($city) {
        $sql .= " AND city LIKE ?";
        $params[] = "%$city%";
    }
    if ($district) {
        $sql .= " AND district LIKE ?";
        $params[] = "%$district%";
    }
}
$sql .= " ORDER BY id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
