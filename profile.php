<?php

// Startet die PHP-Session
session_start();

// Überprüft, ob der Benutzer angemeldet ist und die Rolle "employee" hat
if (isset($_SESSION["role"]) && isset($_SESSION["id"]) && $_SESSION["role"] == "employee") {

    // Einbinden der Datenbankverbindung und des User-Models
    include "DB_connection.php";
    include "app/Model/User.php";

    // Abrufen der Benutzerdaten anhand der Session-ID
    $user = get_user_by_id($connection, $_SESSION["id"]);

?>

<!DOCTYPE html>
<html>
<head>
	<title>Profile</title>
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
            <h4 class="title">Profile<a href="edit_profile.php">Edit Profile</a></h4>
            <!-- Tabelle zur Anzeige der Benutzerdaten -->
            <table class="main-table" style="max-width: 500px;">
                <tr>
                    <td>Full Name</td>
                    <td><?=$user["full_name"]?></td>  
                </tr>
                <tr>
                    <td>User Name</td>
                    <td><?=$user["username"]?></td>  
                </tr>
                <tr>
                    <td>Joined At</td>
                    <td><?=$user["created_at"]?></td>  
                </tr>
            </table>
		</section>
	</div>
    <script>
        // Markiert den dritten Navigationspunkt als aktiv
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

} ?>