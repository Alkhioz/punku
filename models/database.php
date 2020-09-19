<?php
class Database
{

    public static function StartUp()
    {
        /*$HOST=$_ENV['DB_HOST'];
        $DATABASE=$_ENV['DB_DATABASE'];
        $USER=$_ENV['DB_USERNAME'];
        $PASS =$_ENV['DB_PASSWORD'];*/
        $HOST = 'localhost';
        $DATABASE = 'muskuna';
        $USER = 'root';
        $PASSWORD = '';

        $pdo = new PDO('mysql:host='.$HOST.';dbname='.$DATABASE.';charset=utf8', $USER, $PASSWORD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);	
        return $pdo;
    }
}