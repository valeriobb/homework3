<?php
    session_start();

    if(isset($_SESSION["user"])){
        $user = $_SESSION["user"];
    }

    // Carica il file XML
    $xml = simplexml_load_file("database.xml");

    // Controlla se il caricamento del file è avvenuto con successo
    if($xml === false) {
        exit('Errore nel caricamento del file XML.');
    }

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
    <link rel="icon" href="images/download.png"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=deide-width,initial-scale=1.0"/>
    <title>One Piece Fandom - Blog</title>
    <link rel="stylesheet" type="text/css" href="blog.css"/>
</head>

<body>


<div class="header">
    <div class="logo-container">
        <img src="images/one_piece_logo.png" alt="One Piece Logo" width="300" height="100"/>
    </div>
    <div class="navbar-container">
        <div class="navbar">
            <ul>
                <li><a href="onepiece.php" style="--i:1;" >Home</a></li>
                <li><a href="saghe.php" style="--i:2;" >Saghe/Episodi</a></li>
                <li><a href="personaggi.php" style="--i:3;" >Personaggi</a></li>
                <li><a href="citazioni.php" style="--i:4;">Citazioni</a></li>
                <li><a href="blog.php" style="--i:5;" class="active">Blog</a></li>
            </ul>   
        </div>
    </div>
    <div class="login-container">
        <div class="login">
            <?php if(!isset($_SESSION["user"])){?>
            <a href="login.php" type="submit" class="log"> <?php echo"Login" ?></a>
            <?php }else{ ?>
                <p><?php if($_SESSION["email"]!= 'admin@gmail.com'){
                ?><a href="userindex.php" type="submit" class="profilo"><?php echo $user?></a><br><a href="logout.php" type="submit" class="logout"><?php echo "logout"?></a>
                <?php }else{ ?>
                <a href="admin/adminindex.php" type="submit" class="profilo"><?php echo $user?></a><br><a href="logout.php" type="submit" class="logout"><?php echo "logout"?></a>
                </p>
                <?php } ?>         
                
            <?php } ?> 
        </div>
    </div>
</div>

<?php

// Recupera il messaggio di errore dalla sessione
if(isset($_SESSION['errore'])) {
    $messaggio_errore = $_SESSION['errore'];
    // Rimuovi il messaggio di errore dalla sessione per evitare di visualizzarlo nuovamente
    unset($_SESSION['errore']);
}

// Visualizza il messaggio di errore se presente  //da spostare vicino la form
if(isset($messaggio_errore)) {
    ?> <div class="messerror"> 
        <?php  echo "<p>$messaggio_errore</p>"; ?> <a href="blog.php">X</a> 
    </div>
   
<?php }
?>



