<?php
// get_line_login_url.php
$client_id = '2007826048';
$redirect_uri = 'http://localhost/PLAYONE/login/callback.php';
$state = uniqid();
$scope = 'profile openid email';
$prompt = 'consent'; // 或 'none' 可調整

$login_url = 'https://access.line.me/oauth2/v2.1/authorize?' . http_build_query([
  'response_type' => 'code',
  'client_id' => $client_id,
  'redirect_uri' => $redirect_uri,
  'state' => $state,
  'scope' => $scope,
  'prompt' => $prompt
]);

// 回傳 JSON 給前端
header('Content-Type: application/json');
echo json_encode(['login_url' => $login_url]);
