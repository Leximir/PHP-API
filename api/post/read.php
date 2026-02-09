<?php
// Početak PHP fajla.

// Headers
// Komentar: HTTP headeri (CORS i tip sadržaja).

use config\Database;
// Uvozi (aliasuje) klasu Database iz namespace-a config da je možeš koristiti kao Database.

use models\Post;
// Uvozi (aliasuje) klasu Post iz namespace-a models da je možeš koristiti kao Post.

header("Access-Control-Allow-Origin: *");
// CORS header: dozvoljava pristup ovom endpointu sa bilo kojeg origin-a (*).
// TIP: u produkciji je bolje staviti tačan domen umjesto *.

header("Content-Type: application/json");
// Kaže klijentu da će odgovor biti JSON (application/json).

include_once '../../config/Database.php';
// Uključuje fajl sa klasom Database (samo jednom, čak i ako se pozove opet).

include_once '../../models/Post.php';
// Uključuje fajl sa klasom Post (samo jednom).

// Instantiate DB & connect
// Komentar: pravljenje instance baze i konekcija.

$database = new Database();
// Kreira novu instancu klase Database.

$db = $database->connect();
// Poziva connect() da dobije PDO konekciju i čuva je u $db.
// NOTE: u tvom Database kodu connect() trenutno NE vraća konekciju (nema return),
// pa će ovdje $db vjerovatno biti NULL. Treba da connect() vrati $this->conn.

// Instantiate DB & connect
// Komentar: instancira model Post sa konekcijom (trebalo bi da bude "$db").

// (Komentar ti je malo isti kao iznad; ovdje bi bolje bilo: "Instantiate post object")

$post = new Post($db);
// Kreira Post objekat i prosljeđuje konekciju bazi u konstruktor.

// Blog post query
// Komentar: poziva metodu za čitanje postova.

$result = $post->read();
// Poziva read() metodu koja treba da vrati PDOStatement rezultat (ili nešto slično).
// NOTE: u tvom Post modelu read() trenutno samo napravi $query i ništa ne return-a,
// pa će $result biti NULL i sve ispod će puknuti.

// Get row count
// Komentar: broj redova iz rezultata.

$num = $result->rowCount();
// Poziva rowCount() nad statement-om da dobije broj redova.
// NOTE: Za MySQL sa SELECT upitima rowCount() nije uvijek pouzdan.
// Bolje je fetch-ovati pa brojati, ili koristiti SELECT COUNT(*) ako baš treba.

// Check if any posts
// Komentar: provjera da li ima postova.

if($num > 0) {
// Ako je broj redova veći od 0 – znači ima rezultata.

    // Post array
    // Komentar: kreira niz (array) koji će se vratiti kao JSON.

    $posts_arr = array();
    // Inicijalizuje prazan array koji će držati sve postove.

    $posts_arr['data'] = array();
    // Dodaje ključ 'data' koji će biti niz postova (standardno za API response).

    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
        // Petlja: uzima red po red iz rezultata kao асоцијативни array (ključ => vrijednost).
        // PDO::FETCH_ASSOC znači: samo nazivi kolona kao ključevi, bez numeričkih indeksa.

        extract($row);
        // Pretvara ključeve iz $row u varijable.
        // Npr. $row['id'] postaje $id, $row['title'] postaje $title itd.
        // TIP: Ovo je zgodno, ali može biti rizično (može pregaziti postojeće varijable).

        $post_item = array(
            // Kreira array koji predstavlja jedan post (jedan objekat u JSON-u).

            'id' => $id,
            // Postavlja 'id' u array na vrijednost varijable $id.

            'title' => $title,
            // Postavlja 'title' na vrijednost $title.

            'body' => html_entity_decode($body),
            // Dekodira HTML entitete (npr. &amp; -> &) prije slanja u JSON.
            // Korisno ako si u bazi čuvao HTML-escaped sadržaj.

            'author' => $author,
            // Postavlja autora.

            'category_id' => $category_id,
            // Postavlja ID kategorije.

            'category_name' => $category_name
            // Postavlja naziv kategorije (iz JOIN-a).
        );
        // Zatvara definiciju $post_item array-a.

        // Push to data
        // Komentar: dodaje post_item u niz data.

        array_push($posts_arr['data'], $post_item);
        // Dodaje $post_item na kraj $posts_arr['data'] niza.
        // TIP: Može i kraće: $posts_arr['data'][] = $post_item;
    }
    // Kraj while petlje.

    // Turn to JSON & output
    // Komentar: pretvara array u JSON i ispisuje ga kao response.

    echo json_encode($posts_arr);
    // Pretvara $posts_arr u JSON string i šalje klijentu.

} else {
// Ako nema postova (num == 0), šalje poruku.

    // No Posts
    // Komentar: odgovor kad nema rezultata.

    echo json_encode(
    // Ispisuje JSON odgovor.

        array('message' => 'No Post Found')
    // JSON objekat sa ključem "message".
    );
    // Zatvara json_encode poziv.
}
// Zatvara if/else blok.