<div class="home">
    <div class="search-container">
        <h2>Inserisci la parola da cercare:</h2>
        <form action="ricerca.php" method="GET">
            <input type="text" name="parola" placeholder="Search">
            <button type="submit">Cerca</button>
        </form>
    </div>
    <?php if(isset($_SESSION["email"]) && $_SESSION["email"] != 'admin@gmail.com'){ ?>
    <a href="nuovopost.php" class="add-post-link">Aggiungi nuovo post</a>
    <?php } ?>
    <p class="recente">Post più recente</p>
    <div class="post-container">
    <?php
        
        // Seleziona i nodi dei post
        $posts = $xml->posts->post;
        
        // Inizializza le variabili per il post più recente
        $recent_post = null;
        $recent_date = null;
        
        // Scansiona tutti i post per trovare quello più recente
        foreach ($posts as $post) {
            $pub_date = strtotime($post['data_publ']);
            
            // Controlla se la data del post è più recente della data attuale
            if ($pub_date > $recent_date || $recent_date === null) {
                $recent_post = $post;
                $recent_date = $pub_date;
            }
        }
        
        // Verifica se è stato trovato un post più recente
        if ($recent_post !== null) {
    ?>
    <div class="post">
        <div class="post-image">
            <img src="<?php echo $recent_post['img']; ?>" alt="Immagine">
        </div>
        <div class="post-details">
            <h2 class="post-title"><?php echo $recent_post["titolo"]?></h2>
            <p class="post-text">"<?php 
                $maxLength = 40;
                $text = $recent_post->testo;
                if (strlen($text) > $maxLength) {
                    $shortText = substr($text, 0, $maxLength);
                    // Trova l'ultima occorrenza dello spazio prima del 40° carattere
                    $lastSpace = strrpos($shortText, ' ');
                    if ($lastSpace !== false) {
                        $shortText = substr($shortText, 0, $lastSpace) . '...';
                    } else {
                        // Se non ci sono spazi prima del 40° carattere, taglia comunque la stringa
                        $shortText .= '...';
                    }
                } else {
                    $shortText = $text;
                }
                echo $shortText;
            ?>"
            </p>
            <p class="post-author">by <?php echo $recent_post["autore"]?></p>
            <p class="post-date"><?php echo date("d/m/Y", strtotime($recent_post["data_publ"])); ?></p>
            <a class="read-more" href="readsearchpost.php?id=<?php echo $recent_post["id"]?>">Read More</a>
        </div>
    </div>
    <?php } else {
            echo "<p>Non ci sono ancora post nel blog.</p>";
        }
    ?>
</div>


<p class="allpost">Tutti i post:</p>

<div class="additional-posts-container">
<?php
        // Ottieni tutti i post dal file XML
        $posts = $xml->posts->post;

        // Funzione per ordinare i post per data di pubblicazione
        function sortByDate($a, $b) {
            $dateA = strtotime($a['data_publ']);
            $dateB = strtotime($b['data_publ']);
            return $dateB - $dateA; // Ordina in ordine decrescente (dal più recente al meno recente)
        }

        // Converti gli oggetti SimpleXMLElement in un array
        $posts_array = [];
        foreach ($posts as $post) {
            $posts_array[] = $post;
        }

        // Ordina i post in base alla data di pubblicazione
        usort($posts_array, 'sortByDate');

        // Itera attraverso i post per visualizzarli
        foreach ($posts_array as $post) {
    ?>
    <div class="additional-post">
        <!-- Immagine a sinistra -->
        <div class="post-image">
            <img src="<?php echo $post['img']; ?>" alt="Immagine">
        </div>
    
        <!-- Dettagli del post a destra -->
        <div class="post-details">
            <h2 class="post-title"><?php echo $post["titolo"]?></h2>
            <p class="post-text">"<?php 
                $maxLength = 40;
                $text = $post->testo;
                if (strlen($text) > $maxLength) {
                    $shortText = substr($text, 0, $maxLength);
                    // Trova l'ultima occorrenza dello spazio prima del 40° carattere
                    $lastSpace = strrpos($shortText, ' ');
                    if ($lastSpace !== false) {
                        $shortText = substr($shortText, 0, $lastSpace) . '...';
                    } else {
                        // Se non ci sono spazi prima del 40° carattere, taglia comunque la stringa
                        $shortText .= '...';
                    }
                } else {
                    $shortText = $text;
                }
                echo $shortText;
            ?>"</p>
            <p class="post-author">by <?php echo $post["autore"]?></p>
            <p class="post-date"><?php
                // Formatta la data nel formato italiano (GG/MM/YYYY)
                $data_formattata = date("d/m/Y", strtotime($post["data_publ"]));
                echo $data_formattata;
            ?></p>
            <a class="read-more" href="readsearchpost.php?id=<?php echo $post["id"]?>">Read More</a>
        </div>
    </div>
    <?php
        }
    ?>

</div>

<div  class="footer">
    <p class="copy">&copy;2024 Copyright One Piece Fandom by Antonio Agostini &amp; Valerio Baratella</p>
</div>  

</body>
</html>