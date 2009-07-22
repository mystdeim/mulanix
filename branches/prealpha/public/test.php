<?php
/**
 * Description of Application
 *
 * @author deim
 * Created on 24.04.2009, 11:01:50
 */
require_once 'Mulanix/boot/firstconfig.php';
require_once 'Mulanix/test/lib/Core.php';
$app = new Test_Mnix_Core();
$app->run();