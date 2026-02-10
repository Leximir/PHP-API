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
}