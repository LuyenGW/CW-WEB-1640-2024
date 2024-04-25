<?php
include 'partials/header.php';

// Fetch post from database if id is set
if (isset($_GET['id'])) {
    $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
    $query = "SELECT * FROM posts WHERE id=$id";
    $result = mysqli_query($connection, $query);
    if (mysqli_num_rows($result) > 0) {
        $post = mysqli_fetch_assoc($result);
    } else {
        header('location: ' . ROOT_URL . 'blog.php');
        die();
    }
    
    // Fetch comments for the post
    $comment_query = "SELECT c.user_id, u.username, c.comment, c.rating, c.created_at FROM comments c JOIN users u ON c.user_id = u.id WHERE c.post_id = ? ORDER BY c.created_at DESC";
    $stmt = $connection->prepare($comment_query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $comment_result = $stmt->get_result();
    
    $comments = [];
    if ($comment_result->num_rows > 0) {
        while ($row = $comment_result->fetch_assoc()) {
            $comments[] = $row;
        }
    }
    $stmt->close();
} else {
    header('location: ' . ROOT_URL . 'blog.php');
    die();
}
?>

<section class="singlepost">
    <div class="container singlepost__container">
        <h2><?= $post['title'] ?></h2>
        <div class="post__author">
            <?php
            // Fetch author from users table using author_id
            $author_id = $post['author_id'];
            $author_query = "SELECT * FROM users WHERE id=$author_id";
            $author_result = mysqli_query($connection, $author_query);
            $author = mysqli_fetch_assoc($author_result);
            ?>
            <div class="post__author-avatar">
                <img src="./images/<?= $author['avatar'] ?>">
            </div>
            <div class="post__author-info">
                <h5>By: <?= "{$author['firstname']} {$author['lastname']}" ?></h5>
                <small>
                    <?= date("M d, Y - H:i", strtotime($post['date_time'])) ?>
                </small>
            </div>
        </div>
        <div class="singlepost__thumbnail">
            <img src="./images/<?= $post['thumbnail'] ?>">
        </div>
        <p>
            <?= $post['body'] ?>
        </p>
    </div>
</section>

<!--====================== Comments Section ====================-->

<section class="post-comments">
    <div class="container">
        <h3>Comments and Ratings</h3>
        <div class="add-comment">
            <h4>Add your comment</h4>
            <form action="submit_comment.php" method="POST">
                <input type="hidden" name="post_id" value="<?= $id; ?>">
                <textarea name="comment" placeholder="Write your comment here..." required></textarea>
                <label>Rating: </label>
                <select name="rating">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                </select>
                <button type="submit" class="btn">Submit</button>
            </form>
        </div>

        <div class="comments-list">
            <?php
            if (empty($comments)) {
                echo "<p>No comments yet</p>";
            } else {
                foreach ($comments as $comment) {
                    echo "<div class='comment-item'>";
                    echo "<h5>" . htmlspecialchars($comment['username']) . "</h5>";
                    echo "<p>" . htmlspecialchars($comment['comment']) . "</p>";
                    echo "<span>Rating: " . $comment['rating'] . "/5</span>";
                    echo "</div>";
                }
            }
            ?>
        </div>
    </div>
</section>
