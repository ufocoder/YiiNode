-- phpMyAdmin SQL Dump
-- version 3.5.5
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Авг 27 2013 г., 17:14
-- Версия сервера: 5.5.29
-- Версия PHP: 5.4.10

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- База данных: `YiiNode`
--

-- --------------------------------------------------------

--
-- Структура таблицы `db_node`
--

CREATE TABLE `db_node` (
  `id_node` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_node_parent` int(11) unsigned DEFAULT NULL,
  `position` int(11) NOT NULL DEFAULT '0',
  `rgt` int(11) unsigned NOT NULL,
  `lft` int(11) unsigned NOT NULL,
  `level` smallint(5) unsigned NOT NULL,
  `path` varchar(512) NOT NULL,
  `slug` varchar(64) NOT NULL,
  `module` varchar(255) NOT NULL,
  `layout` varchar(128) NOT NULL,
  `title` varchar(255) NOT NULL,
  `label` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `enabled` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `hidden` tinyint(4) NOT NULL DEFAULT '0',
  `time_created` int(11) NOT NULL,
  `time_updated` int(11) NOT NULL,
  PRIMARY KEY (`id_node`),
  KEY `lft` (`lft`),
  KEY `rgt` (`rgt`),
  KEY `level` (`level`),
  KEY `id_parent` (`id_node_parent`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `db_node`
--

INSERT INTO `db_node` (`id_node`, `id_node_parent`, `position`, `rgt`, `lft`, `level`, `path`, `slug`, `module`, `layout`, `title`, `label`, `image`, `description`, `enabled`, `hidden`, `time_created`, `time_updated`) VALUES
(1, NULL, 0, 2, 1, 1, '/', '/', 'page', '', 'Главная', '', '', '', 1, 0, 0, 0);

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `db_node`
--
ALTER TABLE `db_node`
  ADD CONSTRAINT `fk_db_node_id_node_parent` FOREIGN KEY (`id_node_parent`) REFERENCES `db_node` (`id_node`) ON UPDATE CASCADE;
