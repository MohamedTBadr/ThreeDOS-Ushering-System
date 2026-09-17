-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql313.infinityfree.com
-- Generation Time: Feb 12, 2026 at 05:13 PM
-- Server version: 11.4.10-MariaDB
-- PHP Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `if0_40296491_threedosushering`
--

-- --------------------------------------------------------

--
-- Table structure for table `registration`
--

CREATE TABLE `registration` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `college` varchar(255) NOT NULL,
  `level` varchar(50) NOT NULL,
  `council` varchar(100) DEFAULT NULL,
  `ushered_by` varchar(255) DEFAULT NULL,
  `interview_time` datetime DEFAULT NULL,
  `rating` enum('Pending','Acceptance','B','Rejection') DEFAULT 'Pending',
  `interview_questions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL
) ;

--
-- Dumping data for table `registration`
--

INSERT INTO `registration` (`id`, `name`, `email`, `phone`, `college`, `level`, `council`, `ushered_by`, `interview_time`, `rating`, `interview_questions`, `notes`, `interviewed_by`, `created_at`, `updated_at`) VALUES
(33, 'JANA', 'jana.ayoub.004@gmail.com', '01019715441', 'HU', 'Level 4', 'Backend Development', 'JANA', NULL, 'Pending', NULL, NULL, '', '2026-02-12 22:10:18', '2026-02-12 22:10:18'),
(32, 'Shahd Magdy Ahmed Abdelateef', 'shahdabdelateef156@gmail.com', '01203997739', 'BIS Helwan ', 'Level 2', 'Backend Development', 'I\'m a pr member ', NULL, 'Pending', NULL, NULL, '', '2026-02-12 20:59:11', '2026-02-12 20:59:11'),
(31, 'Berry Yasser Mohamed ', 'berry.hr.threedos2026@gmail.com', '01032395779', 'Bis', 'Level 1', 'CEO', 'Berry ', NULL, 'Pending', NULL, NULL, '', '2026-02-12 20:55:57', '2026-02-12 20:55:57');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` timestamp NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `token`, `expires_at`, `created_at`) VALUES
(76, 14, 'a2a175dd6f496cad044450de4dcbb514bd6ce3b74934b68a6eb4018eb77ce50d', '2026-02-04 08:08:26', '2026-02-02 22:08:26'),
(85, 19, '24baf499eb924adb87983f392662f292d4bb5199df70a1684047cb508661f6ea', '2026-02-04 08:26:16', '2026-02-02 22:26:16'),
(92, 19, 'bf10b683a04217db2cf9ee5d0176bc1f830f758d0cf6d2495cb6170af7146521', '2026-02-04 08:49:27', '2026-02-02 22:49:27'),
(94, 19, 'cb314b82cc1b54d1f13dcf91d91257b10e5aded2d42d14777d5817b2a6ffb6e0', '2026-02-04 08:49:50', '2026-02-02 22:49:50'),
(96, 17, 'ece56b1529ca1208aa3347d1e8abddb2b642370220f4754b42ba281ce9cd923e', '2026-02-04 09:10:56', '2026-02-02 23:10:56'),
(103, 19, '443c6a630e8cbfa1546400dff68a2f837269806c8560f1e8e8cad5f348258a71', '2026-02-04 10:57:52', '2026-02-03 00:57:52'),
(132, 21, '2cde83b79e99cee51aebd7203f010ffd775438426ab9f4e65882ad626e672032', '2026-02-13 11:13:53', '2026-02-12 01:13:53'),
(133, 21, '84716a677292c7b6147ef972a399a7248bfc5e0f68ec81445cff1e07e1189d64', '2026-02-13 13:24:08', '2026-02-12 10:24:08'),
(134, 21, '623d75e185ecf6c4444dc0a91480f634490a49dc97fe59897ef9a7282989d126', '2026-02-13 14:01:45', '2026-02-12 11:01:45'),
(135, 21, '474d41da7534fe1c0344334aaa85c27163e49098363415fd35ac8e4e84cc137d', '2026-02-13 14:23:10', '2026-02-12 11:23:10'),
(136, 21, '65ac37c575b2f3458e0738a3acde080a2ee34015047e12abb78f90928c0183eb', '2026-02-13 14:36:08', '2026-02-12 11:36:08'),
(137, 22, '11ca2a565e60bf69fff50b63f91bf07ea1228af71026e4c3b535deea2cc35b02', '2026-02-13 18:15:54', '2026-02-12 15:15:54'),
(138, 22, '9d228523711adafad94c75952b903bdd1624e4a84804166ee894e7d4cfb05c19', '2026-02-13 18:20:18', '2026-02-12 15:20:18'),
(139, 21, '93ad083d849b0a45a910f699697254f1de696b5a7576fdde08e987f371937d6e', '2026-02-13 18:22:11', '2026-02-12 15:22:11'),
(140, 22, 'e9d33a1a04263555e773df3db22cf699420e0de61e5410529bc5aa836e193dfe', '2026-02-13 18:23:07', '2026-02-12 15:23:07'),
(141, 22, '9f401d2591bb65ff8e7891881fa3fe79e271afd556aa1df0b76e575f13520013', '2026-02-13 18:25:17', '2026-02-12 15:25:17'),
(142, 22, 'bdd2a406ae79a1b6e8acfb5f276ab279907195ce38a00a60c935d04243f783df', '2026-02-13 18:34:59', '2026-02-12 15:34:59'),
(143, 23, '851be1ffcfcfb78cecc038216d3a36d472011572db1fa43bb938e4521a547c7f', '2026-02-13 18:37:43', '2026-02-12 15:37:43'),
(144, 25, '11a1118670cac9e403803ec93e5ed2f0592af1459bc47228a91e93746825e101', '2026-02-13 18:57:24', '2026-02-12 15:57:24'),
(145, 27, 'd4b8fb6d913f130628c86d0419b2eb0f69000e48a6f3b2f141679196d7c485bd', '2026-02-13 19:31:01', '2026-02-12 16:31:01'),
(146, 27, '6c53d2e8de9c3e8b34a5f82466665d83fcae11a099ea1149aa388c8aec342756', '2026-02-13 22:19:47', '2026-02-12 19:19:47');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('VP','Head','Instructor','OR','President') NOT NULL,
  `council` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `council`, `created_at`, `updated_at`) VALUES
