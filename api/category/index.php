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

// Blog post query
$result = $category->index();// Poziva read() metodu koja treba da vrati PDOStatement rezultat (ili nešto slično).

// Get row count
$num = $result->rowCount(); // Poziva rowCount() nad statement-om da dobije broj redova.

// Check if any categories
if($num > 0) {
    // Post array
    $categories_arr = array(); // Inicijalizuje prazan array koji će držati sve postove.
    $categories_arr['data'] = array(); // Dodaje ključ 'data' koji će biti niz postova (standardno za API response).

    while($row = $result->fetch(PDO::FETCH_ASSOC)) { // Petlja: uzima red po red iz rezultata kao асоцијативни array (ključ => vrijednost).
        // PDO::FETCH_ASSOC znači: samo nazivi kolona kao ključevi, bez numeričkih indeksa.

        extract($row);// Pretvara ključeve iz $row u varijable.
        // Npr. $row['id'] postaje $id, $row['title'] postaje $title itd.

        $category_item = array( // Kreira array koji predstavlja jedan post (jedan objekat u JSON-u).

            'id' => $id, // Postavlja 'id' u array na vrijednost varijable $id.

            'name' => $name,

            'created_at' => $created_at
        );

        // Push to data
        array_push($categories_arr['data'], $category_item); // Dodaje $post_item na kraj $posts_arr['data'] niza.
    }
    // Turn to JSON & output
    echo json_encode($categories_arr); // Pretvara $posts_arr u JSON string i šalje klijentu.

} else {
    // No Posts
    echo json_encode( // Ispisuje JSON odgovor.
        array('message' => 'No Category Found') // JSON objekat sa ključem "message".
    );
}