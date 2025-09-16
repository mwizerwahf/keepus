<?php include 'templates/header.php'; ?>
<?php
require_once "api/config.php";

$sql = "SELECT id, name, description FROM games";
$result = mysqli_query($link, $sql);
$games = mysqli_fetch_all($result, MYSQLI_ASSOC);

mysqli_close($link);
?>
        <main>
            <ul class="game-list">
                <?php foreach ($games as $game): ?>
                    <li>
                        <h2><a href="games/<?php echo str_replace(' ', '_', strtolower($game['name'])); ?>.php"><?php echo $game['name']; ?></a></h2>
                        <p><?php echo $game['description']; ?></p>
                    </li>
                <?php endforeach; ?>
            </ul>
        </main>
<?php include 'templates/footer.php'; ?>