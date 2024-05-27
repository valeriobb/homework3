<?php 
    session_start();

    if(isset($_SESSION["user"])){
        $user = $_SESSION["user"];
    }

    // Carica il file XML
    $xml = simplexml_load_file('database.xml');

    // Controlla se il caricamento è avvenuto correttamente
    if ($xml === false) {
        exit('Errore nel caricamento del file XML.');
    }
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="icon" href="images/download.png"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=deide-width,initial-scale=1.0"/>
    <title>One Piece Fandom - Cit</title>
    <link rel="stylesheet" type="text/css" href="cit.css"/>    
</head>

<body>
    <div class="background"></div>
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
                    <li><a href="citazioni.php" style="--i:4;" class="active">Citazioni</a></li>
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
    <h1 class="title" id="ritorno">Tutte le citazioni di One piece</h1>
    <div class="container">
        <?php foreach ($xml->citazioni->citazione as $citazione): ?>
            <div class="quote-container">
                <img src="<?php echo $citazione['img']; ?>" alt="Immagine">
                <div class="quote">
                    <div class="content">
                        <h2><?php echo $citazione['nome_pers']; ?></h2>
                        <p>"<?php echo $citazione->cit; ?>"</p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="footer">
        <p class="copy">&copy;2024 Copyright One Piece Fandom by Antonio Agostini &amp; Valerio Baratella</p>
    </div>  

</body>

</html>
