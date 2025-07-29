<?php
session_start();
require_once '../db.php';

// === LINE Login 設定 ===
$client_id = '2007826048';
$client_secret = 'd7b00050882529ff18d6358301c4ae78';
$redirect_uri = 'http://localhost/PLAYONE/login/callback.php';

// === 檢查授權碼 ===
if (!isset($_GET['code'])) {
  header('Location: /PLAYONE/login/login.php?error=missing_code');
  exit;
}

// === 取得 access token ===
$code = $_GET['code'];
$token_url = 'https://api.line.me/oauth2/v2.1/token';
$data = [
  'grant_type' => 'authorization_code',
  'code' => $code,
  'redirect_uri' => $redirect_uri,
  'client_id' => $client_id,
  'client_secret' => $client_secret
];
$options = [
  'http' => [
    'method'  => 'POST',
    'header'  => 'Content-Type: application/x-www-form-urlencoded',
    'content' => http_build_query($data),
  ]
];
$response = file_get_contents($token_url, false, stream_context_create($options));
$token = json_decode($response, true);

if (!isset($token['access_token'])) {
  header('Location: /PLAYONE/login/login.php?error=token_fail');
  exit;
}

// === 使用 access token 取得使用者資料 ===
$profile_url = 'https://api.line.me/v2/profile';
$headers = ["Authorization: Bearer " . $token['access_token']];
$ch = curl_init($profile_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
$userData = json_decode(curl_exec($ch), true);
curl_close($ch);

if (!isset($userData['userId'])) {
  header('Location: /PLAYONE/login/login.php?error=user_fail');
  exit;
}

// === 解碼 ID Token，嘗試取得 email（若有）===
$id_token = $token['id_token'] ?? null;
$email = null;
if ($id_token) {
  $payload = explode('.', $id_token)[1] ?? '';
  $decoded = json_decode(base64_decode(strtr($payload, '-_', '+/')), true);
  $email = $decoded['email'] ?? null;
}

// === 整理使用者資訊 ===
$line_id = $userData['userId'];
$name = $userData['displayName'];
$picture_url = $userData['pictureUrl'] ?? null;
$now = date('Y-m-d H:i:s');

// === 檢查該 LINE ID 是否已存在於資料庫 ===
$stmt = $pdo->prepare("SELECT * FROM users WHERE line_id = ?");
$stmt->execute([$line_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
  // ✅ 使用者已存在，建立登入 session
  session_regenerate_id(true); // 防止 session fixation
  $_SESSION['user'] = [
    'line_id' => $user['line_id'],
    'name' => $user['name'],
    'picture_url' => $user['picture_url'],
    'email' => $user['email'] ?? null
  ];
  header('Location: /PLAYONE/index.html'); 
  exit;
} else {
  // ❌ 使用者不存在，導向註冊頁面
  $_SESSION['temp_user'] = [
    'line_id' => $line_id,
    'name' => $name,
    'picture_url' => $picture_url,
    'email' => $email
  ];
  header('Location: /PLAYONE/register.html');
  exit;
}