(6, 'Yassmine', 'yassmin.abbas9999@gmail.com', '$2y$10$J/m5UeWV2n5ALdplkZoyG.mZqof/QHwTEAMK/xa6bFCR0PVxYhVYG', 'VP', 'Backend Development', '2026-01-20 16:32:28', '2026-01-20 16:32:28'),
(8, 'Jana', 'jana.ayoub.004@gmail.com', '$2y$10$OWo5Oxfe7NjngHjNd9PV9eZpui1mWQaBwGBzr8YI4Bs8jxYA.d52m', 'VP', '', '2026-01-20 19:09:54', '2026-01-20 19:15:25'),
(9, 'Hana tamer', 'headceo.threedos26@gmail.com', '$2y$10$6fHxxbiGmliS2rDSwlyJbuV0ge7LcvHEJgJVP6UXbTBCGjLP6rzim', 'Head', 'CEO', '2026-01-20 19:17:32', '2026-01-20 19:17:32'),
(14, 'Mariam El-Amir', 'mariamelamir.marketing@threedos26.ushering', '$2y$10$0X3xLYq.5StDMrmJ.QyG4OZQkhUTSGpKZFhFPUeDxMPSm6cdhuG.6', 'Instructor', 'Marketing', '2026-01-31 13:33:27', '2026-01-31 13:33:27'),
(15, 'Nada Younies', 'nadayounies.marketing@threedos26.ushering', '$2y$10$BiZgJ1K7D9vI.eMaXAaeFu9rPAgylhjah8dltxwt9tK/kvL7VF4X.', 'Instructor', 'Marketing', '2026-01-31 13:35:30', '2026-01-31 13:35:30'),
(16, 'Farida Khaled', 'faridakhaled.marketing@threedos26.ushering', '$2y$10$vDuDwGm.Krq6Q8XISgCkq.nJXHFy1JM9sQNooS.HvIrKKHrlRtLAS', 'Instructor', 'Marketing', '2026-01-31 13:38:59', '2026-01-31 13:38:59'),
(17, 'Jana Mostafa', 'janamostafa.marketing@threedos26.ushering', '$2y$10$t4Sd2Qmde8pVD59cV1vsX.b5kdBW8PXsUCFZyFX4Lj7NFw3PCNt7W', 'Instructor', 'Marketing', '2026-01-31 13:54:05', '2026-01-31 13:54:05'),
(18, 'Alaa Naguib', 'alaanaguib.backend@threedos26.ushering', '$2y$10$gZjMoKLOJCJTGO6pHVFWj.DEpXLAi65kZ3IaZBI/PzhEaox8EBTze', 'Instructor', 'Backend Development', '2026-01-31 20:26:57', '2026-01-31 20:49:45'),
(19, 'Farah Yasser', 'farahyasser.backend@threedos26.ushering', '$2y$10$R7ECEoo/s3CdgO80RKZMI.TEBqcq4bQuqs.c0ltEh7MzenVVTlI5u', 'Instructor', 'Backend Development', '2026-01-31 20:41:29', '2026-01-31 20:49:48'),
(21, 'Mohamed Tarek ', 'mohamedtarekbadr047@gmail.com', '$2y$10$zeI2bnRoxRzC/uKdjU6vueValppYwB12f3EIzB8TQfInFu6Po7jTW', 'VP', NULL, '2026-02-12 01:13:38', '2026-02-12 01:13:38'),
(22, 'Momen Shehata ', 'momenshehata86@gmail.com', '$2y$10$M5rUAlX1jbgRKdZQMw6SauPJ.8L87luka2qFVxqvr94PA59NKlTEC', 'VP', NULL, '2026-02-12 15:15:36', '2026-02-12 15:15:36'),
(23, 'Abdelrahman Yasser', 'abdelrahmanyaasseray@gmail.com', '$2y$10$LuO2O3tKhAfeA.4UtJOzE.ZSLsrA3osq7BoDJ37JCkBDoIDGiZwzK', 'Head', 'Marketing', '2026-02-12 15:31:27', '2026-02-12 15:31:27'),
(24, 'Kareem Sharaka ', 'ksharaka109@gmail.com', '$2y$10$ul0jczo1k.70raMGv7SZZOi.G8NAoII.4OPoecixrnkq7SQHgaN6K', 'VP', NULL, '2026-02-12 15:49:03', '2026-02-12 15:49:03'),
(25, 'Youssef waleed ', 'youssefwaleed587mail.com@gmail.com', '$2y$10$3YmlMW0OT0Gb9GOE6CSvpOpHNfRF9zpVrGgPb7Wqtj5T6kOEkj83.', 'OR', NULL, '2026-02-12 15:54:30', '2026-02-12 15:54:30'),
(26, 'Marwan hisham', 'mrwanhisham8@gmail.com', '$2y$10$cJnBP3q30Ihko17ll/LuIu2.vllKhv/93Rnjlu0/A09l5Wl.mOrlu', 'OR', NULL, '2026-02-12 16:27:44', '2026-02-12 16:27:44'),
(27, 'MarwanHisham', 'or.threedos26@gmail.com', '$2y$10$FjglXge3wATsrOyssd5t.O03QGy8jBssR2ExrnzT0YgRu7qxWT.y.', 'OR', NULL, '2026-02-12 16:30:38', '2026-02-12 16:30:38');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`),
  ADD KEY `idx_token` (`token`),
  ADD KEY `idx_user` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_role` (`role`),
  ADD KEY `idx_council` (`council`),
  ADD KEY `username` (`username`) USING BTREE;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `registration`
--
ALTER TABLE `registration`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sessions`
--
ALTER TABLE `sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=147;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `sessions`
--
ALTER TABLE `sessions`
  ADD CONSTRAINT `sessions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
