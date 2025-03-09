<?php

// Startet die PHP-Session
session_start();

// Überprüft, ob der Benutzer angemeldet ist und eine Rolle sowie ID hat
if (isset($_SESSION["role"]) && isset($_SESSION["id"])) {

    // Überprüfung der POST-Daten und ob der Benutzer ein Admin ist
    if (!empty($_POST['token']) &&
        isset($_POST["full_name"]) &&
        isset($_POST["user_name"]) &&
        $_SESSION["role"] == "admin") {

        // Validierung des CSRF-Tokens
        if (hash_equals($_SESSION['token'], $_POST['token'])) {

            // Datenbankverbindung und Hilfsfunktionen für Validierung und Benutzerverwaltung
            include "../DB_connection.php";
            include "./Helpers/validation_functions.php";
            include "./Helpers/user_functions.php";

            // Verarbeitung der Eingabedaten aus dem Formular
            $data = array(
                "id" => $_POST["id"],
                "full_name" => $_POST["full_name"],
                "user_name" => $_POST["user_name"],
                "password" => !empty($_POST["password"]) ? password_hash($_POST["password"], PASSWORD_DEFAULT) : "" // Hashing Password wenn bereitgestellt
            );

            // Validierung und Verarbeitung der Benutzerdaten
            $error = validate_and_process_user($connection, $data, $_POST['token'], true);

            // Überprüfung, ob bei der Validierung ein Fehler aufgetreten ist + Weiterleitung und Fehlermeldung
            if ($error) {
                header("Location: ../edit-user.php?error=$error&id=" . $data["id"]);

            // Erfolgreiche änderung der Benutzerdaten, Weiterleitung mit Erfolgsmeldung
            } else {
                $sm = "User data successfully updated";
                header("Location: ../edit-user.php?success=$sm&id=" . $data["id"]);
            }
            exit();

        // Fehlerbehandlung bei ungültigem CSRF-Token + Weiterleitung und Fehlermeldung   
        } else {
            $em = "Invalid CSRF token";
            header("Location: ../edit-user.php?error=$em");
            exit();
        }

    // Fehlerbehandlung für ungültige POST-Daten oder fehlende Admin-Rechte + Weiterleitung und Fehlermeldung
    } else {
        $em = "Invalid input or insufficient permissions";
        header("Location: ../edit-user.php?error=$em");
        exit();
    }

// Fehlerbehandlung für nicht angemeldete Benutzer + Weiterleitung und Fehlermeldung 
} else {
    $em = "First login";
    header("Location: ../login.php?error=$em");
    exit();
} ?>