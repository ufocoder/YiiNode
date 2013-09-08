-- phpMyAdmin SQL Dump
-- version 3.5.5
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Сен 08 2013 г., 20:04
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
  `theme` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `content` text NOT NULL,
  `params` text,
  `time_created` int(11) unsigned DEFAULT NULL,
  `time_updated` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id_block`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `db_block`
--

INSERT INTO `db_block` (`id_block`, `title`, `theme`, `type`, `content`, `params`, `time_created`, `time_updated`) VALUES
(1, 'Footer: copyright', NULL, 'string', '', NULL, 1378641480, NULL);

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
  `slug` varchar(128) NOT NULL,
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
(1, NULL, 0, 2, 1, 1, '/', '/', 'page', '', 'Главная', '', '', '', 1, 0, 1378641875, 0);

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
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', '', 'admin', '', NULL, NULL, 1378641863, 1);

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
  `position` int(11) unsigned NOT NULL DEFAULT '0',
  `visible` tinyint(3) NOT NULL DEFAULT '0',
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
-- Структура таблицы `mod_catalog_brand`
--

CREATE TABLE `mod_catalog_brand` (
  `id_brand` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `notice` text,
  `content` mediumtext,
  `time_created` int(11) DEFAULT NULL,
  `time_updated` int(11) DEFAULT NULL,
  `position` int(11) NOT NULL DEFAULT '0',
  `count` int(11) NOT NULL DEFAULT '0',
  `enabled` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_brand`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `mod_catalog_category`
--

