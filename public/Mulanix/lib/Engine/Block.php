<?php
/**
 * Mulanix Framework
 *
 * @category Mulanix
 * @package Mnix_Engine
 * @since 2008-10-01
 * @version 2009-07-30
 */
/**
 * Блок, содержащий шаблоны
 *
 * @category Mulanix
 * @package Mnix_Engine
 */
class Mnix_Engine_Block extends Mnix_ORM_Prototype
{
    protected $_table = 'mnix_block';
    protected $_has_many = array(
		'pages'     => array(
				    'class'  => 'Mnix_Engine_Page',
				    'fk'     => 'block_id',
                                    'id'     => 'page_id',
                                    'jtable' => 'mnix_page2block'),
                'templates' => array(
				    'class'  => 'Mnix_Engine_Template',
				    'fk'     => 'block_id',
                                    'id'     => 'template_id',
                                    'jtable' => 'mnix_block2template'));
}