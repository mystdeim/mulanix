<?php
/**
 * Mulanix Framework
 *
 * @category Mulanix
 * @package Mnix_Engine
 * @version $Id$
 */
/**
 * Репликация компонентов в БД из файловой системой
 *
 * @category Mulanix
 * @package Mnix_Engine
 */
class Mnix_Engine_Replication
{
    /**
     * Типы файлов, которые учитываться при поиске
     *
     * @var array
     */
    protected $_types = array('php', 'xsl');
    /**
     * Директории для поиска
     *
     * @var array
     */
    protected $_dirs = array(
        'component' => MNIX_LIB,
        'theme'     => MNIX_THEME);
    protected function getFiles()
    {
        
    }
}