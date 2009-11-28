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
 * Кэширование на файлах
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
        $this->_dir = Path\CACHE . '/' . str_replace(array('_', '\\'), '/', $traces[1]['class']);
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
     * @return null
     */
    protected function _dir($dir)
    {
        if (is_string($dir)) {
            if ($dir[0] !== '/') $this->_dir .= '/' . $dir;
            else $this->_dir = Path\CACHE . '/' . substr($dir, 1);
        } else throw new Exception('Wrong type in parametr. Must be string!');
    }
    /**
     * Сохраняем кэш
     *
     * @return object(\Mnix\Cache)
     */
    public function save()
    {
        if (isset($this->_name)) {
            if (isset($this->_data)) {
                $this->_mkdir();
                $handle = fopen($this->_dir . '/' . $this->_name, 'w');
                fputs($handle, $this->_data);
                fclose($handle);
            } else $a = 0; //TODO: Тут нужно написать ворнинг, так как сохранять пустоту в кэш тупо!
        } else throw new Exception('At first, must be create name of cache! Use method name(string $name)');
        return $this;
    }
    /**
     * Загружаем кэш
     *
     * @return bool
     */
    public function load()
    {
        if (isset($this->_name)) {
            if (file_exists($this->_dir . '/' . $this->_name)) {
                $handle = fopen($this->_dir . '/' . $this->_name, 'r');
                $this->_data = fgets($handle);
                fclose($handle);
                return true;
            } else return false;
        } else throw new Exception('At first, must be create name of cache! Use method name(string $name)');
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
        $this->_rmdir($this->_dir);
        return $this;
    }
    /**
     * Создаём структуру для кэша
     *
     * @return null
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
     * Рекурсивное удаление структуры кэша
     *
     * @return null
     */
    protected function _rmdir($dir)
    {
        //GLOB_MARK - добавляет слеш к каталогам
        $files = glob($dir . '/*', GLOB_MARK);
        foreach($files as $file) {
            if(substr($file, -1) === '/') $this->_rmdir($file);
            else unlink($file);
        }
        if (is_dir($dir)) rmdir($dir);
    }
}