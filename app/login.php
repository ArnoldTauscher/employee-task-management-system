<?php

session_start();

// Überprüfung der POST-Daten
if (isset($_POST["user_name"]) && isset($_POST["password"])) {

    // Datenbankverbindung und Eingabevalidierung
    include "../DB_connection.php";
    include "./Helpers/validation_functions.php";

    // Verarbeitung der Eingabedaten
    $user_name = validate_input($_POST["user_name"]);
    $password = validate_input($_POST["password"]);

    // Überprüfung auf leere Eingaben
    if (empty($user_name)) {
        $em = "User name is required";
        header("Location: ../login.php?error=$em");
        exit();

    } elseif (empty($password)) {
        $em = "Password is required";
        header("Location: ../login.php?error=$em");
        exit();
    
    } else {

        // Datenbankabfrage und Überprüfung der Anmeldedaten.
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $connection->prepare($sql);
        $stmt->execute([$user_name]);

        // Wenn ein falscher Username eingegeben wird, können die Daten nicht abgerufen werden.
        if ($stmt->rowCount() == 1) {
            $user = $stmt->fetch();
            $usernameDb = $user["username"];
            $passwordDb = $user["password"];
            $role = $user["role"];
            $id = $user["id"];

            // Überprüfung des Passworts.
            if (password_verify($password, $passwordDb)) {
                
                // Sitzung starten und weiterleiten
                start_session_and_redirect($role, $id, $user_name);

            } else {
                // Fehlerbehandlung bei ungültigem Passwort + Weiterleitung und Fehlermeldung
                $em = "Incorrect username or password";
                header("Location: ../login.php?error=$em");
                exit();
            }

        } else {
            // Fehlerbehandlung bei ungültigem Benutzername + Weiterleitung und Fehlermeldung
            $em = "Incorrect username or password";
            header("Location: ../login.php?error=$em");
            exit();
        }
    }

// Fehlerbehandlung für ungültige POST-Daten oder fehlende Admin-Rechte + Weiterleitung und Fehlermeldung
} else {
    $em = "Invalid input or insufficient permissions";
    header("Location: ../login.php?error=$em");
    exit();
}

// Funktion zum Starten der Sitzung und Weiterleitung
function start_session_and_redirect($role, $id, $username) {
    $_SESSION["role"] = $role;
    $_SESSION["id"] = $id;
    $_SESSION["username"] = $username;
    header("Location: ../index.php");
    exit();
} ?>