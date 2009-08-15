<?php
/**
 * Mulanix Framework
 *
 * @category Mulanix
 * @package Mnix_Engine
 * @version $Id$
 */
/**
 * @category Mulanix
 * @package Mnix_Engine
 * @subpackage Mnix_Engine_Uri
 */
class Mnix_Engine_Uri_controller_View extends Mnix_Core_Controller
{
    public function run()
    {
        $uries = new Mnix_ORM_Collection('Mnix_Uri');
        foreach ($uries as $uri) {

            $node = $this->_xml->createElement('uri');
            //name
            $attrName = $this->_xml->createAttribute('name');
            $node->setAttributeNode($attrName);
            $textName = $this->_xml->createTextNode($uri->name);
            $attrName->appendChild($textName);
            //parent
            $attrName = $this->_xml->createAttribute('parent');
            $node->setAttributeNode($attrName);
            $textName = $this->_xml->createTextNode($uri->parent);
            $attrName->appendChild($textName);
            //page
            $page = $uri->page;
            var_dump($page);
            $page->load();
            var_dump($page);
            exit();
            $nodePage = $this->_xml->createElement('page');
            $attrName = $this->_xml->createAttribute('name');
            $nodePage->setAttributeNode($attrName);
            $textName = $this->_xml->createTextNode($page->name);
            $attrName->appendChild($textName);
            $node->appendChild($nodePage);

            $this->_xmlNodeTemplate->appendChild($node);
        }
    }
}