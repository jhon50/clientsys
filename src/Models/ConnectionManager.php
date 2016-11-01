<?php

namespace Setra\Models;

use PDO;

class ConnectionManager
{
    const CONFIG_DIR = "config/db.ini";
    public static $conn = false;

    public static function get() 
    {

        if (self::$conn) {
            return self::$conn;
        }

        $dbConfig = parse_ini_file(self::CONFIG_DIR, true);        
        $baseConfig = $dbConfig['base'];
        
        $dsn = "{$baseConfig['db']}:host={$baseConfig['servername']};dbname={$baseConfig['dbname']}";
        self::$conn = new PDO($dsn, $baseConfig['username'], $baseConfig['password']);
        self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        return self::$conn;
    }
}
