-- phpMyAdmin SQL Dump
-- version 4.2.3
-- http://www.phpmyadmin.net
--
-- Хост: localhost:3306
-- Время создания: Фев 27 2016 г., 14:59
-- Версия сервера: 5.5.34
-- Версия PHP: 5.5.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- База данных: `urlshortener_db`
--
CREATE DATABASE IF NOT EXISTS `urlshortener_db` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `urlshortener_db`;

-- --------------------------------------------------------

--
-- Структура таблицы `urls`
--

CREATE TABLE `urls` (
`id` bigint(20) NOT NULL,
  `url_long` text NOT NULL,
  `url_short` varchar(10) NOT NULL,
  `created_at` datetime NOT NULL,
  `counter` int(11) DEFAULT NULL,
  `deleted_at` DATETIME NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `urls`
--

INSERT INTO `urls` (`id`, `url_long`, `url_short`, `created_at`, `counter`) VALUES
(1, 'http://google.ru', 'IQkt3usH', '2016-02-27 14:46:40', 0),
(2, 'http://google.com', 'behruz', '2016-02-27 14:56:59', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `urls`
--
ALTER TABLE `urls`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `url_short` (`url_short`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `urls`
--
ALTER TABLE `urls`
MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;