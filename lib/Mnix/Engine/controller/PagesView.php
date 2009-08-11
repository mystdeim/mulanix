<?php
/**
 * Mulanix Framework
 *
 * @category Mulanix
 * @package Mnix_Engine
 * @version 2009-08-10
 * @since 2009-08-10
 */
/**
 * @category Mulanix
 * @package Mnix_Engine
 */
class Mnix_Engine_controller_PagesView extends Mnix_Core_Controller
{
    public function run()
    {
        var_dump($this->_xmlNode);
        $pages = new Mnix_ORM_Collection('Mnix_Engine_Page');
        $pages->load();
        var_dump($pages);
        foreach ($pages as $page) {
            var_dump($page->getName());
        }
    }
}