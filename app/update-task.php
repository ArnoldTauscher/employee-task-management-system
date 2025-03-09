<?php

// Startet die PHP-Session
session_start();

// Überprüft, ob der Benutzer angemeldet ist und eine Rolle sowie ID hat
if (isset($_SESSION["role"]) && isset($_SESSION["id"])) {

    // Überprüfung der POST-Daten und ob der Benutzer ein Admin ist
    if (!empty($_POST['token']) &&
        isset($_POST["id"]) &&
        isset($_POST["title"]) &&
        isset($_POST["description"]) &&
        isset($_POST["due_date"]) &&
        isset($_POST["assigned_to"]) &&
        isset($_POST["status"]) &&
        $_SESSION["role"] == "admin") {

        // Validierung des CSRF-Tokens
        if (hash_equals($_SESSION['token'], $_POST['token'])) {

            // Datenbankverbindung und Hilfsfunktionen für Validierung und Aufgabenverwaltung
            include "../DB_connection.php";
            include "./Helpers/validation_functions.php";
            include "./Helpers/task_functions.php";
/*
            // Überprüfung, ob die Task-ID gültig ist
            $task = get_task_by_id($connection, $_POST["id"]);
            if ($task == 0) {
                $em = "Invalid task ID";
                header("Location: ../edit-task.php?error=$em");
                exit();
            }
*/
            // Verarbeitung der Eingabedaten aus dem Formular
            $data = array(
                "id" => $_POST["id"],
                "title" => $_POST["title"],
                "description" => $_POST["description"],
                "due_date" => $_POST["due_date"],
                "assigned_to" => $_POST["assigned_to"],
                "status" => $_POST["status"]
            );

            // Validierung und Verarbeitung der Aufgabendaten
            $error = validate_and_process_task($connection, $data, true);

            // Überprüfung, ob bei der Validierung ein Fehler aufgetreten ist + Weiterleitung und Fehlermeldung
            if ($error) {
                header("Location: ../edit-task.php?error=$error");
            } else {

                // Erfolgreiche änderung der Aufgabendaten, Weiterleitung mit Erfolgsmeldung
                $em = "Task successfully updated";
                header("Location: ../edit-task.php?success=$em");
            }
            exit();

        // Fehlerbehandlung bei ungültigem CSRF-Token + Weiterleitung und Fehlermeldung  
        } else {
            $em = "Invalid CSRF token";
            header("Location: ../add-user.php?error=$em");
            exit();
        }

    // Fehlerbehandlung für ungültige POST-Daten oder fehlende Admin-Rechte + Weiterleitung und Fehlermeldung
    } else {
        $em = "Invalid input or insufficient permissions";
        header("Location: ../edit-task.php?error=$em");
        exit();
    }

// Fehlerbehandlung für nicht angemeldete Benutzer + Weiterleitung und Fehlermeldung
} else {
    $em = "First login";
    header("Location: ../login.php?error=$em");
    exit();
}

?>