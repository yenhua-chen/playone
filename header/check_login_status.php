<?php
session_start();
header('Content-Type: application/json');

if (isset($_SESSION['user'])) {
  echo json_encode([
    'loggedIn' => true,
    'name' => $_SESSION['user']['name'],
    'picture' => $_SESSION['user']['picture_url'],
    'is_admin' => $_SESSION['user']['is_admin']
  ]);
} else {
  echo json_encode(['loggedIn' => false]);
}
