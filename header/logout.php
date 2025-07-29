<?php
session_start();
session_unset();
session_destroy();

header("Location: /PLAYONE/index.html"); // 登出後回首頁
exit;
