<?php include 'templates/header.php'; ?>
<?php
require_once "api/config.php";

$sql = "SELECT l.url, l.title, u.username, l.created_at FROM links l JOIN users u ON l.user_id = u.id WHERE u.id = ? OR u.id = (SELECT partner_id FROM users WHERE id = ?) ORDER BY l.created_at DESC";
if ($stmt = mysqli_prepare($link, $sql)) {
    mysqli_stmt_bind_param($stmt, "ii", $_SESSION["id"], $_SESSION["id"]);
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        $links = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    mysqli_stmt_close($stmt);
}

mysqli_close($link);
?>
        <main>
            <form action="api/add_link.php" method="post">
                <input type="text" name="url" placeholder="Enter URL">
                <input type="text" name="title" placeholder="Enter title (optional)">
                <button type="submit">Add Link</button>
            </form>
            <div class="links-list">
                <?php foreach ($links as $link): ?>
                    <div class="link-item">
                        <a href="<?php echo $link['url']; ?>" target="_blank"><?php echo $link['title'] ? $link['title'] : $link['url']; ?></a>
                        <small>By <?php echo $link['username']; ?> on <?php echo $link['created_at']; ?></small>
                    </div>
                <?php endforeach; ?>
            </div>
        </main>
<?php include 'templates/footer.php'; ?>