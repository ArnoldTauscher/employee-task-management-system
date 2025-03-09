<?php

/***** Funktionen Admin *****/

/**
 * Aufgabe erstellen
 * 
 * @param PDO $connection Die Datenbankverbindung
 * @param array $data Ein Array mit den Aufgabendaten (Titel, Beschreibung, Fälligkeitsdatum, zugewiesener Benutzer)
 */
function insert_task($connection, $data) {
    $sql = "INSERT INTO tasks (title, description, due_date, assigned_to) VALUES(?,?,?,?)";
    $stmt = $connection-> prepare($sql);
    $stmt->execute($data);
}

/**
 * Alle Aufgaben abrufen
 * 
 * @param PDO $connection Die Datenbankverbindung
 * @return array|int Ein Array mit allen Aufgaben oder 0, wenn keine Aufgaben gefunden wurden
 */
function get_all_tasks($connection) {
    $sql = "SELECT * FROM tasks ORDER BY id DESC";
    $stmt = $connection-> prepare($sql);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $tasks = $stmt->fetchAll();
    } else {
        $tasks = 0;
    }

    return $tasks;
}

/**
 * Eine bestimmte Aufgabe abrufen
 * 
 * @param PDO $connection Die Datenbankverbindung
 * @param int $id Die Aufgaben-ID
 * @return array|int Ein Array mit den Aufgabendaten oder 0, wenn keine Aufgabe gefunden wurde
 */
function get_task_by_id($connection, $id) {
    $sql = "SELECT * FROM tasks WHERE id=?";
    $stmt = $connection-> prepare($sql);
    $stmt->execute([$id]);

    if ($stmt->rowCount() > 0) {
        $task = $stmt->fetch();
    } else {
        $task = 0;
    }

    return $task;
}

/**
 * Aufgabe ändern
 * 
 * @param PDO $connection Die Datenbankverbindung
 * @param array $data Ein Array mit den aktualisierten Aufgabendaten (Titel, Beschreibung, Fälligkeitsdatum, zugewiesener Benutzer, Status, Aufgaben-ID)
 */
function update_task($connection, $data) {
    $sql = "UPDATE tasks SET title=?, description=?, due_date=?, assigned_to=?, status=? WHERE id=?";
    $stmt = $connection-> prepare($sql);
    $stmt->execute($data);
}

/**
 * Aufgabe löschen
 * 
 * @param PDO $connection Die Datenbankverbindung
 * @param array $data Ein Array mit der zu löschenden Aufgaben-ID
 */
function delete_task($connection, $data) {
    $sql = "DELETE FROM tasks WHERE id=?";
    $stmt = $connection-> prepare($sql);
    $stmt->execute($data);
}
/* Funktionen Admin */

/***** Funktionen Employee *****/

/**
 * Alle Aufgaben des Benutzers abrufen
 * 
 * @param PDO $connection Die Datenbankverbindung
 * @param int $id Die Benutzer-ID
 * @return array|int Ein Array mit allen Aufgaben des Benutzers oder 0, wenn keine Aufgaben gefunden wurden
 */
function get_all_tasks_by_user_id($connection, $id) {
    $sql = "SELECT * FROM tasks WHERE assigned_to=?";
    $stmt = $connection-> prepare($sql);
    $stmt->execute([$id]);

    if ($stmt->rowCount() > 0) {
        $tasks = $stmt->fetchAll();
    } else {
        $tasks = 0;
    }

    return $tasks;
}

/**
 * Den Status einer Aufgabe ändern
 * 
 * @param PDO $connection Die Datenbankverbindung
 * @param array $data Ein Array mit dem neuen Status und der Aufgaben-ID
 */
function update_task_status($connection, $data) {
    $sql = "UPDATE tasks SET status=? WHERE id=?";
    $stmt = $connection-> prepare($sql);
    $stmt->execute($data);
}
/* Funktionen Employee */

/***** Aufgaben zählen *****/

