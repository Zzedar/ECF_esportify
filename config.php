<?php

class Database
{
    private $conn;
    private $host;
    private $db_name;
    private $username;
    private $password;

    public function __construct()
    {
        $url = parse_url("mysql://r92r3g8xxrrs5dfd:fkadbr0r78s8j5iv@q2gen47hi68k1yrb.chr7pe7iynqr.eu-west-1.rds.amazonaws.com:3306/e3pqkuprmh1aj5nm");

        $this->host = $url["host"];
        $this->db_name = ltrim($url["path"], '/');
        $this->username = $url["user"];
        $this->password = $url["pass"];

    }

    public function getConnection()
    {
        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
        } catch (PDOException $exception) {
            echo "Erreur de connexion: " . $exception->getMessage();
        }

        return $this->conn;
    }

}
?>