<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../login.php");
    exit;
}

require_once "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $note = trim($_POST["note"]);
    $user_id = $_SESSION["id"];

    if (!empty($note)) {
        $sql = "INSERT INTO notes (user_id, note) VALUES (?, ?)";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "is", $user_id, $note);
            if (mysqli_stmt_execute($stmt)) {
                header("location: ../notes.php");
            } else {
                echo "Something went wrong. Please try again later.";
            }
            mysqli_stmt_close($stmt);
        }
    }
    mysqli_close($link);
}
?>