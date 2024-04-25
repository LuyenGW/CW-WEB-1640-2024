<?php
// Include file cấu hình kết nối cơ sở dữ liệu
include 'config/database.php';

// Kiểm tra xem liệu có phải là một POST request và các trường bắt buộc có tồn tại không
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['post_id'], $_POST['comment'], $_POST['rating'])) {
    // Lấy và làm sạch dữ liệu nhập từ form
    $post_id = filter_input(INPUT_POST, 'post_id', FILTER_SANITIZE_NUMBER_INT);
    $comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_STRING);
    $rating = filter_input(INPUT_POST, 'rating', FILTER_VALIDATE_INT);
    $user_id = $_SESSION['user-id']; // Lấy user_id từ session

    // Kiểm tra giá trị rating có hợp lệ không
    if ($rating < 1 || $rating > 5) {
        // Nếu không hợp lệ, thiết lập rating mặc định là 1
        $rating = 1;
    }

    // Tạo câu lệnh SQL để chèn bình luận vào cơ sở dữ liệu
    $sql = "INSERT INTO comments (post_id, user_id, comment, rating) VALUES (?, ?, ?, ?)";
    $stmt = $connection->prepare($sql);
    if ($stmt) {
        $stmt->bind_param('iisi', $post_id, $user_id, $comment, $rating);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            // Nếu bình luận được thêm thành công, chuyển hướng người dùng trở lại trang bài viết
            header('Location: post.php?id=' . $post_id); // Đảm bảo rằng 'post_view.php' là trang xem bài viết chi tiết
            exit;
        } else {
            // Nếu không thể thêm bình luận, hiển thị thông báo lỗi
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error: Could not prepare the SQL statement.";
    }
} else {
    // Nếu dữ liệu form không hợp lệ hoặc không đầy đủ, hiển thị thông báo lỗi
    echo "Error: Invalid form data. Please make sure all fields are filled out correctly.";
}
?>
