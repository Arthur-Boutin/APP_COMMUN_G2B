<?php

require_once(__DIR__ . '/../config/constants.php');

function connectToSharedDB(): PDO
{
    $host = SHARED_DB_HOST;
    $db = SHARED_DB_NAME;
    $user = SHARED_DB_USER;
    $pass = SHARED_DB_PASS;
    $port = DB_PORT;
    $charset = DB_CHARSET;

    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset;port=$port";

    try {
        return new PDO($dsn, $user, $pass, $options);
    } catch (\PDOException $e) {
        throw new \PDOException($e->getMessage(), (int)$e->getCode());
    }
}