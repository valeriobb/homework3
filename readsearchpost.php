<?php
session_start();
$id = $_GET['id'];

// Carica il file XML
$xml = simplexml_load_file('database.xml') or die("Errore durante il caricamento del file XML.");

if($id){
    $post = $xml->xpath("//post[@id='$id']");
    if($post){
        $post = $post[0];
    } else {
        echo "Nessun post trovato";
        exit();
    }
}else{
    echo "Nessun post trovato";
    exit();
}
?>

<?php echo "<?xml version=\"1.0\" encoding =\"UTF-8\"?>"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
    <link rel="icon" href="images/download.png"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=deide-width,initial-scale=1.0"/>
    <title>One Piece Fandom - Post</title>
    <link rel="stylesheet" type="text/css" href="admin/readpost.css"/>
</head>

<body>
    <div class="container">
        <div class="post">
            <div class="back-link">
                <a href="blog.php">Torna al blog</a>
            </div>
                
            <h1><?php echo $post['titolo'] ?></h1>
            <div class="author-info">
                <h2>Autore:</h2>
                <p><?php echo $post['autore'] ?></p>
            </div>
            <div class="post-content">
                <h2>Testo:</h2>
                <p><?php echo $post->testo ?></p>
            </div>
            <div class="post-info">
                <p><?php
                    // Formattazione della data nel formato italiano (GG/MM/YYYY)
                    $data_formattata = date("d/m/Y", strtotime($post['data_publ']));
                    echo $data_formattata;
                ?></p>
            </div>
        </div>
    </div>
</body>
</html>