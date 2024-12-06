-- --------------------------------------------------------
-- Хост:                         127.0.0.1
-- Версия сервера:               5.6.51-log - MySQL Community Server (GPL)
-- Операционная система:         Win64
-- HeidiSQL Версия:              12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Дамп структуры базы данных blog
CREATE DATABASE IF NOT EXISTS `blog` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */;
USE `blog`;

-- Дамп структуры для таблица blog.posts
CREATE TABLE IF NOT EXISTS `posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `author_id` int(11) NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `author_id` (`author_id`),
  CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Дамп данных таблицы blog.posts: ~11 rows (приблизительно)
INSERT INTO `posts` (`id`, `author_id`, `content`) VALUES
	(1, 1, 'пывкерверавсрывекрврыпар'),
	(2, 1, 'пваправрыерекыр'),
	(3, 1, 'ыверыерыекрныаврыере'),
	(4, 1, 'фкпфукраврыфуерыфуеврыфу'),
	(5, 1, 'фкпуфкнрпукрварварфвкарф'),
	(6, 1, 'кфпфкувпавпмифкыефкупф'),
	(7, 1, 'аааааааааааааааааааааааааааааааааааааааааааааааааааааааааааааааааааавввввввввввыыыыыаааааааааааааааааааааааааааааааааааааааааааааааааааааааааааааааааааавввввввввввыыыыыаааааааааааааааааааааааааааааааааааааааааааааааааааааааааааааааааааавввввввввввыыыыыаааааааааааааааааааааааааааааааааааааааааааааааааааааааааааааааааааавввввввввввыыыыыаааааааааааааааааааааааааааааааааааааааааааааааааааааааааааааааааааавввввввввввыыыыыаааааааааааааааааааааааааааааааааааааааааааааааааааааааааааааааааааавввввввввввыыыыыаааааааааааааааааааааааааааааааааааааааааааааааааааааааааааааааааааавввввввввввыыыыыаааааааааааааааааааааааааааааааааааааааааааааааааааааааааааааааааааавввввввввввыыыыыаааааааааааааааааааааааааааааааааааааааааааааааааааааааааааааааааааавввввввввввыыыыыаааааааааааааааааааааааааааааааааааааааааааааааааааааааааааааааааааавввввввввввыыыыы'),
	(8, 1, 'пывапвапварвар'),
	(9, 1, 'впварварапврапр'),
	(10, 1, 'вапрапоапоапыоыап'),
	(11, 1, 'рварапрпаопаор'),
	(12, 2, 'fggdddddddddddddddddhhd'),
	(13, 3, 'Текст 1: Приветствую вас! Это первый текст для первого пользователя.'),
	(14, 3, 'Текст 2: Второй текст также приветствует вас и желает удачи!'),
	(15, 4, 'Текст 1: Добро пожаловать! Этот текст предназначен для второго пользователя.'),
	(16, 4, 'Текст 2: Надеюсь, этот второй текст принесет вам радость и успех!');

-- Дамп структуры для таблица blog.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_encrypted` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `login` (`login`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Дамп данных таблицы blog.users: ~4 rows (приблизительно)
INSERT INTO `users` (`id`, `login`, `password_hash`, `password_encrypted`, `key`) VALUES
	(1, 'test', '$2y$10$D4.tf8Dp3jIWDqy169rpgOp/l3qBt0745oIMJNqSXdoJaD2O07Oy6', '8splK+SxoUPaHgHHpjWNDzdReTBiOXRGQnNpNWIvRlkySVJUVmc9PQ==', '46b2a9fa729a2ae548368e33e96fc8bd'),
	(2, 'test2', '$2y$10$xtWWBG8Q06lnArrOTP5XxOo4FSyOfEY.WFtafUKrleZ7P0T2Jxh.u', '5ZlEfkSoh/ZlVHIZbIWJnTFMalBzWmdOZGRQclNpVlhUalVlQVE9PQ==', '991ec857332fcd0f3b2d34fc8e9c1153'),
	(3, 'UserOne', '$2y$10$AYQTyhnXtaAZM9X/Q5XAXO0b28Fi0EWnmnQ.FuAFbHq3JSY07/XAm', 'Vq3+C114uq2KhM8YRgqpRk5vYzJSak1GTmpLOUF2TENLZ0ZuV3c9PQ==', '23850254c3fe047b55648308348df61a'),
	(4, 'SecondUser', '$2y$10$oOSoQgchLaEoKPrNk.HOYurZ0J48C7naumoOKss1wWdNlxgafix.m', 'CLvpskXwcN8YgADbAPcEVDIwODJNY08wbTZQOVZ0eDNsdHExZ3c9PQ==', '0a2d4e3fa3b809c87c472ecea620135f');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
