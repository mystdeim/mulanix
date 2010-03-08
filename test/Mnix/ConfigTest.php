<?php
/**
 * Mulanix Framework
 */
namespace Mnix;

require_once '_files/ConfigSub.php';
/**
 * Mulanix Framework
 *
 * @author deim
 */
class ConfigTest extends \PHPUnit_Framework_TestCase
{
    public function test1()
    {
        $config = new ConfigSub(__DIR__ . '/_files/ConfigSub/configSub.xml');
        $config->load();

        $this->assertTRUE(defined('Mnix\ATTR1'));
        //$this->assertEquals(\Mnix\ATTR1, 0);
    }
}