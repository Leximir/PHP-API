<?php

namespace config;

class Database
{
//    Database parameters
    private $host = 'localhost';
    private $db_name = 'my_blog';
    private $username = 'root';
    private $password = '';
    private $conn;

//    Database connect

    public function connect()
    {
        $this->conn = null;

        try {
            $this->conn = new \PDO('mysql:host=' . $this->host . ';dbname=' . $this->db_name, $this->username, $this->password);

            // Kreira novi PDO objekat (konekcija na MySQL).
            // DSN string: "mysql:host=...;dbname=..." se sklapa iz varijabli klase.
            // Drugi i treći parametar su username i password za bazu.
            // "\PDO" znači da koristi globalnu PDO klasu (nije iz namespace-a config).

            $this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            // Podešava PDO da baca exception kada se desi greška (lakše za debuggiranje).
            // ATTR_ERRMODE = način rukovanja greškama, EXCEPTION = bacaj izuzetak.

        } catch(\PDOException $e) {

            // Hvata PDOException ako konekcija ne uspije ili dođe do PDO greške.
            // $e sadrži detalje greške.

            echo 'Connection error: ' . $e->getMessage();

            // Ispisuje poruku o grešci.
            // TIP: U produkciji je bolje logovati grešku, a ne echo-ovati korisniku.
        }

        return $this->conn;
    }
}