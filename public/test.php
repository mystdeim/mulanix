<?php
/**
 * Тестирование
 *
 * Для проведения всех тестов: запустить http://localhost/test
 * Для конкретного теста: http://localhost/test/__CLASS__
 * где __CLASS__ названия определённого классна, например: Test_Mnix_DbTest
 * 
 * @author deim
 * Created on 24.04.2009, 11:01:50
 */
require_once 'Mulanix/boot/firstconfig.php';
require_once 'Mulanix/test/lib/Core.php';
$app = new Test_Mnix_Core();
$app->run();