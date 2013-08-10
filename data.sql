-- phpMyAdmin SQL Dump
-- version 3.5.5
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Авг 10 2013 г., 21:04
-- Версия сервера: 5.5.29
-- Версия PHP: 5.4.10

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- База данных: `YiiNode`
--

-- --------------------------------------------------------

--
-- Структура таблицы `db_block`
--

CREATE TABLE `db_block` (
  `id_block` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `type` varchar(255) DEFAULT NULL,
  `content` text NOT NULL,
  `time_created` int(11) unsigned DEFAULT NULL,
  `time_updated` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id_block`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `db_block`
--

INSERT INTO `db_block` (`id_block`, `title`, `type`, `content`, `time_created`, `time_updated`) VALUES
(1, 'Footer: copyright', 'string', '', 1376139023, NULL);

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
  `content` text NOT NULL,
  `enabled` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `time_created` int(11) NOT NULL,
  `time_updated` int(11) NOT NULL,
  PRIMARY KEY (`id_node`),
  KEY `lft` (`lft`),
  KEY `rgt` (`rgt`),
  KEY `level` (`level`),
  KEY `id_parent` (`id_node_parent`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `db_node`
--

INSERT INTO `db_node` (`id_node`, `id_node_parent`, `position`, `rgt`, `lft`, `level`, `path`, `slug`, `module`, `layout`, `title`, `label`, `image`, `content`, `enabled`, `time_created`, `time_updated`) VALUES
(1, NULL, 0, 4, 1, 1, '/', '/', 'page', '', 'Main', '', '', '', 1, 0, 0),
(2, 1, 1, 3, 2, 2, '/News', 'News', 'articles', '', 'News', '', '', '', 1, 0, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `db_setting`
--

CREATE TABLE `db_setting` (
  `id_setting` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_node` int(11) unsigned DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `value` varchar(255) DEFAULT NULL,
  `time_created` int(11) unsigned DEFAULT NULL,
  `time_updated` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id_setting`),
  KEY `time_created` (`time_created`),
  KEY `time_updated` (`time_updated`),
  KEY `id_node` (`id_node`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `db_user`
--

CREATE TABLE `db_user` (
  `id_user` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(255) NOT NULL,
  `password` varchar(64) NOT NULL,
  `activekey` varchar(64) NOT NULL,
  `role` varchar(64) NOT NULL,
  `email` varchar(255) NOT NULL,
  `time_created` int(11) unsigned DEFAULT NULL,
  `time_updated` int(11) unsigned DEFAULT NULL,
  `time_visited` int(11) unsigned DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `db_user`
--

INSERT INTO `db_user` (`id_user`, `login`, `password`, `activekey`, `role`, `email`, `time_created`, `time_updated`, `time_visited`, `status`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', '', 'admin', '', NULL, NULL, 1376137688, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `db_user_field`
--

CREATE TABLE `db_user_field` (
  `id_user_field` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `varname` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `field_type` varchar(50) NOT NULL,
  `field_size` varchar(15) NOT NULL DEFAULT '0',
  `field_size_min` varchar(15) NOT NULL DEFAULT '0',
  `required` int(1) NOT NULL DEFAULT '0',
  `match` varchar(255) NOT NULL DEFAULT '',
  `range` varchar(255) NOT NULL DEFAULT '',
  `error_message` varchar(255) NOT NULL DEFAULT '',
  `default` varchar(255) NOT NULL DEFAULT '',
  `position` int(3) NOT NULL DEFAULT '0',
  `visible` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_user_field`),
  KEY `varname` (`varname`,`visible`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Дамп данных таблицы `db_user_field`
--

INSERT INTO `db_user_field` (`id_user_field`, `varname`, `title`, `field_type`, `field_size`, `field_size_min`, `required`, `match`, `range`, `error_message`, `default`, `position`, `visible`) VALUES
(1, 'lastname', 'Last Name', 'VARCHAR', '50', '3', 1, '', '', 'Incorrect Last Name (length between 3 and 50 characters).', '', 1, 3),
(2, 'firstname', 'First Name', 'VARCHAR', '50', '3', 1, '', '', 'Incorrect First Name (length between 3 and 50 characters).', '', 0, 3),
(3, 'phone', 'Телефон', 'VARCHAR', '255', '0', 0, '', '', '', '', 0, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `db_user_profile`
--

CREATE TABLE `db_user_profile` (
  `id_user` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `lastname` varchar(50) NOT NULL DEFAULT '',
  `firstname` varchar(50) NOT NULL DEFAULT '',
  `phone` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `db_user_profile`
--

INSERT INTO `db_user_profile` (`id_user`, `lastname`, `firstname`, `phone`) VALUES
(1, '', '', '');

-- --------------------------------------------------------

--
-- Структура таблицы `mod_article`
--

CREATE TABLE `mod_article` (
  `id_article` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_node` int(11) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `meta_keywords` text,
  `meta_description` text,
  `notice` text NOT NULL,
  `content` text,
  `time_created` int(11) unsigned DEFAULT NULL,
  `time_published` int(11) unsigned DEFAULT NULL,
  `time_updated` int(11) unsigned DEFAULT NULL,
  `enabled` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_article`),
  KEY `id_node` (`id_node`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `mod_page`
--

CREATE TABLE `mod_page` (
  `id_node` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `time_created` int(11) DEFAULT NULL,
  `time_updated` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_node`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `mod_page`
--

INSERT INTO `mod_page` (`id_node`, `title`, `content`, `time_created`, `time_updated`) VALUES
(1, 'Main', '<p style="margin: 15px 0px; color: rgb(51, 51, 51); font-family: Helvetica, arial, freesans, clean, sans-serif; font-size: 15px; line-height: 25px;">Yii framework based CMS with tree structure</p>\r\n\r\n<h2 style="margin: 1em 0px 15px; line-height: 1.7; font-size: 2em; padding: 0px; cursor: text; position: relative; border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: rgb(238, 238, 238); color: rgb(51, 51, 51); font-family: Helvetica, arial, freesans, clean, sans-serif;">Extensions</h2>\r\n\r\n<ul style="padding-right: 0px; padding-left: 30px; margin: 15px 0px; color: rgb(51, 51, 51); font-family: Helvetica, arial, freesans, clean, sans-serif; font-size: 15px; line-height: 25px;">\r\n <li><a href="https://github.com/clevertech/YiiBooster" style="color: rgb(65, 131, 196); text-decoration: none;">YiiBooster</a>;</li>\r\n  <li><a href="http://www.yiiframework.com/extension/yii-debug-toolbar" style="color: rgb(65, 131, 196); text-decoration: none;">Yii Debug Toolbar</a>;</li>\r\n  <li><a href="http://www.yiiframework.com/extension/mail" style="color: rgb(65, 131, 196); text-decoration: none;">Mail</a>;</li>\r\n  <li><a href="http://www.yiiframework.com/extension/ztree" style="color: rgb(65, 131, 196); text-decoration: none;">zTree</a>.</li>\r\n</ul>\r\n\r\n<h2 style="margin: 1em 0px 15px; line-height: 1.7; font-size: 2em; padding: 0px; cursor: text; position: relative; border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: rgb(238, 238, 238); color: rgb(51, 51, 51); font-family: Helvetica, arial, freesans, clean, sans-serif;">Features</h2>\r\n\r\n<p style="margin: 15px 0px; color: rgb(51, 51, 51); font-family: Helvetica, arial, freesans, clean, sans-serif; font-size: 15px; line-height: 25px;">Admin and User modules are parts of YiiNode CMS, other standart modules could be node in site tree structure</p>\r\n\r\n<h2 style="margin: 1em 0px 15px; line-height: 1.7; font-size: 2em; padding: 0px; cursor: text; position: relative; border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: rgb(238, 238, 238); color: rgb(51, 51, 51); font-family: Helvetica, arial, freesans, clean, sans-serif;">Modules</h2>\r\n\r\n<p style="margin: 15px 0px; color: rgb(51, 51, 51); font-family: Helvetica, arial, freesans, clean, sans-serif; font-size: 15px; line-height: 25px;">There are few cms module that is able to help you build website:</p>\r\n\r\n<ul style="padding-right: 0px; padding-left: 30px; margin-top: 15px; margin-right: 0px; margin-left: 0px; color: rgb(51, 51, 51); font-family: Helvetica, arial, freesans, clean, sans-serif; font-size: 15px; line-height: 25px; margin-bottom: 0px !important;">\r\n  <li>Admin</li>\r\n  <li>Articles</li>\r\n <li>Page</li>\r\n <li>User</li>\r\n</ul>\r\n', 1376138967, 1376139020);

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `db_node`
--
ALTER TABLE `db_node`
  ADD CONSTRAINT `fk_db_node_id_node_parent` FOREIGN KEY (`id_node_parent`) REFERENCES `db_node` (`id_node`) ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `db_setting`
--
ALTER TABLE `db_setting`
  ADD CONSTRAINT `fk_db_node_id_node` FOREIGN KEY (`id_node`) REFERENCES `db_node` (`id_node`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `db_user_profile`
--
ALTER TABLE `db_user_profile`
  ADD CONSTRAINT `fk_user_profile_id_user` FOREIGN KEY (`id_user`) REFERENCES `db_user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `mod_article`
--
ALTER TABLE `mod_article`
  ADD CONSTRAINT `fk_mod_article_id_node` FOREIGN KEY (`id_node`) REFERENCES `db_node` (`id_node`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `mod_page`
--
ALTER TABLE `mod_page`
  ADD CONSTRAINT `fk_mod_page_id_node` FOREIGN KEY (`id_node`) REFERENCES `db_node` (`id_node`) ON DELETE CASCADE ON UPDATE CASCADE;
