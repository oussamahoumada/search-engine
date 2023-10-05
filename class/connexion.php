<?php
class connexion extends PDO
{
    const USER = "root";
    const PASSWD = "";
    const SERVER = "localhost";
    const BASE = "recherche_db";
    private $dsn;
    public function __construct()
    {
        try {
            $this->dsn = "mysql:dbname=" . self::BASE . ";host=" . self::SERVER . ";port=3306;charset=utf8";
            parent::__construct($this->dsn, self::USER, self::PASSWD, [PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING]);
        } catch (PDOException $e) {
            echo 'ERREUR PDO dans ' . $e->getFile() . ' L.' . $e->getLine() . ' : ' . $e->getMessage();
            exit();
        }
    }
}