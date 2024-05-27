<?php 
session_start();

if(isset($_SESSION["user"])){
    $user = $_SESSION["user"];
}


?>

<!DOCTYPE html>
<html lang="it">

<head>
    <link rel="icon" href="images/download.png" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width,initial-scale=1.0"/>
    <title>One Piece Fandom - Saghe</title>
    <link rel="stylesheet" type="text/css" href="saghe.css"/>    
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
                <li><a href="saghe.php" style="--i:2;" class="active">Saghe/Episodi</a></li>
                <li><a href="personaggi.php" style="--i:3;">Personaggi</a></li>
                <li><a href="citazioni.php" style="--i:4;">Citazioni</a></li>
                <li><a href="blog.php" style="--i:5;">Blog</a></li>
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



<div class="page">
    <h1 class="title" id="ritorno">Tutte le saghe di One piece</h1>
    <div class="desc_saga">La storia di One Piece è gremita di personaggi incredibili,
        avventure indimenticabili e vanta un'ambientazione costruita in maniera ineccepibile.
        Ah, ovviamente narra anche un sacco di bravate piratesche.
        Ma potresti avere delle domande su questa storia, che tu sia un veterano o una persona che ha appena conosciuto la serie. Da dove bisogna partire?
        Ovviamenente dal primo episodio.</div>
    <br/>

    <table>
        <thead>
            <tr>
                <th>Saga:</th>
                <!--<th>Trama</th>-->
                <th>Episodi</th>
                <th>Copertina</th>
                <th>Recensione</th>
            </tr>
        </thead>

        <tbody>

        <?php
// Carica il file XML
$xml = simplexml_load_file('database.xml');
if ($xml === false) {
    die('Errore durante il caricamento del file XML.');
}

foreach ($xml->saghe->saga as $saga) {
    ?>
    <tr>
        <td><a href="viewsaga.php?id=<?php echo $saga['id'] ?>"><?php echo $saga['nome'] ?></a></td>
        <td><?php echo $saga['ep_iniziale'] ?> - <?php echo $saga['ep_finale'] ?></td>
        <td>
            <img src="<?php echo $saga['img'] ?>" alt="Immagine" style="width: 120px; height: 120px;">
        </td>
        <td>
            <?php
            // Calcola la media delle recensioni per questa saga
            $id_saga = $saga['id'];
            $reviews = $xml->xpath("/database/recensioni/recensione[@id_saga='$id_saga']");
            if ($reviews !== false && count($reviews) > 0) {
                $total = 0;
                foreach ($reviews as $review) {
                    $total += (int)$review->review;
                }
                $average = $total / count($reviews);
                echo number_format($average, 1) . '★';
            } else {
                echo 'Nessuna recensione disponibile';
            }
            ?>
        </td>
    </tr>
<?php } ?>
    </table>
</div>

<div  class="footer">
    <p class="copy">&copy;2024 Copyright One Piece Fandom by Antonio Agostini &amp; Valerio Baratella</p>
</div>    
</body>
</html>