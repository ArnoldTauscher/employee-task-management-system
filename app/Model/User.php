<?php

/**
 * Alle Benutzer mit der Rolle "employee" abrufen
 * 
 * @param PDO $connection Die Datenbankverbindung
 * @return array|int Ein Array mit allen Benutzern oder 0, wenn keine Benutzer gefunden wurden
 */
function get_all_users($connection) {
    $sql = "SELECT * FROM users WHERE role =?";
    $stmt = $connection-> prepare($sql);
    $stmt->execute(["employee"]);

    if ($stmt->rowCount() > 0) {
        $users = $stmt->fetchAll();
    } else {
        $users = 0;
    }

    return $users;
}

/**
 * Einen bestimmten Benutzer anhand der ID abrufen
 * 
 * @param PDO $connection Die Datenbankverbindung
 * @param int $id Die Benutzer-ID
 * @return array|int Ein Array mit den Benutzerdaten oder 0, wenn kein Benutzer gefunden wurde
 */
function get_user_by_id($connection, $id) {
    $sql = "SELECT * FROM users WHERE id =?";
    $stmt = $connection-> prepare($sql);
    $stmt->execute([$id]);

    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch();
    } else {
        $user = 0;
    }

    return $user;
}

/**
 * Gesamtanzahl der Benutzer mit der Rolle "employee" zählen
 * 
 * @param PDO $connection Die Datenbankverbindung
 * @return int Die Anzahl der Benutzer mit der Rolle "employee"
 */
function count_users($connection) {
    $sql = "SELECT id FROM users where role = 'employee'";
    $stmt = $connection-> prepare($sql);
    $stmt->execute([]);

    return $stmt->rowCount();
}

/**
 * Einen neuen Benutzer erstellen
 * 
 * @param PDO $connection Die Datenbankverbindung
 * @param array $data Ein Array mit den Benutzerdaten (vollständiger Name, Benutzername, Passwort, Rolle)
 */
function insert_user($connection, $data) {
    $sql = "INSERT INTO users (full_name, username, password, role) VALUES(?,?,?,?)";
    $stmt = $connection-> prepare($sql);
    $stmt->execute($data);
}

/**
 * Einen bestehenden Benutzer aktualisieren (inkl. Passwort)
 * 
 * @param PDO $connection Die Datenbankverbindung
 * @param array $data Ein Array mit den aktualisierten Benutzerdaten (vollständiger Name, Benutzername, Passwort, Rolle, Benutzer-ID, aktuelle Rolle)
 */
function update_user($connection, $data) {
    $sql = "UPDATE users SET full_name=?, username=?, password=?, role=? WHERE id=? AND role=?";
    $stmt = $connection-> prepare($sql);
    $stmt->execute($data);
}

/**
 * Einen bestehenden Benutzer aktualisieren (ohne Passwortänderung)
 * 
 * @param PDO $connection Die Datenbankverbindung
 * @param array $data Ein Array mit den aktualisierten Benutzerdaten (vollständiger Name, Benutzername, Benutzer-ID, aktuelle Rolle)
 */
function update_user_without_password($connection, $data) {
    $sql = "UPDATE users SET full_name=?, username=? WHERE id=? AND role=?";
    $stmt = $connection-> prepare($sql);
    $stmt->execute($data);
}

/**
 * Einen Benutzer löschen
 * 
 * @param PDO $connection Die Datenbankverbindung
 * @param array $data Ein Array mit der zu löschenden Benutzer-ID und der aktuellen Rolle
 */
function delete_user($connection, $data) {
    $sql = "DELETE FROM users WHERE id=? AND role=?";
    $stmt = $connection-> prepare($sql);
    $stmt->execute($data);
}

/***** Funktion Employee *****/

/**
 * Das Passwort eines Benutzers aktualisieren (Profilaktualisierung)
 * 
 * @param PDO $connection Die Datenbankverbindung
 * @param array $data Ein Array mit dem neuen Passwort und der Benutzer-ID
 */
function update_profile($connection, $data) {
    $sql = "UPDATE users SET password=? WHERE id=?";
    $stmt = $connection-> prepare($sql);
    $stmt->execute($data);
}

?>