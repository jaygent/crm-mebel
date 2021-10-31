-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Окт 31 2021 г., 21:15
-- Версия сервера: 5.7.33
-- Версия PHP: 7.1.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `crm`
--

-- --------------------------------------------------------

--
-- Структура таблицы `master`
--

CREATE TABLE `master` (
  `id_master` int(11) NOT NULL,
  `name` varchar(256) NOT NULL,
  `login` varchar(256) DEFAULT NULL,
  `password` varchar(256) DEFAULT NULL,
  `master_churnal` int(11) DEFAULT NULL,
  `proff` varchar(256) NOT NULL,
  `work` int(11) NOT NULL,
  `pro_max` varchar(256) NOT NULL,
  `del_status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `master`
--

INSERT INTO `master` (`id_master`, `name`, `login`, `password`, `master_churnal`, `proff`, `work`, `pro_max`, `del_status`) VALUES
(3, 'admin', 'admin', '$2y$10$GNc7CqeCyg3Kjqwt.rtClOPLRF9v8lNuuX6Z4mzzWa3CReJEI03SW', NULL, 'Руководитель', 1, '10', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `master_pro`
--

CREATE TABLE `master_pro` (
  `id` int(11) NOT NULL,
  `id_zakaza` int(11) NOT NULL,
  `id_master` int(11) NOT NULL,
  `pro` varchar(225) NOT NULL,
  `date_m` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `mebel`
--

CREATE TABLE `mebel` (
  `id_mebel` int(11) NOT NULL,
  `id_zakaza` int(11) NOT NULL,
  `name_mebel` text NOT NULL,
  `col` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `rasxod`
--

CREATE TABLE `rasxod` (
  `id` int(11) NOT NULL,
  `id_master` int(11) NOT NULL,
  `date` date NOT NULL,
  `money` text NOT NULL,
  `com` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `setting`
--

CREATE TABLE `setting` (
  `id` int(11) NOT NULL,
  `messagezakaz` text NOT NULL,
  `messageotziv` text NOT NULL,
  `messagezaivka` text CHARACTER SET utf8mb4 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `sms`
--

CREATE TABLE `sms` (
  `id` int(11) NOT NULL,
  `phone` text NOT NULL,
  `ack` int(11) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `splitup_zakaz`
--

CREATE TABLE `splitup_zakaz` (
  `id_split` int(11) NOT NULL,
  `id_zakaza` int(11) NOT NULL,
  `id_master` int(11) NOT NULL,
  `pro` varchar(225) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prices` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_m` date NOT NULL,
  `part` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `tkani`
--

CREATE TABLE `tkani` (
  `id_tkani` int(11) NOT NULL,
  `id_zakaza` int(11) NOT NULL,
  `name_tkani` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `zakaz`
--

CREATE TABLE `zakaz` (
  `id_zakaza` int(11) NOT NULL,
  `nomer_zakaza` int(11) NOT NULL,
  `nomer_lena` int(11) DEFAULT NULL,
  `dates` date NOT NULL,
  `year` year(4) NOT NULL,
  `klient` text NOT NULL,
  `phone1` text,
  `send_m1_1` text,
  `send_m1_2` text,
  `phone2` text,
  `send_m2_1` text,
  `send_m2_2` text,
  `price` varchar(256) NOT NULL,
  `statuss` int(10) NOT NULL,
  `date_sd` date DEFAULT NULL,
  `type_oplata` int(11) DEFAULT NULL,
  `prim` text,
  `pro_ob` varchar(265) NOT NULL,
  `pro_sh` varchar(265) NOT NULL,
  `idmaster` int(11) NOT NULL,
  `splitup` int(11) NOT NULL,
  `lena_status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `master`
--
ALTER TABLE `master`
  ADD PRIMARY KEY (`id_master`);

--
-- Индексы таблицы `master_pro`
--
ALTER TABLE `master_pro`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_zakaza` (`id_zakaza`,`id_master`),
  ADD KEY `id_master` (`id_master`);

--
-- Индексы таблицы `mebel`
--
ALTER TABLE `mebel`
  ADD PRIMARY KEY (`id_mebel`),
  ADD KEY `id_zakaz` (`id_zakaza`);

--
-- Индексы таблицы `rasxod`
--
ALTER TABLE `rasxod`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_master` (`id_master`);

--
-- Индексы таблицы `setting`
--
ALTER TABLE `setting`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `sms`
--
ALTER TABLE `sms`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `splitup_zakaz`
--
ALTER TABLE `splitup_zakaz`
  ADD PRIMARY KEY (`id_split`),
  ADD KEY `id_zakaza` (`id_zakaza`);

--
-- Индексы таблицы `tkani`
--
ALTER TABLE `tkani`
  ADD PRIMARY KEY (`id_tkani`),
  ADD KEY `id_zakaza` (`id_zakaza`);

--
-- Индексы таблицы `zakaz`
--
ALTER TABLE `zakaz`
  ADD PRIMARY KEY (`id_zakaza`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `master`
--
ALTER TABLE `master`
  MODIFY `id_master` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `master_pro`
--
ALTER TABLE `master_pro`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `mebel`
--
ALTER TABLE `mebel`
  MODIFY `id_mebel` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `rasxod`
--
ALTER TABLE `rasxod`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `setting`
--
ALTER TABLE `setting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `sms`
--
ALTER TABLE `sms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `splitup_zakaz`
--
ALTER TABLE `splitup_zakaz`
  MODIFY `id_split` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `tkani`
--
ALTER TABLE `tkani`
  MODIFY `id_tkani` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `zakaz`
--
ALTER TABLE `zakaz`
  MODIFY `id_zakaza` int(11) NOT NULL AUTO_INCREMENT;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `master_pro`
--
ALTER TABLE `master_pro`
  ADD CONSTRAINT `master_pro_ibfk_1` FOREIGN KEY (`id_zakaza`) REFERENCES `zakaz` (`id_zakaza`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `master_pro_ibfk_2` FOREIGN KEY (`id_master`) REFERENCES `master` (`id_master`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Ограничения внешнего ключа таблицы `mebel`
--
ALTER TABLE `mebel`
  ADD CONSTRAINT `mebel_ibfk_1` FOREIGN KEY (`id_zakaza`) REFERENCES `zakaz` (`id_zakaza`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `rasxod`
--
ALTER TABLE `rasxod`
  ADD CONSTRAINT `master_ibfk_1` FOREIGN KEY (`id_master`) REFERENCES `master` (`id_master`);

--
-- Ограничения внешнего ключа таблицы `splitup_zakaz`
--
ALTER TABLE `splitup_zakaz`
  ADD CONSTRAINT `splitupi_ibfk_1` FOREIGN KEY (`id_zakaza`) REFERENCES `zakaz` (`id_zakaza`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `tkani`
--
ALTER TABLE `tkani`
  ADD CONSTRAINT `tkani_ibfk_1` FOREIGN KEY (`id_zakaza`) REFERENCES `zakaz` (`id_zakaza`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
