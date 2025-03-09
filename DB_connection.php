<?php
    // Datenbankverbindungsinformationen definieren
    $db_server = "localhost";
    $db_user = "root";
    $db_password = "";
    $db_name = "task_management_db";

    // Verbindungsaufbau mit PDO
    try {
        $connection = new PDO("mysql:host=$db_server;dbname=$db_name", $db_user, $db_password);

        // Fehlerbehandlungsmodus setzen
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Fehlerbehandlung
    } catch (PDOException $exception) {
        echo "Connection failed: " . $exception->getMessage();
        exit;
    }

?>