/**
 * Gesamtanzahl der Aufgaben zählen
 * 
 * @param PDO $connection Die Datenbankverbindung
 * @return int Die Gesamtanzahl der Aufgaben
 */
function count_tasks($connection) {
    $sql = "SELECT id FROM tasks";
    $stmt = $connection-> prepare($sql);
    $stmt->execute();

    return $stmt->rowCount();
}

/***** Aufgaben filtern - Admin *****/

/**
 * Alle Aufgaben abrufen, die heute fällig sind
 * 
 * @param PDO $connection Die Datenbankverbindung
 * @return array|int Ein Array mit allen heute fälligen Aufgaben oder 0, wenn keine Aufgaben gefunden wurden
 */
function get_all_tasks_due_today($connection) {
    $sql = "SELECT * FROM tasks WHERE due_date = CURDATE() ORDER BY id DESC";
    $stmt = $connection-> prepare($sql);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $tasks = $stmt->fetchAll();
    } else {
        $tasks = 0;
    }

    return $tasks;
}

/**
 * Alle überfälligen Aufgaben abrufen
 * 
 * @param PDO $connection Die Datenbankverbindung
 * @return array|int Ein Array mit allen überfälligen Aufgaben oder 0, wenn keine Aufgaben gefunden wurden
 */
function get_all_tasks_overdue($connection) {
    $sql = "SELECT * FROM tasks WHERE due_date < CURDATE() AND status != 'completed' AND due_date != '0000-00-00' AND due_date IS NOT NULL ORDER BY id DESC";
    $stmt = $connection-> prepare($sql);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $tasks = $stmt->fetchAll();
    } else {
        $tasks = 0;
    }

    return $tasks;
}

/**
 * Alle Aufgaben ohne Fälligkeitsdatum abrufen
 * 
 * @param PDO $connection Die Datenbankverbindung
 * @return array|int Ein Array mit allen Aufgaben ohne Fälligkeitsdatum oder 0, wenn keine Aufgaben gefunden wurden
 */
function get_all_tasks_no_deadline($connection) {
    $sql = "SELECT * FROM tasks WHERE due_date IS NULL OR due_date = '0000-00-00'";
    $stmt = $connection-> prepare($sql);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $tasks = $stmt->fetchAll();
    } else {
        $tasks = 0;
    }

    return $tasks;
}

/**
 * Alle ausstehenden Aufgaben abrufen
 * 
 * @param PDO $connection Die Datenbankverbindung
 * @return array|int Ein Array mit allen ausstehenden Aufgaben oder 0, wenn keine Aufgaben gefunden wurden
 */
function get_all_tasks_pending($connection) {
    $sql = "SELECT * FROM tasks WHERE status='pending'";
    $stmt = $connection-> prepare($sql);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $tasks = $stmt->fetchAll();
    } else {
        $tasks = 0;
    }

    return $tasks;
}

/**
 * Alle Aufgaben in Bearbeitung abrufen
 * 
 * @param PDO $connection Die Datenbankverbindung
 * @return array|int Ein Array mit allen Aufgaben in Bearbeitung oder 0, wenn keine Aufgaben gefunden wurden
 */
function get_all_tasks_in_progress($connection) {
    $sql = "SELECT * FROM tasks WHERE status='in progress'";
    $stmt = $connection-> prepare($sql);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $tasks = $stmt->fetchAll();
    } else {
        $tasks = 0;
    }

    return $tasks;
}

/**
 * Alle abgeschlossenen Aufgaben abrufen
 * 
 * @param PDO $connection Die Datenbankverbindung
 * @return array|int Ein Array mit allen abgeschlossenen Aufgaben oder 0, wenn keine Aufgaben gefunden wurden
 */
function get_all_tasks_completed($connection) {
    $sql = "SELECT * FROM tasks WHERE status='completed'";
    $stmt = $connection-> prepare($sql);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $tasks = $stmt->fetchAll();
    } else {
        $tasks = 0;
    }

    return $tasks;
}
/* Aufgaben filtern - Admin */

