<?php
require_once "config.php";

$sql = "CREATE TABLE IF NOT EXISTS game_20_questions (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT(6) UNSIGNED NOT NULL,
    partner_id INT(6) UNSIGNED NOT NULL,
    secret_word VARCHAR(255) NOT NULL,
    questions JSON,
    guesses JSON,
    status VARCHAR(255) NOT NULL DEFAULT 'in_progress',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (partner_id) REFERENCES users(id)
)";

if (mysqli_query($link, $sql)) {
    echo "Table game_20_questions created successfully";
} else {
    echo "Error creating table: " . mysqli_error($link);
}

mysqli_close($link);
?>