<?php
/**
 * Mulanix Framework
 */
namespace Mnix\UriSub;
/**
 * Mulanix Framework
 *
 * @author deim
 */
class LangMok
{
    public $short;
    public function __construct($short = null)
    {
        $this->short = $short;
    }
    public function load()
    {
        if ($this->short === 'ru' || $this->short === 'en') return true;
        else return false;
    }
}