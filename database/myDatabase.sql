-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hostiteľ: db
-- Čas generovania: So 25.Mar 2023, 09:09
-- Verzia serveru: 8.0.32
-- Verzia PHP: 8.0.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáza: `myDatabase`
--

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `fieldsOfStudy`
--

CREATE TABLE `fieldsOfStudy` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `shortcut` varchar(8) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `Rooms`
--

CREATE TABLE `Rooms` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `SubjectFieldOfStudies`
--

CREATE TABLE `SubjectFieldOfStudies` (
  `id` int UNSIGNED NOT NULL,
  `subject_id` int UNSIGNED NOT NULL,
  `fieldOfStudy_id` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `Subjects`
--

CREATE TABLE `Subjects` (
  `id` int UNSIGNED NOT NULL,
  `lecture_room_id` int UNSIGNED DEFAULT NULL,
  `exercise_room_id` int UNSIGNED DEFAULT NULL,
  `name` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `shortcut` varchar(8) NOT NULL,
  `grade` enum('bc.','ing.','phd.') CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `year` enum('1','2','3') NOT NULL,
  `semestre` enum('ZS','LS') NOT NULL,
  `lecture_day` enum('pondelok','utorok','streda','štvrtok','piatok') CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `lecture_time_from` time DEFAULT NULL,
  `lecture_time_to` time DEFAULT NULL,
  `exercise_day` enum('pondelok','utorok','streda','štvrtok','piatok') CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `exercise_time_from` time DEFAULT NULL,
  `exercise_time_to` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `SubjectTeachers`
--

CREATE TABLE `SubjectTeachers` (
  `id` int UNSIGNED NOT NULL,
  `subject_id` int UNSIGNED NOT NULL,
  `teacher_id` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32;

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `TeacherConstraints`
--

CREATE TABLE `TeacherConstraints` (
  `id` int UNSIGNED NOT NULL,
  `teacher_id` int UNSIGNED NOT NULL,
  `banned_day` enum('pondelok','utorok','streda','štvrtok','piatok') CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `time_from` time DEFAULT NULL,
  `time_to` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `Teachers`
--

CREATE TABLE `Teachers` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Kľúče pre exportované tabuľky
--

--
-- Indexy pre tabuľku `fieldsOfStudy`
--
ALTER TABLE `fieldsOfStudy`
  ADD PRIMARY KEY (`id`);

--
-- Indexy pre tabuľku `Rooms`
--
ALTER TABLE `Rooms`
  ADD PRIMARY KEY (`id`);

--
-- Indexy pre tabuľku `SubjectFieldOfStudies`
--
ALTER TABLE `SubjectFieldOfStudies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subject_id` (`subject_id`),
  ADD KEY `fieldOfStudy_id` (`fieldOfStudy_id`);

--
-- Indexy pre tabuľku `Subjects`
--
ALTER TABLE `Subjects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `room_id` (`lecture_room_id`),
  ADD KEY `exercise_room_id` (`exercise_room_id`);

--
-- Indexy pre tabuľku `SubjectTeachers`
--
ALTER TABLE `SubjectTeachers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subject_id` (`subject_id`),
  ADD KEY `teacher_id` (`teacher_id`);

--
-- Indexy pre tabuľku `TeacherConstraints`
--
ALTER TABLE `TeacherConstraints`
  ADD PRIMARY KEY (`id`),
  ADD KEY `teacher_id` (`teacher_id`);

--
-- Indexy pre tabuľku `Teachers`
--
ALTER TABLE `Teachers`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pre exportované tabuľky
--

--
-- AUTO_INCREMENT pre tabuľku `fieldsOfStudy`
--
ALTER TABLE `fieldsOfStudy`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pre tabuľku `Rooms`
--
ALTER TABLE `Rooms`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pre tabuľku `SubjectFieldOfStudies`
--
ALTER TABLE `SubjectFieldOfStudies`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pre tabuľku `Subjects`
--
ALTER TABLE `Subjects`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pre tabuľku `SubjectTeachers`
--
ALTER TABLE `SubjectTeachers`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pre tabuľku `TeacherConstraints`
--
ALTER TABLE `TeacherConstraints`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pre tabuľku `Teachers`
--
ALTER TABLE `Teachers`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Obmedzenie pre exportované tabuľky
--

--
-- Obmedzenie pre tabuľku `SubjectFieldOfStudies`
--
ALTER TABLE `SubjectFieldOfStudies`
  ADD CONSTRAINT `SubjectFieldOfStudies_ibfk_1` FOREIGN KEY (`subject_id`) REFERENCES `Subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `SubjectFieldOfStudies_ibfk_2` FOREIGN KEY (`fieldOfStudy_id`) REFERENCES `fieldsOfStudy` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Obmedzenie pre tabuľku `Subjects`
--
ALTER TABLE `Subjects`
  ADD CONSTRAINT `Subjects_ibfk_2` FOREIGN KEY (`lecture_room_id`) REFERENCES `Rooms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Subjects_ibfk_3` FOREIGN KEY (`exercise_room_id`) REFERENCES `Rooms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Obmedzenie pre tabuľku `SubjectTeachers`
--
ALTER TABLE `SubjectTeachers`
  ADD CONSTRAINT `SubjectTeachers_ibfk_1` FOREIGN KEY (`subject_id`) REFERENCES `Subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `SubjectTeachers_ibfk_2` FOREIGN KEY (`teacher_id`) REFERENCES `Teachers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Obmedzenie pre tabuľku `TeacherConstraints`
--
ALTER TABLE `TeacherConstraints`
  ADD CONSTRAINT `TeacherConstraints_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `Teachers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
