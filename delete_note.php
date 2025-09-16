<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

require_once "api/config.php";

if (isset($_GET["id"]) && !empty($_GET["id"])) {
    $id = $_GET["id"];

    $sql = "DELETE FROM notes WHERE id = ? AND user_id = ?";

    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "ii", $id, $_SESSION["id"]);

        if (mysqli_stmt_execute($stmt)) {
            header("location: notes.php");
            exit();
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
    }

    mysqli_stmt_close($stmt);

    mysqli_close($link);
} else {
    header("location: notes.php");
    exit();
}
?>