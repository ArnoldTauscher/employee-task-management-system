<?php

// Startet die PHP-Session
session_start();

// Überprüft, ob der Benutzer angemeldet ist und eine Rolle sowie ID hat
if (isset($_SESSION["role"]) && isset($_SESSION["id"])) {

    // Datenbankverbindung und Hilfsfunktionen für Aufgabenfilterung sowie Aufgaben- und Benutzerverwaltung
    include "DB_connection.php";
    include "app/Model/Task.php";
    include "app/Model/User.php";
    include "app/Helpers/task_filters.php";

    // Filtert die Aufgaben basierend auf den GET-Parametern
    list($text, $tasks, $num_task) = filter_tasks($connection, $_SESSION["id"]);

?>

<!DOCTYPE html>
<html>
<head>
	<title>My Tasks</title>
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
            <!-- Navigationslinks für verschiedene Aufgabenfilter -->
            <h4 class="title-2">
                <a href="my_task.php?due_date=Due Today">Due Today</a>
				<a href="my_task.php?due_date=Overdue">Overdue</a>
				<a href="my_task.php?due_date=No Deadline">No Deadline</a>
				<a href="my_task.php?status=Pending">Pending</a>
				<a href="my_task.php?status=In progress">In progress</a>
				<a href="my_task.php?status=Completed">Completed</a>
				<a href="my_task.php">My Tasks</a>
			</h4>
            <h4 class="title-2"><?=$text?> (<?=$num_task?>)</h4>
            <!-- Anzeige von Erfolgsmeldungen -->
            <?php if (isset($_GET["success"])) { ?>
                <div class="success" role="alert">
                <?php echo htmlspecialchars($_GET["success"], ENT_QUOTES, 'UTF-8'); ?>
                </div>
            <?php } ?>
            <?php 
                // Überprüfen, ob Aufgaben vorhanden sind
                if ($tasks != 0) {
            ?>

            <!-- Tabelle zur Anzeige der Aufgaben -->
            <table class="main-table">
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Due Date</th>
                    <th>Action</th>
                </tr>

                <?php

                    // Schleife durch alle Aufgaben
                    $i = 0; foreach ($tasks as $task) {

                ?>

                <tr>
                    <td><?=++$i?></td>
                    <td><?=$task["title"]?></td>
                    <td><?=$task["description"]?></td>
                    <td><?=$task["status"]?></td>
                    <td>
                        <?php
                            // Anzeige des Fälligkeitsdatums oder "No Deadline"
                            if ($task["due_date"] == "" || $task["due_date"] == "0000-00-00") echo "No Deadline";
                            else echo $task["due_date"];
                        ?>
                    </td>
                    <td>
                        <!-- Edit Button -->
                        <a href="edit-task-employee.php?id=<?=$task["id"]?>" class="edit-btn">Edit</a>
                    </td>
                </tr>

                <?php }  ?>

            </table>

            <?php } else { ?>

                <!-- Anzeige, wenn keine Aufgaben vorhanden sind -->
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

    // Wenn der Benutzer nicht angemeldet ist, Weiterleitung zur Login-Seite
	$em = "First login";
	header("Location: login.php?error=$em");
	exit();

}

?>