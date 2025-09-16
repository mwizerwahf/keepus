<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../login.php");
    exit;
}

require_once "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION["id"];

    if (isset($_FILES["picture"]) && $_FILES["picture"]["error"] == 0) {
        $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png");
        $filename = $_FILES["picture"]["name"];
        $filetype = $_FILES["picture"]["type"];
        $filesize = $_FILES["picture"]["size"];

        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if (!array_key_exists($ext, $allowed)) {
            die("Error: Please select a valid file format.");
        }

        $maxsize = 5 * 1024 * 1024;
        if ($filesize > $maxsize) {
            die("Error: File size is larger than the allowed limit.");
        }

        if (in_array($filetype, $allowed)) {
            $new_filename = uniqid() . "." . $ext;
            $image_path = "uploads/images/" . $new_filename;

            if (move_uploaded_file($_FILES["picture"]["tmp_name"], $image_path)) {
                $sql = "INSERT INTO pictures (user_id, image_path) VALUES (?, ?)";
                if ($stmt = mysqli_prepare($link, $sql)) {
                    mysqli_stmt_bind_param($stmt, "is", $user_id, $image_path);
                    if (mysqli_stmt_execute($stmt)) {
                        header("location: ../pictures.php");
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