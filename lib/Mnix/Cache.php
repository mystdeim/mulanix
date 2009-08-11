<?php
/**
 * Mulanix Framework
 *
 * @package Mnix_Cache
 * @author deim
 * @copyright 2009
 */
/**
 * @package Mnix_Cache
 */
class Mnix_Cache
{
    /**
     * Путь до класса, вызывающего кэш
     * @var string
     */
    protected $_path;
    /**
     * Путь кэша
     * @var string
     */
    protected $_pathCache;
    /**
     * Имя для кэшируемых данных
     * @var string
     */
    protected $_name = null;
    /**
     * Путь до файла, который будет кэшироваться
     * @var string
     */
    protected $_file = null;
    /**
     * Флаг упаковки
     * @var boolean
     */
    protected $_serialize = false;
    protected $_tag = null;
    /**
     * Хэш
     * @var string
     */
    protected $_hash = null;
    /**
     * Содержимое кэша
     * @var string
     */
    protected $_content = false;
    protected $_mode;
    public function  __construct($path)
    {
        $this->_path = $path;
        return $this;
    }
    /**
     * Сохраняем кэш
     *
     * @return object(Mnix_Cache)
     */
    public function save()
    {
        //Создаём дерево каталогов
        if (isset($this->_name)) {
            if ($this->_hash === 'n') $name = md5($this->_name);
            else $name = $this->_name;
        } else {
            if ($this->_hash === 'f') $name = md5_file($this->_file);
        }
        $path = $this->_mkdir() . $name;
        //Пишем в файл
        $handle = fopen($path, 'w');
        if ($this->_serialize) $content = serialize($this->_content);
        else $content = $this->_content;
        fputs($handle, $content);
		fclose($handle);
        Mnix_Core::putMessage(__CLASS__, 'sys', 'Save cache to ' . $path);
        Mnix_Core::putCount('cache_s');
        return $this;
    }
    /**
     * Загружаем кэш
     * @return object(Mnix_Cache)
     */
    public function load()
    {
        if (isset($this->_name)) {
            if ($this->_hash === 'n') $name = md5($this->_name);
            else $name = $this->_name;
        } else {
            if ($this->_hash === 'f') $name = md5_file($this->_file);
        }
        $path = MNIX_CACHE . str_replace(array(MNIX_DIR, '.php'), null, $this->_path) . '/' . $name;
        $path = str_replace('//', '/', $path);
        Mnix_Core::putMessage(__CLASS__, 'sys', 'Request cache from ' . $path);
        Mnix_Core::putCount('cache_r');
        if (file_exists($path)) {
            $content = fgets(fopen($path, 'r'));
            if ($this->_serialize) $this->_content = unserialize($content);
            else $this->_content = $content;
            Mnix_Core::putMessage(__CLASS__, 'sys', 'Load cache from ' . $path);
            Mnix_Core::putCount('cache_l');
        }
        $this->_name = $name;
        $this->_pathCache = $path;
        return $this;
    }
    /**
     * Достаём данные из кэша
     * @return mixed
     */
    public function get()
    {
        return $this->_content;
    }
    /**
     * Суём данные, которые будем кэшировать
     * @return object(Mnix_Cache)
     */
    public function put($content)
    {
        $this->_content = $content;
        return $this;
    }
    public function mode()
    {
        
    }
    /**
     * Имя для кэшируемых данных
     */
    public function name($name = null)
    {
        if ($name) {
            $this->_name = $name;
            return $this;
        } else return $this->_name;
    }
    /**
     * Указываем, откуда брать данные для кэширования
     *
     * $flag:
     *  true - из файла
     */
    public function file($file)
    {
        $this->_file = $file;
        return $this;
    }
    /**
     * Указываем от чего брать хэш
     *
     * $flag:
     *  'f' - file
     *  'n' - name
     *
     */
    public function hash($flag)
    {
        $this->_hash = $flag;
        return $this;
    }
    public function tag()
    {

    }
    public function size()
    {

    }
    /**
     * Удаление файла с кэшом
     */
    public function remove()
    {
        $path = MNIX_CACHE . str_replace(array(MNIX_DIR, '.php'), null, $this->_path) . '/' . $this->_name;
        unlink($path);
        Mnix_Core::putMessage(__CLASS__, 'sys', 'Remove cache ' . $path);
        Mnix_Core::putCount('cache_d');
    }
    public function clear()
    {
        $path = MNIX_CACHE . str_replace(array(MNIX_DIR, '.php'), null, $this->_path) . '/*';
        $path = str_replace('//', '/', $path);
        self::_delete($path);
    }
    public function path()
    {
        return $this->_pathCache;
    }
    public function serialize($flag = false)
    {
        $this->_serialize = $flag;
        return $this;
    }
    /**
     * Создаём путь до кэша
     * @return string
     */
    protected function _mkdir()
    {
        $diff = explode('/', str_replace(array(MNIX_DIR, '.php'), null, $this->_path));
        $path = MNIX_CACHE . implode($diff, '/') . '/';
        if (!is_dir($path)) {
			$local = MNIX_CACHE;
			foreach ($diff as $temp) {
				$local .= $temp.'/';
				if (!is_dir($local)) mkdir($local);
			}
		}
        return $path;
    }
    /**
     * Рекурсивное удаление кэша
     */
	static protected function _delete($path)
    {
		foreach (glob($path.'*') as $temp) {
			if (is_dir($temp)) {
				self::_delete($temp.'/');
				rmdir($temp);
			}
			else {
                unlink($temp);
            }
		}
	}
}