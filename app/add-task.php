<?php

// Startet die PHP-Session
session_start();

// Überprüft, ob der Benutzer angemeldet ist und eine Rolle sowie ID hat
if (isset($_SESSION["role"]) && isset($_SESSION["id"])) {

    // Überprüfung der POST-Daten und ob der Benutzer ein Admin ist
    if (!empty($_POST['token']) &&
        isset($_POST["title"]) &&
        isset($_POST["description"]) &&
        isset($_POST["due_date"]) &&
        isset($_POST["assigned_to"]) &&
        $_SESSION["role"] == "admin") {
        
        // Validierung des CSRF-Tokens
        if (hash_equals($_SESSION['token'], $_POST['token'])) {
            
            // Datenbankverbindung und Hilfsfunktionen für Validierung und Aufgabenverwaltung
            include "../DB_connection.php";
            include "./Helpers/validation_functions.php";
            include "./Helpers/task_functions.php";

            // Verarbeitung der Eingabedaten aus dem Formular
            $data = array(
                "title" => $_POST["title"],
                "description" => $_POST["description"],
                "due_date" => $_POST["due_date"],
                "assigned_to" => $_POST["assigned_to"],
            );

            // Validierung und Verarbeitung der Aufgabendaten
            $error = validate_and_process_task($connection, $data);

            // Überprüfung, ob bei der Validierung ein Fehler aufgetreten ist
            if ($error) {
                // Weiterleitung zur Erstellungsseite mit Fehlermeldung
                header("Location: ../create_task.php?error=$error");

            // Erfolgreiche Erstellung einer neuen Aufgabe, Weiterleitung mit Erfolgsmeldung
            } else {
                $em = "Task created successfully";
                header("Location: ../create_task.php?success=$em");
            }
            exit();

        } else {
            // Fehlerbehandlung bei ungültigem CSRF-Token + Weiterleitung und Fehlermeldung
            $em = "Invalid CSRF token";
            header("Location: ../add-user.php?error=$em");
            exit();
        }



    // Fehlerbehandlung für ungültige POST-Daten oder fehlende Admin-Rechte + Weiterleitung und Fehlermeldung
    } else {
        $em = "Invalid input or insufficient permissions";
        header("Location: ../create_task.php?error=$em");
        exit();
    }

// Fehlerbehandlung für nicht angemeldete Benutzer + Weiterleitung und Fehlermeldung
} else {
    $em = "First login";
    header("Location: ../login.php?error=$em");
    exit();
} ?>