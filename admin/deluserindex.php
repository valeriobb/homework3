<?php
require_once("requireadmin.php");

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
    <link rel="stylesheet" type="text/css" href="deluserindex.css"/> 
</head>

<body>

    <div class="container">

        <a href="../onepiece.php">Torna alla Home del FANDOM</a>
        <br>
        <a href="adminindex.php">Torna Alla Home dell'Admin</a>
        <table>
            <thead>
                <tr>
                    <th style="width:15%;">Username</th>
                    <th style="width:15%;">Post pubblicati</th>
                    <th></th>
                </tr>
            </thead>

            <tbody>
                <?php
                // Carica il file XML
                $xml = simplexml_load_file('../database.xml');

                // Crea un array per tenere traccia del numero di post per utente
                $postCounts = [];
                foreach ($xml->posts->post as $post) {
                    $userId = (string) $post['id_user'];
                    if (!isset($postCounts[$userId])) {
                        $postCounts[$userId] = 0;
                    }
                    $postCounts[$userId]++;
                }

                // Genera la tabella utenti
                foreach ($xml->users->user as $user) {
                    if ((string) $user['email'] != 'admin@gmail.com') {
                        $userId = (string) $user['id'];
                        $username = (string) $user['username'];
                        $numPosts = isset($postCounts[$userId]) ? $postCounts[$userId] : 0;
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($username); ?></td>
                            <td><?php echo htmlspecialchars($numPosts); ?></td>
                            <td>
                                <a class="ban" href="deleteuser.php?id=<?php echo htmlspecialchars($userId); ?>">Delete user</a>
                            </td>
                        </tr>
                        <?php
                    }
                }
                ?>
            </tbody>

        </table>
        

    </div>
</body>
</html>