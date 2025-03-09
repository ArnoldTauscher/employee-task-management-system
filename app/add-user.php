<?php

// Startet die PHP-Session
session_start();

// Überprüft, ob der Benutzer angemeldet ist und eine Rolle sowie ID hat
if (isset($_SESSION["role"]) && isset($_SESSION["id"])) {

    // Überprüfung der POST-Daten und ob der Benutzer ein Admin ist
    if (!empty($_POST['token']) &&
        isset($_POST["full_name"]) &&
        isset($_POST["user_name"]) &&
        isset($_POST["password"]) &&
        $_SESSION["role"] == "admin") {

        // Validierung des CSRF-Tokens
        if (hash_equals($_SESSION['token'], $_POST['token'])) {

            // Datenbankverbindung und Hilfsfunktionen für Validierung und Benutzerverwaltung
            include "../DB_connection.php";
            include "./Helpers/validation_functions.php";
            include "./Helpers/user_functions.php";

            // Verarbeitung der Eingabedaten aus dem Formular
            $data = array(
                "full_name" => $_POST["full_name"],
                "user_name" => $_POST["user_name"],
                "password" => password_hash($_POST["password"], PASSWORD_DEFAULT) // Hashing the password
            );

            // Validierung und Verarbeitung der Benutzerdaten
            $error = validate_and_process_user($connection, $data, $_POST['token'], false);

            // Überprüfung, ob bei der Validierung ein Fehler aufgetreten ist
            if ($error) {
                // Weiterleitung zur Erstellungsseite mit Fehlermeldung
                header("Location: ../add-user.php?error=$error");
                
            // Erfolgreiche Erstellung eines neuen Benutzers, Weiterleitung mit Erfolgsmeldung
            } else {
                $em = "User created successfully";
                header("Location: ../add-user.php?success=$em");
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
        header("Location: ../add-user.php?error=$em");
        exit();
    }

// Fehlerbehandlung für nicht angemeldete Benutzer + Weiterleitung und Fehlermeldung 
} else {
    $em = "First login";
    header("Location: ../login.php?error=$em");
    exit();
} ?>