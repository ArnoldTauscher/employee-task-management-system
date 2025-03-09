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
	<title>Create Task</title>
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
            <h4 class="title">Create Task</h4>
            <form class="form-1" method="POST" action="app/add-task.php">
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
                <!-- Formularfelder für Aufgabedaten -->
                <div class="input-holder">
                    <label>Title</label>
                    <input type="text" name="title" class="input-1" placeholder="Title"><br>
                </div>
                <div class="input-holder">
                    <label>Description</label>
                    <textarea type="text" name="description" class="input-1" placeholder="Description"></textarea><br>
                </div>
                <div class="input-holder">
                    <label>Due Date</label>
                    <input type="date" name="due_date" class="input-1" placeholder="Due Date"><br>
                </div>
                <div class="input-holder">
                    <label>Assigned to</label>
                    <select name="assigned_to" class="input-1">
                        <option value="0">Select employee</option>
                        <?php 
                            // Überprüfen, ob Benutzer vorhanden sind
                            if ($users != 0) {
                                // Schleife durch alle Benutzer
                                foreach ($users as $user) {
                        ?>
                        <option value="<?=$user["id"]?>"><?=$user["full_name"]?></option>
                        <?php }}  ?>
                    </select><br>
                </div>
                <button class="edit-btn">Create Task</button>
            </form>

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

    // Wenn der Benutzer nicht als Admin angemeldet ist, Weiterleitung zur Login-Seite
	$em = "First login";
	header("Location: login.php?error=$em");
	exit();

}

?>