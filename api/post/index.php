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

// Blog post query
$result = $post->read();// Poziva read() metodu koja treba da vrati PDOStatement rezultat (ili nešto slično).

// Get row count
$num = $result->rowCount(); // Poziva rowCount() nad statement-om da dobije broj redova.

// Check if any posts
if($num > 0) {
    // Post array
    $posts_arr = array(); // Inicijalizuje prazan array koji će držati sve postove.
    $posts_arr['data'] = array(); // Dodaje ključ 'data' koji će biti niz postova (standardno za API response).

    while($row = $result->fetch(PDO::FETCH_ASSOC)) { // Petlja: uzima red po red iz rezultata kao асоцијативни array (ključ => vrijednost).
                                                    // PDO::FETCH_ASSOC znači: samo nazivi kolona kao ključevi, bez numeričkih indeksa.

        extract($row);// Pretvara ključeve iz $row u varijable.
                            // Npr. $row['id'] postaje $id, $row['title'] postaje $title itd.

        $post_item = array( // Kreira array koji predstavlja jedan post (jedan objekat u JSON-u).

            'id' => $id, // Postavlja 'id' u array na vrijednost varijable $id.

            'title' => $title, // Postavlja 'title' na vrijednost $title.

            'body' => html_entity_decode($body), // Dekodira HTML entitete (npr. &amp; -> &) prije slanja u JSON.

            'author' => $author, // Postavlja autora.

            'category_id' => $category_id, // Postavlja ID kategorije.

            'category_name' => $category_name// Postavlja naziv kategorije (iz JOIN-a).
        );

        // Push to data
        array_push($posts_arr['data'], $post_item); // Dodaje $post_item na kraj $posts_arr['data'] niza.
    }
    // Turn to JSON & output
    echo json_encode($posts_arr); // Pretvara $posts_arr u JSON string i šalje klijentu.

} else {
    // No Posts
    echo json_encode( // Ispisuje JSON odgovor.
        array('message' => 'No Post Found') // JSON objekat sa ključem "message".
    );
}