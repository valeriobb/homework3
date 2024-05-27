<?php
if(isset($_POST["create"])) {

    // Carica il documento XML
    $xml = new DOMDocument();
    $xml->load('../database.xml');

    // Trova l'ultimo ID presente nel documento XML
    $lastId = 0;
    $saghe = $xml->getElementsByTagName('saga');
    foreach ($saghe as $saga) {
        $sagaId = (int)$saga->getAttribute('id');
        if ($sagaId > $lastId) {
            $lastId = $sagaId;
        }
    }

    // Incrementa l'ultimo ID di 1 per ottenere il nuovo ID
    $newId = $lastId + 1;

    // Crea un nuovo elemento <saga>
    $saga = $xml->createElement('saga');

    // Aggiungi gli attributi all'elemento <saga>
    $saga->setAttribute('id', $newId); // Attributo ID autoincrementato
    $saga->setAttribute('nome', $_POST["nome"]);
    $saga->setAttribute('ep_iniziale', $_POST["ep_iniziale"]);
    $saga->setAttribute('ep_finale', $_POST["ep_finale"]);

    // Crea un nuovo nodo per la trama
    $trama = $xml->createElement('trama');
    // Aggiungi il testo della trama come testo del nodo
    $trama->appendChild($xml->createCDATASection($_POST["trama"]));
    // Aggiungi il nodo della trama come figlio dell'elemento <saga>
    $saga->appendChild($trama);

    // Salva l'immagine nella directory images se è stata caricata
    if ($_FILES['img']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = "../images/";
        $img_name = $_FILES['img']['name'];
        move_uploaded_file($_FILES['img']['tmp_name'], $upload_dir . $img_name);
        // Aggiungi l'attributo img all'elemento <saga>
        $saga->setAttribute('img', 'images/' . $img_name);
    }

    // Aggiungi l'elemento <saga> al documento XML
    $xml->getElementsByTagName('saghe')->item(0)->appendChild($saga);

    // Salva le modifiche nel file XML
    $xml->save('../database.xml');

    session_start();
    $_SESSION["create"] = "Saga added successfully";
    header("Location: sagaindex.php");
    exit(); // Termina lo script dopo il reindirizzamento
}
?>

<?php
if(isset($_POST["update"])) {
    // Carica il documento XML
    $xml = new DOMDocument();
    $xml->load('../database.xml');

    // Ottieni l'ID della saga da aggiornare
    $sagaIdToUpdate = $_POST["id"];

    // Trova l'elemento <saga> con l'ID corrispondente
    $sagas = $xml->getElementsByTagName('saga');
    foreach ($sagas as $saga) {
        // Ottieni l'attributo ID dell'elemento <saga>
        $sagaId = $saga->getAttribute('id');
        if ($sagaId == $sagaIdToUpdate) {
            // Aggiorna i valori degli attributi
            $saga->setAttribute('nome', $_POST["nome"]);
            $saga->setAttribute('ep_iniziale', $_POST["ep_iniziale"]);
            $saga->setAttribute('ep_finale', $_POST["ep_finale"]);

            // Aggiorna il nodo della trama
            $saga->getElementsByTagName('trama')->item(0)->nodeValue = $_POST["trama"];

            // Salva la nuova immagine nella directory images se è stata caricata
            if(isset($_FILES['img']) && $_FILES['img']['error'] === UPLOAD_ERR_OK) {
                $upload_dir = "../images/";
                $img_name = $_FILES['img']['name'];
                move_uploaded_file($_FILES['img']['tmp_name'], $upload_dir . $img_name);
                // Aggiorna il percorso dell'immagine
                $saga->setAttribute('img', 'images/' . $img_name);
            }

            // Salva le modifiche nel file XML
            $xml->save('../database.xml');

            session_start();
            $_SESSION["update"] = "Saga updated successfully";
            header("Location: sagaindex.php");
            exit(); // Termina lo script dopo il reindirizzamento
        }
    }

    // Se l'ID specificato non corrisponde a nessuna saga, mostra un messaggio di errore
    session_start();
    $_SESSION["update"] = "Saga with ID $sagaIdToUpdate not found";
    header("Location: sagaindex.php");
    exit(); // Termina lo script dopo il reindirizzamento
}
?>
<?php
session_start();

// Carica il file XML
$xml = new DOMDocument();
$xml->load('../database.xml');