/***** Aufgaben filtern Admin / Dashboard für Admin *****/

/**
 * Anzahl der heute fälligen Aufgaben zählen
 * 
 * @param PDO $connection Die Datenbankverbindung
 * @return int Die Anzahl der heute fälligen Aufgaben
 */
function count_tasks_due_today($connection) {
    $sql = "SELECT id FROM tasks WHERE due_date = CURDATE() AND status != 'completed'";
    $stmt = $connection-> prepare($sql);
    $stmt->execute();

    return $stmt->rowCount();
}

/**
 * Anzahl der überfälligen Aufgaben zählen
 * 
 * @param PDO $connection Die Datenbankverbindung
 * @return int Die Anzahl der überfälligen Aufgaben
 */
function count_tasks_overdue($connection) {
    $sql = "SELECT id FROM tasks WHERE due_date < CURDATE() AND status != 'completed' AND due_date != '0000-00-00'";
    
    $stmt = $connection-> prepare($sql);
    $stmt->execute();

    return $stmt->rowCount();
}

/**
 * Anzahl der Aufgaben ohne Fälligkeitsdatum zählen
 * 
 * @param PDO $connection Die Datenbankverbindung
 * @return int Die Anzahl der Aufgaben ohne Fälligkeitsdatum
 */
function count_tasks_no_deadline($connection) {
    $sql = "SELECT id FROM tasks WHERE due_date IS NULL OR due_date = '0000-00-00' AND status != 'completed'";
    $stmt = $connection-> prepare($sql);
    $stmt->execute();

    return $stmt->rowCount();
}

/**
 * Anzahl der ausstehenden Aufgaben zählen
 * 
 * @param PDO $connection Die Datenbankverbindung
 * @return int Die Anzahl der ausstehenden Aufgaben
 */
function count_pending_tasks($connection) {
    $sql = "SELECT * FROM tasks WHERE status='pending'";
    $stmt = $connection-> prepare($sql);
    $stmt->execute();

    return $stmt->rowCount();
}

/**
 * Anzahl der Aufgaben in Bearbeitung zählen
 * 
 * @param PDO $connection Die Datenbankverbindung
 * @return int Die Anzahl der Aufgaben in Bearbeitung
 */
function count_in_progress_tasks($connection) {
    $sql = "SELECT * FROM tasks WHERE status='in progress'";
    $stmt = $connection-> prepare($sql);
    $stmt->execute();

    return $stmt->rowCount();
}

/**
 * Anzahl der abgeschlossenen Aufgaben zählen
 * 
 * @param PDO $connection Die Datenbankverbindung
 * @return int Die Anzahl der abgeschlossenen Aufgaben
 */
function count_completed_tasks($connection) {
    $sql = "SELECT * FROM tasks WHERE status='completed'";
    $stmt = $connection-> prepare($sql);
    $stmt->execute();

    return $stmt->rowCount();
}
/* Aufgaben filtern - Admin / Dashboard für Admin */

/***** Aufgaben filtern - Employee *****/

/**
 * Alle heute fälligen Aufgaben eines Benutzers abrufen
 * 
 * @param PDO $connection Die Datenbankverbindung
 * @param int $id Die Benutzer-ID
 * @return array|int Ein Array mit allen heute fälligen Aufgaben des Benutzers oder 0, wenn keine gefunden wurden
 */
function get_all_tasks_due_today_by_user_id($connection, $id) {
    $sql = "SELECT * FROM tasks WHERE due_date = CURDATE() AND status != 'completed' AND assigned_to=?";

    $stmt = $connection-> prepare($sql);
    $stmt->execute([$id]);

    if ($stmt->rowCount() > 0) {
        $tasks = $stmt->fetchAll();
    } else {
        $tasks = 0;
    }

    return $tasks;
}

/**
 * Alle überfälligen Aufgaben eines Benutzers abrufen
 * 
 * @param PDO $connection Die Datenbankverbindung
 * @param int $id Die Benutzer-ID
 * @return array|int Ein Array mit allen überfälligen Aufgaben des Benutzers oder 0, wenn keine gefunden wurden
 */
