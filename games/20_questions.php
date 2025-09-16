<?php
include '../templates/header.php';


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
    echo "<main><h2>You don\'t have a partner yet.</h2></main>";
    include '../templates/footer.php';
    exit;
}

// Check for active game
$active_game = null;
$sql = "SELECT * FROM game_20_questions WHERE ((user_id = ? AND partner_id = ?) OR (user_id = ? AND partner_id = ?)) AND status = 'in_progress'";
if ($stmt = mysqli_prepare($link, $sql)) {
    mysqli_stmt_bind_param($stmt, "iiii", $user_id, $partner_id, $partner_id, $user_id);
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        $active_game = mysqli_fetch_assoc($result);
    }
    mysqli_stmt_close($stmt);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["secret_word"])) {
    $secret_word = trim($_POST["secret_word"]);

    if (!empty($secret_word)) {
        $sql = "INSERT INTO game_20_questions (user_id, partner_id, secret_word) VALUES (?, ?, ?)";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "iis", $user_id, $partner_id, $secret_word);
            if (mysqli_stmt_execute($stmt)) {
                header("location: 20_questions.php");
            } else {
                echo "Something went wrong. Please try again later.";
            }
            mysqli_stmt_close($stmt);
        }
    }
}

?>

<main>
    <h2>20 Questions</h2>

    <?php if (!$active_game): ?>
        <h3>Start a new game</h3>
        <form action="20_questions.php" method="post">
            <input type="text" name="secret_word" placeholder="Enter a secret word">
            <button type="submit">Start Game</button>
        </form>
    <?php else: ?>
        <h3>Game in progress...</h3>
        
    <h2>20 Questions</h2>

    <?php if (!$active_game): ?>
        <div class="game-section">
            <h3>Start a new game</h3>
            <form action="20_questions.php" method="post" class="game-form">
                <input type="text" name="secret_word" placeholder="Enter a secret word" class="game-input">
                <button type="submit" class="game-button">Start Game</button>
            </form>
        </div>
    <?php else: ?>
        <div class="game-section">
            <h3>Game in progress...</h3>
            <?php
            $questions = json_decode($active_game['questions'], true) ?? [];
            $guesses = json_decode($active_game['guesses'], true) ?? [];

            if ($active_game['user_id'] == $user_id) { // User is the one who set the word
                echo "<p>Your partner is guessing the word: <b>{$active_game['secret_word']}</b></p>";

                if (count($questions) > 0) {
                    echo "<h4>Questions asked:</h4>";
                    echo "<ul class=\"question-list\">";
                    foreach ($questions as $question) {
                        echo "<li>{$question['text']} - {$question['answer']}</li>";
                    }
                    echo "</ul>";
                }

                // Handle answering questions
                if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["answer"])) {
                    $question_index = $_POST["question_index"];
                    $answer = $_POST["answer"];
                    $questions[$question_index]['answer'] = $answer;
                    $sql = "UPDATE game_20_questions SET questions = ? WHERE id = ?";
                    if ($stmt = mysqli_prepare($link, $sql)) {
                        $questions_json = json_encode($questions);
                        mysqli_stmt_bind_param($stmt, "si", $questions_json, $active_game['id']);
                        mysqli_stmt_execute($stmt);
                        header("location: 20_questions.php");
                    }
                }

                // Display unanswered questions
                foreach ($questions as $index => $question) {
                    if ($question['answer'] == 'unanswered') {
                        echo "<div class=\"question-item\"><p><b>Question:</b> {$question['text']} </p>
                        <form action='20_questions.php' method='post' class=\"answer-form\">
                            <input type='hidden' name='question_index' value='$index'>
                            <button type='submit' name='answer' value='yes' class=\"game-button\">Yes</button>
                            <button type='submit' name='answer' value='no' class=\"game-button\">No</button>
                        </form></div>";
                    }
                }

            } else { // User is the one guessing
                echo "<p>You have to guess the secret word.</p>";

                if (count($questions) > 0) {
                    echo "<h4>Questions asked:</h4>";
                    echo "<ul class=\"question-list\">";
                    foreach ($questions as $question) {
                        echo "<li>{$question['text']} - {$question['answer']}</li>";
                    }
                    echo "</ul>";
                }

                if (count($guesses) > 0) {
                    echo "<h4>Guesses made:</h4>";
                    echo "<ul class=\"guess-list\">";
                    foreach ($guesses as $guess) {
                        echo "<li>{$guess}</li>";
                    }
                    echo "</ul>";
                }

                // Handle asking a new question
                if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["question"])) {
                    $new_question = trim($_POST["question"]);
                    if (!empty($new_question)) {
                        $questions[] = ['text' => $new_question, 'answer' => 'unanswered'];
                        $sql = "UPDATE game_20_questions SET questions = ? WHERE id = ?";
                        if ($stmt = mysqli_prepare($link, $sql)) {
                            $questions_json = json_encode($questions);
                            mysqli_stmt_bind_param($stmt, "si", $questions_json, $active_game['id']);
                            mysqli_stmt_execute($stmt);
                            header("location: 20_questions.php");
                        }
                    }
                }

                // Handle making a guess
                if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["guess"])) {
                    $new_guess = trim($_POST["guess"]);
                    if (!empty($new_guess)) {
                        $guesses[] = $new_guess;
                        if (strtolower($new_guess) == strtolower($active_game['secret_word'])) {
                            $sql = "UPDATE game_20_questions SET guesses = ?, status = 'won' WHERE id = ?";
                        } else {
                            $sql = "UPDATE game_20_questions SET guesses = ? WHERE id = ?";
                        }
                        if ($stmt = mysqli_prepare($link, $sql)) {
                            $guesses_json = json_encode($guesses);
                            mysqli_stmt_bind_param($stmt, "si", $guesses_json, $active_game['id']);
                            mysqli_stmt_execute($stmt);
                            header("location: 20_questions.php");
                        }
                    }
                }

                if ($active_game['status'] == 'in_progress') {
                    echo "<form action='20_questions.php' method='post' class=\"game-form\">
                        <input type='text' name='question' placeholder='Ask a yes/no question' class=\"game-input\">
                        <button type='submit' class=\"game-button\">Ask</button>
                    </form>
                    <form action='20_questions.php' method='post' class=\"game-form\">
                        <input type='text' name='guess' placeholder='Make a guess' class=\"game-input\">
                        <button type='submit' class=\"game-button\">Guess</button>
                    </form>";
                }
            }

            if ($active_game['status'] == 'won') {
                echo "<h3>Congratulations! You guessed the word: {$active_game['secret_word']}</h3>";
                echo "<form action='20_questions.php' method='post'><button type='submit' name='delete_game' class=\"game-button\">Play Again</button></form>";
            } elseif ($active_game['status'] == 'lost') {
                echo "<h3>Game over! You couldn't guess the word.</h3>";
                echo "<form action='20_questions.php' method='post'><button type='submit' name='delete_game' class=\"game-button\">Play Again</button></form>";
            }

            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_game"])) {
                $sql = "DELETE FROM game_20_questions WHERE id = ?";
                if ($stmt = mysqli_prepare($link, $sql)) {
                    mysqli_stmt_bind_param($stmt, "i", $active_game['id']);
                    mysqli_stmt_execute($stmt);
                    header("location: 20_questions.php");
                }
            }
            ?>
        </div>
    <?php endif; ?>

</main>

<?php include '../templates/footer.php'; ?>