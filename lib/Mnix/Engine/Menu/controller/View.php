<?php
/**
 * Mulanix Framework
 *
 * @category Mulanix
 * @package Mnix_Engine
 * @version 2009-08-13
 * @since 2009-08-13
 */
/**
 * Контроллер вывода админского меню
 *
 * @category Mulanix
 * @package Mnix_Engine
 * @subpackage Menu
 */
class Mnix_Engine_Menu_controller_View extends Mnix_Core_Controller
{
    public function run()
    {
        $menus = new Mnix_ORM_Collection('Mnix_Engine_Menu');
        $menus->find('?t = ?i', array('group', 1));
        foreach($menus as $menu) {

            //Создаём ноду 'menu'
            $node = $this->_xml->createElement('menu');

            //Создаём аттрибут 'name'
            $attr = $this->_xml->createAttribute('name');
            $node->setAttributeNode($attr);
            $text = $this->_xml->createTextNode($menu->name);
            $attr->appendChild($text);

            //Создаём аттрибут 'link'
            $attr = $this->_xml->createAttribute('link');
            $node->setAttributeNode($attr);
            $text = $this->_xml->createTextNode($menu->value);
            $attr->appendChild($text);

            //Присоединяем ноду
            $this->_xmlNodeTemplate->appendChild($node);
        }
    }
}