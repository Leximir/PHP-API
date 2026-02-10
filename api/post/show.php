<?php

use config\Database;
use models\Post;

header("Access-Control-Allow-Origin: *"); // CORS header: dozvoljava pristup ovom endpointu sa bilo kojeg origin-a (*).
header("Content-Type: application/json"); // Kaže klijentu da će odgovor biti JSON (application/json).

include_once '../../config/Database.php';
include_once '../../models/Post.php';

// Instantiate DB & connect
$database = new Database(); // Kreira novu instancu klase Database.
$db = $database->connect(); // Poziva connect() da dobije PDO konekciju i čuva je u $db.

// Instantiate Post object
$post = new Post($db); // Kreira Post objekat i prosljeđuje konekciju bazi u konstruktor.

// Get ID
$post->id = isset($_GET['id']) ? $_GET['id'] : die();

// Get Post
$post->show();

// Create array
$postShow = array(
    'id' => $post->id,
    'title' => $post->title,
    'body' => $post->body,
    'author' => $post->author,
    'category_name' => $post->category_name
);

// Make JSON
print_r(json_encode($postShow));
