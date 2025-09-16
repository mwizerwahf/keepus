<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../login.php");
    exit;
}

require_once "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $url = trim($_POST["url"]);
    $title = trim($_POST["title"]);
    $user_id = $_SESSION["id"];

    if (!empty($url)) {
        $sql = "INSERT INTO links (user_id, url, title) VALUES (?, ?, ?)";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "iss", $user_id, $url, $title);
            if (mysqli_stmt_execute($stmt)) {
                header("location: ../links.php");
            } else {
                echo "Something went wrong. Please try again later.";
            }
            mysqli_stmt_close($stmt);
        }
    }
    mysqli_close($link);
}
?>