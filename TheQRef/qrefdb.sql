-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 15, 2020 at 07:46 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `qrefdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `choice`
--

CREATE TABLE `choice` (
  `id` int(11) NOT NULL,
  `questionId` int(11) DEFAULT NULL,
  `text` varchar(255) DEFAULT NULL,
  `correct` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `choice`
--

INSERT INTO `choice` (`id`, `questionId`, `text`, `correct`) VALUES
(1, 1, 'Beč', 0),
(2, 1, 'Buenos Aires', 1),
(3, 1, 'Lima', 0),
(4, 1, 'Rio de Janeiro', 0),
(5, 2, 'Tallinn', 1),
(6, 2, 'Riga', 0),
(7, 2, 'Helsinki', 0),
(8, 2, 'Vilnius', 0),
(9, 3, 'Budimpešta', 0),
(10, 3, 'Sofija', 0),
(11, 3, 'Bukurešt', 0),
(12, 3, 'Bratislava', 1),
(13, 4, 'Caracas', 1),
(14, 4, 'Bogota', 0),
(15, 4, 'Havana', 0),
(16, 4, 'Lima', 0),
(17, 5, 'Namibija', 0),
(18, 5, 'Peru', 1),
(19, 5, 'Meksiko', 0),
(20, 5, 'Paragvaj', 1),
(21, 6, 'Malta', 1),
(22, 6, 'Sjeverna Makedonija', 0),
(23, 6, 'Finska', 1),
(24, 6, 'Norveška', 0),
(25, 7, 'Slovenija', 1),
(26, 7, 'Albanija', 0),
(27, 7, 'Austrija', 0),
(28, 7, 'Italija', 1),
(29, 8, 'Nizozemska', 0),
(30, 8, 'Španjolska', 1),
(31, 8, 'Austrija', 0),
(32, 8, 'Italija', 1),
(33, 9, '', NULL),
(34, 10, '', NULL),
(35, 11, '8', NULL),
(36, 11, '9', NULL),
(37, 11, '10', 1),
(38, 11, '11', NULL),
(39, 12, 'Hrvatska', 1),
(40, 12, 'Njemačka', NULL),
(41, 12, 'Španjolska', NULL),
(42, 12, 'Italija', NULL),
(43, 13, '', NULL),
(44, 14, 'Milan', NULL),
(45, 14, 'Inter', NULL),
(46, 14, 'Atalanta', 1),
(47, 14, 'Juventus', NULL),
(48, 15, 'Arsenal', NULL),
(49, 15, 'Chelsea', 1),
(50, 15, 'Man Utd', NULL),
(51, 15, 'Leicester', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `choiceuseranswer`
--

CREATE TABLE `choiceuseranswer` (
  `id` int(11) NOT NULL,
  `userAnswerId` int(11) DEFAULT NULL,
  `choiceId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `choiceuseranswer`
--

