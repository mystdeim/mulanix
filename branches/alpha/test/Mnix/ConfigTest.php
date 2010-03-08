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
        $cache = new Cache();
        $cache->dir('/');
        $cache->clear();

        $config = new ConfigSub(__DIR__ . '/_files/ConfigSub/configSub.xml');
        $config->load();

        unset($config);
        $config = new ConfigSub(__DIR__ . '/_files/ConfigSub/configSub.xml');
        $config->load();

        $this->assertTRUE(defined('MnixSub\ATTR1'));
        $this->assertEquals(0, \MnixSub\ATTR1);

        $this->assertTRUE(is_int(\MnixSub\ATTR2));
        $this->assertEquals(5, \MnixSub\ATTR2);

        $this->assertTRUE(is_bool(\MnixSub\ATTR3));
        $this->assertTRUE(\MnixSub\ATTR3);

        $this->assertTRUE(is_bool(\MnixSub\ATTR4));
        $this->assertFALSE(\MnixSub\ATTR4);

        $this->assertTRUE(is_string(\MnixSub\ATTR5));
        $this->assertEquals(\Mnixsub\ATTR5, 'text');

        $this->assertTRUE(is_string(\MnixSub\ATTR6\ATTR61));
        $this->assertEquals(\MnixSub\ATTR6\ATTR61, 'text');

        $this->assertTRUE(is_float(\MnixSub\ATTR7));
        $this->assertEquals(10.7, \MnixSub\ATTR7);

        $cache->clear();
    }
}