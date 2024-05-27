<?php
// Inizia la sessione
session_start();

// Inizializza la variabile $trovato a false
$trovato = false;

// Verifica se è stata inviata una richiesta GET e se il campo 'parola' non è vuoto
if(isset($_GET['parola']) && !empty(trim($_GET['parola']))) {
    // Ottieni la parola cercata dall'input del modulo
    $parola_cercata = $_GET['parola'];

    // Carica il file XML
    $xml = simplexml_load_file('database.xml') or die("Errore durante il caricamento del file XML.");

    // Array per memorizzare i risultati della ricerca
    $risultati_ricerca = array();

    // Effettua la ricerca della parola nei post e visualizza i titoli corrispondenti
    foreach ($xml->posts->post as $post) {
        // Verifica se la parola è presente nel titolo o nel testo del post
        if (stripos((string)$post['titolo'], $parola_cercata) !== false || stripos((string)$post['autore'], $parola_cercata) !== false) {
            // Memorizza il risultato della ricerca
            $risultati_ricerca[] = array(
                'titolo' => (string)$post['titolo'],
                'autore' => (string)$post['autore'],
                'testo' => (string)$post->testo,
                'data_publ' => (string)$post['data_publ'],
                'id_user' => (string)$post['id_user'],
                'img' => (string)$post['img'],
                'id' => (string)$post['id']
            );
            // Imposta la variabile di controllo su true poiché almeno una corrispondenza è stata trovata
            $trovato = true;
        }
    }

    // Se nessuna corrispondenza è stata trovata, imposta il messaggio di errore nella sessione
    if (!$trovato) {
        $_SESSION['errore'] = "Nessun titolo o autore corrisponde ad un post per la parola '$parola_cercata'";
    } else {
        // Memorizza i risultati della ricerca nella sessione
        $_SESSION['risultati_ricerca'] = $risultati_ricerca;
        $_SESSION['parola_cercata'] = $parola_cercata;

        // Reindirizza l'utente alla pagina dei risultati della ricerca
        header("Location: risultati_ricerca.php");
        exit();
    }
} else {
    // Se il campo 'parola' è vuoto, mostra un messaggio di avviso
    $_SESSION['errore'] = "Inserisci una parola per effettuare la ricerca.";
}

// Se non sono stati trovati risultati o non è stata fornita una parola, reindirizza l'utente a blog.php
header("Location: blog.php");
exit();
?>