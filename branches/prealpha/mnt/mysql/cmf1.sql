-- phpMyAdmin SQL Dump
-- version 3.1.2deb1ubuntu0.1
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Авг 14 2009 г., 18:35
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
  `name` varchar(255) collate utf8_unicode_ci NOT NULL,
  `title` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Дамп данных таблицы `mnix_block`
--

INSERT INTO `mnix_block` (`id`, `name`, `title`) VALUES
(1, 'main', NULL),
(2, 'left', NULL),
(3, 'top', NULL),
(4, 'bottom', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `mnix_component`
--

DROP TABLE IF EXISTS `mnix_component`;
CREATE TABLE IF NOT EXISTS `mnix_component` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) collate utf8_unicode_ci NOT NULL,
  `title` int(11) default NULL,
  PRIMARY KEY  (`id`,`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Дамп данных таблицы `mnix_component`
--

INSERT INTO `mnix_component` (`id`, `name`, `title`) VALUES
(1, 'Mnix_Engine_Static', NULL),
(2, 'Mnix_Engine_Page', NULL),
(3, 'Mnix_Engine_Menu', NULL),
(4, 'Mnix_Engine_Uri', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `mnix_controller`
--

DROP TABLE IF EXISTS `mnix_controller`;
CREATE TABLE IF NOT EXISTS `mnix_controller` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) collate utf8_unicode_ci NOT NULL,
  `title` int(11) default NULL,
  `component_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Дамп данных таблицы `mnix_controller`
--

INSERT INTO `mnix_controller` (`id`, `name`, `title`, `component_id`) VALUES
(1, 'Simple', NULL, 1),
(2, 'View', NULL, 2),
(3, 'View', NULL, 3),
(4, 'View', NULL, 4);

-- --------------------------------------------------------

--
-- Структура таблицы `mnix_group`
--

DROP TABLE IF EXISTS `mnix_group`;
CREATE TABLE IF NOT EXISTS `mnix_group` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) collate utf8_unicode_ci NOT NULL,
  `title` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `mnix_group`
--

INSERT INTO `mnix_group` (`id`, `name`, `title`) VALUES
(1, 'anonymous', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `mnix_menu`
--

DROP TABLE IF EXISTS `mnix_menu`;
CREATE TABLE IF NOT EXISTS `mnix_menu` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `value` varchar(255) default NULL,
  `title` int(11) default NULL,
  `weight` int(11) default NULL,
  `parent_id` int(11) default NULL,
  `group` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Дамп данных таблицы `mnix_menu`
--

INSERT INTO `mnix_menu` (`id`, `name`, `value`, `title`, `weight`, `parent_id`, `group`) VALUES
(1, 'admin', '/admin', NULL, 0, 0, 1),
(2, 'pages', '/admin/pages', NULL, 2, 0, 1),
(3, 'uri', '/admin/uri', NULL, 1, 0, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `mnix_page`
--

DROP TABLE IF EXISTS `mnix_page`;
CREATE TABLE IF NOT EXISTS `mnix_page` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) collate utf8_unicode_ci NOT NULL,
  `title` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Дамп данных таблицы `mnix_page`
--

INSERT INTO `mnix_page` (`id`, `name`, `title`) VALUES
(1, 'index', NULL),
(2, 'admin', NULL),
(3, 'pages', NULL),
(4, 'error_404', NULL),
(5, 'uries', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `mnix_page2template2block`
--

DROP TABLE IF EXISTS `mnix_page2template2block`;
CREATE TABLE IF NOT EXISTS `mnix_page2template2block` (
  `page_id` int(11) NOT NULL,
  `template_id` int(11) NOT NULL,
  `block_id` int(11) NOT NULL,
  PRIMARY KEY  (`page_id`,`template_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Дамп данных таблицы `mnix_page2template2block`
--

INSERT INTO `mnix_page2template2block` (`page_id`, `template_id`, `block_id`) VALUES
(1, 1, 1),
(1, 2, 2),
(1, 6, 3),
(1, 7, 4),
(2, 3, 1),
(2, 5, 2),
(2, 6, 3),
(2, 7, 4),
(3, 4, 1),
(3, 5, 2),
(3, 6, 3),
(3, 7, 4),
(5, 5, 2),
(5, 6, 3),
(5, 7, 4),
(5, 8, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `mnix_template`
--

DROP TABLE IF EXISTS `mnix_template`;
CREATE TABLE IF NOT EXISTS `mnix_template` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) collate utf8_unicode_ci NOT NULL,
  `title` int(11) default NULL,
  `controller_id` int(11) NOT NULL,
  `component_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=9 ;

--
-- Дамп данных таблицы `mnix_template`
--

INSERT INTO `mnix_template` (`id`, `name`, `title`, `controller_id`, `component_id`) VALUES
(1, 'Index', NULL, 1, 1),
(2, 'TestMenu', NULL, 1, 1),
(3, 'Admin', NULL, 1, 1),
(4, 'View', NULL, 2, 2),
(5, 'View', NULL, 3, 3),
(6, 'Top', NULL, 1, 1),
(7, 'Bottom', NULL, 1, 1),
(8, 'View', NULL, 4, 4);

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
  `name` varchar(255) collate utf8_unicode_ci default NULL,
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

INSERT INTO `mnix_test_uri2` (`id`, `name`, `regular`, `parametr`, `parent`, `page_id`, `obligate`) VALUES
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
  `name` varchar(255) collate utf8_unicode_ci NOT NULL,
  `title` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `mnix_theme`
--

INSERT INTO `mnix_theme` (`id`, `name`, `title`) VALUES
(1, 'Default', 0),
(2, 'Style1', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `mnix_uri`
--

DROP TABLE IF EXISTS `mnix_uri`;
CREATE TABLE IF NOT EXISTS `mnix_uri` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) collate utf8_unicode_ci NOT NULL,
  `regular` varchar(255) collate utf8_unicode_ci default NULL,
  `parametr` varchar(255) collate utf8_unicode_ci default NULL,
  `parent` int(11) NOT NULL,
  `page_id` int(11) NOT NULL,
  `obligate` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

--
-- Дамп данных таблицы `mnix_uri`
--

INSERT INTO `mnix_uri` (`id`, `name`, `regular`, `parametr`, `parent`, `page_id`, `obligate`) VALUES
(1, 'index', '\\/', NULL, 0, 1, 1),
(2, 'lang', '(ru|en)', '.*', 1, 1, 0),
(3, 'admin', 'admin', NULL, 2, 2, 1),
(4, 'pages', 'pages', NULL, 3, 3, 1),
(5, 'error_404', NULL, NULL, -1, 4, 0),
(6, 'uries', 'uri', NULL, 3, 5, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `mnix_user`
--

DROP TABLE IF EXISTS `mnix_user`;
CREATE TABLE IF NOT EXISTS `mnix_user` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) collate utf8_unicode_ci NOT NULL,
  `title` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `theme_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `mnix_user`
--

INSERT INTO `mnix_user` (`id`, `name`, `title`, `group_id`, `theme_id`) VALUES
(1, 'anonymous', 0, 1, 1);
