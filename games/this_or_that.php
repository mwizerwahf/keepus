<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>This or That</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header>
        <h1>This or That</h1>
        <a href="../games.php">Back to Games</a>
    </header>
    <main>
        <p>Game logic will be implemented here.</p>
    </main>
</body>
</html>