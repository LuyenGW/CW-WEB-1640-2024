<?php
// Kiểm tra nếu session chưa được khởi động thì mới gọi session_start()
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

define('ROOT_URL', 'http://localhost/blog/');
define('DB_HOST', 'localhost');
define('DB_USER', 'egator');
define('DB_PASS', 'admin1234');
define('DB_NAME', 'blog');
