<?php require_once("requireadmin.php"); ?>

<?php
if (isset($_SESSION["create"])) {
?>
<div class="alert-success">
    <?php 
    echo $_SESSION["create"];
    ?>
</div>
<?php
unset($_SESSION["create"]);
}
?>
<?php
if (isset($_SESSION["update"])) {
?>
<div class="alert-success">
    <?php 
    echo $_SESSION["update"];
    ?>
</div>
<?php
unset($_SESSION["update"]);
}
?>
<?php
if (isset($_SESSION["delete"])) {
?>
<div class="alert-danger">
    <?php 
    echo $_SESSION["delete"];
    ?>
</div>
<?php
unset($_SESSION["delete"]);
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
    <link rel="stylesheet" type="text/css" href="citindex.css"/> 
</head>

<body>

    <div class="container">

        <a href="../onepiece.php">Torna alla Home del FANDOM</a>
        <br>
        <a href="adminindex.php">Torna Alla Home dell'Admin</a>
        <div class="cit">
        <form action="crudsaga.php" method="post" enctype="multipart/form-data">
            <div>
                <p>Inserisci una nuova citazione:</p>
                <input type="text" name="nome_pers" id="" placeholder="Inserisci Nome:">
                <textarea name="cit" id="" cols="30" rows="10"></textarea>
                <input type="file" name="img" >
                <input type="submit" value="invio" name="citCreate">
            </div>
        </form>

        <br>
        <table>
            <thead>
                <tr>
                    <th style="width:15%;">Nome Personaggio</th>
                    <th style="width:15%;">Citazione</th>
                    <th style="width:15%;">Immagine</th>
                </tr>
            </thead>

            <tbody>

                <?php
                // Carica il documento XML
                $xml = simplexml_load_file('../database.xml');
                // Seleziona tutti gli elementi <citazione>
                $citazioni = $xml->citazioni->citazione;
                foreach ($citazioni as $citazione) {
                ?>
                <tr>
                    <td><?php echo $citazione['nome_pers']; ?></td>
                    <td><?php 
                        // Tronca la citazione se supera i 40 caratteri senza interrompere una parola
                        $citazioneText = $citazione->cit;
                        if (strlen($citazioneText) > 40) {
                            $citazioneText = substr($citazioneText, 0, 40);
                            $lastSpacePos = strrpos($citazioneText, ' ');
                            $citazioneText = substr($citazioneText, 0, $lastSpacePos) . '...';
                        }
                        echo $citazioneText; 
                    ?>
                </td>
                    <td>
                        <img src="../<?php echo $citazione['img']; ?>" alt="Immagine" style="width: 80px;">
                    </td>
                    <td>
                        <a href="editcit.php?id=<?php echo $citazione['id']; ?>">Edit</a>
                        <a href="deletecit.php?id=<?php echo $citazione['id']; ?>">Delete</a>
                    </td>
                </tr>
                <?php } ?>

            </tbody>

        </table>

        </div>
    </div>
</body>
</html>
