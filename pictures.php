<?php include 'templates/header.php'; ?>
<?php
require_once "api/config.php";

$sql = "SELECT p.image_path, u.username, p.created_at FROM pictures p JOIN users u ON p.user_id = u.id WHERE u.id = ? OR u.id = (SELECT partner_id FROM users WHERE id = ?) ORDER BY p.created_at DESC";
if ($stmt = mysqli_prepare($link, $sql)) {
    mysqli_stmt_bind_param($stmt, "ii", $_SESSION["id"], $_SESSION["id"]);
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        $pictures = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    mysqli_stmt_close($stmt);
}

mysqli_close($link);
?>
        <main>
            <form action="api/upload_picture.php" method="post" enctype="multipart/form-data">
                <input type="file" name="picture" accept="image/*">
                <button type="submit">Upload Picture</button>
            </form>
            <div class="gallery">
                <?php foreach ($pictures as $picture): ?>
                    <div class="gallery-item">
                        <img src="<?php echo $picture['image_path']; ?>" alt="Picture">
                        <small>By <?php echo $picture['username']; ?> on <?php echo $picture['created_at']; ?></small>
                    </div>
                <?php endforeach; ?>
            </div>
        </main>
<?php include 'templates/footer.php'; ?>