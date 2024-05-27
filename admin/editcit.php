<?php 
require_once("requireadmin.php");

$id = $_GET['id'];
if($id){
    // Carica il documento XML
    $xml = new DOMDocument();
    $xml->load('../database.xml');

    // Trova l'elemento <citazione> con l'ID specificato
    $citazioni = $xml->getElementsByTagName('citazione');
    $citazioneToEdit = null;
    foreach ($citazioni as $citazione) {
        if ($citazione->getAttribute('id') == $id) {
            $citazioneToEdit = $citazione;
            break;
        }
    }

    if ($citazioneToEdit) {
        // Ottieni i valori attuali dell'elemento <citazione>
        $nome_pers = $citazioneToEdit->getAttribute('nome_pers');
        // Ottieni il testo della citazione
        $cit = $citazioneToEdit->getElementsByTagName('cit')->item(0)->nodeValue;
    } else {
        echo "Nessuna citazione trovata";
        exit(); // Termina lo script se non viene trovata la citazione
    }
} else {
    echo "Nessun ID specificato";
    exit(); // Termina lo script se non viene specificato un ID
}
?>

<?php echo "<?xml version=\"1.0\" encoding =\"UTF-8\"?>"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
    <link rel="icon" href="images/download.png"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=deide-width,initial-scale=1.0"/>
    <title>One Piece Fandom - Admin</title>
    <link rel="stylesheet" type="text/css" href="edit.css"/> <!--fa riferimento allo style di edit.css -->
</head>

<body>

<div class="container">
    <div class="saghe">
        <p>MODIFICA:</p>
        <br>
        <form action="crudsaga.php" method="post" enctype="multipart/form-data">
            <div>
                <input type="text" name="nome_pers" placeholder="Inserisci Nome:" value="<?php echo $nome_pers; ?>">
                <textarea name="cit" cols="30" rows="10"><?php echo $cit; ?></textarea>
                <input type="file" name="img">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <input type="submit" value="Invio" name="citUpdate">
            </div>
        </form>
    </div>
</div>

</body>></html>