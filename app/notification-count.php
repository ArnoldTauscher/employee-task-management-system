<?php

// Startet die PHP-Session
session_start();

// Überprüft, ob der Benutzer angemeldet ist und eine Rolle sowie ID hat
if (isset($_SESSION["role"]) && isset($_SESSION["id"])) {

    // Einbinden der Datenbankverbindung und des Notification-Models
    include "../DB_connection.php";
    include "Model/Notification.php";

    // Abrufen die Anzahl der Benachrichtigungen des Benutzers aus der Datenbank
    $count_notifications = count_notifications_by_user_id($connection, $_SESSION["id"]);

    // Anzeige der Anzahl der Benachrichtigungen, wenn vorhanden
    if ($count_notifications) {
        echo "&nbsp;". $count_notifications. "&nbsp;";
    }

} else {
    // Meldung für nicht angemeldete Benutzer
    echo "Please log in to view notifications.";
} ?>