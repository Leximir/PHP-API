<?php

use config\Database;
use models\Category;

header("Access-Control-Allow-Origin: *"); // CORS header: dozvoljava pristup ovom endpointu sa bilo kojeg origin-a (*).
header("Content-Type: application/json"); // Kaže klijentu da će odgovor biti JSON (application/json).

include_once '../../config/Database.php';
include_once '../../models/Category.php';

// Instantiate DB & connect
$database = new Database(); // Kreira novu instancu klase Database.
$db = $database->connect(); // Poziva connect() da dobije PDO konekciju i čuva je u $db.

// Instantiate Post object
$category = new Category($db); // Kreira Post objekat i prosljeđuje konekciju bazi u konstruktor.

// Get ID
$category->id = isset($_GET['id']) ? $_GET['id'] : die();

// Get Post
$category->show();

// Create array
$categoryShow = array(
    'id' => $category->id,
    'name' => $category->name,
    'created_at' => $category->created_at
);

// Make JSON
print_r(json_encode($categoryShow));
