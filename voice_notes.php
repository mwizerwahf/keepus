<?php include 'templates/header.php'; ?>
<?php
require_once "api/config.php";

$sql = "SELECT v.audio_path, u.username, v.created_at FROM voice_notes v JOIN users u ON v.user_id = u.id WHERE u.id = ? OR u.id = (SELECT partner_id FROM users WHERE id = ?) ORDER BY v.created_at DESC";
if ($stmt = mysqli_prepare($link, $sql)) {
    mysqli_stmt_bind_param($stmt, "ii", $_SESSION["id"], $_SESSION["id"]);
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        $voice_notes = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    mysqli_stmt_close($stmt);
}

mysqli_close($link);
?>
        <main>
            <div class="recorder-container">
                <h2>Record a new voice note</h2>
                <button id="record-button">Record</button>
                <button id="stop-button" disabled>Stop</button>
                <audio id="audio-player" controls></audio>
                <a id="download-link" style="display: none;">Download</a>
                <form id="upload-form" action="api/upload_voice_note.php" method="post" enctype="multipart/form-data" style="display: none;">
                    <input type="file" name="voice_note" id="voice-note-input">
                    <button type="submit">Upload Voice Note</button>
                </form>
            </div>
            <div class="voice-notes-list">
                <?php foreach ($voice_notes as $voice_note): ?>
                    <div class="voice-note-item">
                        <audio controls>
                            <source src="<?php echo $voice_note['audio_path']; ?>" type="audio/mpeg">
                            Your browser does not support the audio element.
                        </audio>
                        <small>By <?php echo $voice_note['username']; ?> on <?php echo $voice_note['created_at']; ?></small>
                    </div>
                <?php endforeach; ?>
            </div>
        </main>
<?php include 'templates/footer.php'; ?>