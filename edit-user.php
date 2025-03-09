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

    // Überprüft, ob eine Benutzer-ID übergeben wurde
    if (!isset($_GET["id"])) {
        header("Location: user.php");
        exit();
    }

    $id = $_GET["id"];
    // Abrufen der Benutzerdaten anhand der ID
    $user = get_user_by_id($connection, $id);
    // print_r($user['username']);

    // Überprüft, ob ein Benutzer mit der angegebenen ID existiert
    // ha nincs olyan user-id, vissza a user oldalra (a browser kereső sávjába olyan id-t írok, amit akarok)
    if ($user == 0) {
        header("Location: user.php");
        exit();
    }

?>

<!DOCTYPE html>
<html>
<head>
	<title>Edit User</title>
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
            <h4 class="title">Edit User<a href="user.php">Users</a></h4>
            <form class="form-1" method="POST" action="app/update-user.php">
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
                    <input type="text" name="full_name" value="<?=htmlspecialchars($user["full_name"], ENT_QUOTES, 'UTF-8')?>"  class="input-1" placeholder="Full Name"><br> 
                </div>
                <div class="input-holder">
                    <label>Username</label>
                    <input type="text" name="user_name" value="<?=htmlspecialchars($user["username"], ENT_QUOTES, 'UTF-8')?>" class="input-1" placeholder="Username"><br>
                </div>
                <div class="input-holder">
                    <label>Password</label>
                    <input type="password" name="password" value=""  class="input-1" placeholder="Password"><br>
                </div>
                <input type="text" name="id" value="<?=htmlspecialchars($user["id"], ENT_QUOTES, 'UTF-8')?>" hidden>

                <button class="edit-btn">Update</button>
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