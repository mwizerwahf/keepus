<?php
$servername = "localhost";
$username = "root";
$password = "";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database
$sql = "CREATE DATABASE IF NOT EXISTS im_app";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully";
} else {
    echo "Error creating database: " . $conn->error;
}

$conn->close();

// Create connection to the new database
$dbname = "im_app";
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// sql to create table
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(30) NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";

if ($conn->query($sql) === TRUE) {
    echo "Table users created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}

$sql = "CREATE TABLE IF NOT EXISTS games (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT
    )";

if ($conn->query($sql) === TRUE) {
    echo "Table games created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}

$sql = "CREATE TABLE IF NOT EXISTS notes (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT(6) UNSIGNED,
    note TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
    )";

if ($conn->query($sql) === TRUE) {
    echo "Table notes created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}

$sql = "CREATE TABLE IF NOT EXISTS pictures (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT(6) UNSIGNED,
    image_path VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
    )";

if ($conn->query($sql) === TRUE) {
    echo "Table pictures created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}

$sql = "CREATE TABLE IF NOT EXISTS voice_notes (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT(6) UNSIGNED,
    audio_path VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
    )";

if ($conn->query($sql) === TRUE) {
    echo "Table voice_notes created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}

$sql = "CREATE TABLE IF NOT EXISTS links (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT(6) UNSIGNED,
    url VARCHAR(255) NOT NULL,
    title VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
    )";

if ($conn->query($sql) === TRUE) {
    echo "Table links created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}

// Insert initial game data
$sql = "INSERT INTO games (name, description) VALUES
    ('20 Questions', 'One of you thinks of a person, place, or object. The other has up to 20 yes/no questions to figure it out.'),
    ('Would You Rather', 'Take turns asking “Would you rather…?” questions.'),
    ('This or That', 'Throw quick options at her, she has to pick instantly.'),
    ('Two Truths and a Lie', 'Each person says three statements about themselves (two true, one false). The other guesses which is the lie.')";

if ($conn->query($sql) === TRUE) {
    echo "New records created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>