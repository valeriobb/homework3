<?php require_once("requireadmin.php"); ?>

<?php
$id = $_GET["id"];
if($id){
    // Carica il documento XML
    $xml = new DOMDocument();
    $xml->load('../database.xml');

    // Trova l'elemento <saga> con l'ID corrispondente
    $xpath = new DOMXPath($xml);
    $sagaNodes = $xpath->query("/database/saghe/saga[@id='$id']");

    // Verifica se l'elemento <saga> è stato trovato
    if($sagaNodes->length > 0) {
        // Rimuovi l'elemento <saga> dal documento XML
        $sagaNode = $sagaNodes->item(0);
        $sagaNode->parentNode->removeChild($sagaNode);

        // Salva le modifiche nel file XML
        $xml->save('../database.xml');

        session_start();
        $_SESSION["delete"] = "Saga deleted successfully";
        header("Location: sagaindex.php");
        exit(); // Termina lo script dopo il reindirizzamento
    } else {
        echo "Saga not found";
    }
} else {
    echo "Post ID not provided";
}
?>