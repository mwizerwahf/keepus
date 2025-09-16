<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

require_once __DIR__ . "/../api/config.php";

$partner_name = "";
$sql = "SELECT p.username FROM users u JOIN users p ON u.partner_id = p.id WHERE u.id = ?";
if ($stmt = mysqli_prepare($link, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $_SESSION["id"]);
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_bind_result($stmt, $partner_name);
        mysqli_stmt_fetch($stmt);
    }
    mysqli_stmt_close($stmt);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IM App</title>
    <link rel="stylesheet" href="/IM/css/style.css">
    <link rel="stylesheet" href="/IM/css/pell.css">
</head>
<body>
    <header>
        <h1>Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welcome to IM App.</h1>
        <?php if ($partner_name): ?>
            <p>Your partner is <b><?php echo htmlspecialchars($partner_name); ?></b>.</p>
        <?php else: ?>
            <p>You don't have a partner yet.</p>
        <?php endif; ?>
        <a href="logout.php">Logout</a>
    </header>
    <nav>
        <div class="hamburger">
            <span></span>
            <span></span>
            <span></span>
        </div>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="games.php">Games</a></li>
            <li><a href="notes.php">Notes</a></li>
            <li><a href="pictures.php">Pictures</a></li>
            <li><a href="voice_notes.php">Voice Notes</a></li>
            <li><a href="links.php">Links</a></li>
        </ul>
    </nav>
    <div class="container">