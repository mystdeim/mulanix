-- phpMyAdmin SQL Dump
-- version 3.1.2deb1ubuntu0.1
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Авг 09 2009 г., 11:12
-- Версия сервера: 5.0.75
-- Версия PHP: 5.2.6-3ubuntu4.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `cmf1`
--

-- --------------------------------------------------------

--
-- Структура таблицы `mnix_block`
--

DROP TABLE IF EXISTS `mnix_block`;
CREATE TABLE IF NOT EXISTS `mnix_block` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(255) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `mnix_block`
--

INSERT INTO `mnix_block` (`id`, `title`) VALUES
(1, 'main'),
(2, 'left');

-- --------------------------------------------------------

--
-- Структура таблицы `mnix_block2template`
--

DROP TABLE IF EXISTS `mnix_block2template`;
CREATE TABLE IF NOT EXISTS `mnix_block2template` (
  `block_id` int(11) NOT NULL,
  `template_id` int(11) NOT NULL,
  PRIMARY KEY  (`block_id`,`template_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `mnix_block2template`
--

INSERT INTO `mnix_block2template` (`block_id`, `template_id`) VALUES
(1, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `mnix_component`
--

DROP TABLE IF EXISTS `mnix_component`;
CREATE TABLE IF NOT EXISTS `mnix_component` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(255) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`,`title`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `mnix_component`
--

INSERT INTO `mnix_component` (`id`, `title`) VALUES
(1, 'Static');

-- --------------------------------------------------------

--
-- Структура таблицы `mnix_controller`
--

DROP TABLE IF EXISTS `mnix_controller`;
CREATE TABLE IF NOT EXISTS `mnix_controller` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(255) collate utf8_unicode_ci NOT NULL,
  `component_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `mnix_controller`
--

INSERT INTO `mnix_controller` (`id`, `title`, `component_id`) VALUES
(1, 'simple', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `mnix_group`
--

DROP TABLE IF EXISTS `mnix_group`;
CREATE TABLE IF NOT EXISTS `mnix_group` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(255) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `mnix_group`
--

INSERT INTO `mnix_group` (`id`, `title`) VALUES
(1, 'anonymous');

-- --------------------------------------------------------

--
-- Структура таблицы `mnix_page`
--

DROP TABLE IF EXISTS `mnix_page`;
CREATE TABLE IF NOT EXISTS `mnix_page` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(255) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `mnix_page`
--

INSERT INTO `mnix_page` (`id`, `title`) VALUES
(1, 'index');

-- --------------------------------------------------------

--
-- Структура таблицы `mnix_page2block`
--

DROP TABLE IF EXISTS `mnix_page2block`;
CREATE TABLE IF NOT EXISTS `mnix_page2block` (
  `page_id` int(11) NOT NULL,
  `block_id` int(11) NOT NULL,
  PRIMARY KEY  (`page_id`,`block_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `mnix_page2block`
--

INSERT INTO `mnix_page2block` (`page_id`, `block_id`) VALUES
(1, 1),
(1, 2);

-- --------------------------------------------------------

--
-- Структура таблицы `mnix_template`
--

DROP TABLE IF EXISTS `mnix_template`;
CREATE TABLE IF NOT EXISTS `mnix_template` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(255) collate utf8_unicode_ci NOT NULL,
  `controller_id` int(11) NOT NULL,
  `component_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `mnix_template`
--

INSERT INTO `mnix_template` (`id`, `title`, `controller_id`, `component_id`) VALUES
(1, 'index', 1, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `mnix_test_table1`
--

DROP TABLE IF EXISTS `mnix_test_table1`;
CREATE TABLE IF NOT EXISTS `mnix_test_table1` (
  `id` int(11) NOT NULL auto_increment,
  `text` varchar(255) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Дамп данных таблицы `mnix_test_table1`
--

INSERT INTO `mnix_test_table1` (`id`, `text`) VALUES
(1, 'text11'),
(2, 'text12'),
(3, 'text13'),
(4, 'text14'),
(5, 'text15');

-- --------------------------------------------------------

--
-- Структура таблицы `mnix_test_table2`
--

DROP TABLE IF EXISTS `mnix_test_table2`;
CREATE TABLE IF NOT EXISTS `mnix_test_table2` (
  `id` int(11) NOT NULL auto_increment,
  `table1_id` int(11) NOT NULL,
  `table3_id` int(11) NOT NULL,
  `table4_id` int(11) NOT NULL,
  `text` varchar(255) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Дамп данных таблицы `mnix_test_table2`
--

INSERT INTO `mnix_test_table2` (`id`, `table1_id`, `table3_id`, `table4_id`, `text`) VALUES
(1, 1, 5, 1, 'text21'),
(2, 2, 4, 2, 'text22'),
(3, 3, 3, 3, 'text23'),
(4, 4, 2, 4, 'text24'),
(5, 5, 1, 5, 'text25');

-- --------------------------------------------------------

--
-- Структура таблицы `mnix_test_table3`
--

DROP TABLE IF EXISTS `mnix_test_table3`;
CREATE TABLE IF NOT EXISTS `mnix_test_table3` (
  `id` int(11) NOT NULL auto_increment,
  `table1_id` int(11) NOT NULL,
  `table4_id` int(11) NOT NULL,
  `text` varchar(255) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Дамп данных таблицы `mnix_test_table3`
--

INSERT INTO `mnix_test_table3` (`id`, `table1_id`, `table4_id`, `text`) VALUES
(1, 1, 5, 'text31'),
(2, 1, 4, 'text32'),
(3, 2, 3, 'text33'),
(4, 2, 2, 'text34'),
(5, 2, 1, 'text35');

-- --------------------------------------------------------

--
-- Структура таблицы `mnix_test_table4`
--

DROP TABLE IF EXISTS `mnix_test_table4`;
CREATE TABLE IF NOT EXISTS `mnix_test_table4` (
  `id` int(11) NOT NULL auto_increment,
  `text` varchar(255) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Дамп данных таблицы `mnix_test_table4`
--

INSERT INTO `mnix_test_table4` (`id`, `text`) VALUES
(1, 'text41'),
(2, 'text42'),
(3, 'text43'),
(4, 'text44'),
(5, 'text45');

-- --------------------------------------------------------

--
-- Структура таблицы `mnix_test_table124`
--

DROP TABLE IF EXISTS `mnix_test_table124`;
CREATE TABLE IF NOT EXISTS `mnix_test_table124` (
  `table1_id` int(11) NOT NULL,
  `table4_id` int(11) NOT NULL,
  PRIMARY KEY  (`table1_id`,`table4_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `mnix_test_table124`
--

INSERT INTO `mnix_test_table124` (`table1_id`, `table4_id`) VALUES
(1, 1),
(2, 1),
(3, 3),
(4, 4),
(4, 5);

-- --------------------------------------------------------

--
-- Структура таблицы `mnix_test_uri2`
--

DROP TABLE IF EXISTS `mnix_test_uri2`;
CREATE TABLE IF NOT EXISTS `mnix_test_uri2` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(255) collate utf8_unicode_ci default NULL,
  `regular` varchar(255) collate utf8_unicode_ci default NULL,
  `parametr` varchar(255) collate utf8_unicode_ci default NULL,
  `parent` int(11) default NULL,
  `page_id` int(11) default NULL,
  `obligate` tinyint(4) NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=10 ;

--
-- Дамп данных таблицы `mnix_test_uri2`
--

INSERT INTO `mnix_test_uri2` (`id`, `title`, `regular`, `parametr`, `parent`, `page_id`, `obligate`) VALUES
(1, 'index', '\\/', NULL, 0, 1, 0),
(2, 'lang', '(ru|en)', '.*', 1, 1, 0),
(3, 'faqs', 'faqs', NULL, 2, 2, 1),
(4, 'faq', 'faq\\d+', '\\d+', 2, 3, 1),
(5, 'course', 'course\\d+', '\\d+', 4, 3, 0),
(6, 'error', NULL, NULL, -1, 5, 1),
(7, 'term', 'term\\d+', '\\d+', 5, 3, 0),
(8, 'note', '\\d+', '\\d+', 4, 4, 1),
(9, 'note', 'note\\d+', '\\d+', 7, 4, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `mnix_theme`
--

DROP TABLE IF EXISTS `mnix_theme`;
CREATE TABLE IF NOT EXISTS `mnix_theme` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(255) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `mnix_theme`
--

INSERT INTO `mnix_theme` (`id`, `title`) VALUES
(1, 'Default'),
(2, 'Style1');

-- --------------------------------------------------------

--
-- Структура таблицы `mnix_uri`
--

DROP TABLE IF EXISTS `mnix_uri`;
CREATE TABLE IF NOT EXISTS `mnix_uri` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(255) collate utf8_unicode_ci NOT NULL,
  `regular` varchar(255) collate utf8_unicode_ci default NULL,
  `parametr` varchar(255) collate utf8_unicode_ci default NULL,
  `parent` int(11) NOT NULL,
  `page_id` int(11) NOT NULL,
  `obligate` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `mnix_uri`
--

INSERT INTO `mnix_uri` (`id`, `title`, `regular`, `parametr`, `parent`, `page_id`, `obligate`) VALUES
(1, 'index', '\\/', NULL, 0, 1, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `mnix_user`
--

DROP TABLE IF EXISTS `mnix_user`;
CREATE TABLE IF NOT EXISTS `mnix_user` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(255) collate utf8_unicode_ci NOT NULL,
  `group_id` int(11) NOT NULL,
  `theme_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `mnix_user`
--

INSERT INTO `mnix_user` (`id`, `title`, `group_id`, `theme_id`) VALUES
(1, 'anonymous', 1, 1);