function get_all_tasks_overdue_by_user_id($connection, $id) {
    $sql = "SELECT * FROM tasks WHERE due_date < CURDATE() AND status != 'completed' AND due_date != '0000-00-00' AND due_date IS NOT NULL AND assigned_to=? ORDER BY id DESC";

    $stmt = $connection-> prepare($sql);
    $stmt->execute([$id]);

    if ($stmt->rowCount() > 0) {
        $tasks = $stmt->fetchAll();
    } else {
        $tasks = 0;
    }

    return $tasks;
}

/**
 * Alle Aufgaben ohne Fälligkeitsdatum eines Benutzers abrufen
 * 
 * @param PDO $connection Die Datenbankverbindung
 * @param int $id Die Benutzer-ID
 * @return array|int Ein Array mit allen Aufgaben ohne Fälligkeitsdatum des Benutzers oder 0, wenn keine gefunden wurden
 */
function get_all_tasks_no_deadline_by_user_id($connection, $id) {
    $sql = "SELECT * FROM tasks WHERE due_date IS NULL OR due_date = '0000-00-00' AND assigned_to=?";

    $stmt = $connection-> prepare($sql);
    $stmt->execute([$id]);

    if ($stmt->rowCount() > 0) {
        $tasks = $stmt->fetchAll();
    } else {
        $tasks = 0;
    }

    return $tasks;
}

/**
 * Alle ausstehenden Aufgaben eines Benutzers abrufen
 * 
 * @param PDO $connection Die Datenbankverbindung
 * @param int $id Die Benutzer-ID
 * @return array|int Ein Array mit allen ausstehenden Aufgaben des Benutzers oder 0, wenn keine gefunden wurden
 */
function get_all_tasks_pending_by_user_id($connection, $id) {
    $sql = "SELECT * FROM tasks WHERE status='pending' AND assigned_to=?";
    $stmt = $connection-> prepare($sql);
    $stmt->execute([$id]);

    if ($stmt->rowCount() > 0) {
        $tasks = $stmt->fetchAll();
    } else {
        $tasks = 0;
    }

    return $tasks;
}

/**
 * Alle Aufgaben in Bearbeitung eines Benutzers abrufen
 * 
 * @param PDO $connection Die Datenbankverbindung
 * @param int $id Die Benutzer-ID
 * @return array|int Ein Array mit allen Aufgaben in Bearbeitung des Benutzers oder 0, wenn keine gefunden wurden
 */
function get_all_tasks_in_progress_by_user_id($connection, $id) {
    $sql = "SELECT * FROM tasks WHERE status='in progress' AND assigned_to=?";
    $stmt = $connection-> prepare($sql);
    $stmt->execute([$id]);

    if ($stmt->rowCount() > 0) {
        $tasks = $stmt->fetchAll();
    } else {
        $tasks = 0;
    }

    return $tasks;
}

/**
 * Alle abgeschlossenen Aufgaben eines Benutzers abrufen
 * 
 * @param PDO $connection Die Datenbankverbindung
 * @param int $id Die Benutzer-ID
 * @return array|int Ein Array mit allen abgeschlossenen Aufgaben des Benutzers oder 0, wenn keine gefunden wurden
 */
function get_all_tasks_completed_by_user_id($connection, $id) {
    $sql = "SELECT * FROM tasks WHERE status='completed' AND assigned_to=?";
    $stmt = $connection-> prepare($sql);
    $stmt->execute([$id]);

    if ($stmt->rowCount() > 0) {
        $tasks = $stmt->fetchAll();
    } else {
        $tasks = 0;
    }

    return $tasks;
}
/* Aufgaben filtern - Employee */

/***** Aufgaben filtern - Employee / Dashboard für Employee *****/

/**
 * Anzahl der Aufgaben eines Benutzers zählen
 * 
 * @param PDO $connection Die Datenbankverbindung
 * @param int $id Die Benutzer-ID
 * @return int Die Anzahl der Aufgaben des Benutzers
 */
