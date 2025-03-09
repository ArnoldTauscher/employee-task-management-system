<?php

/**
 * Validiert und verarbeitet Aufgabendaten
 *
 * @param PDO $connection Datenbankverbindung
 * @param array $data Eingabedaten der Aufgabe
 * @param bool $is_update Optional: Gibt an, ob es sich um eine Aktualisierung handelt
 * @return string|null Fehlermeldung oder null bei erfolgreicher Verarbeitung
 */
function validate_and_process_task($connection, $data, $is_update = false) {

    // Validierung und Bereinigung der Eingabedaten
    $title = validate_input($_POST["title"]);
    $description = validate_input($_POST["description"]);
    $due_date = validate_input($_POST["due_date"]);
    $assigned_to = validate_input($_POST["assigned_to"]);
    $status = validate_input($_POST["status"]);

    // Überprüfung auf leere Pflichtfelder
    if (empty($title)) {
        return "Title is required";
    } elseif (empty($description)) {
        return "Description is required";
    } elseif (empty($assigned_to)) {
        return "Choose an employee";
    } else {
        // Einbinden der erforderlichen Model-Dateien
        include "../app/Model/Task.php";
        include "../app/Model/Notification.php";

        // Setze das Fälligkeitsdatum auf NULL, wenn es leer ist
        if (empty($due_date)) $due_date = NULL;

        if ($is_update) {
            // Aktualisierung einer bestehenden Aufgabe
            $id = validate_input($data["id"]);
            $data = array($title, $description, $due_date, $assigned_to, $status, $id);
            update_task($connection, $data, $id);
        } else {
            // Erstellung einer neuen Aufgabe
            $data = array($title, $description, $due_date, $assigned_to);
            insert_task($connection, $data);

            // Benachrichtigung für den zugewiesenen Mitarbeiter erstellen
            $notif_data = array("'$title' has been assigned to you. Please review and start working on it", $assigned_to, 'New Task Assigned');
            insert_notification($connection, $notif_data);
        }
        return null; // Erfolgreiche Verarbeitung
    }
}

/**
 * Validiert und aktualisiert den Status einer Aufgabe
 *
 * @param PDO $connection Datenbankverbindung
 * @param array $data Eingabedaten für die Statusaktualisierung
 * @return string|null Fehlermeldung oder null bei erfolgreicher Aktualisierung
 */
function validate_and_process_task_status($connection, $data) {

    // Validierung und Bereinigung der Eingabedaten
    $id = validate_input($_POST["id"]);
    $status = validate_input($_POST["status"]);

    // Überprüfung auf leeren Status
    if (empty($status)) {
        return "Status is required";
    } else {
        // Einbinden der Task-Model-Datei
        include "Model/Task.php";
        $data = array($status, $id);
        update_task_status($connection, $data);
        
        return null; // Erfolgreiche Aktualisierung
    }
}

?>

