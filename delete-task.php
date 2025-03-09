<?php

// Startet die PHP-Session
session_start();

// Überprüft, ob der Benutzer angemeldet ist und die Admin-Rolle hat
if (isset($_SESSION["role"]) && isset($_SESSION["id"]) && $_SESSION["role"] == "admin") {

    // Einbinden der Datenbankverbindung und des User-Models
    include "DB_connection.php";
    include "app/Model/Task.php";

    // Überprüfung, ob eine Aufgabe-ID übergeben wurde
    if (!isset($_GET["id"])) {
        header("Location: tasks.php");
        exit();
    }

    $id = $_GET["id"];
    $task = get_task_by_id($connection, $id);
    // print_r($task['title']);

    // Überprüfung, ob die Aufgabe existiert
    // ha nincs olyan task-id, vissza a tasks oldalra (a browser kereső sávjába olyan id-t írok, amit akarok)
    if ($task == 0) {
        header("Location: tasks.php");
        exit();
    }

    // Vorbereitung der Daten für das Löschen der Aufgabe
    $data = array($id);
    // Ausführen der Löschoperation
    delete_task($connection, $data);

    // Erfolgsmeldung und Weiterleitung
    $sm = "Deleted Successfully";
    header("Location: tasks.php?success=$sm");
    exit();

} else {

    // Wenn der Benutzer nicht angemeldet ist, Weiterleitung zur Login-Seite
	$em = "First login";
	header("Location: login.php?error=$em");
	exit();

}

?>