CREATE TABLE `mod_catalog_category` (
  `id_category` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_category_parent` int(11) unsigned DEFAULT NULL,
  `position` int(11) NOT NULL DEFAULT '0',
  `root` int(11) unsigned DEFAULT NULL,
  `lft` int(11) unsigned DEFAULT NULL,
  `rgt` int(11) unsigned DEFAULT NULL,
  `level` smallint(5) unsigned NOT NULL,
  `slug` varchar(128) NOT NULL,
  `path` varchar(512) NOT NULL,
  `title` varchar(255) NOT NULL,
  `notice` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `time_created` int(11) DEFAULT NULL,
  `time_updated` int(11) DEFAULT NULL,
  `count` int(11) NOT NULL DEFAULT '0',
  `enabled` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_category`),
  KEY `root` (`root`),
  KEY `lft` (`lft`),
  KEY `rgt` (`rgt`),
  KEY `level` (`level`),
  KEY `id_category_parent` (`id_category_parent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `mod_catalog_fields`
--

CREATE TABLE `mod_catalog_fields` (
  `id_field` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `varname` varchar(255) NOT NULL,
  `field_type` varchar(255) NOT NULL,
  `field_size` int(11) NOT NULL,
  `field_size_min` int(11) DEFAULT NULL,
  `default` varchar(255) DEFAULT NULL,
  `position` int(11) NOT NULL,
  `required` tinyint(4) NOT NULL DEFAULT '0',
  `match` varchar(255) DEFAULT NULL,
  `range` varchar(255) DEFAULT NULL,
  `error_message` varchar(255) DEFAULT NULL,
  `visible` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id_field`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `mod_catalog_image`
--

CREATE TABLE `mod_catalog_image` (
  `id_image` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_product` int(11) unsigned NOT NULL,
  `image` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `time_created` int(11) DEFAULT NULL,
  `time_updated` int(11) DEFAULT NULL,
  `enabled` tinyint(4) NOT NULL,
  PRIMARY KEY (`id_image`),
  KEY `id_product` (`id_product`),
  KEY `id_product_2` (`id_product`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `mod_catalog_product`
--

CREATE TABLE `mod_catalog_product` (
  `id_product` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_brand` int(11) unsigned DEFAULT NULL,
  `id_category` int(11) unsigned DEFAULT NULL,
  `article` varchar(255) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `notice` text,
  `content` mediumtext,
  `price` decimal(10,2) DEFAULT NULL,
  `count` int(11) unsigned DEFAULT NULL,
  `store` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `preview` varchar(255) DEFAULT NULL,
  `special` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `time_created` int(11) DEFAULT NULL,
  `time_updated` int(11) DEFAULT NULL,
  `enabled` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_product`),
  KEY `article` (`article`),
  KEY `id_brand` (`id_brand`),
  KEY `price` (`price`),
  KEY `fk_catalog_product_id_category` (`id_category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `mod_catalog_product_fields`
--

CREATE TABLE `mod_catalog_product_fields` (
  `id_product` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id_product`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `mod_catalog_product_store`
--

CREATE TABLE `mod_catalog_product_store` (
  `id_store` int(11) unsigned NOT NULL,
  `id_product` int(11) unsigned NOT NULL,
  `value` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id_product`),
  KEY `id_store` (`id_store`),
  KEY `id_product` (`id_product`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `mod_catalog_store`
--

CREATE TABLE `mod_catalog_store` (
  `id_store` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `alttitle` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `notice` text NOT NULL,
  `time_created` int(11) unsigned NOT NULL,
  `time_updated` int(11) unsigned NOT NULL,
  `count` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id_store`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `mod_contact`
--

CREATE TABLE `mod_contact` (
  `id_contact` int(11) NOT NULL AUTO_INCREMENT,
  `id_node` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text,
  `image` varchar(255) NOT NULL,
  `addr` text,
  `timework` text,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `skype` varchar(255) DEFAULT NULL,
  `icq` varchar(255) DEFAULT NULL,
  `map_lat` double DEFAULT NULL,
  `map_long` double DEFAULT NULL,
  `map_zoom` int(11) DEFAULT NULL,
  `coord_code` text,
  `time_created` int(11) DEFAULT NULL,
  `time_updated` int(11) DEFAULT NULL,
  `enabled` smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_contact`),
  KEY `time_created` (`time_created`),
  KEY `time_updated` (`time_updated`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `mod_feedback`
--

CREATE TABLE `mod_feedback` (
  `id_feedback` int(11) NOT NULL AUTO_INCREMENT,
  `id_node` int(11) NOT NULL,
  `person_name` varchar(255) NOT NULL,
  `contact_phone` varchar(255) NOT NULL,
  `contact_email` varchar(255) NOT NULL,
  `content` text,
  `time_created` int(11) DEFAULT NULL,
  `time_readed` int(11) DEFAULT NULL,
  `time_updated` int(11) DEFAULT NULL,
  `enabled` smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_feedback`),
  KEY `time_created` (`time_created`),
  KEY `time_readed` (`time_readed`),
  KEY `time_updated` (`time_updated`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `mod_gallery_category`
--

CREATE TABLE `mod_gallery_category` (
  `id_gallery_category` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_node` int(11) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text,
  `image` varchar(255) NOT NULL,
  `position` int(11) NOT NULL,
  `enabled` tinyint(4) NOT NULL DEFAULT '0',
  `time_created` int(11) NOT NULL,
  `time_updated` int(11) NOT NULL,
  PRIMARY KEY (`id_gallery_category`),
  KEY `id_node` (`id_node`),
  KEY `id_node_2` (`id_node`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `mod_gallery_image`
--

CREATE TABLE `mod_gallery_image` (
  `id_gallery_image` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_gallery_category` int(11) unsigned DEFAULT NULL,
  `id_node` int(11) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text,
  `image` varchar(255) NOT NULL,
  `enabled` smallint(6) NOT NULL DEFAULT '0',
  `time_created` int(11) DEFAULT NULL,
  `time_updated` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_gallery_image`),
  KEY `id_gallery_category` (`id_gallery_category`),
  KEY `id_gallery_category_2` (`id_gallery_category`),
  KEY `title` (`title`),
  KEY `id_gallery_category_3` (`id_gallery_category`),
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
(1, 'Главная', '', 1378641875, NULL);

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
-- Ограничения внешнего ключа таблицы `mod_catalog_category`
--
ALTER TABLE `mod_catalog_category`
  ADD CONSTRAINT `fk_mod_catalog_category_id_parent` FOREIGN KEY (`id_category_parent`) REFERENCES `mod_catalog_category` (`id_category`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `mod_catalog_image`
--
ALTER TABLE `mod_catalog_image`
  ADD CONSTRAINT `fk_catalog_image_id_product` FOREIGN KEY (`id_product`) REFERENCES `mod_catalog_product` (`id_product`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `mod_catalog_product`
--
ALTER TABLE `mod_catalog_product`
  ADD CONSTRAINT `fk_catalog_product_id_brand` FOREIGN KEY (`id_brand`) REFERENCES `mod_catalog_brand` (`id_brand`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_catalog_product_id_category` FOREIGN KEY (`id_category`) REFERENCES `mod_catalog_category` (`id_category`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `mod_catalog_product_fields`
--
ALTER TABLE `mod_catalog_product_fields`
  ADD CONSTRAINT `fk_catalog_product_fields_id_product` FOREIGN KEY (`id_product`) REFERENCES `mod_catalog_product` (`id_product`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `mod_catalog_product_store`
--
ALTER TABLE `mod_catalog_product_store`
  ADD CONSTRAINT `fk_cps_id_product` FOREIGN KEY (`id_product`) REFERENCES `mod_catalog_product` (`id_product`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_cps_id_store` FOREIGN KEY (`id_store`) REFERENCES `mod_catalog_store` (`id_store`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `mod_gallery_category`
--
ALTER TABLE `mod_gallery_category`
  ADD CONSTRAINT `mod_gallery_category_ibfk_1` FOREIGN KEY (`id_node`) REFERENCES `db_node` (`id_node`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `mod_page`
--
ALTER TABLE `mod_page`
  ADD CONSTRAINT `fk_mod_page_id_node` FOREIGN KEY (`id_node`) REFERENCES `db_node` (`id_node`) ON DELETE CASCADE ON UPDATE CASCADE;