INSERT INTO `choiceuseranswer` (`id`, `userAnswerId`, `choiceId`) VALUES
(1, 1, 2),
(2, 2, 5),
(3, 3, 12),
(4, 4, 13),
(5, 5, 18),
(6, 5, 20),
(7, 6, 21),
(8, 6, 23),
(9, 7, 25),
(10, 7, 28),
(11, 8, 30),
(12, 8, 32),
(13, 11, 37),
(14, 12, 39),
(15, 14, 46),
(16, 15, 49);

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE `comment` (
  `id` int(11) NOT NULL,
  `quizId` int(11) DEFAULT NULL,
  `userId` int(11) DEFAULT NULL,
  `text` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `comment`
--

INSERT INTO `comment` (`id`, `quizId`, `userId`, `text`) VALUES
(1, 2, 1, '*odličan* kviz!');

-- --------------------------------------------------------

--
-- Table structure for table `question`
--

CREATE TABLE `question` (
  `id` int(11) NOT NULL,
  `text` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `quizId` int(11) DEFAULT NULL,
  `correctTextAnswer` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `question`
--

INSERT INTO `question` (`id`, `text`, `type`, `quizId`, `correctTextAnswer`) VALUES
(1, 'Glavni grad Argentine?', '1', 1, NULL),
(2, 'Glavni grad Estonije?', '1', 1, NULL),
(3, 'Glavni grad Slovačke?', '1', 1, NULL),
(4, 'Glavni grad Venezuele?', '1', 1, NULL),
(5, 'Odaberi države koje se nalaze u Južnoj Americi.', '2', 1, NULL),
(6, 'Odaberi države koje su članice EU', '2', 1, NULL),
(7, 'Odaberite hrvatske susjede', '2', 1, NULL),
(8, 'Odaberite francuske susjede', '2', 1, NULL),
(9, 'Glavni grad Irske', '3', 1, 'Dublin'),
(10, 'Najveća europska luka', '3', 1, 'Rotterdam'),
(11, 'Broj na dresu Luke Modrića?', '1', 2, NULL),
(12, 'Drugo mjesto na posljednjem SP osvojila je?', '1', 2, NULL),
(13, '2019. godine Ligu prvaka osvojio je koji klub?', '3', 2, 'Liverpool'),
(14, 'U sezoni 19/20, Dinamo je na Maksimiru s velikih 4-0 svladao koji talijanski klub?', '1', 2, NULL),
(15, '2019. godine u finalu Europske lige su igrala dva velika engleska kluba. Koji je pobijedio?', '1', 2, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `quiz`
--

CREATE TABLE `quiz` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `private` tinyint(1) DEFAULT NULL,
  `canBeCommented` tinyint(1) DEFAULT NULL,
  `uniqId` varchar(255) DEFAULT NULL,
  `creatorId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `quiz`
--

INSERT INTO `quiz` (`id`, `name`, `description`, `private`, `canBeCommented`, `uniqId`, `creatorId`) VALUES
(1, 'Geography quiz', 'Small geography quiz!', 0, 1, '5ebece62d6289', 1),
(2, 'Football quiz', 'For all football fans!', 0, 1, '5ebed332c5025', 1);

-- --------------------------------------------------------

--
-- Table structure for table `quizplay`
--

CREATE TABLE `quizplay` (
  `id` int(11) NOT NULL,
  `quizId` int(11) DEFAULT NULL,
  `userId` int(11) DEFAULT NULL,
  `score` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `quizplay`
--

INSERT INTO `quizplay` (`id`, `quizId`, `userId`, `score`) VALUES
(1, 1, 1, 100),
(2, 2, 1, 100);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `firstName` varchar(255) DEFAULT NULL,
  `lastName` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `dob` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `firstName`, `lastName`, `email`, `dob`, `password`) VALUES
(1, 'Stjepan', 'Kovačić', 'stjepan@gmail.com', '1999-01-01', '$2y$10$Ub1bAG/.sjlty8C2D1DQW.g.WjX9sck3p3LHVjhrQkMa0e4Nx3O3S');

-- --------------------------------------------------------

--
-- Table structure for table `useranswer`
--

CREATE TABLE `useranswer` (
  `id` int(11) NOT NULL,
  `questionId` int(11) DEFAULT NULL,
  `quizPlayId` int(11) DEFAULT NULL,
  `textAnswer` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `useranswer`
--

INSERT INTO `useranswer` (`id`, `questionId`, `quizPlayId`, `textAnswer`) VALUES
(1, 1, 1, NULL),
(2, 2, 1, NULL),
(3, 3, 1, NULL),
(4, 4, 1, NULL),
(5, 5, 1, NULL),
(6, 6, 1, NULL),
(7, 7, 1, NULL),
(8, 8, 1, NULL),
(9, 9, 1, 'Dublin'),
(10, 10, 1, 'Rotterdam'),
(11, 11, 2, NULL),
(12, 12, 2, NULL),
(13, 13, 2, 'Liverpool'),
(14, 14, 2, NULL),
(15, 15, 2, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `choice`
--
ALTER TABLE `choice`
  ADD PRIMARY KEY (`id`),
  ADD KEY `choice_ibfk_1` (`questionId`);

--
-- Indexes for table `choiceuseranswer`
--
ALTER TABLE `choiceuseranswer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `choiceuseranswer_ibfk_1` (`userAnswerId`),
  ADD KEY `choiceuseranswer_ibfk_2` (`choiceId`);

--
-- Indexes for table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userId` (`userId`),
  ADD KEY `comment_ibfk_1` (`quizId`);

--
-- Indexes for table `question`
--
ALTER TABLE `question`
  ADD PRIMARY KEY (`id`),
  ADD KEY `question_ibfk_1` (`quizId`);

--
-- Indexes for table `quiz`
--
ALTER TABLE `quiz`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quiz_ibfk_1` (`creatorId`);

--
-- Indexes for table `quizplay`
--
ALTER TABLE `quizplay`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quizplay_ibfk_1` (`quizId`),
  ADD KEY `quizplay_ibfk_2` (`userId`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `useranswer`
--
ALTER TABLE `useranswer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `useranswer_ibfk_1` (`questionId`),
  ADD KEY `useranswer_ibfk_2` (`quizPlayId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `choice`
--
ALTER TABLE `choice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `choiceuseranswer`
--
ALTER TABLE `choiceuseranswer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `comment`
--
ALTER TABLE `comment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `question`
--
ALTER TABLE `question`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `quiz`
--
ALTER TABLE `quiz`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `quizplay`
--
ALTER TABLE `quizplay`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `useranswer`
--
ALTER TABLE `useranswer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `choice`
--
ALTER TABLE `choice`
  ADD CONSTRAINT `choice_ibfk_1` FOREIGN KEY (`questionId`) REFERENCES `question` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `choiceuseranswer`
--
ALTER TABLE `choiceuseranswer`
  ADD CONSTRAINT `choiceuseranswer_ibfk_1` FOREIGN KEY (`userAnswerId`) REFERENCES `useranswer` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `choiceuseranswer_ibfk_2` FOREIGN KEY (`choiceId`) REFERENCES `choice` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`quizId`) REFERENCES `quiz` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comment_ibfk_2` FOREIGN KEY (`userId`) REFERENCES `user` (`id`);

--
-- Constraints for table `question`
--
ALTER TABLE `question`
  ADD CONSTRAINT `question_ibfk_1` FOREIGN KEY (`quizId`) REFERENCES `quiz` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `quiz`
--
ALTER TABLE `quiz`
  ADD CONSTRAINT `quiz_ibfk_1` FOREIGN KEY (`creatorId`) REFERENCES `user` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `quizplay`
--
ALTER TABLE `quizplay`
  ADD CONSTRAINT `quizplay_ibfk_1` FOREIGN KEY (`quizId`) REFERENCES `quiz` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `quizplay_ibfk_2` FOREIGN KEY (`userId`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `useranswer`
--
ALTER TABLE `useranswer`
  ADD CONSTRAINT `useranswer_ibfk_1` FOREIGN KEY (`questionId`) REFERENCES `question` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `useranswer_ibfk_2` FOREIGN KEY (`quizPlayId`) REFERENCES `quizplay` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
