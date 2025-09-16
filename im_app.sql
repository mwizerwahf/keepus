-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 16, 2025 at 05:43 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `im_app`
--

-- --------------------------------------------------------

--
-- Table structure for table `games`
--

CREATE TABLE `games` (
  `id` int(6) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `games`
--

INSERT INTO `games` (`id`, `name`, `description`) VALUES
(1, '20 Questions', 'One of you thinks of a person, place, or object. The other has up to 20 yes/no questions to figure it out.'),
(2, 'Would You Rather', 'Take turns asking â€œWould you ratherâ€¦?â€ questions.'),
(3, 'This or That', 'Throw quick options at her, she has to pick instantly.'),
(4, 'Two Truths and a Lie', 'Each person says three statements about themselves (two true, one false). The other guesses which is the lie.');

-- --------------------------------------------------------

--
-- Table structure for table `game_20_questions`
--

CREATE TABLE `game_20_questions` (
  `id` int(6) UNSIGNED NOT NULL,
  `user_id` int(6) UNSIGNED NOT NULL,
  `partner_id` int(6) UNSIGNED NOT NULL,
  `secret_word` varchar(255) NOT NULL,
  `questions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`questions`)),
  `guesses` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`guesses`)),
  `status` varchar(255) NOT NULL DEFAULT 'in_progress',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `game_would_you_rather`
--

CREATE TABLE `game_would_you_rather` (
  `id` int(6) UNSIGNED NOT NULL,
  `user1_id` int(6) UNSIGNED NOT NULL,
  `user2_id` int(6) UNSIGNED NOT NULL,
  `current_question_index` int(6) DEFAULT 0,
  `user1_answers` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`user1_answers`)),
  `user2_answers` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`user2_answers`)),
  `status` varchar(255) NOT NULL DEFAULT 'in_progress',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `game_would_you_rather`
--

INSERT INTO `game_would_you_rather` (`id`, `user1_id`, `user2_id`, `current_question_index`, `user1_answers`, `user2_answers`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 0, '[\"option1\"]', '[]', 'in_progress', '2025-09-16 14:52:37', '2025-09-16 14:52:58');

-- --------------------------------------------------------

--
-- Table structure for table `links`
--

CREATE TABLE `links` (
  `id` int(6) UNSIGNED NOT NULL,
  `user_id` int(6) UNSIGNED DEFAULT NULL,
  `url` varchar(255) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notes`
--

CREATE TABLE `notes` (
  `id` int(6) UNSIGNED NOT NULL,
  `user_id` int(6) UNSIGNED DEFAULT NULL,
  `note` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pictures`
--

CREATE TABLE `pictures` (
  `id` int(6) UNSIGNED NOT NULL,
  `user_id` int(6) UNSIGNED DEFAULT NULL,
  `image_path` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(6) UNSIGNED NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `partner_id` int(6) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `created_at`, `partner_id`) VALUES
(1, 'admin', '$2y$10$aOuTDBw93eg/CP93YYOlj.tN5UgB8DfA6F0MUFU84oonvVKp9A0Mi', '2025-09-16 14:49:11', 2),
(2, 'user', '$2y$10$z9rDSfh0fgMQiKxdV8wZ0OU13N57MgfviNUWx5r.luP8LUOE8kLSS', '2025-09-16 14:49:11', 1);

-- --------------------------------------------------------

--
-- Table structure for table `voice_notes`
--

CREATE TABLE `voice_notes` (
  `id` int(6) UNSIGNED NOT NULL,
  `user_id` int(6) UNSIGNED DEFAULT NULL,
  `audio_path` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `would_you_rather_questions`
--

CREATE TABLE `would_you_rather_questions` (
  `id` int(6) UNSIGNED NOT NULL,
  `question_text` text NOT NULL,
  `created_by` int(6) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `would_you_rather_questions`
--

INSERT INTO `would_you_rather_questions` (`id`, `question_text`, `created_by`, `created_at`) VALUES
(1, 'Would you rather always be honest, even if it hurts, or always be kind, even if itâ€™s a lie?', NULL, '2025-09-16 14:42:25'),
(2, 'Would you rather people respect you or love you?', NULL, '2025-09-16 14:42:25'),
(3, 'Would you rather be liked by everyone but feel fake, or be disliked by many but feel authentic?', NULL, '2025-09-16 14:42:25'),
(4, 'Would you rather never feel fear or never feel guilt?', NULL, '2025-09-16 14:42:26'),
(5, 'Would you rather know exactly who you are or always be discovering yourself?', NULL, '2025-09-16 14:42:26'),
(6, 'Would you rather be more logical or more emotional?', NULL, '2025-09-16 14:42:26'),
(7, 'Would you rather be judged for your worst mistake or never truly be known?', NULL, '2025-09-16 14:42:26'),
(8, 'Would you rather forgive easily or never be hurt deeply?', NULL, '2025-09-16 14:42:26'),
(9, 'Would you rather keep your emotions private or always express them openly?', NULL, '2025-09-16 14:42:26'),
(10, 'Would you rather always feel in control or always feel at peace?', NULL, '2025-09-16 14:42:26'),
(11, 'Would you rather be loved deeply but misunderstood, or understood fully but not loved deeply?', NULL, '2025-09-16 14:42:26'),
(12, 'Would you rather your partner never lies to you or always makes you feel safe?', NULL, '2025-09-16 14:42:26'),
(13, 'Would you rather fight often but grow stronger, or never fight but stay shallow?', NULL, '2025-09-16 14:42:26'),
(14, 'Would you rather be with someone who makes you laugh or someone who makes you feel secure?', NULL, '2025-09-16 14:42:26'),
(15, 'Would you rather fall in love fast or fall in love slowly?', NULL, '2025-09-16 14:42:26'),
(16, 'Would you rather know your partnerâ€™s every secret or have them know all of yours?', NULL, '2025-09-16 14:42:26'),
(17, 'Would you rather be someoneâ€™s first love or their last love?', NULL, '2025-09-16 14:42:26'),
(18, 'Would you rather be admired from afar or cherished closely?', NULL, '2025-09-16 14:42:26'),
(19, 'Would you rather be loved more than you love, or love more than youâ€™re loved?', NULL, '2025-09-16 14:42:26'),
(20, 'Would you rather spend a lifetime with one person or love many but never stay?', NULL, '2025-09-16 14:42:26'),
(21, 'Would you rather forget your worst memory or remember it but not feel pain from it?', NULL, '2025-09-16 14:42:26'),
(22, 'Would you rather control your thoughts or control your feelings?', NULL, '2025-09-16 14:42:26'),
(23, 'Would you rather always know what people think of you or never care?', NULL, '2025-09-16 14:42:26'),
(24, 'Would you rather live with constant anxiety or constant boredom?', NULL, '2025-09-16 14:42:26'),
(25, 'Would you rather overthink everything or feel nothing deeply?', NULL, '2025-09-16 14:42:26'),
(26, 'Would you rather be emotionally intelligent or intellectually brilliant?', NULL, '2025-09-16 14:42:26'),
(27, 'Would you rather relive your happiest memory or erase your saddest?', NULL, '2025-09-16 14:42:27'),
(28, 'Would you rather heal quickly from pain or never be hurt in the first place?', NULL, '2025-09-16 14:42:27'),
(29, 'Would you rather be vulnerable and risk heartbreak or guarded and never connect deeply?', NULL, '2025-09-16 14:42:27'),
(30, 'Would you rather never feel jealousy or never feel loneliness?', NULL, '2025-09-16 14:42:27'),
(31, 'Would you rather live a life of passion or a life of stability?', NULL, '2025-09-16 14:42:27'),
(32, 'Would you rather always chase dreams or always settle for comfort?', NULL, '2025-09-16 14:42:27'),
(33, 'Would you rather know your purpose but never achieve it, or achieve success without purpose?', NULL, '2025-09-16 14:42:27'),
(34, 'Would you rather make a big impact but be forgotten quickly, or a small impact but remembered forever?', NULL, '2025-09-16 14:42:27'),
(35, 'Would you rather die peacefully but young, or live long with struggles?', NULL, '2025-09-16 14:42:27'),
(36, 'Would you rather live a life of constant adventure or constant peace?', NULL, '2025-09-16 14:42:27'),
(37, 'Would you rather always give to others or always receive from others?', NULL, '2025-09-16 14:42:27'),
(38, 'Would you rather live in the past or live in the future?', NULL, '2025-09-16 14:42:27'),
(39, 'Would you rather be remembered as kind or as strong?', NULL, '2025-09-16 14:42:27'),
(40, 'Would you rather live one amazing day or 10,000 average ones?', NULL, '2025-09-16 14:42:27'),
(41, 'Would you rather always talk or always listen in a relationship?', NULL, '2025-09-16 14:42:27'),
(42, 'Would you rather know your partnerâ€™s deepest fear or deepest desire?', NULL, '2025-09-16 14:42:27'),
(43, 'Would you rather have a partner who surprises you or one whoâ€™s always predictable?', NULL, '2025-09-16 14:42:27'),
(44, 'Would you rather share silence comfortably or share endless conversations?', NULL, '2025-09-16 14:42:27'),
(45, 'Would you rather be physically close or emotionally close?', NULL, '2025-09-16 14:42:27'),
(46, 'Would you rather always support your partner or always be supported by them?', NULL, '2025-09-16 14:42:27'),
(47, 'Would you rather grow together through hardship or always live in comfort but never grow?', NULL, '2025-09-16 14:42:27'),
(48, 'Would you rather have constant butterflies or steady warmth in love?', NULL, '2025-09-16 14:42:27'),
(49, 'Would you rather your partner understands your words or your unspoken feelings?', NULL, '2025-09-16 14:42:27'),
(50, 'Would you rather always know your partnerâ€™s mood or have them always know yours?', NULL, '2025-09-16 14:42:27'),
(51, 'Would you rather achieve all your dreams but feel lonely, or never achieve them but feel loved?', NULL, '2025-09-16 14:42:27'),
(52, 'Would you rather risk failure chasing greatness, or stay safe and small?', NULL, '2025-09-16 14:42:27'),
(53, 'Would you rather live for yourself or for others?', NULL, '2025-09-16 14:42:27'),
(54, 'Would you rather succeed alone or struggle together?', NULL, '2025-09-16 14:42:27'),
(55, 'Would you rather never give up or know exactly when to quit?', NULL, '2025-09-16 14:42:27'),
(56, 'Would you rather inspire others but feel unfulfilled, or be fulfilled but never inspire?', NULL, '2025-09-16 14:42:27'),
(57, 'Would you rather be famous but insecure, or unknown but confident?', NULL, '2025-09-16 14:42:27'),
(58, 'Would you rather risk heartbreak for love, or risk regret for safety?', NULL, '2025-09-16 14:42:28'),
(59, 'Would you rather chase passion with no money or money with no passion?', NULL, '2025-09-16 14:42:28'),
(60, 'Would you rather always strive or always be content?', NULL, '2025-09-16 14:42:28'),
(61, 'Would you rather be feared or forgotten?', NULL, '2025-09-16 14:42:28'),
(62, 'Would you rather face rejection or never take risks?', NULL, '2025-09-16 14:42:28'),
(63, 'Would you rather lose trust or lose love?', NULL, '2025-09-16 14:42:28'),
(64, 'Would you rather always fear abandonment or always fear betrayal?', NULL, '2025-09-16 14:42:28'),
(65, 'Would you rather be too sensitive or completely numb?', NULL, '2025-09-16 14:42:28'),
(66, 'Would you rather cry openly or never cry at all?', NULL, '2025-09-16 14:42:28'),
(67, 'Would you rather fail publicly or suffer silently?', NULL, '2025-09-16 14:42:28'),
(68, 'Would you rather be emotionally dependent or emotionally isolated?', NULL, '2025-09-16 14:42:28'),
(69, 'Would you rather always forgive but never forget, or forget but never forgive?', NULL, '2025-09-16 14:42:28'),
(70, 'Would you rather face heartbreak once or small disappointments forever?', NULL, '2025-09-16 14:42:28'),
(71, 'Would you rather always feel joy but never excitement, or always excitement but never peace?', NULL, '2025-09-16 14:42:28'),
(72, 'Would you rather laugh daily but never cry, or cry deeply but never laugh?', NULL, '2025-09-16 14:42:28'),
(73, 'Would you rather always chase happiness or always appreciate what you have?', NULL, '2025-09-16 14:42:28'),
(74, 'Would you rather be content alone or dependent on love for happiness?', NULL, '2025-09-16 14:42:28'),
(75, 'Would you rather make yourself happy or make others happy?', NULL, '2025-09-16 14:42:28'),
(76, 'Would you rather live without stress or live without sadness?', NULL, '2025-09-16 14:42:28'),
(77, 'Would you rather be emotionally stable or emotionally passionate?', NULL, '2025-09-16 14:42:28'),
(78, 'Would you rather be happy but unknown, or miserable but famous?', NULL, '2025-09-16 14:42:28'),
(79, 'Would you rather always feel safe or always feel free?', NULL, '2025-09-16 14:42:28'),
(80, 'Would you rather have peace of mind or thrill of life?', NULL, '2025-09-16 14:42:28'),
(81, 'Would you rather keep a painful secret or reveal it and hurt someone?', NULL, '2025-09-16 14:42:28'),
(82, 'Would you rather know the truth even if it breaks you, or stay ignorant and happy?', NULL, '2025-09-16 14:42:28'),
(83, 'Would you rather always trust too much or never trust enough?', NULL, '2025-09-16 14:42:28'),
(84, 'Would you rather your partner be open but blunt, or gentle but secretive?', NULL, '2025-09-16 14:42:28'),
(85, 'Would you rather hide your feelings or confess them and risk rejection?', NULL, '2025-09-16 14:42:28'),
(86, 'Would you rather have your secrets revealed or your lies discovered?', NULL, '2025-09-16 14:42:28'),
(87, 'Would you rather always be vulnerable or always be guarded?', NULL, '2025-09-16 14:42:28'),
(88, 'Would you rather know everything about others or nothing at all?', NULL, '2025-09-16 14:42:29'),
(89, 'Would you rather trust one person fully or trust many halfway?', NULL, '2025-09-16 14:42:29'),
(90, 'Would you rather betray someone or be betrayed?', NULL, '2025-09-16 14:42:29'),
(91, 'Would you rather find out your biggest strength or your biggest weakness?', NULL, '2025-09-16 14:42:29'),
(92, 'Would you rather face your past or your future?', NULL, '2025-09-16 14:42:29'),
(93, 'Would you rather live knowing all your choices or never knowing what couldâ€™ve been?', NULL, '2025-09-16 14:42:29'),
(94, 'Would you rather be feared for your power or loved for your kindness?', NULL, '2025-09-16 14:42:29'),
(95, 'Would you rather feel too much or feel nothing at all?', NULL, '2025-09-16 14:42:29'),
(96, 'Would you rather never be wrong or never doubt yourself?', NULL, '2025-09-16 14:42:29'),
(97, 'Would you rather know the meaning of life or create your own meaning?', NULL, '2025-09-16 14:42:29'),
(98, 'Would you rather sacrifice yourself for love or sacrifice love for yourself?', NULL, '2025-09-16 14:42:29'),
(99, 'Would you rather face the truth of who you are or live happily in denial?', NULL, '2025-09-16 14:42:29'),
(100, 'Would you rather live a long life without passion or a short life full of it?', NULL, '2025-09-16 14:42:29');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `games`
--
ALTER TABLE `games`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `game_20_questions`
--
ALTER TABLE `game_20_questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `partner_id` (`partner_id`);

--
-- Indexes for table `game_would_you_rather`
--
ALTER TABLE `game_would_you_rather`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user1_id` (`user1_id`),
  ADD KEY `user2_id` (`user2_id`);

--
-- Indexes for table `links`
--
ALTER TABLE `links`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `pictures`
--
ALTER TABLE `pictures`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `voice_notes`
--
ALTER TABLE `voice_notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `would_you_rather_questions`
--
ALTER TABLE `would_you_rather_questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `games`
--
ALTER TABLE `games`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `game_20_questions`
--
ALTER TABLE `game_20_questions`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `game_would_you_rather`
--
ALTER TABLE `game_would_you_rather`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `links`
--
ALTER TABLE `links`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notes`
--
ALTER TABLE `notes`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pictures`
--
ALTER TABLE `pictures`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `voice_notes`
--
ALTER TABLE `voice_notes`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `would_you_rather_questions`
--
ALTER TABLE `would_you_rather_questions`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `game_20_questions`
--
ALTER TABLE `game_20_questions`
  ADD CONSTRAINT `game_20_questions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `game_20_questions_ibfk_2` FOREIGN KEY (`partner_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `game_would_you_rather`
--
ALTER TABLE `game_would_you_rather`
  ADD CONSTRAINT `game_would_you_rather_ibfk_1` FOREIGN KEY (`user1_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `game_would_you_rather_ibfk_2` FOREIGN KEY (`user2_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `links`
--
ALTER TABLE `links`
  ADD CONSTRAINT `links_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `notes`
--
ALTER TABLE `notes`
  ADD CONSTRAINT `notes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `pictures`
--
ALTER TABLE `pictures`
  ADD CONSTRAINT `pictures_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `voice_notes`
--
ALTER TABLE `voice_notes`
  ADD CONSTRAINT `voice_notes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `would_you_rather_questions`
--
ALTER TABLE `would_you_rather_questions`
  ADD CONSTRAINT `would_you_rather_questions_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
