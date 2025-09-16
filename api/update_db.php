<?php
require_once "config.php";

$sql = "ALTER TABLE users ADD partner_id INT(6) UNSIGNED NULL";

if (mysqli_query($link, $sql)) {
    echo "Table users altered successfully";
} else {
    echo "Error altering table: " . mysqli_error($link);
}

mysqli_close($link);
?>