if(isset($_POST["voto"])){
    $id = $_POST["id"];
    $id_user = $_POST["id_user"];
    $votazione = $_POST["value"];

    // Verifica se il valore della valutazione è valido
    if ($votazione < 1 || $votazione > 5) {
        $_SESSION["error"] = "La valutazione deve essere compresa tra 1 e 5.";
        header("Location: {$_SERVER['HTTP_REFERER']}");
        exit();
    }

    // Verifica se esiste già una recensione per questo utente e questa saga
    $xpath = new DOMXPath($xml);
    $query = "/database/recensioni/recensione[@id_user='$id_user' and @id_saga='$id']";
    $result = $xpath->query($query);

    if ($result->length > 0) {
        // Se esiste già una recensione, aggiorna invece di inserire
        foreach ($result as $review) {
            $reviewNodeList = $review->getElementsByTagName('review');
            if ($reviewNodeList->length > 0) {
                $reviewNodeList->item(0)->nodeValue = $votazione;
            } else {
                // Se non viene trovato il nodo 'review', gestisci l'errore
                // ad esempio con un messaggio di log o un'eccezione
                echo "Nodo 'review' non trovato.";
            }
        }
        $_SESSION["update"] = "Recensione aggiornata con successo.";
    } else {
        // Se non esiste una recensione, inserisci una nuova
        $root = $xml->getElementsByTagName('recensioni')->item(0);
        $newReview = $xml->createElement('recensione');
        $newReview->setAttribute('id_user', $id_user);
        $newReview->setAttribute('id_saga', $id);
        $newReview->appendChild($xml->createElement('review', $votazione));
        $root->appendChild($newReview);
        $_SESSION["createV"] = "Recensione inserita con successo.";
    }

    // Salva le modifiche nel file XML
    $xml->save('../database.xml');

    header("Location: {$_SERVER['HTTP_REFERER']}");
    exit();
}
?>

<?php
if(isset($_POST["citCreate"])) {
    // Carica il documento XML
    $xml = new DOMDocument();
    $xml->load('../database.xml');

    /// Ottieni l'elemento <citazioni> dal documento XML
    $citazioni = $xml->getElementsByTagName('citazioni')->item(0);

    // Conta il numero di citazioni nel documento XML
    $numCitazioni = $citazioni->getElementsByTagName('citazione')->length;

    // Calcola l'ID autoincrementale per la nuova citazione
    $newId = $numCitazioni + 1;

    // Crea un nuovo elemento <citazione>
    $citazione = $xml->createElement('citazione');

    // Aggiungi l'attributo ID all'elemento <citazione>
    $citazione->setAttribute('id', $newId);
    $citazione->setAttribute('nome_pers', $_POST["nome_pers"]);

    // Crea un nuovo elemento <cit> per il testo della citazione
    $cit = $xml->createElement('cit');
    // Imposta il testo della citazione come testo del nodo
    $cit->appendChild($xml->createTextNode($_POST["cit"]));
    // Aggiungi il nodo della citazione come figlio dell'elemento <citazione>
    $citazione->appendChild($cit);

    // Aggiungi l'attributo img all'elemento <citazione>
    $citazione->setAttribute('img', 'images/' . $_FILES['img']['name']); // Assicurati che il percorso sia corretto

    // Aggiungi l'elemento <citazione> al documento XML
    $citazioni->appendChild($citazione);

    // Salva le modifiche nel file XML
    $xml->save('../database.xml');

    session_start();
    $_SESSION["create"] = "Post added successfully";
    header("Location: citindex.php");
    exit(); // Termina lo script dopo il reindirizzamento
}
?>


<?php 
if(isset($_POST["citUpdate"])) {
    // Carica il documento XML
    $xml = new DOMDocument();
    $xml->load('../database.xml');

    // Ottieni l'elemento <citazioni> dal documento XML
    $citazioni = $xml->getElementsByTagName('citazioni')->item(0);

    // Trova l'elemento <citazione> da aggiornare
    $citazioneIdToUpdate = $_POST["id"];
    $citazioniList = $citazioni->getElementsByTagName('citazione');
    $citazioneToUpdate = null;
    foreach ($citazioniList as $citazione) {
        $id = $citazione->getAttribute('id');
        if ($id == $citazioneIdToUpdate) {
            $citazioneToUpdate = $citazione;
            break;
        }
    }

    if ($citazioneToUpdate) {
        // Aggiorna gli attributi dell'elemento <citazione>
        $citazioneToUpdate->setAttribute('nome_pers', $_POST["nome_pers"]);

        // Aggiorna il testo della citazione
        $citazioneText = $_POST["cit"];
        $citNode = $citazioneToUpdate->getElementsByTagName('cit')->item(0);
        $citNode->nodeValue = $citazioneText;

        // Aggiorna l'immagine solo se è stata fornita una nuova immagine
        if(isset($_FILES['img']) && $_FILES['img']['error'] === UPLOAD_ERR_OK) {
            // Cartella di destinazione per l'upload delle immagini
            $upload_dir = "../images/";
            // Genera un nome univoco per il file
            $img_name = $_FILES['img']['name'];
            // Sposta il file temporaneo nella cartella di destinazione
            move_uploaded_file($_FILES['img']['tmp_name'], $upload_dir . $img_name);
            // Aggiorna il percorso dell'immagine
            $citazioneToUpdate->setAttribute('img', 'images/' . $img_name);
        }

        // Salva le modifiche nel file XML
        $xml->save('../database.xml');

        session_start();
        $_SESSION["update"] = "Post updated successfully";
        header("Location: citindex.php");
        exit(); // Termina lo script dopo il reindirizzamento
    } else {
        session_start();
        $_SESSION["update"] = "Post with ID $citazioneIdToUpdate not found";
        header("Location: citindex.php");
        exit(); // Termina lo script dopo il reindirizzamento
    }
}
?>

