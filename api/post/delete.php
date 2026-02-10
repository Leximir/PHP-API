<?php

use config\Database;
use models\Post;

header("Access-Control-Allow-Origin: *"); // CORS header: dozvoljava pristup ovom endpointu sa bilo kojeg origin-a (*).
header("Content-Type: application/json"); // Kaže klijentu da će odgovor biti JSON (application/json).
header("Access-Control-Allow-Methods: DELETE"); //
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With"); //

include_once '../../config/Database.php';
include_once '../../models/Post.php';

// Instantiate DB & connect
$database = new Database(); // Kreira novu instancu klase Database.
$db = $database->connect(); // Poziva connect() da dobije PDO konekciju i čuva je u $db.

// Instantiate Post object
$post = new Post($db); // Kreira Post objekat i prosljeđuje konekciju bazi u konstruktor.

// Get raw posted data
$data = json_decode(file_get_contents("php://input"));
// file_get_contents("php://input") čita "raw" tijelo HTTP zahtjeva.
// json_decode() pretvara JSON string u PHP objekat (stdClass).

$post->id = $data->id;

// DELETE Post
if($post->delete()){
    echo json_encode(array('message' => "Post deleted"));
} else {
    echo json_encode(array('message' => "Post not deleted"));
}
