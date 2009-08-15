<?php
/**
 * Mulanix Framework
 *
 * @category Mulanix
 * @package Mnix_Engine
 * @version 2009-08-12
 * @since 2009-08-10
 */
/**
 * Контроллер вывода списка страниц
 *
 * @category Mulanix
 * @package Mnix_Engine
 * @subpackage Page
 */
class Mnix_Engine_Page_controller_View extends Mnix_Core_Controller
{
    public function run()
    {
        $pages = new Mnix_ORM_Collection('Mnix_Engine_Page');
        foreach ($pages as $page) {
            //var_dump($page->getName());
            $node = $this->_xml->createElement('page');
            $attrName = $this->_xml->createAttribute('name');
            $node->setAttributeNode($attrName);
            $textName = $this->_xml->createTextNode($page->name);
            $attrName->appendChild($textName);

            $this->_xmlNodeTemplate->appendChild($node);
        }
    }
}