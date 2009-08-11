<?php
/**
 * Mulanix Framework
 *
 * @category Mulanix
 * @package Mnix_Core
 * @version 2009-08-10
 * @since 2009-08-10
 */
/**
 * @category Mulanix
 * @package Mnix_Core
 */
abstract class Mnix_Core_Controller
{
	protected $_uriParam;
	protected $_xmlNode;

	public function __construct($xmlNode, $uriParam)
    {
        $this->_xmlNode = $xmlNode;
        $this->_uriParam = $uriParam;
	}

    abstract public function run();
}