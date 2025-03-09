<?php

// Startet die PHP-Session
session_start();

// Holt den CSRF-Token aus der Session
$token = $_SESSION['token'];

// Überprüft, ob der Benutzer angemeldet ist und eine Rolle sowie ID hat
if (isset($_SESSION["role"]) && isset($_SESSION["id"]) && $_SESSION["role"] == "employee") {

    // Einbinden der Datenbankverbindung und des User-Models
    include "DB_connection.php";
    include "app/Model/Task.php";
    include "app/Model/User.php";

        // Überprüft, ob eine Aufgabe-ID übergeben wurde
    if (!isset($_GET["id"])) {
        header("Location: tasks.php");
        exit();
    }

    $id = $_GET["id"];
    // Abrufen der Aufgabedaten anhand der ID
    $task = get_task_by_id($connection, $id);
    // print_r($task['title']);

    // Überprüfung, ob die Aufgabe existiert
    // ha nincs olyan user-id, vissza a user oldalra (a browser kereső sávjába olyan id-t írok, amit akarok)
    if ($task == 0) {
        header("Location: tasks.php");
        exit();
    }

    // Abrufen aller Benutzer aus der Datenbank !!!!!!!!!!!!!!!!!!!!!!!!!
    $users = get_all_users($connection);

?>

<!DOCTYPE html>
<html>
<head>
	<title>Edit Task</title>
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
            <h4 class="title">Edit Task<a href="my_task.php">My Tasks</a></h4>
            <form class="form-1" method="POST" action="app/update-task-employee.php">
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
                <!-- Formularfelder für Aufgabendaten -->
                <div class="input-holder">
                    <label></label>
                    <p><b>Title: </b><?=$task["title"]?></p>
                </div>
                <div class="input-holder">
                    <label></label>
                    <p><b>Description: </b><?=$task["description"]?></p><br>
                </div>
                <div class="input-holder">
                    <label>Status</label>
                    <select name="status" class="input-1">
                        <option
                            <?php if($task["status"] == "pending") echo "selected"; ?>
                        >
                            pending
                        </option>
                        <option
                            <?php if($task["status"] == "in progress") echo "selected"; ?>
                        >
                            in progress
                        </option>
                        <option
                            <?php if($task["status"] == "completed") echo "selected"; ?>
                        >
                            completed
                        </option>
                    </select><br>
                </div>
                <input type="text" name="id" value="<?=$task["id"]?>" hidden>

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

}

?>