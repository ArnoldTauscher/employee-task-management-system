<?php

// Startet die PHP-Session
session_start();

// Überprüft, ob der Benutzer angemeldet ist, Admin-Rechte hat und ein gültiges CSRF-Token vorliegt
if (!empty($_GET['token']) &&
    isset($_SESSION["role"]) &&
    isset($_SESSION["id"]) &&
    $_SESSION["role"] == "admin") {

    // Validierung des CSRF-Tokens
    if (hash_equals($_SESSION['token'], $_GET['token'])) {
        
        // Einbinden der Datenbankverbindung und des User-Models
        include "DB_connection.php";
        include "app/Model/User.php";

        // Überprüfung, ob eine Benutzer-ID übergeben wurde
        if (!isset($_GET["id"])) {
            header("Location: user.php");
            exit();
        }

        $id = $_GET["id"];
        $user = get_user_by_id($connection, $id);
        // print_r($user['username']);

        // Überprüfung, ob der Benutzer existiert
        // ha nincs olyan user-id, vissza a user oldalra (a browser kereső sávjába olyan id-t írok, amit akarok)
        if ($user == 0) {
            header("Location: user.php");
            exit();
        }

        // Vorbereitung der Daten für das Löschen des Benutzers
        $data = array($id, "employee");
        // Ausführen der Löschoperation
        delete_user($connection, $data);

        // Erfolgsmeldung und Weiterleitung
        $sm = "Deleted Successfully";
        header("Location: user.php?success=$sm");
        exit();
        
    } else {
        // Fehler bei ungültigem CSRF-Token
        $em = "Invalid CSRF token";
        header("Location: ../user.php?error=$em");
        exit();
    }

} else {

    // Wenn der Benutzer nicht angemeldet ist, Weiterleitung zur Login-Seite
	$em = "First login";
	header("Location: login.php?error=$em");
	exit();

} ?>