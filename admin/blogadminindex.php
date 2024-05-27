<?php 
require_once("requireadmin.php");

if (isset($_SESSION["delete_PA"])) {
    ?>
    <div class="alert-success">
        <?php echo $_SESSION["delete_PA"]; ?>
    </div>
<?php
    unset($_SESSION["delete_PA"]);
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
    <link rel="stylesheet" type="text/css" href="blogadminindex.css"/> 
</head>

<body>

    <div class="container">

    <div class="page-links">
            <a href="../onepiece.php">Torna alla Home</a>
            <br>   
            <a href="adminindex.php">Torna alla Home dell'ADMIN</a>
        </div>
        <br><br>

        <table>
            <thead>
                <tr>
                    <th style="width:15%;">Titolo</th>
                    <th style="width:15%;">Autore</th>
                    <th style="width:45%;">Testo</th>
                    <th style="width:15%;">Data pubblicazione</th>
                    <th>Copertina</th>
                    <th style="width:10%;">Azioni</th> <!-- Aggiunto il th per le azioni -->
                </tr>
            </thead>

            <tbody>

                <?php
                    
                    $xml = simplexml_load_file('../database.xml');
                    foreach ($xml->posts->post as $post) {
                ?>
                    <tr>
                        <td><?php echo $post["titolo"]?></td>
                        <td><?php echo $post["autore"]?></td>
                        <td><?php 
                            $maxLength = 50;
                            $text = $post->testo;
                            if (strlen($text) > $maxLength) {
                                $shortText = substr($text, 0, $maxLength);
                                // Trova l'ultima occorrenza dello spazio prima del 50° carattere
                                $lastSpace = strrpos($shortText, ' ');
                                if ($lastSpace !== false) {
                                    $shortText = substr($shortText, 0, $lastSpace) . '...';
                                } else {
                                    // Se non ci sono spazi prima del 50° carattere, taglia comunque la stringa
                                    $shortText .= '...';
                                }
                            } else {
                                $shortText = $text;
                            }
                            echo $shortText;
                        ?></td>
                        <td><?php
                                $data_publ = $post["data_publ"];
                                // Formattazione della data nel formato italiano (GG/MM/YYYY)
                                $data_formattata = date("d/m/Y", strtotime($data_publ));
                                // Output della data formattata
                                echo $data_formattata;
                                ?></td>
                        <td>
                            <img src="../<?php echo $post["img"]; ?>" alt="Immagine" style="width: 80px;"></td>
                        
                        <td style="width:10%;">
                            <a href="readpost.php?id=<?php echo $post["id"]?>">Read</a>
                            <a href="deletepostadmin.php?id=<?php echo $post["id"]?>">Delete</a>
                        </td>
                    </tr>
                    <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>