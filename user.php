<?php

// Startet die PHP-Session
session_start();

// Holt den CSRF-Token aus der Session
$token = $_SESSION['token'];

// Überprüft, ob der Benutzer angemeldet ist und die Admin-Rolle hat
if (isset($_SESSION["role"]) && isset($_SESSION["id"]) && $_SESSION["role"] == "admin") {

    // Einbinden der Datenbankverbindung und des User-Models
    include "DB_connection.php";
    include "app/Model/User.php";

    // Abrufen aller Benutzer aus der Datenbank
    $users = get_all_users($connection);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Users</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <input type="checkbox" id="checkbox">
    <!-- Einbinden der Kopfzeile -->
    <?php include "inc/header.php" ?>

    <div class="body">
        <!-- Einbinden der Navigation -->
        <?php include "inc/nav.php" ?>

        <section class="section-1">
            <h4 class="title">Manage Users<a href="add-user.php">Add User</a></h4>
            <!-- Anzeige von Erfolgsmeldungen -->
            <?php if (isset($_GET["success"])) { ?>
                <div class="success" role="alert">
                <?php echo htmlspecialchars($_GET["success"], ENT_QUOTES, 'UTF-8'); ?>
                </div>
            <?php } ?>
            <?php 
                // Überprüfen, ob Benutzer vorhanden sind
                if ($users != 0) {
            ?>

            <!-- Tabelle zur Anzeige der Benutzer -->
            <table class="main-table">
                <tr>
                    <th>#</th>
                    <th>Full Name</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Action</th>
                </tr>

                <?php
                    // Schleife durch alle Benutzer
                    $i = 0; foreach ($users as $user) {
                ?>

                <tr>
                    <td><?=++$i?></td>
                    <td><?=htmlspecialchars($user["full_name"], ENT_QUOTES, 'UTF-8')?></td>
                    <td><?=htmlspecialchars($user["username"], ENT_QUOTES, 'UTF-8')?></td>
                    <td><?=htmlspecialchars($user["role"], ENT_QUOTES, 'UTF-8')?></td>
                    <td>
                        <a href="edit-user.php?id=<?=htmlspecialchars($user["id"], ENT_QUOTES, 'UTF-8')?>" class="edit-btn">Edit</a>
                        <!-- CSRF-Token für die Löschfunktion -->
                        <a href="delete-user.php?id=<?=htmlspecialchars($user["id"], ENT_QUOTES, 'UTF-8')?>&token=<?=htmlspecialchars($token, ENT_QUOTES, 'UTF-8')?>" class="delete-btn">Delete</a>
                    </td>
                </tr>

                <?php }  ?>

            </table>

            <?php } else { ?>
                <!-- Anzeige, wenn keine Benutzer vorhanden sind -->
                <h3>Empty</h3>
            <?php }  ?>

        </section>
    </div>
    <script>
        // Markiert den zweiten Navigationspunkt als aktiv
        const active = document.querySelector("#navList li:nth-child(2)"); 
        active.classList.add("active");
    </script>
</body>
</html>

<?php } else {
    // Wenn der Benutzer nicht angemeldet oder kein Admin ist, Weiterleitung zur Login-Seite
    $em = "First login";
    header("Location: login.php?error=$em");
    exit();
} ?>
