<?php

// Startet die PHP-Session
session_start();

// Holt den CSRF-Token aus der Session
$token = $_SESSION['token'];

// Überprüft, ob der Benutzer angemeldet ist und die Employee-Rolle hat
if (isset($_SESSION["role"]) && isset($_SESSION["id"]) && $_SESSION["role"] == "employee") {

    // Einbinden der Datenbankverbindung und des User-Models
    include "DB_connection.php";
    include "app/Model/User.php";

    // Abrufen der Benutzerdaten anhand seiner ID
    $user = get_user_by_id($connection, $_SESSION["id"]);

?>

<!DOCTYPE html>
<html>
<head>
	<title>Edit Profile</title>
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
            <h4 class="title">Edit Profile<a href="profile.php">Profile</a></h4>
            <form class="form-1" method="POST" action="app/update-profile.php">
                <!-- CSRF token dem Formular hinzugefügt -->
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
                    <p class="input-1"><?=htmlspecialchars($user["full_name"], ENT_QUOTES, 'UTF-8')?></p><br>
                </div>
                <div class="input-holder">
                    <label>Old Password</label>
                    <input type="password" name="password"  class="input-1" placeholder="Old Password"><br>
                </div>
                <div class="input-holder">
                    <label>New Password</label>
                    <input type="password" name="new_password"  class="input-1" placeholder="New Password"><br>
                </div>
                <div class="input-holder">
                    <label>Confirm Password</label>
                    <input type="password" name="confirm_password"  class="input-1" placeholder="Confirm Password"><br>
                </div>

                <button class="edit-btn">Change</button>
            </form>
		</section>
	</div>
    <script>
        // Markiert den zweiten Navigationspunkt als aktiv
        const active = document.querySelector("#navList li:nth-child(3)"); 
        active.classList.add("active");
    </script>
</body>
</html>

<?php } else {

    // Wenn der Benutzer nicht angemeldet ist, Weiterleitung zur Login-Seite
	$em = "First login";
	header("Location: login.php?error=$em");
	exit();

}

?>