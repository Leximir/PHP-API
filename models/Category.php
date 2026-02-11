<?php

namespace models;

class Category
{
//    Database stuff
    private $conn;
    private $table = 'categories';

// Category properties
    public $id;
    public $name;
    public $created_at;

//  Constructor with Database
    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function index()
    {
        $query =
        "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function show()
    {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();

        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        $this->name = $row['name'];
        $this->created_at = $row['created_at'];

        return $stmt;
    }

    public function create()
    { //"2026-02-10 00:00:49"
        $query =
            "INSERT INTO " . $this->table . " " .
            "SET 
                name = :name,
                created_at = :created_at";

        $stmt = $this->conn->prepare($query);

        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->created_at = date("Y-m-d H:i:s");

        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":created_at", $this->created_at);

        if($stmt->execute()){
            return true;
        }

        printf("Error: %s.\n", $stmt->errorInfo());
        return false;
    }

    public function delete()
    {
        $query =
            "DELETE FROM " . $this->table . " " .
            "WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(":id", $this->id);

        if($stmt->execute()){
            return true;
        }

        printf("Error: %s.\n", $stmt->errorInfo());
        return false;
    }
}