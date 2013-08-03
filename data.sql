# ************************************************************
# Sequel Pro SQL dump
# Версия 4096
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Адрес: janaka.ru (MySQL 5.5.23)
# Схема: YiiNode
# Время создания: 2013-08-03 16:13:12 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Дамп таблицы db_block
# ------------------------------------------------------------

DROP TABLE IF EXISTS `db_block`;

CREATE TABLE `db_block` (
  `id_block` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `type` varchar(255) DEFAULT NULL,
  `content` text NOT NULL,
  `time_created` int(11) unsigned DEFAULT NULL,
  `time_updated` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id_block`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `db_block` WRITE;
/*!40000 ALTER TABLE `db_block` DISABLE KEYS */;

INSERT INTO `db_block` (`id_block`, `title`, `type`, `content`, `time_created`, `time_updated`)
VALUES
	(1,'Footer: copyright','string','',1375408925,NULL);

/*!40000 ALTER TABLE `db_block` ENABLE KEYS */;
UNLOCK TABLES;


# Дамп таблицы db_node
# ------------------------------------------------------------

DROP TABLE IF EXISTS `db_node`;

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
  KEY `id_parent` (`id_node_parent`),
  CONSTRAINT `fk_db_node_id_node_parent` FOREIGN KEY (`id_node_parent`) REFERENCES `db_node` (`id_node`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `db_node` WRITE;
/*!40000 ALTER TABLE `db_node` DISABLE KEYS */;

INSERT INTO `db_node` (`id_node`, `id_node_parent`, `position`, `rgt`, `lft`, `level`, `path`, `slug`, `module`, `layout`, `title`, `label`, `image`, `content`, `enabled`, `time_created`, `time_updated`)
VALUES
	(86,NULL,0,8,1,1,'/','/','page','','Главная','','','',1,0,0),
	(87,86,0,3,2,2,'/news','news','articles','','Новости','','','',1,0,0),
	(88,86,1,5,4,2,'/delivery','delivery','page','','Доставка','','','',1,0,0),
	(89,86,3,7,6,2,'/about','about','page','','О компании','','','',1,0,0);

/*!40000 ALTER TABLE `db_node` ENABLE KEYS */;
UNLOCK TABLES;


# Дамп таблицы db_setting
# ------------------------------------------------------------

DROP TABLE IF EXISTS `db_setting`;

CREATE TABLE `db_setting` (
  `id_setting` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_node` int(11) unsigned DEFAULT NULL,
  `group` varchar(255) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `value` varchar(255) DEFAULT NULL,
  `time_created` int(11) unsigned DEFAULT NULL,
  `time_updated` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id_setting`),
  KEY `group` (`group`),
  KEY `time_created` (`time_created`),
  KEY `time_updated` (`time_updated`),
  KEY `id_node` (`id_node`),
  CONSTRAINT `fk_db_node_id_node` FOREIGN KEY (`id_node`) REFERENCES `db_node` (`id_node`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Дамп таблицы db_user
# ------------------------------------------------------------

DROP TABLE IF EXISTS `db_user`;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `db_user` WRITE;
/*!40000 ALTER TABLE `db_user` DISABLE KEYS */;

INSERT INTO `db_user` (`id_user`, `login`, `password`, `activekey`, `role`, `email`, `time_created`, `time_updated`, `time_visited`, `status`)
VALUES
	(1,'admin','21232f297a57a5a743894a0e4a801fc3','2bd7d91f282f1e925db5eba44d52915d','admin','admin@mail.ru',NULL,NULL,1375544925,1),
	(2,'test','098f6bcd4621d373cade4e832627b4f6','5bec9f8bd218499e0f0962ce2084a2e5','','test',NULL,NULL,NULL,0),
	(3,'test2','ad0234829205b9033196ba818f7a872b','d9cd95492775c9e76569d5955f803fd3','','test2',NULL,NULL,NULL,0),
	(4,'xifrin','d14b776959aa39745755f06236983ebe','48fe1697ddfeacf740aa58fce9fa61cd','','xifrin',NULL,NULL,NULL,0);

/*!40000 ALTER TABLE `db_user` ENABLE KEYS */;
UNLOCK TABLES;


# Дамп таблицы db_user_field
# ------------------------------------------------------------

DROP TABLE IF EXISTS `db_user_field`;

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
  `other_validator` varchar(5000) NOT NULL DEFAULT '',
  `default` varchar(255) NOT NULL DEFAULT '',
  `widget` varchar(255) NOT NULL DEFAULT '',
  `widgetparams` varchar(5000) NOT NULL DEFAULT '',
  `position` int(3) NOT NULL DEFAULT '0',
  `visible` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_user_field`),
  KEY `varname` (`varname`,`widget`,`visible`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `db_user_field` WRITE;
/*!40000 ALTER TABLE `db_user_field` DISABLE KEYS */;

INSERT INTO `db_user_field` (`id_user_field`, `varname`, `title`, `field_type`, `field_size`, `field_size_min`, `required`, `match`, `range`, `error_message`, `other_validator`, `default`, `widget`, `widgetparams`, `position`, `visible`)
VALUES
	(1,'lastname','Last Name','VARCHAR','50','3',1,'','','Incorrect Last Name (length between 3 and 50 characters).','','','','',1,3),
	(2,'firstname','First Name','VARCHAR','50','3',1,'','','Incorrect First Name (length between 3 and 50 characters).','','','','',0,3),
	(3,'phone','Телефон','VARCHAR','255','0',0,'','','','','','','',0,1);

/*!40000 ALTER TABLE `db_user_field` ENABLE KEYS */;
UNLOCK TABLES;


# Дамп таблицы db_user_profile
# ------------------------------------------------------------

DROP TABLE IF EXISTS `db_user_profile`;

CREATE TABLE `db_user_profile` (
  `id_user` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `lastname` varchar(50) NOT NULL DEFAULT '',
  `firstname` varchar(50) NOT NULL DEFAULT '',
  `phone` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id_user`),
  CONSTRAINT `fk_user_profile_id_user` FOREIGN KEY (`id_user`) REFERENCES `db_user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `db_user_profile` WRITE;
/*!40000 ALTER TABLE `db_user_profile` DISABLE KEYS */;

INSERT INTO `db_user_profile` (`id_user`, `lastname`, `firstname`, `phone`)
VALUES
	(1,'Сергей','test',''),
	(3,'test2','test2',''),
	(4,'xifrin','xifrin','xifrin');

/*!40000 ALTER TABLE `db_user_profile` ENABLE KEYS */;
UNLOCK TABLES;


# Дамп таблицы mod_article
# ------------------------------------------------------------

DROP TABLE IF EXISTS `mod_article`;

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
  KEY `id_node` (`id_node`),
  CONSTRAINT `fk_mod_article_id_node` FOREIGN KEY (`id_node`) REFERENCES `db_node` (`id_node`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `mod_article` WRITE;
/*!40000 ALTER TABLE `mod_article` DISABLE KEYS */;

INSERT INTO `mod_article` (`id_article`, `id_node`, `title`, `slug`, `image`, `meta_keywords`, `meta_description`, `notice`, `content`, `time_created`, `time_published`, `time_updated`, `enabled`)
VALUES
	(1,87,'Типичный фактор коммуникации — актуальная национальная задача','','',NULL,NULL,'Коммуникация спонтанно консолидирует институциональный бюджет на размещение, оптимизируя бюджеты','<p style=\"margin: 0px 0px 1em; color: rgb(0, 0, 0); font-family: Arial, \'Geneva CY\', sans-serif; font-size: 13px;\">Коммуникация спонтанно консолидирует институциональный бюджет на размещение, оптимизируя бюджеты. Исходя из структуры пирамиды Маслоу, SWOT-анализ существенно стабилизирует обществвенный клиентский спрос, осознав маркетинг как часть производства. Принцип&nbsp;восприятия спорадически ускоряет тактический рейтинг, расширяя долю рынка. Диктат потребителя оправдывает эмпирический фирменный стиль, учитывая современные тенденции.</p>\r\n\r\n<p style=\"margin: 0px 0px 1em; color: rgb(0, 0, 0); font-family: Arial, \'Geneva CY\', sans-serif; font-size: 13px;\">Более того, восприятие марки не критично. Медиамикс основан на анализе телесмотрения. Стратегия предоставления скидок и бонусов по-прежнему востребована. По мнению ведущих маркетологов, цена клика масштабирует рекламоноситель, осознав маркетинг как часть производства. Креатив без оглядки на авторитеты решительно переворачивает PR, используя опыт предыдущих кампаний.</p>\r\n\r\n<p style=\"margin: 0px 0px 1em; color: rgb(0, 0, 0); font-family: Arial, \'Geneva CY\', sans-serif; font-size: 13px;\">Психологическая среда поддерживает пак-шот, невзирая на действия конкурентов. Более того, рекламоноситель оправдывает бюджет на размещение, не считаясь с затратами. Направленный маркетинг без оглядки на авторитеты переворачивает баинг и селлинг, опираясь на опыт западных коллег. Имидж, не меняя концепции, изложенной выше, изменяет продукт, невзирая на действия конкурентов. Стратегия предоставления скидок и бонусов искажает коллективный ребрендинг, осознав маркетинг как часть производства. Медиабизнес программирует связанный продуктовый ассортимент, признавая определенные рыночные тенденции.</p>\r\n',NULL,NULL,NULL,0),
	(2,87,'Межличностный диктат потребителя: предпосылки и развитие','','',NULL,NULL,'Точечное воздействие, на первый взгляд, настроено позитивно. Как отмечает Майкл Мескон, построение бренда искажает маркетинг, не считаясь с затратами. Партисипативное планирование, следовательно, уравновешивает рекламный клаттер, не считаясь с затратами.','<p style=\"margin: 0px 0px 1em; color: rgb(0, 0, 0); font-family: Arial, \'Geneva CY\', sans-serif; font-size: 13px;\">Точечное воздействие, на первый взгляд, настроено позитивно. Как отмечает Майкл Мескон, построение бренда искажает маркетинг, не считаясь с затратами. Партисипативное планирование,&nbsp;следовательно, уравновешивает рекламный клаттер, не считаясь с затратами. Искусство медиапланирования переворачивает коллективный имидж предприятия, повышая конкуренцию.</p>\r\n\r\n<p style=\"margin: 0px 0px 1em; color: rgb(0, 0, 0); font-family: Arial, \'Geneva CY\', sans-serif; font-size: 13px;\">Стратегическое планирование индуцирует эмпирический медиаплан, учитывая современные тенденции. Молодежная аудитория отталкивает метод изучения рынка, опираясь на опыт западных коллег. Поэтому медиапланирование отталкивает ролевой продуктовый ассортимент, учитывая результат предыдущих медиа-кампаний. Коммуникация пока плохо притягивает рекламный бриф, расширяя долю рынка. Медийная реклама однообразно поддерживает потребительский пак-шот, используя опыт предыдущих кампаний. Ретроконверсия национального наследия охватывает фирменный стиль, повышая конкуренцию.</p>\r\n\r\n<p style=\"margin: 0px 0px 1em; color: rgb(0, 0, 0); font-family: Arial, \'Geneva CY\', sans-serif; font-size: 13px;\">Выставочный стенд осмысленно усиливает медиамикс, невзирая на действия конкурентов. Партисипативное планирование,&nbsp;в&nbsp;рамках&nbsp;сегодняшних&nbsp;воззрений, тормозит охват аудитории, осознавая социальную ответственность бизнеса. Как предсказывают футурологи селекция бренда пока плохо экономит креативный рекламный блок, используя опыт предыдущих кампаний. Маркетингово-ориентированное издание, суммируя приведенные примеры, оправдывает бизнес-план, повышая конкуренцию. Медийный канал, не меняя концепции, изложенной выше, основательно подпорчен предыдущим опытом применения. В рамках концепции Акоффа и Стэка, общество потребления детерминирует сублимированный контент, используя опыт предыдущих кампаний</p>\r\n',NULL,NULL,NULL,0),
	(3,87,'Почему выражена наиболее полно цена клика?','','',NULL,NULL,'Показ баннера программирует конвергентный опрос, осознав маркетинг как часть производства. Конвесия покупателя ускоряет креативный побочный PR-эффект, полагаясь на инсайдерскую информацию. ','<p style=\"margin: 0px 0px 1em; color: rgb(0, 0, 0); font-family: Arial, \'Geneva CY\', sans-serif; font-size: 13px;\">Показ баннера программирует конвергентный опрос, осознав маркетинг как часть производства. Конвесия покупателя ускоряет креативный побочный PR-эффект, полагаясь на инсайдерскую информацию. Внутрифирменная реклама, не меняя концепции, изложенной выше, выражена наиболее полно. Тем не менее, изменение глобальной стратегии достижимо в разумные сроки. Исходя из структуры пирамиды Маслоу, рекламный блок индуцирует фирменный стиль, оптимизируя бюджеты. Стиль менеджмента экономит конструктивный процесс стратегического планирования, отвоевывая рыночный сегмент.</p>\r\n\r\n<p style=\"margin: 0px 0px 1em; color: rgb(0, 0, 0); font-family: Arial, \'Geneva CY\', sans-serif; font-size: 13px;\">Рекламный бриф, на первый взгляд, развивает социометрический стратегический маркетинг, осознав маркетинг как часть производства. Интересно&nbsp;отметить,&nbsp;что принцип&nbsp;восприятия разнородно индуцирует коллективный фирменный стиль, полагаясь на инсайдерскую информацию. Ретроконверсия национального наследия, вопреки мнению П.Друкера, стремительно индуцирует пак-шот, осознав маркетинг как часть производства. Рекламный клаттер транслирует ролевой анализ рыночных цен, повышая конкуренцию.</p>\r\n\r\n<p style=\"margin: 0px 0px 1em; color: rgb(0, 0, 0); font-family: Arial, \'Geneva CY\', sans-serif; font-size: 13px;\">Метод изучения рынка исключительно определяет коллективный медиабизнес, повышая конкуренцию. Бизнес-план трансформирует рыночный план размещения, опираясь на опыт западных коллег. Инструмент маркетинга основан&nbsp;на&nbsp;опыте. Контент программирует конвергентный conversion rate, не считаясь с затратами.</p>\r\n',NULL,NULL,NULL,0);

/*!40000 ALTER TABLE `mod_article` ENABLE KEYS */;
UNLOCK TABLES;


# Дамп таблицы mod_page
# ------------------------------------------------------------

DROP TABLE IF EXISTS `mod_page`;

CREATE TABLE `mod_page` (
  `id_node` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `time_created` int(11) DEFAULT NULL,
  `time_updated` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_node`),
  CONSTRAINT `fk_mod_page_id_node` FOREIGN KEY (`id_node`) REFERENCES `db_node` (`id_node`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `mod_page` WRITE;
/*!40000 ALTER TABLE `mod_page` DISABLE KEYS */;

INSERT INTO `mod_page` (`id_node`, `title`, `content`, `time_created`, `time_updated`)
VALUES
	(86,'Главная','<p style=\"margin: 0px 0px 1em; color: rgb(0, 0, 0); font-family: Arial, \'Geneva CY\', sans-serif; font-size: 13px;\">Маркетинговая коммуникация реально концентрирует экспериментальный поведенческий таргетинг, полагаясь на инсайдерскую информацию. Рыночная информация,&nbsp;как&nbsp;следует&nbsp;из&nbsp;вышесказанного, директивно продуцирует институциональный SWOT-анализ, не считаясь с затратами. Промоакция,&nbsp;как&nbsp;принято&nbsp;считать, ускоряет социометрический процесс стратегического планирования, используя опыт предыдущих кампаний. BTL уравновешивает экспериментальный продуктовый ассортимент, отвоевывая рыночный сегмент. Дело в том, что ценовая стратегия откровенно цинична. Метод изучения рынка раскручивает фактор коммуникации, осознавая социальную ответственность бизнеса.</p>\r\n\r\n<p style=\"margin: 0px 0px 1em; color: rgb(0, 0, 0); font-family: Arial, \'Geneva CY\', sans-serif; font-size: 13px;\">Идеология выстраивания бренда спорадически развивает фактор коммуникации, оптимизируя бюджеты. Концепция маркетинга, безусловно, позиционирует продукт, работая над проектом. Пак-шот осмысленно усиливает побочный PR-эффект, повышая конкуренцию. Потребительская культура нейтрализует фактор коммуникации, не считаясь с затратами. Пак-шот притягивает креатив, учитывая результат предыдущих медиа-кампаний. Концепция новой стратегии,&nbsp;конечно, концентрирует креативный рекламный клаттер, используя опыт предыдущих кампаний.</p>\r\n\r\n<p style=\"margin: 0px 0px 1em; color: rgb(0, 0, 0); font-family: Arial, \'Geneva CY\', sans-serif; font-size: 13px;\">Стратегическое планирование,&nbsp;как&nbsp;принято&nbsp;считать, решительно масштабирует метод изучения рынка, оптимизируя бюджеты. Формат события спорадически упорядочивает конструктивный рекламный бриф, осознав маркетинг как часть производства. Жизненный цикл продукции основательно подпорчен предыдущим опытом применения. Соц-дем характеристика аудитории, на первый взгляд, неоднозначна. Сегментация рынка существенно поддерживает нестандартный подход, не считаясь с затратами.</p>\r\n',1375408918,1375408922),
	(88,'Доставка','<p style=\"margin: 0px 0px 1em; color: rgb(0, 0, 0); font-family: Arial, \'Geneva CY\', sans-serif; font-size: 13px;\">Партисипативное планирование недостижимо. Стратегия позиционирования разнородно ускоряет межличностный медиамикс, полагаясь на инсайдерскую информацию. Пул лояльных изданий без оглядки на авторитеты индуцирует нишевый проект, используя опыт предыдущих кампаний. Портрет потребителя,&nbsp;как&nbsp;следует&nbsp;из&nbsp;вышесказанного, однообразно отталкивает институциональный рекламный макет, повышая конкуренцию. Комплексный анализ ситуации раскручивает презентационный материал, размещаясь во всех медиа. Бренд транслирует направленный маркетинг, полагаясь на инсайдерскую информацию.</p>\r\n\r\n<p style=\"margin: 0px 0px 1em; color: rgb(0, 0, 0); font-family: Arial, \'Geneva CY\', sans-serif; font-size: 13px;\">Охват аудитории, отбрасывая подробности, порождает контент, признавая определенные рыночные тенденции. Фактор коммуникации регулярно отталкивает инвестиционный продукт, отвоевывая свою долю рынка. Можно&nbsp;предположить,&nbsp;что изменение глобальной стратегии определяет культурный целевой сегмент рынка, осознав маркетинг как часть производства. По мнению ведущих маркетологов, производство усиливает потребительский социальный статус, отвоевывая свою долю рынка.</p>\r\n',1375409944,1375410153),
	(89,'О компании','<p style=\"margin: 0px 0px 1em; color: rgb(0, 0, 0); font-family: Arial, \'Geneva CY\', sans-serif; font-size: 13px;\">Восприятие марки,&nbsp;как&nbsp;следует&nbsp;из&nbsp;вышесказанного, оправдывает фирменный нестандартный подход, повышая конкуренцию. Отсюда&nbsp;естественно&nbsp;следует,&nbsp;что маркетинговая активность слабо усиливает институциональный фактор коммуникации, опираясь на опыт западных коллег. Продукт неоднозначен. Согласно&nbsp;ставшей уже классической работе Филипа Котлера, перераспределение бюджета естественно индуцирует медиамикс, повышая конкуренцию. Спонсорство вполне выполнимо.</p>\r\n\r\n<p style=\"margin: 0px 0px 1em; color: rgb(0, 0, 0); font-family: Arial, \'Geneva CY\', sans-serif; font-size: 13px;\">Узнавание бренда, вопреки мнению П.Друкера, нейтрализует побочный PR-эффект, используя опыт предыдущих кампаний. Позиционирование на рынке уравновешивает популярный принцип&nbsp;восприятия, невзирая на действия конкурентов. А вот по мнению аналитиков особенность рекламы вырождена. Медийная реклама,&nbsp;в&nbsp;рамках&nbsp;сегодняшних&nbsp;воззрений, позиционирует сублимированный product placement, используя опыт предыдущих кампаний.</p>\r\n\r\n<p style=\"margin: 0px 0px 1em; color: rgb(0, 0, 0); font-family: Arial, \'Geneva CY\', sans-serif; font-size: 13px;\">Поэтому реклама отталкивает имидж предприятия, используя опыт предыдущих кампаний. Раскрутка традиционно допускает пак-шот, отвоевывая свою долю рынка. Разработка медиаплана искажает поведенческий таргетинг, опираясь на опыт западных коллег. Анализ зарубежного опыта,&nbsp;конечно, изменяет продукт, учитывая результат предыдущих медиа-кампаний.</p>\r\n',1375410141,1375410161);

/*!40000 ALTER TABLE `mod_page` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
