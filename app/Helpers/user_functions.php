<?php

/**
 * Validiert und verarbeitet Benutzerdaten für Erstellung oder Aktualisierung
 *
 * @param PDO $connection Datenbankverbindung
 * @param array $data Eingabedaten des Benutzers
 * @param string $token CSRF-Token zur Validierung
 * @param bool $is_update Optional: Gibt an, ob es sich um eine Aktualisierung handelt
 * @return string|null Fehlermeldung oder null bei erfolgreicher Verarbeitung
 */
function validate_and_process_user($connection, $data, $token, $is_update = false) {

    // Validierung des CSRF-Tokens zur Verhinderung von Cross-Site Request Forgery
    if (hash_equals($_SESSION['token'], $token)) {

        // Validierung und Bereinigung der Eingabedaten
        $full_name = validate_input($data["full_name"]);
        $user_name = validate_input($data["user_name"]);
        $password = validate_input($data["password"]);

        // Überprüfung auf leere Pflichtfelder
        if (empty($full_name)) {
            return "Full name is required";
        } elseif (empty($user_name)) {
            return "User name is required";
        } elseif ($is_update && empty($password)) {
            // Bei Aktualisierung ohne Passwortänderung
            include "../app/Model/User.php";
            $id = validate_input($data["id"]);
            $data = array($full_name, $user_name, $id, "employee");
            update_user_without_password($connection, $data);
            return null;
        } elseif (empty($password)) {
            return "Password is required";
        } else {
            // Benutzer erstellen oder aktualisieren
            include "../app/Model/User.php";
            if ($is_update) {
                $id = validate_input($data["id"]);
                $data = array($full_name, $user_name, $password, "employee", $id, "employee");
                update_user($connection, $data);
            } else {
                $data = array($full_name, $user_name, $password, "employee");
                insert_user($connection, $data);
            }
            return null;
        }
    } else {
        return "Invalid CSRF token";
    }
}

/**
 * Validiert und aktualisiert das Passwort eines Benutzers
 *
 * @param PDO $connection Datenbankverbindung
 * @param array $data Eingabedaten für die Passwortänderung
 * @param int $id Benutzer-ID
 * @param string $token CSRF-Token zur Validierung
 * @return string|null Fehlermeldung oder null bei erfolgreicher Aktualisierung
 */
function validate_and_process_user_password($connection, $data, $id, $token) {

    // Validierung des CSRF-Tokens zur Verhinderung von Cross-Site Request Forgery
    if (hash_equals($_SESSION['token'], $token)) {

        // Validierung und Bereinigung der Eingabedaten
        $password = validate_input($_POST["password"]);
        $new_password = validate_input($_POST["new_password"]);
        $confirm_password = validate_input($_POST["confirm_password"]);

        // Überprüfung auf leere Pflichtfelder
        if (empty($password)) {
            return "Old password is required";
        } elseif (empty($new_password)) {
            return "New password is required";
        } elseif (empty($confirm_password)) {
            return "Please confirm your new password";
        } else {
            // Überprüfung der Übereinstimmung von neuem Passwort und Bestätigung
            if ($new_password === $confirm_password) {
                include "../app/Model/User.php";
                $user = get_user_by_id($connection, $id);

                if ($user) {
                    // Überprüfung des alten Passworts und Aktualisierung bei Korrektheit
                    if (password_verify($password, $user["password"])) {
                        $password = password_hash($new_password, PASSWORD_DEFAULT);
                        $data = array($password, $id);
                        update_profile($connection, $data);
                        return null; // Erfolgreiche Aktualisierung
                    } else {
                        return "Incorrect password";
                    }
                } else {
                    return "Unknown error occurred";
                }
            } else {
                return "New password and confirm password do not match";
            }
        }
    } else {
        return "Invalid CSRF token";
    }
}

?>

