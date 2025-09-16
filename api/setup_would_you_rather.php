<?php
require_once "config.php";

// Create game_would_you_rather table
$sql = "CREATE TABLE IF NOT EXISTS game_would_you_rather (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user1_id INT(6) UNSIGNED NOT NULL,
    user2_id INT(6) UNSIGNED NOT NULL,
    current_question_index INT(6) DEFAULT 0,
    user1_answers JSON,
    user2_answers JSON,
    status VARCHAR(255) NOT NULL DEFAULT 'in_progress',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user1_id) REFERENCES users(id),
    FOREIGN KEY (user2_id) REFERENCES users(id)
)";

if (mysqli_query($link, $sql)) {
    echo "Table game_would_you_rather created successfully\n";
} else {
    echo "Error creating table: " . mysqli_error($link) . "\n";
}

// Create would_you_rather_questions table
$sql = "CREATE TABLE IF NOT EXISTS would_you_rather_questions (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    question_text TEXT NOT NULL,
    created_by INT(6) UNSIGNED NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id)
)";

if (mysqli_query($link, $sql)) {
    echo "Table would_you_rather_questions created successfully\n";
} else {
    echo "Error creating table: " . mysqli_error($link) . "\n";
}

// Insert predefined questions
$questions = [
    "Would you rather always be honest, even if it hurts, or always be kind, even if it’s a lie?",
    "Would you rather people respect you or love you?",
    "Would you rather be liked by everyone but feel fake, or be disliked by many but feel authentic?",
    "Would you rather never feel fear or never feel guilt?",
    "Would you rather know exactly who you are or always be discovering yourself?",
    "Would you rather be more logical or more emotional?",
    "Would you rather be judged for your worst mistake or never truly be known?",
    "Would you rather forgive easily or never be hurt deeply?",
    "Would you rather keep your emotions private or always express them openly?",
    "Would you rather always feel in control or always feel at peace?",
    "Would you rather be loved deeply but misunderstood, or understood fully but not loved deeply?",
    "Would you rather your partner never lies to you or always makes you feel safe?",
    "Would you rather fight often but grow stronger, or never fight but stay shallow?",
    "Would you rather be with someone who makes you laugh or someone who makes you feel secure?",
    "Would you rather fall in love fast or fall in love slowly?",
    "Would you rather know your partner’s every secret or have them know all of yours?",
    "Would you rather be someone’s first love or their last love?",
    "Would you rather be admired from afar or cherished closely?",
    "Would you rather be loved more than you love, or love more than you’re loved?",
    "Would you rather spend a lifetime with one person or love many but never stay?",
    "Would you rather forget your worst memory or remember it but not feel pain from it?",
    "Would you rather control your thoughts or control your feelings?",
    "Would you rather always know what people think of you or never care?",
    "Would you rather live with constant anxiety or constant boredom?",
    "Would you rather overthink everything or feel nothing deeply?",
    "Would you rather be emotionally intelligent or intellectually brilliant?",
    "Would you rather relive your happiest memory or erase your saddest?",
    "Would you rather heal quickly from pain or never be hurt in the first place?",
    "Would you rather be vulnerable and risk heartbreak or guarded and never connect deeply?",
    "Would you rather never feel jealousy or never feel loneliness?",
    "Would you rather live a life of passion or a life of stability?",
    "Would you rather always chase dreams or always settle for comfort?",
    "Would you rather know your purpose but never achieve it, or achieve success without purpose?",
    "Would you rather make a big impact but be forgotten quickly, or a small impact but remembered forever?",
    "Would you rather die peacefully but young, or live long with struggles?",
    "Would you rather live a life of constant adventure or constant peace?",
    "Would you rather always give to others or always receive from others?",
    "Would you rather live in the past or live in the future?",
    "Would you rather be remembered as kind or as strong?",
    "Would you rather live one amazing day or 10,000 average ones?",
    "Would you rather always talk or always listen in a relationship?",
    "Would you rather know your partner’s deepest fear or deepest desire?",
    "Would you rather have a partner who surprises you or one who’s always predictable?",
    "Would you rather share silence comfortably or share endless conversations?",
    "Would you rather be physically close or emotionally close?",
    "Would you rather always support your partner or always be supported by them?",
    "Would you rather grow together through hardship or always live in comfort but never grow?",
    "Would you rather have constant butterflies or steady warmth in love?",
    "Would you rather your partner understands your words or your unspoken feelings?",
    "Would you rather always know your partner’s mood or have them always know yours?",
    "Would you rather achieve all your dreams but feel lonely, or never achieve them but feel loved?",
    "Would you rather risk failure chasing greatness, or stay safe and small?",
    "Would you rather live for yourself or for others?",
    "Would you rather succeed alone or struggle together?",
    "Would you rather never give up or know exactly when to quit?",
    "Would you rather inspire others but feel unfulfilled, or be fulfilled but never inspire?",
    "Would you rather be famous but insecure, or unknown but confident?",
    "Would you rather risk heartbreak for love, or risk regret for safety?",
    "Would you rather chase passion with no money or money with no passion?",
    "Would you rather always strive or always be content?",
    "Would you rather be feared or forgotten?",
    "Would you rather face rejection or never take risks?",
    "Would you rather lose trust or lose love?",
    "Would you rather always fear abandonment or always fear betrayal?",
    "Would you rather be too sensitive or completely numb?",
    "Would you rather cry openly or never cry at all?",
    "Would you rather fail publicly or suffer silently?",
    "Would you rather be emotionally dependent or emotionally isolated?",
    "Would you rather always forgive but never forget, or forget but never forgive?",
    "Would you rather face heartbreak once or small disappointments forever?",
    "Would you rather always feel joy but never excitement, or always excitement but never peace?",
    "Would you rather laugh daily but never cry, or cry deeply but never laugh?",
    "Would you rather always chase happiness or always appreciate what you have?",
    "Would you rather be content alone or dependent on love for happiness?",
    "Would you rather make yourself happy or make others happy?",
    "Would you rather live without stress or live without sadness?",
    "Would you rather be emotionally stable or emotionally passionate?",
    "Would you rather be happy but unknown, or miserable but famous?",
    "Would you rather always feel safe or always feel free?",
    "Would you rather have peace of mind or thrill of life?",
    "Would you rather keep a painful secret or reveal it and hurt someone?",
    "Would you rather know the truth even if it breaks you, or stay ignorant and happy?",
    "Would you rather always trust too much or never trust enough?",
    "Would you rather your partner be open but blunt, or gentle but secretive?",
    "Would you rather hide your feelings or confess them and risk rejection?",
    "Would you rather have your secrets revealed or your lies discovered?",
    "Would you rather always be vulnerable or always be guarded?",
    "Would you rather know everything about others or nothing at all?",
    "Would you rather trust one person fully or trust many halfway?",
    "Would you rather betray someone or be betrayed?",
    "Would you rather find out your biggest strength or your biggest weakness?",
    "Would you rather face your past or your future?",
    "Would you rather live knowing all your choices or never knowing what could’ve been?",
    "Would you rather be feared for your power or loved for your kindness?",
    "Would you rather feel too much or feel nothing at all?",
    "Would you rather never be wrong or never doubt yourself?",
    "Would you rather know the meaning of life or create your own meaning?",
    "Would you rather sacrifice yourself for love or sacrifice love for yourself?",
    "Would you rather face the truth of who you are or live happily in denial?",
    "Would you rather live a long life without passion or a short life full of it?"
];

foreach ($questions as $question_text) {
    $sql = "INSERT INTO would_you_rather_questions (question_text) VALUES (?)";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "s", $question_text);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

mysqli_close($link);
?>