<?php
session_start();
if(isset($_POST["createPost"])) {
    // Carica il file XML
    $xml = simplexml_load_file('../database.xml') or die("Errore durante il caricamento del file XML.");

    // Genera un nuovo ID per il post
    $lastPost = $xml->posts->post[count($xml->posts->post) - 1];
    $newId = (int)$lastPost['id'] + 1;

    // Crea un nuovo elemento post
    $newPost = $xml->posts->addChild('post');
    $newPost->addAttribute('id', $newId);
    $newPost->addAttribute('titolo', $_POST["titolo"]);
    $newPost->addAttribute('autore', $_POST["autore"]);
    $newPost->addAttribute('data_publ', $_POST["data_publ"]);
    $newPost->addAttribute('id_user', $_POST["id_user"]);
    
    // Aggiunge il testo del post come elemento figlio
    $newPost->addChild('testo', $_POST["testo"]);
    
    // Imposta il percorso dell'immagine su NULL di default
    $img_path = NULL;

    // Controlla se è stata fornita un'immagine
    if ($_FILES['img']['error'] === UPLOAD_ERR_OK) {
        // Cartella di destinazione per l'upload delle immagini
        $upload_dir = "../images/";
        $upload_img = "images/";
        // Genera un nome univoco per il file
        $img_name = $_FILES['img']['name'];
        $img = $upload_img . $_FILES['img']['name'];

        // Percorso completo dell'immagine
        $img_path = $upload_dir . $img_name;

        // Controlla se l'immagine esiste già nella cartella
        if(!file_exists($img_path)) {
            // Sposta il file temporaneo nella cartella di destinazione solo se non esiste già
            move_uploaded_file($_FILES['img']['tmp_name'], $img_path);
        }
        $newPost->addAttribute('img', $img);
    } else {
        $newPost->addAttribute('img', ''); // Aggiungi un elemento img vuoto se non è stata fornita un'immagine
    }

    // Salva il file XML aggiornato
    $xml->asXML('../database.xml');
    
    $_SESSION["createP"] = "Post added successfully";
    header("Location:../userindex.php");
    exit(); // Termina lo script dopo il reindirizzamento
}
?>

<?php
session_start();
if(isset($_POST["editPost"])) {
    // Carica il file XML
    $xml = simplexml_load_file('../database.xml') or die("Errore durante il caricamento del file XML.");
    
    $id = $_POST["id"];
    $postFound = false;
    
    // Cerca il post con l'ID corrispondente
    foreach ($xml->posts->post as $post) {
        if ((int)$post['id'] == $id) {
            $postFound = true;
            
            // Aggiorna i campi del post
            $post['titolo'] = $_POST["titolo"];
            $post['autore'] = $_POST["autore"];
            $post->testo = $_POST["testo"];
            $post['data_publ'] = $_POST["data_publ"];
            $post['id_user'] = $_POST["id_user"];
            
            // Controlla se è stata fornita una nuova immagine
            if(isset($_FILES['img']) && $_FILES['img']['error'] === UPLOAD_ERR_OK) {
                // Cartella di destinazione per l'upload delle immagini
                $upload_dir = "../images/";
                $upload_img = "images/";
                // Genera un nome univoco per il file
                $img_name = $_FILES['img']['name'];
                $img = $upload_img . $_FILES['img']['name'];
                
                // Percorso completo dell'immagine
                $img_path = $upload_dir . $img_name;

                // Controlla se l'immagine esiste già nella cartella
                if(!file_exists($img_path)) {
                    // Sposta il file temporaneo nella cartella di destinazione solo se non esiste già
                    move_uploaded_file($_FILES['img']['tmp_name'], $img_path);
                }
                // Aggiorna l'attributo img con il nuovo percorso dell'immagine
                $post['img'] = $img;
            }
            
            break;
        }
    }

    if ($postFound) {
        // Salva il file XML aggiornato
        $xml->asXML('../database.xml');
        
        $_SESSION["updateP"] = "Post updated successfully";
        header("Location:../userindex.php");
        exit(); // Termina lo script dopo il reindirizzamento
    } else {
        die("Data is not updated!");
    }
}
?>










