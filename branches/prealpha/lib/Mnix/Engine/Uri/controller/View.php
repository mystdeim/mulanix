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
            //uri
            $node = $this->_xml->createElement('uri');
            //uri->id
            $attrName = $this->_xml->createAttribute('id');
            $node->setAttributeNode($attrName);
            $textName = $this->_xml->createTextNode($uri->id);
            $attrName->appendChild($textName);
            //uri->name
            $attrName = $this->_xml->createAttribute('name');
            $node->setAttributeNode($attrName);
            $textName = $this->_xml->createTextNode($uri->name);
            $attrName->appendChild($textName);
            //uri->parent
            $attrName = $this->_xml->createAttribute('parent');
            $node->setAttributeNode($attrName);
            $textName = $this->_xml->createTextNode($uri->parent);
            $attrName->appendChild($textName);
            //uri->page
            $page = $uri->page;
            $nodePage = $this->_xml->createElement('page');
            //uri->page->name
            $attrName = $this->_xml->createAttribute('name');
            $nodePage->setAttributeNode($attrName);
            $textName = $this->_xml->createTextNode($page->name);
            $attrName->appendChild($textName);
            $node->appendChild($nodePage);
            //uri->page->regions
            $regions = $page->regions;
            foreach ($regions as $region) {
                //uri->page->region
                $noderegion = $this->_xml->createElement('region');
                $attrName = $this->_xml->createAttribute('name');
                $noderegion->setAttributeNode($attrName);
                $textName = $this->_xml->createTextNode($region->name);
                $attrName->appendChild($textName);
                $nodePage->appendChild($noderegion);
                //uri->page->templates
                $templates = $region->templates;
                //Уточняем поиск, выбираем шаблоны, соответствующие текущей странице
                $templates->find('?t = ?i', array('mnix_page2template2region.page_id', $page->id));
                foreach ($templates as $template) {
                    //uri->page->region
                    $nodeTemplate = $this->_xml->createElement('template');
                    $attrName = $this->_xml->createAttribute('name');
                    $nodeTemplate->setAttributeNode($attrName);
                    $textName = $this->_xml->createTextNode($template->name);
                    $attrName->appendChild($textName);
                    $noderegion->appendChild($nodeTemplate);
                }
            }

            $this->_xmlNodeTemplate->appendChild($node);
        }
    }
}