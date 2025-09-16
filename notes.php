<?php include 'templates/header.php'; ?>
<?php
require_once "api/config.php";

$sql = "SELECT u.id as user_id, u.username, n.id, n.note, n.created_at FROM notes n JOIN users u ON n.user_id = u.id WHERE u.id = ? OR u.id = (SELECT partner_id FROM users WHERE id = ?) ORDER BY n.created_at DESC";
if ($stmt = mysqli_prepare($link, $sql)) {
    mysqli_stmt_bind_param($stmt, "ii", $_SESSION["id"], $_SESSION["id"]);
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        $notes = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    mysqli_stmt_close($stmt);
}

mysqli_close($link);
?>
        <main>
            <form action="api/add_note.php" method="post">
                <div id="editor"></div>
                <input name="note" id="note-input">
                <button type="submit">Add Note</button>
            </form>
            <ul class="notes-list">
                <?php foreach ($notes as $note): ?>
                    <li class="note-item">
                        <div class="note-content"><?php echo $note['note']; ?></div>
                        <small>By <?php echo $note['username']; ?> on <?php echo $note['created_at']; ?></small>
                        <?php if ($note['user_id'] == $_SESSION['id']): ?>
                            <div class="note-actions">
                                <a href="edit_note.php?id=<?php echo $note['id']; ?>">Edit</a>
                                <a href="delete_note.php?id=<?php echo $note['id']; ?>">Delete</a>
                            </div>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </main>
<?php include 'templates/footer.php'; ?>