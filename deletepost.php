<?php
session_start();
$id = $_GET["id"];

if ($id) {
    // Carica il file XML
    $xml = simplexml_load_file('database.xml') or die("Errore durante il caricamento del file XML.");

    // Trova il post con l'ID specificato e rimuovilo
    $found = false;
    foreach ($xml->posts->post as $post) {
        if ((string)$post['id'] === $id) {
            $dom = dom_import_simplexml($post);
            $dom->parentNode->removeChild($dom);
            $found = true;
            break;
        }
    }

    if ($found) {
        // Salva il file XML aggiornato
        $xml->asXML('database.xml');

        $_SESSION["deleteP"] = "Post deleted successfully";
        header("Location: userindex.php");
        exit(); // Termina lo script dopo il reindirizzamento
    } else {
        die("Post not found or already deleted.");
    }
} else {
    echo "Post ID is missing.";
}
?>