<?php

/**
 * Filtert und zählt Aufgaben basierend auf GET-Parametern und optionalem Benutzer-ID
 *
 * @param PDO $connection Datenbankverbindung
 * @param int|null $user_id Optional: Benutzer-ID für personalisierte Aufgabenfilterung
 * @return array Array mit Filterbeschreibung, gefilterten Aufgaben und Aufgabenanzahl
 */
function filter_tasks($connection, $user_id = null) {
    $text = "All Tasks";
    $tasks = [];
    $num_task = 0;

    // Filtern nach Fälligkeitsdatum: Heute fällig
    if (isset($_GET["due_date"]) && $_GET["due_date"] == "Due Today") {
        $text = "Due Today";
        $tasks = $user_id ? get_all_tasks_due_today_by_user_id($connection, $user_id) : get_all_tasks_due_today($connection);
        $num_task = $user_id ? count_tasks_due_today_by_user_id($connection, $user_id) : count_tasks_due_today($connection);

    // Filtern nach Fälligkeitsdatum: Überfällig
    } elseif (isset($_GET["due_date"]) && $_GET["due_date"] == "Overdue") {
        $text = "Overdue";
        $tasks = $user_id ? get_all_tasks_overdue_by_user_id($connection, $user_id) : get_all_tasks_overdue($connection);
        $num_task = $user_id ? count_tasks_overdue_by_user_id($connection, $user_id) : count_tasks_overdue($connection);

    // Filtern nach Fälligkeitsdatum: Kein Fälligkeitsdatum
    } elseif (isset($_GET["due_date"]) && $_GET["due_date"] == "No Deadline") {
        $text = "No Deadline";
        $tasks = $user_id ? get_all_tasks_no_deadline_by_user_id($connection, $user_id) : get_all_tasks_no_deadline($connection);
        $num_task = $user_id ? count_tasks_no_deadline_by_user_id($connection, $user_id) : count_tasks_no_deadline($connection);

    // Filtern nach Status: Ausstehend
    } elseif (isset($_GET["status"]) && $_GET["status"] == "Pending") {
        $text = "Pending";
        $tasks = $user_id ? get_all_tasks_pending_by_user_id($connection, $user_id) : get_all_tasks_pending($connection);
        $num_task = $user_id ? count_pending_tasks_by_user_id($connection, $user_id) : count_pending_tasks($connection);

    // Filtern nach Status: In Bearbeitung
    } elseif (isset($_GET["status"]) && $_GET["status"] == "In progress") {
        $text = "In progress";
        $tasks = $user_id ? get_all_tasks_in_progress_by_user_id($connection, $user_id) : get_all_tasks_in_progress($connection);
        $num_task = $user_id ? count_in_progress_tasks_by_user_id($connection, $user_id) : count_in_progress_tasks($connection);

    // Filtern nach Status: Abgeschlossen
    } elseif (isset($_GET["status"]) && $_GET["status"] == "Completed") {
        $text = "Completed";
        $tasks = $user_id ? get_all_tasks_completed_by_user_id($connection, $user_id) : get_all_tasks_completed($connection);
        $num_task = $user_id ? count_completed_tasks_by_user_id($connection, $user_id) : count_completed_tasks($connection);

    // Keine Filter: Alle Aufgaben
    } else {
        $tasks = $user_id ? get_all_tasks_by_user_id($connection, $user_id) : get_all_tasks($connection);
        $num_task = $user_id ? count_tasks_by_user_id($connection, $user_id) : count_tasks($connection);
    }

    // Rückgabe der Filterergebnisse
    return [$text, $tasks, $num_task];
} ?>
