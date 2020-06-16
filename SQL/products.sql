-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Июн 16 2020 г., 14:45
-- Версия сервера: 5.7.24-27
-- Версия PHP: 7.1.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `u1078224_default`
--

-- --------------------------------------------------------

--
-- Структура таблицы `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` int(11) NOT NULL,
  `weight` float NOT NULL,
  `quantity_0` int(11) NOT NULL DEFAULT '0',
  `quantity_1` int(11) NOT NULL DEFAULT '0',
  `quantity_2` int(11) NOT NULL DEFAULT '0',
  `quantity_3` int(11) NOT NULL DEFAULT '0',
  `quantity_4` int(11) NOT NULL DEFAULT '0',
  `quantity_5` int(11) NOT NULL DEFAULT '0',
  `quantity_6` int(11) NOT NULL DEFAULT '0',
  `quantity_7` int(11) NOT NULL DEFAULT '0',
  `price_0` float NOT NULL DEFAULT '0',
  `price_1` float DEFAULT '0',
  `price_2` float NOT NULL DEFAULT '0',
  `price_3` float NOT NULL DEFAULT '0',
  `price_4` float NOT NULL DEFAULT '0',
  `price_5` float NOT NULL DEFAULT '0',
  `price_6` float NOT NULL DEFAULT '0',
  `price_7` float NOT NULL DEFAULT '0',
  `usage` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
