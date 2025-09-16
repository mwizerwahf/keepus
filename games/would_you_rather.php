<?php
include '../templates/header.php';
require_once "../api/config.php";

$user_id = $_SESSION["id"];
$partner_id = null;

// Get partner ID
$sql = "SELECT partner_id FROM users WHERE id = ?";
if ($stmt = mysqli_prepare($link, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_bind_result($stmt, $partner_id);
        mysqli_stmt_fetch($stmt);
    }
    mysqli_stmt_close($stmt);
}

if (!$partner_id) {
    echo "<main><h2>You don't have a partner yet.</h2></main>";
    include '../templates/footer.php';
    exit;
}

// Check for active game
$active_game = null;
$sql = "SELECT * FROM game_would_you_rather WHERE ((user1_id = ? AND user2_id = ?) OR (user1_id = ? AND user2_id = ?)) AND status = 'in_progress'";
if ($stmt = mysqli_prepare($link, $sql)) {
    mysqli_stmt_bind_param($stmt, "iiii", $user_id, $partner_id, $partner_id, $user_id);
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        $active_game = mysqli_fetch_assoc($result);
    }
    mysqli_stmt_close($stmt);
}

// Get all questions
$all_questions = [];
$sql = "SELECT id, question_text FROM would_you_rather_questions";
$result = mysqli_query($link, $sql);
$all_questions = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Handle starting a new game
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["start_game"])) {
    if (!$active_game) {
        $sql = "INSERT INTO game_would_you_rather (user1_id, user2_id, user1_answers, user2_answers) VALUES (?, ?, ?, ?)";
        if ($stmt = mysqli_prepare($link, $sql)) {
            $empty_json = json_encode([]);
            mysqli_stmt_bind_param($stmt, "iiss", $user_id, $partner_id, $empty_json, $empty_json);
            if (mysqli_stmt_execute($stmt)) {
                header("location: would_you_rather.php");
            } else {
                echo "Something went wrong. Please try again later.";
            }
            mysqli_stmt_close($stmt);
        }
    }
}

// Handle answering a question
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["answer"])) {
    $answer = $_POST["answer"];
    $current_question_index = $active_game['current_question_index'];

    $user1_answers = json_decode($active_game['user1_answers'], true) ?? [];
    $user2_answers = json_decode($active_game['user2_answers'], true) ?? [];

    if ($active_game['user1_id'] == $user_id) {
        $user1_answers[$current_question_index] = $answer;
    } else {
        $user2_answers[$current_question_index] = $answer;
    }

    $user1_answers_json = json_encode($user1_answers);
    $user2_answers_json = json_encode($user2_answers);

    $sql = "UPDATE game_would_you_rather SET user1_answers = ?, user2_answers = ? WHERE id = ?";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "ssi", $user1_answers_json, $user2_answers_json, $active_game['id']);
        mysqli_stmt_execute($stmt);
        header("location: would_you_rather.php");
    }
}

// Handle moving to next question
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["next_question"])) {
    $current_question_index = $active_game['current_question_index'] + 1;
    $status = ($current_question_index >= count($all_questions)) ? 'completed' : 'in_progress';

    $sql = "UPDATE game_would_you_rather SET current_question_index = ?, status = ? WHERE id = ?";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "isi", $current_question_index, $status, $active_game['id']);
        mysqli_stmt_execute($stmt);
        header("location: would_you_rather.php");
    }
}

// Handle adding a new question
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["new_question_text"])) {
    $new_question_text = trim($_POST["new_question_text"]);
    if (!empty($new_question_text)) {
        $sql = "INSERT INTO would_you_rather_questions (question_text, created_by) VALUES (?, ?)";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "si", $new_question_text, $user_id);
            mysqli_stmt_execute($stmt);
            header("location: would_you_rather.php");
        }
    }
}

// Handle deleting game
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_game"])) {
    $sql = "DELETE FROM game_would_you_rather WHERE id = ?";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $active_game['id']);
        mysqli_stmt_execute($stmt);
        header("location: would_you_rather.php");
    }
}

?>

<main class="game-content">
    <h2>Would You Rather</h2>

    <?php if (!$active_game): ?>
        <div class="game-section">
            <h3>Start a new game</h3>
            <form action="would_you_rather.php" method="post" class="game-form">
                <button type="submit" name="start_game" class="game-button">Start Game</button>
            </form>
        </div>
    <?php elseif ($active_game['status'] == 'completed'): ?>
        <div class="game-section">
            <h3>Game Completed!</h3>
            <p>You have answered all questions.</p>
            <form action="would_you_rather.php" method="post">
                <button type="submit" name="delete_game" class="game-button">Play Again</button>
            </form>
        </div>
    <?php else: ?>
        <div class="game-section">
            <?php
            $current_question_index = $active_game['current_question_index'];
            $current_question = $all_questions[$current_question_index];
            $user1_answers = json_decode($active_game['user1_answers'], true) ?? [];
            $user2_answers = json_decode($active_game['user2_answers'], true) ?? [];

            $user_answered = (isset($user1_answers[$current_question_index]) && $active_game['user1_id'] == $user_id) ||
                             (isset($user2_answers[$current_question_index]) && $active_game['user2_id'] == $user_id);

            $partner_answered = (isset($user1_answers[$current_question_index]) && $active_game['user2_id'] == $user_id) ||
                                (isset($user2_answers[$current_question_index]) && $active_game['user1_id'] == $user_id);

            echo "<h3>Question " . ($current_question_index + 1) . "</h3>";
            echo "<p>" . $current_question['question_text'] . "</p>";

            if (!$user_answered) {
                echo "<form action='would_you_rather.php' method='post' class=\"game-form\">
                    <button type='submit' name='answer' value='option1' class=\"game-button\">Option 1</button>
                    <button type='submit' name='answer' value='option2' class=\"game-button\">Option 2</button>
                </form>";
            } else {
                echo "<p>You have answered this question.</p>";
                if ($user_answered && $partner_answered) {
                    echo "<h4>Your answers:</h4>";
                    echo "<p>You: " . ($active_game['user1_id'] == $user_id ? $user1_answers[$current_question_index] : $user2_answers[$current_question_index]) . "</p>";
                    echo "<p>Partner: " . ($active_game['user1_id'] == $user_id ? $user2_answers[$current_question_index] : $user1_answers[$current_question_index]) . "</p>";
                    echo "<form action='would_you_rather.php' method='post' class=\"game-form\">
                        <button type='submit' name='next_question' class=\"game-button\">Next Question</button>
                    </form>";
                } else {
                    echo "<p>Waiting for your partner to answer...</p>";
                }
            }
            ?>
        </div>
    <?php endif; ?>

    <div class="game-section">
        <h3>Add a new question</h3>
        <form action="would_you_rather.php" method="post" class="game-form">
            <textarea name="new_question_text" placeholder="Enter a new 'Would You Rather' question"></textarea>
            <button type="submit" class="game-button">Add Question</button>
        </form>
    </div>

</main>

<?php include '../templates/footer.php'; ?>