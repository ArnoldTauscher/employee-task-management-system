<?php

// Startet die PHP-Session
session_start();

// Holt den CSRF-Token aus der Session
$token = $_SESSION['token'];

// Überprüft, ob der Benutzer angemeldet ist und die Admin-Rolle hat
if (isset($_SESSION["role"]) && isset($_SESSION["id"]) && $_SESSION["role"] == "admin") {

?>

<!DOCTYPE html>
<html>
<head>
    <title>Add User</title>
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
            <h4 class="title">Add User<a href="user.php">Users</a></h4>
            <form class="form-1" method="POST" action="app/add-user.php">
                <!-- CSRF-Token dem Formular hinzugefügt -->
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token, ENT_QUOTES, 'UTF-8'); ?>" />
                <!-- Anzeige von Fehler- und Erfolgsmeldungen -->
                <?php if (isset($_GET["error"])) { ?>
                    <div class="danger" role="alert">
                    <?php echo htmlspecialchars($_GET["error"], ENT_QUOTES, 'UTF-8'); ?>
                    </div>
                <?php } ?>
                <?php if (isset($_GET["success"])) { ?>
                    <div class="success" role="alert">
                    <?php echo htmlspecialchars($_GET["success"], ENT_QUOTES, 'UTF-8'); ?>
                    </div>
                <?php } ?>
                <!-- Formularfelder für Benutzerdaten -->
                <div class="input-holder">
                    <label>Full Name</label>
                    <input type="text" name="full_name" class="input-1" placeholder="Full Name"><br>
                </div>
                <div class="input-holder">
                    <label>Username</label>
                    <input type="text" name="user_name" class="input-1" placeholder="Username"><br>
                </div>
                <div class="input-holder">
                    <label>Password</label>
                    <input type="password" name="password" class="input-1" placeholder="Password"><br>
                </div>

                <button class="edit-btn">Add</button>
            </form>
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

    // Wenn der Benutzer nicht angemeldet ist, Weiterleitung zur Login-Seite
    $em = "First login";
    header("Location: login.php?error=$em");
    exit();

} ?>