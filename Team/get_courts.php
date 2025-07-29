<?php
require_once('../db.php');
header('Content-Type: application/json');

$stmt = $pdo->query("SELECT id, name FROM courts ORDER BY id DESC");
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($data);
