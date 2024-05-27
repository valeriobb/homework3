<?php
require_once("requireadmin.php");

$id = $_GET["id"];
if($id){
    // Carica il documento XML
    $xml = simplexml_load_file('../database.xml');

    // Trova l'elemento <post> con l'ID specificato
    $postToDelete = null;
    foreach ($xml->posts->post as $post) {
        if ($post["id"] == $id) {
            $postToDelete = $post;
            break;
        }
    }

    if ($postToDelete) {
        // Rimuovi l'elemento <post> dal documento XML
        $dom = dom_import_simplexml($postToDelete);
        $dom->parentNode->removeChild($dom);

        // Salva le modifiche nel file XML
        $xml->asXML('../database.xml');

        session_start();
        $_SESSION["delete_PA"] = "Post deleted successfully";
        header("Location:blogadminindex.php");
        exit(); // Termina lo script dopo il reindirizzamento
    } else {
        echo "Post not found";
    }
} else {
    echo "Post not found";
}
?>