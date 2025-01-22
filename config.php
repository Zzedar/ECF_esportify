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
        $url = parse_url(getenv("JAWSDB_URL"));
        $this->host = $url["q2gen47hi68k1yrb.chr7pe7iynqr.eu-west-1.rds.amazonaws.com"];
        $this->db_name = $url["r92r3g8xxrrs5dfd"];
        $this->username = $url["fkadbr0r78s8j5iv"];
        $this->password = substr($url["e3pqkuprmh1aj5nm"], 1);
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