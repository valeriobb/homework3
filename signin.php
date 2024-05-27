<?php
session_start();

if (isset($_SESSION["user"])) {
    header("Location: onepiece.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fullName = $_POST["fullname"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $passwordRepeat = $_POST["repeat_password"];

    // Carica il file XML
    $xml = new DOMDocument();
    $xml->load('database.xml');

    // Ottenere l'ultimo ID utente e incrementarlo per il nuovo utente
    $users = $xml->getElementsByTagName('user');
    $lastUserID = 0;
    foreach ($users as $user) {
        $idAttr = $user->getAttributeNode('id');
        if ($idAttr) {
            $userID = intval($idAttr->nodeValue);
            if ($userID > $lastUserID) {
                $lastUserID = $userID;
            }
        }
    }
    $newUserID = $lastUserID + 1;

    $errors = array();

    if (empty($fullName) || empty($email) || empty($password) || empty($passwordRepeat)) {
        array_push($errors, "All fields are required");
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        array_push($errors, "Email is not valid");
    }
    if (strlen($password) < 8) {
        array_push($errors, "Password must be at least 8 characters long");
    }
    if ($password !== $passwordRepeat) {
        array_push($errors, "Password does not match");
    }

    // Verifica se l'email esiste già nel file XML
    foreach ($users as $user) {
        $emailAttr = $user->getAttributeNode('email');
        if ($emailAttr && $emailAttr->nodeValue === $email) {
            array_push($errors, "Email already exists");
            break;
        }
    }

    // Se non ci sono errori, aggiungi il nuovo utente al file XML
    if (count($errors) === 0) {
        $user = $xml->createElement('user');
        $user->setAttribute('id', $newUserID); // Aggiungi l'ID auto-incrementante
        $user->setAttribute('username', $fullName);
        $user->setAttribute('email', $email);
        $user->setAttribute('password', password_hash($password, PASSWORD_DEFAULT));

        $usersNode = $xml->getElementsByTagName('users')->item(0);
        $usersNode->appendChild($user);

        // Salva le modifiche nel file XML
        $xml->save('database.xml');

        echo "<div class='alert alert-success'>You are registered successfully.</div>";
    } else {
        foreach ($errors as $error) {
            echo "<div class='alert alert-danger'>$error</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <link rel="stylesheet" type="text/css" href="login.css"/> <!-- lo stile è lo stesso del login -->
</head>
<body>

<div class="container">
    <div class="signup-form">
        <div class="form-wrapper">
            <form action="signin.php" method="post">
                <div class="form-group">
                    <input type="text" class="form-control" name="fullname" placeholder="Full Name">
                </div>
                <div class="form-group">
                    <input type="email" class="form-control" name="email" placeholder="Email">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" name="password" placeholder="Password">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" name="repeat_password" placeholder="Repeat Password">
                </div>
                <div class="form-btn">
                    <input type="submit" class="btn btn-primary" value="Register" name="submit">
                </div>
            </form>
        </div>
        <div class="login-link">
            <p>Already Registered? <a href="login.php">Login Here</a></p>
        </div>
        <div class="back-home">
            <a href="onepiece.php">Torna alla Home</a>
        </div>
    </div>
</div>

</body>
</html>