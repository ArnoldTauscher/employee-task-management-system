<?php

// Startet die PHP-Session
session_start();

// Überprüft, ob der Benutzer angemeldet ist und eine Rolle sowie ID hat
if (isset($_SESSION["role"]) && isset($_SESSION["id"])) {

    // Einbinden der Datenbankverbindung und des Notification-Models
    include "../DB_connection.php";
    include "Model/Notification.php";

    // Abrufen aller Benachrichtigungen des Benutzers aus der Datenbank
    $notifications = get_all_notifications_by_user_id($connection, $_SESSION["id"]);

    // Überprüfung, ob Benachrichtigungen vorhanden sind
    if ($notifications == 0) {

        // Anzeige, wenn keine Benachrichtigungen vorhanden sind
        ?>
        <li>
            <a href="#">
                You have 0 notifications
            </a>
        </li>
    <?php } else {
    
    // Schleife durch alle Benachrichtigungen des Benutzers
    foreach ($notifications as $notification) {
?>

    <li>
        <a href="app/notification-read.php?notification_id=<?=$notification["id"]?>">

            <?php 
            // Hervorhebung ungelesener Benachrichtigungen
            if ($notification["is_read"] == 0) {
                echo "<mark>".$notification['type']."</mark>: ";
            } else echo $notification['type'].": " ?>
             <?=$notification["message"]?>
            &nbsp;&nbsp;<small><?=$notification["date"]?></small>
        </a>
    </li>

<?php
}}
} else {
    // Meldung für nicht angemeldete Benutzer
    echo "Please log in to view notifications.";
} ?>