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
    public function index()
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

    // Get single post (show)
    public function show()
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
        WHERE 
            p.id = ?
        LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id); // Bind id to query
        $stmt->execute();

        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        $this->title = $row['title'];
        $this->body = $row['body'];
        $this->author = $row['author'];
        $this->created_at = $row['created_at'];
        $this->category_id = $row['category_id'];
        $this->category_name = $row['category_name'];

        return $stmt;

    }

    public function create()
    {
        // Create query
        $query =
            "INSERT INTO " . $this->table . " " .
            "SET 
                title = :title,
                body = :body,
                author = :author,
                category_id = :category_id";

        // Prepare stmt
        $stmt = $this->conn->prepare($query);

        // Clean data
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->body = htmlspecialchars(strip_tags($this->body));
        $this->author = htmlspecialchars(strip_tags($this->author));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));

        // Bind data
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":body", $this->body);
        $stmt->bindParam(":author", $this->author);
        $stmt->bindParam(":category_id", $this->category_id);

        if($stmt->execute()){
            return true;
        }

        // Return error
        printf("Error: %s.\n", $stmt->errorInfo());
        return false;
    }

    public function update()
    {
        // Create query
        $query =
            "UPDATE " . $this->table . " " .
            "SET 
                title = :title,
                body = :body,
                author = :author,
                category_id = :category_id
            WHERE id = :id";

        // Prepare stmt
        $stmt = $this->conn->prepare($query);

        // Clean data
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->body = htmlspecialchars(strip_tags($this->body));
        $this->author = htmlspecialchars(strip_tags($this->author));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind data
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":body", $this->body);
        $stmt->bindParam(":author", $this->author);
        $stmt->bindParam(":category_id", $this->category_id);
        $stmt->bindParam(":id", $this->id);

        if($stmt->execute()){
            return true;
        }

        // Return error
        printf("Error: %s.\n", $stmt->errorInfo());
        return false;
    }
    public function delete()
    {
        // Create query
        $query =
            "DELETE FROM " . $this->table . " " .
            "WHERE id = :id";

        // Prepare stmt
        $stmt = $this->conn->prepare($query);

        // Clean data
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind data
        $stmt->bindParam(":id", $this->id);

        if($stmt->execute()){
            return true;
        }

        // Return error
        printf("Error: %s.\n", $stmt->errorInfo());
        return false;
    }


}
