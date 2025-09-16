<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../login.php");
    exit;
}

require_once "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION["id"];

    if (isset($_FILES["voice_note"]) && $_FILES["voice_note"]["error"] == 0) {
        $allowed = array("mp3" => "audio/mpeg", "wav" => "audio/wav", "ogg" => "audio/ogg");
        $filename = $_FILES["voice_note"]["name"];
        $filetype = $_FILES["voice_note"]["type"];
        $filesize = $_FILES["voice_note"]["size"];

        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if (!array_key_exists($ext, $allowed)) {
            die("Error: Please select a valid file format.");
        }

        $maxsize = 10 * 1024 * 1024;
        if ($filesize > $maxsize) {
            die("Error: File size is larger than the allowed limit.");
        }

        if (in_array($filetype, $allowed)) {
            $new_filename = uniqid() . "." . $ext;
            $audio_path = "uploads/audios/" . $new_filename;

            if (move_uploaded_file($_FILES["voice_note"]["tmp_name"], "../" . $audio_path)) {
                $sql = "INSERT INTO voice_notes (user_id, audio_path) VALUES (?, ?)";
                if ($stmt = mysqli_prepare($link, $sql)) {
                    mysqli_stmt_bind_param($stmt, "is", $user_id, $audio_path);
                    if (mysqli_stmt_execute($stmt)) {
                        header("location: ../voice_notes.php");
                    } else {
                        echo "Something went wrong. Please try again later.";
                    }
                    mysqli_stmt_close($stmt);
                }
            } else {
                echo "Error: There was a problem uploading your file. Please try again.";
            }
        }
    }
    mysqli_close($link);
}
?>