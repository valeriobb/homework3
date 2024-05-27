<?php
session_start();

if (isset($_SESSION["user"])) {
    header("Location: onepiece.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Carica il file XML
    $xml = new DOMDocument();
    $xml->load('database.xml');

    // Trova tutti gli elementi utente nel file XML
    $users = $xml->getElementsByTagName('user');
    
    // Itera attraverso gli utenti e verifica le credenziali
    foreach ($users as $user) {
        if ($user->getAttribute('email') === $email) {
            $hashedPassword = $user->getAttribute('password');
            if (password_verify($password, $hashedPassword)) {
                // Credenziali valide, imposta la sessione
                $_SESSION["email"] = $email;
                $_SESSION["user"] = $user->getAttribute('username');
                $_SESSION["id_user"] = $user->getAttribute('id');
                if ($_SESSION["email"] == 'admin@gmail.com') {
                    header("Location: admin/adminindex.php");
                } else {
                    header("Location: userindex.php");
                }
                exit();
            } else {
                echo "<div class='alert alert-danger'>Password does not match</div>";
            }
        }
    }

    // Se l'email non è stata trovata
    echo "<div class='alert alert-danger'>Email does not match</div>";
}
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <link rel="icon" href="images/download.png"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=deide-width,initial-scale=1.0"/>
    <title>One Piece Fandom - Login</title>
    <link rel="stylesheet" type="text/css" href="login.css"/>
</head>

<body>

<div class="container">
    <div class="login-form">
        <div class="form-wrapper">
            <form action="login.php" method="post">
                <div class="form-group">
                    <input type="email" placeholder="Enter Email:" name="email" class="form-control">
                </div>
                <div class="form-group">
                    <input type="password" placeholder="Enter Password:" name="password" class="form-control">
                </div>
                <div class="form-btn">
                    <input type="submit" value="Login" name="login" class="btn btn-primary">
                </div>
            </form>
        </div>
        <div class="signup-link">
            <p>Not registered yet? <a href="signin.php">Register Here</a></p>
        </div>
        <div class="back-home">
            <a href="onepiece.php">Torna alla Home</a>
        </div>
    </div>
</div>

</body>

</html>