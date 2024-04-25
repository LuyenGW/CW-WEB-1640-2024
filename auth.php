<?php
session_start();  // Khởi động session

// Kiểm tra nếu không tồn tại biến session cho đăng nhập
if (!isset($_SESSION['user-id'])) {
    // Chuyển hướng người dùng đến trang đăng nhập
    header('Location: signin.php');
    exit;  // Dừng script hiện tại
}
?>
