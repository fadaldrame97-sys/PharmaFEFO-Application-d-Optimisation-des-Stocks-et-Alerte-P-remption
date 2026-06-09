<?php

class Database{
    private static? PDO $pdo=null;

    public static function getConnection() : PDO {
        if(self::$pdo===null){
            self::$pdo=new PDO('mysql:host=localhost;dbname=PharmaFEFO','root','');

            self::$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        }
        return self::$pdo;

        
    }


}