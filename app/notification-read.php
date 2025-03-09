<?php

// Startet die PHP-Session
session_start();

// Überprüft, ob der Benutzer angemeldet ist und eine Rolle sowie ID hat
if (isset($_SESSION["role"]) && isset($_SESSION["id"])) {

    // Einbinden der Datenbankverbindung und des Notification-Models
    include "../DB_connection.php";
    include "Model/Notification.php";

    // Überprüft, ob eine Benachrichtigungs-ID als GET-Parameter übergeben wurde
    if (isset($_GET["notification_id"])) {
        $notification_id = $_GET["notification_id"];
        
        // Markiert die spezifische Benachrichtigung als gelesen
        notification_make_read($connection, $_SESSION["id"], $notification_id);
        
        // Weiterleitung zur Benachrichtigungsübersicht
        header("Location: ../notifications.php");
        exit();

    } else {
        // Wenn keine Benachrichtigungs-ID übergeben wurde, Weiterleitung zur Startseite
        header("Location: index.php");
        exit();
    }

} else {
    // Wenn der Benutzer nicht angemeldet ist, Weiterleitung zur Login-Seite mit Fehlermeldung
    $em = "First login";
    header("Location: login.php?error=$em");
    exit();
} ?>