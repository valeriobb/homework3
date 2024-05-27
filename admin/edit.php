<?php 
// Carica il documento XML
$xml = new DOMDocument();
$xml->load('../database.xml');

$id = $_GET['id'];

// Trova l'elemento <saga> con l'ID corrispondente
$saga = null;
$sagas = $xml->getElementsByTagName('saga');
foreach ($sagas as $s) {
    if ($s->getAttribute('id') == $id) {
        $saga = $s;
        break;
    }
}

if (!$saga) {
    echo "Nessuna saga trovata";
} else {
?>

<?php echo "<?xml version=\"1.0\" encoding =\"UTF-8\"?>"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
    <link rel="icon" href="images/download.png"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=deide-width,initial-scale=1.0"/>
    <title>One Piece Fandom - Admin</title>
    <link rel="stylesheet" type="text/css" href="edit.css"/> 
</head>

<body>

<div class="container">
    <div class="saghe">
        <p>MODIFICA:</p>
        <br>
        <form action="crudsaga.php" method="post" enctype="multipart/form-data">
            <div>
                <input type="text" name="nome" placeholder="Inserisci titolo:" value="<?php echo $saga->getAttribute('nome'); ?>">
                <input type="text" name="ep_iniziale" placeholder="Ep. Iniziale" value="<?php echo $saga->getAttribute('ep_iniziale'); ?>">
                <input type="text" name="ep_finale" placeholder="Ep. Finale" value="<?php echo $saga->getAttribute('ep_finale'); ?>">
                <textarea name="trama" cols="30" rows="10"><?php echo $saga->getElementsByTagName('trama')->item(0)->nodeValue; ?></textarea>
                <input type="file" name="img">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <input type="submit" value="Invio" name="update">
            </div>
        </form>
    </div>
</div>

</body>
</html>

<?php } ?>