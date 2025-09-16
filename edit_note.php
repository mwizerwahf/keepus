<?php
include 'templates/header.php';
require_once "api/config.php";

$note = "";
$note_err = "";

if (isset($_GET["id"]) && !empty($_GET["id"])) {
    $id = $_GET["id"];

    $sql = "SELECT note FROM notes WHERE id = ? AND user_id = ?";

    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "ii", $id, $_SESSION["id"]);

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_bind_result($stmt, $note);
            mysqli_stmt_fetch($stmt);
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
        mysqli_stmt_close($stmt);
    }
} else {
    header("location: notes.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty(trim($_POST["note"]))) {
        $note_err = "Please enter a note.";
    } else {
        $note = trim($_POST["note"]);
    }

    if (empty($note_err)) {
        $sql = "UPDATE notes SET note = ? WHERE id = ? AND user_id = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "sii", $note, $id, $_SESSION["id"]);

            if (mysqli_stmt_execute($stmt)) {
                header("location: notes.php");
                exit();
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
            mysqli_stmt_close($stmt);
        }
    }

    mysqli_close($link);
}

?>

<main>
    <h2>Edit Note</h2>
    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
        <div id="editor"><?php echo $note; ?></div>
        <input type="hidden" name="note" id="note-input">
        <span class="help-block"><?php echo $note_err; ?></span>
        <input type="submit" class="btn btn-primary" value="Submit">
        <a href="notes.php" class="btn btn-default">Cancel</a>
    </form>
</main>

<script src="js/pell.min.js"></script>
<script>
    const editor = pell.init({
        element: document.getElementById('editor'),
        onChange: html => {
            document.getElementById('note-input').value = html;
        },
        defaultParagraphSeparator: 'p',
        styleWithCSS: false,
        actions: [
            'bold',
            'italic',
            'underline',
            'strikethrough',
            'heading1',
            'heading2',
            'paragraph',
            'quote',
            'olist',
            'ulist',
            'code',
            'line',
            'link'
        ],
    });
    editor.content.innerHTML = '<?php echo $note; ?>';
</script>

<?php include 'templates/footer.php'; ?>