

<?php 
session_start();
$id = $_GET['id'];
if ($id) {
    $xml = simplexml_load_file('database.xml') or die("Errore durante il caricamento del file XML.");
    $postFound = false;
    foreach ($xml->posts->post as $post) {
        if ((int)$post['id'] == $id) {
            $postFound = true;
            $postDetails = $post;
            break;
        }
    }
    if (!$postFound) {
        echo "Nessun post trovato";
        exit;
    }
} else {
    echo "Nessun post trovato";
    exit;
}
?>

<?php echo "<?xml version=\"1.0\" encoding =\"UTF-8\"?>"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
    <link rel="icon" href="images/download.png"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width,initial-scale=1.0"/>
    <title>One Piece Fandom - EditPost</title>
    <link rel="stylesheet" type="text/css" href="admin/edit.css"/> 
</head>

<body>

    <div class="container">
        <div class="saghe">
        <p>MODIFICA:</p>
        <br>

        <form action="admin/crudsaga.php" method="post" enctype="multipart/form-data">
            <div>
                <input type="text" name="titolo" placeholder="Modifica titolo:" value="<?php echo htmlspecialchars($postDetails['titolo']); ?>">
                <input type="hidden" name="autore" value="<?php echo htmlspecialchars($_SESSION["user"]); ?>">
                <textarea name="testo" cols="30" rows="10" placeholder="Modifica il testo:"><?php echo htmlspecialchars($postDetails->testo); ?></textarea>
                <input type="file" name="img">
                <input type="hidden" name="data_publ" value="<?php echo date("Y-m-d"); ?>" >
                <input type="hidden" name="id_user" value="<?php echo htmlspecialchars($_SESSION["id_user"]); ?>"> 
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
                <input type="submit" value="invio" name="editPost">
            </div>
        </form>

        </div>
        <div class="navigation-links">
            <a href="onepiece.php">Torna alla Home del FANDOM</a>
            <?php
            // Verifica se c'è un URL di riferimento
            if (isset($_SERVER['HTTP_REFERER'])) {
                // Stampa un link per tornare alla pagina precedente
                echo '<a href="' . htmlspecialchars($_SERVER['HTTP_REFERER']) . '" class="back-link">Torna indietro</a>';
            } else {
                // Se non c'è un URL di riferimento, stampa un messaggio di default
                echo '<p>Non è possibile tornare indietro.</p>';
            }
            ?>
        </div>

    </div>
</body>
</html>