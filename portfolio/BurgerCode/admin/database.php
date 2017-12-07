<?php

class DataBase{
    
    private static $dbHost = "localhost";
    private static $dbName = "burger_code";
    private static $dbUser = "burger_admin";
    private static $dbUserPassword = "BurgerAdmin2@";

    private static $connection = null;
    
    public static function connect(){
        try{
            self::$connection = new PDO("mysql:host=".self::$dbHost.";dbname=".self::$dbName,self::$dbUser,self::$dbUserPassword);
        }
        catch(PDOEception $e)
        {
            die($e->getMessage());
        }
        return self::$connection;
    }
    
    public static function disconnect(){
        $connection = null;
    }
}

?>