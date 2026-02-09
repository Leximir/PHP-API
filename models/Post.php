<?php

namespace models;

class Post {
//    Database stuff
    private $conn;
    private $table = 'posts';

// Post properties
    public $id;
    public $category_id;
    public $category_name;
    public $title;
    public $body;
    public $author;
    public $created_at;

//  Constructor with Database
    public function __construct($db)
    {
        $this->conn = $db;
    }

//    Get Posts
    public function read()
    {
        $query =
        "SELECT 
            c.name AS category_name,
            p.id,
            p.category_id,
            p.title,
            p.body,
            p.author,
            p.created_at
        FROM 
            " . $this->table . " p 
        LEFT JOIN 
            categories c ON p.category_id = c.id
        ORDER BY 
            p.created_at DESC";

        // SQL upit:
        // - SELECT: bira kolone koje želiš
        // - c.name AS category_name: uzima naziv kategorije i daje alias "category_name"
        // - p.*: uzima polja iz posts tabele (ali ovdje si ih nabrojao posebno)
        // - FROM: koristi tabelu posts, ali dinamički preko $this->table i daje alias "p"
        // - LEFT JOIN categories: spaja tabelu categories kao "c"
        //   tako da i postovi bez kategorije (NULL category_id) i dalje mogu biti vraćeni
        // - ON p.category_id = c.id: uslov spajanja
        // - ORDER BY p.created_at DESC: najnoviji postovi prvi

//        Prepare and Execute statement
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }


}
