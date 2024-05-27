<?php require_once("requireadmin.php"); ?>

<?php
$id = $_GET["id"];
if($id){
    // Carica il documento XML
    $xml = new DOMDocument();
    $xml->load('../database.xml');

    // Trova l'elemento <citazione> con l'ID specificato
    $citazioni = $xml->getElementsByTagName('citazione');
    $citazioneToDelete = null;
    foreach ($citazioni as $citazione) {
        if ($citazione->getAttribute('id') == $id) {
            $citazioneToDelete = $citazione;
            break;
        }
    }

    if ($citazioneToDelete) {
        // Rimuovi l'elemento <citazione> dal documento XML
        $citazioneToDelete->parentNode->removeChild($citazioneToDelete);

        // Salva le modifiche nel file XML
        $xml->save('../database.xml');

        session_start();
        $_SESSION["delete"] = "Post deleted successfully";
        header("Location:citindex.php");
        exit();
    } else {
        echo "Post not found";
    }
} else {
    echo "Post not found";
}
?>