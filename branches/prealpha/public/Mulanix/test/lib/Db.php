<?php
class Test_Mnix_Db extends Mnix_Db
{
    public static function connect($param = MNIX_DEFAULT_DB)
    {
        if (!is_array($param)) {
            if (defined('MNIX_DB_' . $param .'_TYPE')) {
                $paramObj['type'] = constant('MNIX_DB_' . $param .'_TYPE');
                $paramObj['login'] = constant('MNIX_DB_' . $param .'_LOGIN');
                $paramObj['pass'] = constant('MNIX_DB_' . $param .'_PASS');
                $paramObj['host'] = constant('MNIX_DB_' . $param .'_HOST');
                $paramObj['base'] = constant('MNIX_DB_' . $param .'_BASE');
            } else throw new Exception('Not exist "' . $param . '" database.');
        } else {
            $paramObj = $param;
        }

        if (isset(self::$_instance[$paramObj['type']])) {
            foreach (self::$_instance[$paramObj['type']] as $temp) {
                if ($temp->getParam() === $paramObj) return $temp;
            }
        }
        self::$_instance[$paramObj['type']][] = new Test_Mnix_Db($paramObj);
        Test_Mnix_Core::putMessage(__CLASS__, 'sys', 'Connect to '.$paramObj['type'].' "'.$paramObj['base'].'"');
        return end(self::$_instance[$paramObj['type']]);
        
    }
    public static function getInstance()
    {
        return self::$_instance;
    }
    public function shielding($value, $mode)
    {
        $this->_setDb();
        return $this->_shielding($value, $mode);
    }
    public function placeHolder($condition, $data = null)
    {
        $this->_setDb();
        return $this->_placeHolder($condition, $data);
    }
    public static function dump()
    {
        $db = Test_Mnix_Db::connect('DB0');
        //--------------------------------------------------------------------------------------------------------------
        $db->query(
            'CREATE TABLE mnix_test_table1 (
                id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                text VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
            ) ENGINE = InnoDB;');
        $db->query(
            "INSERT INTO mnix_test_table1 (
                id,
                text
            )
            VALUES (
                '1', 'text11'
            ), (
                '2', 'text12'
            ), (
                '3', 'text13'
            ), (
                '4', 'text14'
            ), (
                '5', 'text15'
            );");
        //--------------------------------------------------------------------------------------------------------------
        $db->query(
            'CREATE TABLE mnix_test_table2 (
                id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                table1_id INT NOT NULL,
                table3_id INT NOT NULL,
                table4_id INT NOT NULL,
                text VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
            ) ENGINE = InnoDB;');
        $db->query(
            "INSERT INTO mnix_test_table2 (
                id,
                text,
                table1_id,
                table3_id,
                table4_id
            )
            VALUES (
                '1', 'text21', 1, 5, 1
            ), (
                '2', 'text22', 2, 4, 2
            ), (
                '3', 'text23', 3, 3, 3
            ), (
                '4', 'text24', 4, 2, 4
            ), (
                '5', 'text25', 5, 1, 5
            );");
        //--------------------------------------------------------------------------------------------------------------
        $db->query(
            'CREATE TABLE mnix_test_table3 (
                id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                table1_id INT NOT NULL,
                table4_id INT NOT NULL,
                text VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
            ) ENGINE = InnoDB;');
        $db->query(
            "INSERT INTO mnix_test_table3 (
                id,
                text,
                table1_id,
                table4_id
            )
            VALUES (
                '1', 'text31', 1, 5
            ), (
                '2', 'text32', 1, 4
            ), (
                '3', 'text33', 2, 3
            ), (
                '4', 'text34', 2, 2
            ), (
                '5', 'text35', 2, 1
            );");
        //--------------------------------------------------------------------------------------------------------------
        $db->query(
            'CREATE TABLE mnix_test_table4 (
                id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                text VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
            ) ENGINE = InnoDB;');
        $db->query(
            "INSERT INTO mnix_test_table4 (
                id,
                text
            )
            VALUES (
                '1', 'text41'
            ), (
                '2', 'text42'
            ), (
                '3', 'text43'
            ), (
                '4', 'text44'
            ), (
                '5', 'text45'
            );");
        //--------------------------------------------------------------------------------------------------------------
        $db->query(
            'CREATE TABLE mnix_test_table124 (
                table1_id INT NOT NULL,
                table4_id INT NOT NULL,
                    PRIMARY KEY ( `table1_id` , `table4_id` )
            ) ENGINE = InnoDB;');
        $db->query(
            "INSERT INTO mnix_test_table124 (
                table1_id,
                table4_id
            )
            VALUES (
                '1', '1'
            ), (
                '2', '1'
            ), (
                '3', '3'
            ), (
                '4', '4'
            ), (
                '4', '5'
            );");
        //--------------------------------------------------------------------------------------------------------------
    }
    public static function dump_end()
    {
        $db = Test_Mnix_Db::connect('DB0');
        $db->query('DROP TABLE mnix_test_table1');
        $db->query('DROP TABLE mnix_test_table2');
        $db->query('DROP TABLE mnix_test_table3');
        $db->query('DROP TABLE mnix_test_table4');
        $db->query('DROP TABLE mnix_test_table124');
    }
}