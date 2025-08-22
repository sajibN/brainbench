-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 22, 2025 at 07:55 AM
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
-- Database: `brainbench`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Bangla'),
(2, 'English'),
(5, 'General Knowledge'),
(3, 'Math'),
(4, 'Science');

-- --------------------------------------------------------

--
-- Table structure for table `exams`
--

CREATE TABLE `exams` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `duration_minutes` int(11) NOT NULL DEFAULT 60
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `exam_participants`
--

CREATE TABLE `exam_participants` (
  `id` int(11) NOT NULL,
  `exam_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `score` int(11) DEFAULT 0,
  `taken_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `exam_questions`
--

CREATE TABLE `exam_questions` (
  `id` int(11) NOT NULL,
  `exam_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `question` text NOT NULL,
  `option_a` varchar(255) NOT NULL,
  `option_b` varchar(255) NOT NULL,
  `option_c` varchar(255) NOT NULL,
  `option_d` varchar(255) NOT NULL,
  `correct_option` enum('a','b','c','d') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `category_id`, `question`, `option_a`, `option_b`, `option_c`, `option_d`, `correct_option`) VALUES
(2, 1, 'অমর কাব্য’ কোন কবির রচনা?', 'কাজী নজরুল ইসলাম', 'জসীমউদ্দীন', 'জীবনানন্দ দাশ', 'রবীন্দ্রনাথ ঠাকুর', 'b'),
(3, 1, 'বাংলা সাহিত্যে নবজাগরণের যুগের শুরু হয় প্রায় কোন সালে?', '১৭৮০', '১৮১৭', '১৮৪৮', '১৯০১', 'c'),
(4, 1, '‘গীতাঞ্জলি’ কাব্যগ্রন্থটি কোন ভাষায় মূলত লেখা হয়েছে?', 'বাংলা', 'সংস্কৃত', 'ইংরেজি', 'হিন্দি', 'a'),
(5, 1, 'নিম্নলিখিত কোনটি বাংলা ভাষার প্রাচীন সাহিত্যের অংশ?', 'সত্যজিৎ রায়ের ছোটগল্প', 'চণ্ডীদাসের বাঙ্গালা ভঙ্গিমা', 'জসীমউদ্দীনের কবিতা', 'শরৎচন্দ্র চট্টোপাধ্যায়ের উপন্যাস', 'b'),
(6, 1, '‘হাজার বছর ধরে’ কোন কবির লেখা একটি লম্বা কবিতা?', 'সেলিনা হোসেন', 'জীবনানন্দ দাশ', 'জসীমউদ্দীন', 'কাজী নজরুল ইসলাম', 'c'),
(7, 2, 'Who is the author of “Paradise Lost”?', 'William Shakespeare', 'John Milton', 'Geoffrey Chaucer', 'John Keats', 'b'),
(8, 2, 'Which of the following is considered a metaphysical poet?', 'William Wordsworth', 'John Donne', 'Robert Frost', 'T.S. Eliot', 'b'),
(9, 2, 'The play “Waiting for Godot” was written by:', 'Samuel Beckett', 'George Bernard Shaw', 'Oscar Wilde', 'Arthur Miller', 'a'),
(10, 2, 'What is the main theme of George Orwell’s “Animal Farm”?', 'Love and Romance', 'Socialism and Corruption', 'Adventure and Exploration', 'Mythology', 'b'),
(11, 2, 'Which novel begins with the line, “Call me Ishmael”?', 'Pride and Prejudice', 'Moby Dick', 'Great Expectations', 'Jane Eyre', 'b'),
(12, 3, '১২ এবং ১৮-এর সর্বনিম্ন গুণিতক (LCM) কত?', '৩৬', '৫৪', '৭২', '৬০', 'a'),
(13, 3, 'যদি x2−5x+6=0 হয়, তবে x-এর মান কী?', '২, ৩', '১, ৬', '৩, ৪', '২, ৪', 'a'),
(14, 3, 'sin2θ+cos2θ সমান হয়:', '০', '১', 'θ', '1/2', 'b'),
(15, 3, 'একটি বৃত্তের ব্যাসার্ধ ৭ মি। বৃত্তের ক্ষেত্রফল কত', '১৫৪', '১৪৪', '১৬৮', '১৫০', 'a'),
(16, 4, 'মানবদেহে সবচেয়ে বড় অঙ্গ কোনটি?', 'লিভার', 'ত্বক', 'মস্তিষ্ক', 'হৃৎপিণ্ড', 'b'),
(17, 4, 'সৌরজগতে সবচেয়ে বড় গ্রহ কোনটি?', 'পৃথিবী', 'মঙ্গল', 'বৃহস্পতি', 'শুক্র', 'c'),
(18, 4, 'কোন অণু কোষের জেনেটিক তথ্য বহন করে?', 'প্রোটিন', 'RNA', 'DNA', 'লিপিড', 'c'),
(19, 4, 'কোন গ্যাসটি সবচেয়ে হালকা?', 'হাইড্রোজেন', 'হিলিয়াম', 'নাইট্রোজেন', 'অক্সিজেন', 'a'),
(20, 5, 'বিশ্বের সবচেয়ে বড় মহাসাগর কোনটি?', 'আটলান্টিক', 'ভারত মহাসাগর', 'প্রশান্ত মহাসাগর', 'আর্কটিক মহাসাগর', 'c'),
(21, 5, 'জাতিসংঘের সদর দফতর কোথায়?', 'জেনেভা', 'নিউ ইয়র্ক', 'প্যারিস', 'লন্ডন', 'b'),
(22, 5, 'বাংলাদেশের বর্তমান সংবিধান প্রবর্তিত হয় কবে?', '১৯৭১', '১৯৭৫', '১৯৭৩', '১৯৭২', 'd'),
(23, 5, 'কোন দেশ প্রথম চাঁদে পা রাখে?', 'রাশিয়া', 'যুক্তরাষ্ট্র', 'চীন', 'জাপান', 'b'),
(24, 5, 'বাংলাদেশের সবচেয়ে বড় জেলা কোনটি?', 'ঢাকা', 'চট্টগ্রাম', 'কুমিল্লা', 'রংপুর', 'c');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','student') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
(1, 'sajib', '$2y$10$DJOel1nnyvP3A2Dylbx4susWkVsIXtU2F0Zq//cLN1h7iBiaTyDWK', 'student'),
(3, 'sajib', '', 'admin'),
(4, 'arijeet', '', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `exams`
--
ALTER TABLE `exams`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `exam_participants`
--
ALTER TABLE `exam_participants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `exam_id` (`exam_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `exam_questions`
--
ALTER TABLE `exam_questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `exam_id` (`exam_id`),
  ADD KEY `question_id` (`question_id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `exams`
--
ALTER TABLE `exams`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `exam_participants`
--
ALTER TABLE `exam_participants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `exam_questions`
--
ALTER TABLE `exam_questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `exam_participants`
--
ALTER TABLE `exam_participants`
  ADD CONSTRAINT `exam_participants_ibfk_1` FOREIGN KEY (`exam_id`) REFERENCES `exams` (`id`),
  ADD CONSTRAINT `exam_participants_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `exam_questions`
--
ALTER TABLE `exam_questions`
  ADD CONSTRAINT `exam_questions_ibfk_1` FOREIGN KEY (`exam_id`) REFERENCES `exams` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `exam_questions_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