function count_tasks_by_user_id($connection, $id) {
    $sql = "SELECT id FROM tasks WHERE assigned_to=?";
    $stmt = $connection-> prepare($sql);
    $stmt->execute([$id]);

    return $stmt->rowCount();
}

/**
 * Anzahl der ausstehenden Aufgaben eines Benutzers zählen
 * 
 * @param PDO $connection Die Datenbankverbindung
 * @param int $id Die Benutzer-ID
 * @return int Die Anzahl der ausstehenden Aufgaben des Benutzers
 */
function count_pending_tasks_by_user_id($connection, $id) {
    $sql = "SELECT * FROM tasks WHERE status='pending' AND assigned_to=?";
    $stmt = $connection-> prepare($sql);
    $stmt->execute([$id]);

    return $stmt->rowCount();
}

/**
 * Anzahl der Aufgaben in Bearbeitung eines Benutzers zählen
 * 
 * @param PDO $connection Die Datenbankverbindung
 * @param int $id Die Benutzer-ID
 * @return int Die Anzahl der Aufgaben in Bearbeitung des Benutzers
 */
function count_in_progress_tasks_by_user_id($connection, $id) {
    $sql = "SELECT * FROM tasks WHERE status='in progress' AND assigned_to=?";
    $stmt = $connection-> prepare($sql);
    $stmt->execute([$id]);

    return $stmt->rowCount();
}

/**
 * Anzahl der abgeschlossenen Aufgaben eines Benutzers zählen
 * 
 * @param PDO $connection Die Datenbankverbindung
 * @param int $id Die Benutzer-ID
 * @return int Die Anzahl der abgeschlossenen Aufgaben des Benutzers
 */
function count_completed_tasks_by_user_id($connection, $id) {
    $sql = "SELECT * FROM tasks WHERE status='completed' AND assigned_to=?";
    $stmt = $connection-> prepare($sql);
    $stmt->execute([$id]);

    return $stmt->rowCount();
}

/**
 * Anzahl der heute fälligen Aufgaben eines Benutzers zählen
 * 
 * @param PDO $connection Die Datenbankverbindung
 * @param int $id Die Benutzer-ID
 * @return int Die Anzahl der heute fälligen Aufgaben des Benutzers
 */
function count_tasks_due_today_by_user_id($connection, $id) {
    $sql = "SELECT id FROM tasks WHERE due_date = CURDATE() AND status != 'completed' AND assigned_to=?";
    $stmt = $connection-> prepare($sql);
    $stmt->execute([$id]);

    return $stmt->rowCount();
}

/**
 * Anzahl der überfälligen Aufgaben eines Benutzers zählen
 * 
 * @param PDO $connection Die Datenbankverbindung
 * @param int $id Die Benutzer-ID
 * @return int Die Anzahl der überfälligen Aufgaben des Benutzers
 */
function count_tasks_overdue_by_user_id($connection, $id) {
	$sql = "SELECT id FROM tasks WHERE due_date < CURDATE() AND status != 'completed' AND assigned_to=? AND due_date != '0000-00-00'";
	$stmt = $connection->prepare($sql);
	$stmt->execute([$id]);

	return $stmt->rowCount();
}

/**
 * Anzahl der Aufgaben ohne Fälligkeitsdatum eines Benutzers zählen
 * 
 * @param PDO $connection Die Datenbankverbindung
 * @param int $id Die Benutzer-ID
 * @return int Die Anzahl der Aufgaben ohne Fälligkeitsdatum des Benutzers
 */
function count_tasks_no_deadline_by_user_id($connection, $id) {
    $sql = "SELECT id FROM tasks WHERE due_date IS NULL OR due_date = '0000-00-00' AND status != 'completed' AND assigned_to=?";
    $stmt = $connection-> prepare($sql);
    $stmt->execute([$id]);

    return $stmt->rowCount();
}
/* Aufgaben filtern - Employee / Dashboard für Employee */

?>