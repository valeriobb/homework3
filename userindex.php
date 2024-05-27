<?php 
session_start();

if(isset($_SESSION["user"])){
    $user = $_SESSION["user"];
}
?>

<?php
if (isset($_SESSION["createP"])) {
?>
    <div class="alert-success">
        <?php 
        echo $_SESSION["createP"];
        ?>
    </div>
<?php
unset($_SESSION["createP"]);
}
?>
<?php
if (isset($_SESSION["updateP"])) {
?>
    <div class="alert-success">
        <?php 
        echo $_SESSION["updateP"];
        ?>
    </div>
<?php
unset($_SESSION["updateP"]);
}
?>
<?php
if (isset($_SESSION["deleteP"])) {
?>
    <div class="alert-danger">
        <?php 
        echo $_SESSION["deleteP"];
        ?>
    </div>
<?php
unset($_SESSION["deleteP"]);
}
?>

<?php echo "<?xml version=\"1.0\" encoding =\"UTF-8\"?>"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
    <link rel="icon" href="images/download.png"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width,initial-scale=1.0"/>
    <title>One Piece Fandom - UserHome</title>
    <link rel="stylesheet" type="text/css" href="userindex.css"/>   
</head>

<body>
    <div class="container">
        
        <h1>Ciao <?php echo htmlspecialchars($user) ?></h1>
        <p>I tuoi post:</p>

        <div class="post-table">
            <table>
                <thead>
                    <tr>
                        <th>Titolo</th>
                        <th>Autore</th>
                        <th>Testo</th>
                        <th>Data Pubblicazione</th>
                        <th>Copertina</th>
                        <th>Azioni</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $id_u = $_SESSION["id_user"];
                    $xml = simplexml_load_file('database.xml') or die("Errore durante il caricamento del file XML.");
                    $posts = $xml->posts->post;
                    
                    $userPosts = [];
                    foreach ($posts as $post) {
                        if ((int)$post['id_user'] == $id_u) {
                            $userPosts[] = $post;
                        }
                    }

                    if (count($userPosts) > 0) {
                        foreach ($userPosts as $data) {
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($data['titolo']); ?></td>
                        <td><?php echo htmlspecialchars($data['autore']); ?></td>
                        <td><?php
                            // Tronca la citazione se supera i 50 caratteri
                            $citazione = (string)$data->testo;
                            if (strlen($citazione) > 50) {
                                $citazione = substr($citazione, 0, 50) . '...';
                                $lastSpace = strrpos($citazione, ' ');
                                if ($lastSpace !== false) {
                                    $citazione = substr($citazione, 0, $lastSpace) . '...';
                                } else {
                                    // Se non ci sono spazi prima del 40° carattere, taglia comunque la stringa
                                    $citazione .= '...';
                                }
                            }
                            echo htmlspecialchars($citazione);
                        ?></td>
                        <td><?php echo date("d/m/Y", strtotime($data['data_publ'])); ?></td>
                        <td>
                            <img src="<?php echo htmlspecialchars($data['img']); ?>" alt="Immagine" style="width: 80px;">
                        </td>
                        <td class="action-links">
                            <a href="editpost.php?id=<?php echo $data['id']; ?>">Edit</a>
                            <a href="deletepost.php?id=<?php echo $data['id']; ?>">Delete</a>
                        </td>
                    </tr>
                    <?php   } ?>
                    <tr>
                        <td colspan="6" class="add-post-link">
                            <a href="nuovopost.php">Vuoi aggiungere un nuovo post?</a>
                        </td>
                    </tr>
                    <?php    } else { ?>
                    <tr>
                        <td colspan="6" class="message">
                            Non hai scritto ancora nessun post.
                        </td>
                    </tr>
                    <tr>
                        <td colspan="6" class="message">
                            <a href="nuovopost.php">Vuoi aggiungerne uno?</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <div class="navigation-links">
            <a href="onepiece.php">Torna alla Home del FANDOM</a>
            <?php
            // Verifica se c'è un URL di riferimento
            if(isset($_SERVER['HTTP_REFERER'])) {
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
