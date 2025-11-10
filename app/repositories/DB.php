<?php
namespace app\repositories;
use PDO;
class DB{
    private static $conn=null;
    public static function getConnection(){
        if (self::$conn==null){
            $config = require PROJECT_ROOT . 'config/config.php';
            
            self::$conn=new PDO('mysql:host=' . $config['db_host'] . ';port=3306;dbname=' . $config['db_name'], $config['db_user'], $config['db_pass']);
        }
    return self::$conn;
    }
}

?>
