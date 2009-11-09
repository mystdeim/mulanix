<?php
 /**
 * Mulanix Framework
 *
 * @category Mulanix
 * @version $Id$
 * @author mystdeim <mysteim@gmail.com>
 */
namespace Mnix;
/**
 * Кэширование
 *
 * @category Mulanix
 */
class Cache
{
    /**
     * Директория для кэшируемых данных
     *
     * @var string
     */
    protected $_dir;
    /**
     * Имя для кэшируемых данных
     *
     * @var string
     */
    protected $_name = null;
    /**
     * Хэш
     * 
     * @var string
     */
    protected $_hash = false;
    /**
     * Содержимое кэша
     *
     * @var string
     */
    protected $_data = null;
    /**
     * Конструктор
     *
     * @param string $dir
     * @return object(Mnix_Cache)
     */
    public function  __construct($dir = null)
    {
        $traces = debug_backtrace(false);
        $this->_dir = Path\CACHE . str_replace(array('_', '\\'), '/', $traces[1]['class']);
        if (isset($dir)) $this->_dir($dir);
    }
    /**
     * Задаёт директорию
     *
     * @param string $dir
     * @return string|object(Mnix_Cache)
     */
    public function dir($dir = null)
    {
        if (isset($dir)) {
            $this->_dir($dir);
            return $this;
        } else return \str_replace(Path\CACHE, null, $this->_dir);
    }
    /**
     * Задаёт директорию
     *
     * @param string $dir
     */
    protected function _dir($dir)
    {
        if (is_string($dir)) {
            if ($dir[0] !== '/') $this->_dir .= '/' . $dir;
            else $this->_dir = Path\CACHE . substr($dir, 1);
        } else throw new Exception('Wrong type in parametr. Must be string!');
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
     *
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
        $path = MNIX_dir_CACHE . str_replace(array(MNIX_dir_DIR, '.php'), null, $this->_dir) . '/' . $name;
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
        $this->_dirCache = $path;
        return $this;
    }
    /**
     * Достаём данные из кэша
     *
     * @return mixed
     */
    public function get()
    {
        return $this->_content;
    }
    /**
     * Суём данные, которые будем кэшировать
     *
     * @param mixed $content
     * @return mixed|object(\Mnix\Cache)
     */
    public function data($data = null)
    {
        if (isset($data)) {
            $this->_data = serialize($data);
            return $this;
        } else return unserialize($this->_data);
    }
    /**
     * Имя для кэшируемых данных
     *
     * @param string $name
     * @return string|object(\Mnix\Cache)
     */
    public function name($name = null)
    {
        if (isset($name)) {
            if (is_string($name)) {
                $this->_name = $name;
                return $this;
            } else throw new Exception('Wrong type in parametr. Must be string!');
        } else return $this->_name;
    }
    /**
     * Указываем нужно ли брать хэш
     *
     * @param bool $flag
     * @return bool|object(\Mnix\Cache)
     */
    public function hash($flag = null)
    {
        if (isset($flag)) {
            if (is_bool($flag)) {
                $this->_hash = $flag;
                return $this;
            } else throw new Exception('Wrong type in parametr. Must be bool!');
        } else return $this->_hash;
    }
    public function clear()
    {
        //var_dump($this->_dir);
        $this->_rmdir($this->_dir);
        //$this->_rmdir(Path\CACHE);
        return $this;
    }
    /**
     * Создаём путь до кэша
     *
     */
    protected function _mkdir()
    {
        if (!is_dir($this->_dir)) {
            $local = Path\CACHE;
            if (!is_dir($local)) mkdir($local);
            foreach (explode('/', $this->dir()) as $temp) {
                $local .= $temp.'/';
                if (!is_dir($local)) mkdir($local);
            }
        }
    }
    /**
     * Рекурсивное удаление каталогов
     */
    protected function _rmdir($dir)
    {
        //GLOB_MARK - добавляет слеш к каталогам
        $files = glob($dir . '/' . '*', GLOB_MARK);
        foreach($files as $file) {
            if(substr($file, -1) === '/') $this->_rmdir($file);
            else {
                var_dump($file);
                //unlink($file);
            }
        }
        if (is_dir($dir)) {
            var_dump($dir);
            //rmdir($dir);
        }
    }
}