<?php
require_once("requireadmin.php");

function deleteUserAndPosts($userId) {
    // Carica il file XML
    $xml = simplexml_load_file('../database.xml');
    
    // Elimina i post dell'utente
    $posts = $xml->xpath("/database/posts/post[id_user='$userId']");
    foreach ($posts as $post) {
        // Rimuovi il post dal suo genitore
        $dom = dom_import_simplexml($post);
        $dom->parentNode->removeChild($dom);
    }

    // Elimina l'utente
    $users = $xml->xpath("/database/users/user[@id='$userId']");
    foreach ($users as $user) {
        // Rimuovi l'utente dal suo genitore
        $dom = dom_import_simplexml($user);
        $dom->parentNode->removeChild($dom);
    }

    // Salva le modifiche nel file XML
    $xml->asXML('../database.xml');

    // Se l'eliminazione è andata a buon fine, impostiamo il messaggio di successo
    session_start();
    $_SESSION['delete'] = "L'utente è stato eliminato con successo.";
}

// Verifica se è stato inviato l'ID dell'utente da cancellare
if (isset($_GET['id']) && !empty($_GET['id'])) {
    // Ottieni l'id dell'utente dalla query string
    $userId = $_GET['id'];

    // Chiama la funzione per cancellare l'utente e i suoi post
    deleteUserAndPosts($userId);

    // Redirect o qualsiasi altra azione dopo la cancellazione
    header("Location: deluserindex.php");
    exit(); // Assicura che lo script termini dopo il reindirizzamento
}
?>