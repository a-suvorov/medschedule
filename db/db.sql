-- --------------------------------------------------------
-- Хост:                         127.0.0.1
-- Версия сервера:               5.6.17-log - MySQL Community Server (GPL)
-- ОС Сервера:                   Win32
-- HeidiSQL Версия:              8.3.0.4694
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Дамп структуры базы данных medschedule
CREATE DATABASE IF NOT EXISTS `medschedule` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `medschedule`;


-- Дамп структуры для таблица medschedule.doctors
CREATE TABLE IF NOT EXISTS `doctors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `id_spec` varchar(255) DEFAULT NULL,
  `opisanie` varchar(1000) DEFAULT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

-- Дамп данных таблицы medschedule.doctors: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `doctors` DISABLE KEYS */;
/*!40000 ALTER TABLE `doctors` ENABLE KEYS */;


-- Дамп структуры для таблица medschedule.pacients
CREATE TABLE IF NOT EXISTS `pacients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fam` varchar(255) DEFAULT NULL,
  `im` varchar(255) DEFAULT NULL,
  `ot` varchar(255) DEFAULT NULL,
  `dr` date DEFAULT NULL,
  `n_polis` varchar(255) DEFAULT NULL,
  `s_polis` varchar(255) DEFAULT NULL,
  `kod_lpu` varchar(50) DEFAULT NULL,
  `strahovaya` varchar(255) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Дамп данных таблицы medschedule.pacients: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `pacients` DISABLE KEYS */;
/*!40000 ALTER TABLE `pacients` ENABLE KEYS */;


-- Дамп структуры для таблица medschedule.sched
CREATE TABLE IF NOT EXISTS `sched` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_doctors` int(11) DEFAULT NULL,
  `data_priem` date DEFAULT NULL,
  `time_priem` time DEFAULT NULL,
  `id_pacient` int(11) DEFAULT NULL,
  `cancel_key` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `NewIndex` (`id_doctors`,`data_priem`,`time_priem`)
) ENGINE=InnoDB AUTO_INCREMENT=457 DEFAULT CHARSET=utf8;

-- Дамп данных таблицы medschedule.sched: ~246 rows (приблизительно)
/*!40000 ALTER TABLE `sched` DISABLE KEYS */;
/*!40000 ALTER TABLE `sched` ENABLE KEYS */;


-- Дамп структуры для таблица medschedule.spec
CREATE TABLE IF NOT EXISTS `spec` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

-- Дамп данных таблицы medschedule.spec: ~5 rows (приблизительно)
/*!40000 ALTER TABLE `spec` DISABLE KEYS */;
INSERT INTO `spec` (`id`, `name`) VALUES
	(14, 'Хирург'),
	(15, 'Гинеколог'),
	(16, 'Невролог'),
	(17, 'Дерматолог'),
	(18, 'Уролог');
/*!40000 ALTER TABLE `spec` ENABLE KEYS */;


-- Дамп структуры для таблица medschedule.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(255) DEFAULT NULL,
  `pass` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Дамп данных таблицы medschedule.users: 1 rows
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `login`, `pass`) VALUES
	(1, 'admin', '239d69cade737ee78b31232f6eb75737');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
