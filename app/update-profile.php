<?php

// Startet die PHP-Session
session_start();

// Überprüft, ob der Benutzer angemeldet ist und eine Rolle sowie ID hat
if (isset($_SESSION["role"]) && isset($_SESSION["id"])) {

    // Überprüfung der POST-Daten und ob der Benutzer ein Employee ist
    if  (!empty($_POST['token']) &&
        isset($_POST["password"]) &&
        isset($_POST["new_password"]) &&
        isset($_POST["confirm_password"]) &&
        $_SESSION["role"] == "employee") {

        // Validierung des CSRF-Tokens
        if (hash_equals($_SESSION['token'], $_POST['token'])) {

            // Datenbankverbindung und Hilfsfunktionen für Validierung und Benutzerverwaltung
            include "../DB_connection.php";
            include "./Helpers/validation_functions.php";
            include "./Helpers/user_functions.php";

            $id = $_SESSION["id"];

            // Verarbeitung der Eingabedaten aus dem Formular
            $data = array(
                "password" => $_POST["password"],
                "new_password" => $_POST["new_password"],
                "confirm_password" => $_POST["confirm_password"],
            );

            // Validierung und Verarbeitung der Passwortänderung
            $error = validate_and_process_user_password($connection, $data, $id, $_POST['token']);

            // Überprüfung, ob bei der Validierung ein Fehler aufgetreten ist + Weiterleitung und Fehlermeldung
            if ($error) {
                header("Location: ../edit_profile.php?error=$error&id=" . $id);

            // Erfolgreiche änderung des Benutzer-Passwortes, Weiterleitung mit Erfolgsmeldung
            } else {
                $sm = "Password successfully updated";
                header("Location: ../edit_profile.php?success=$sm&id=" . $id);
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
        header("Location: ../edit_profile.php?error=$em&id=$id");
        exit();
    }

// Fehlerbehandlung für nicht angemeldete Benutzer + Weiterleitung und Fehlermeldung 
} else {
    $em = "First login";
    header("Location: ../login.php?error=$em");
    exit();
}

?>