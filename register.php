<?php
include "api/config.php";

$username = $password = "";
$username_err = $password_err = "";
$partner_id = null;

$sql = "SELECT id, username FROM users WHERE partner_id IS NULL";
$result = mysqli_query($link, $sql);
$users = mysqli_fetch_all($result, MYSQLI_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter a username.";
    } else {
        $sql = "SELECT id FROM users WHERE username = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            $param_username = trim($_POST["username"]);
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    $username_err = "This username is already taken.";
                } else {
                    $username = trim($_POST["username"]);
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
            mysqli_stmt_close($stmt);
        }
    }

    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "Password must have at least 6 characters.";
    } else {
        $password = trim($_POST["password"]);
    }

    if (isset($_POST["partner_id"])) {
        $partner_id = $_POST["partner_id"];
    }

    if (empty($username_err) && empty($password_err)) {
        $sql = "INSERT INTO users (username, password, partner_id) VALUES (?, ?, ?)";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "ssi", $param_username, $param_password, $param_partner_id);
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT);
            $param_partner_id = $partner_id;
            if (mysqli_stmt_execute($stmt)) {
                $new_user_id = mysqli_insert_id($link);
                if ($partner_id) {
                    $sql_update = "UPDATE users SET partner_id = ? WHERE id = ?";
                    if ($stmt_update = mysqli_prepare($link, $sql_update)) {
                        mysqli_stmt_bind_param($stmt_update, "ii", $new_user_id, $partner_id);
                        mysqli_stmt_execute($stmt_update);
                        mysqli_stmt_close($stmt_update);
                    }
                }
                header("location: login.php");
            } else {
                echo "Something went wrong. Please try again later.";
            }
            mysqli_stmt_close($stmt);
        }
    }
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="wrapper">
        <h2>Sign Up</h2>
        <p>Please fill this form to create an account.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <label>Partner</label>
                <select name="partner_id" class="form-control">
                    <option value="">Select a partner (optional)</option>
                    <?php foreach ($users as $user): ?>
                        <option value="<?php echo $user['id']; ?>"><?php echo $user['username']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-default" value="Reset">
            </div>
            <p>Already have an account? <a href="login.php">Login here</a>.</p>
        </form>
    </div>
</body>
</html>