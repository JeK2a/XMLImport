<?php

namespace db;

use PDO;
use PDOException;

class DB
{
    public static function getConnection()
    {
        $params = [
            'dbdriver'  => 'mysql',
            'dbcharset' => 'UTF8',
            'host'      => '37.140.192.106',
            'dbname'    => 'u1078224_default',
            'user'      => 'u1078224_default',
            'password'  => 'lI_41bBq'
        ];

        try {
            // Устанавливаем соединение
            $dsn = "{$params['dbdriver']}:host={$params['host']};dbname={$params['dbname']}";
            $db = new PDO($dsn, $params['user'], $params['password']);

            // Задаем кодировку
            $db->exec("set names utf8");
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }

        return $db;
    }
}