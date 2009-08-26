<?php

/***********************************************************************************************************************
 * Подключение к Базе данных
 *
 * Примечание:
 * Конфигурации подключения к бд лежат в файле boot/config.xml
 */

/**
 * 1. Использую дефолтную БД("DB0")
 */
$db = Mnix_Db::connect();


/**
 * 2. Явно передавая имя базы данных
 */
$db = Mnix_Db::connect('DB0');


/**
 * 3. Передавая параметры вручную
 */
$param = array(
     'type'  => 'MySql',
     'login' => 'user',
     'pass'  => 'pass',
     'host'  => 'localhost',
     'base'  => 'database');
$db = Mnix_Db::connect($param);


/**
 * 
 *
 */