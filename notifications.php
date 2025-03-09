<?php

// Startet die PHP-Session
session_start();

// Überprüft, ob der Benutzer angemeldet ist und eine Rolle sowie ID hat
if (isset($_SESSION["role"]) && isset($_SESSION["id"])) {

    // Einbinden der Datenbankverbindung und der Model-Dateien
    include "DB_connection.php";
    include "app/Model/Notification.php";

    // Abrufen aller Nachrichten des Benutzers aus der Datenbank
    $notifications = get_all_notifications_by_user_id($connection, $_SESSION["id"]);

?>

<!DOCTYPE html>
<html>
<head>
	<title>Notifications</title>
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
            <h4 class="title">All Notifications</h4>
            <!-- Anzeige von Erfolgsmeldungen -->
            <?php if (isset($_GET["success"])) { ?>
                <div class="success" role="alert">
                <?php echo htmlspecialchars($_GET["success"], ENT_QUOTES, 'UTF-8'); ?>
                </div>
            <?php } ?>
            <?php 
                // Überprüfen, ob Nachrichten vorhanden sind
                if ($notifications != 0) {
            ?>

            <!-- Tabelle zur Anzeige der Nachrichten -->
            <table class="main-table">
                <tr>
                    <th>#</th>
                    <th>Message</th>
                    <th>Type</th>
                    <th>Date</th>
                </tr>

                <?php

                    // Schleife durch alle Nachrichten
                    $i = 0; foreach ($notifications as $notification) {

                ?>

                <tr>
                    <td><?=++$i?></td>
                    <td><?=$notification["message"]?></td>
                    <td><?=$notification["type"]?></td>
                    <td><?=$notification["date"]?></td>

                </tr>

                <?php }  ?>

            </table>

            <?php } else { ?>

                <!-- Anzeige, wenn keine Nachrichten vorhanden sind -->
                <h3>You have 0 notification</h3>

            <?php }  ?>

		</section>
	</div>
    <script>
        // Markiert den vierten Navigationspunkt als aktiv
        const active = document.querySelector("#navList li:nth-child(4)"); 
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