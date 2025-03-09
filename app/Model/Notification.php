<?php

/**
 * Eine neue Benachrichtigung erstellen
 * 
 * @param PDO $connection Die Datenbankverbindung
 * @param array $data Ein Array mit den Benachrichtigungsdaten (Nachricht, Empfänger-ID, Typ)
 */
function insert_notification($connection, $data) {
    $sql = "INSERT INTO notifications (message, recipient, type) VALUES(?,?,?)";
    $stmt = $connection-> prepare($sql);
    $stmt->execute($data);
}

/**
 * Die letzten 5 Benachrichtigungen eines Benutzers abrufen
 * 
 * @param PDO $connection Die Datenbankverbindung
 * @param int $id Die Benutzer-ID
 * @return array|int Ein Array mit den letzten 5 Benachrichtigungen oder 0, wenn keine Benachrichtigungen gefunden wurden
 */
function get_all_notifications_by_user_id($connection, $id) {
    $sql = "SELECT * FROM notifications WHERE recipient =? LIMIT 5";
    $stmt = $connection-> prepare($sql);
    $stmt->execute([$id]);

    if ($stmt->rowCount() > 0) {
        $notifications = $stmt->fetchAll();
    } else {
        $notifications = 0;
    }

    return $notifications;
}

/**
 * Die Anzahl der ungelesenen Benachrichtigungen eines Benutzers zählen
 * 
 * @param PDO $connection Die Datenbankverbindung
 * @param int $id Die Benutzer-ID
 * @return int Die Anzahl der ungelesenen Benachrichtigungen
 */
function count_notifications_by_user_id($connection, $id){
	$sql = "SELECT id FROM notifications WHERE recipient=? AND is_read=0";
	$stmt = $connection->prepare($sql);
	$stmt->execute([$id]);

    return $stmt->rowCount();
}

/**
 * Eine Benachrichtigung als gelesen markieren
 * 
 * @param PDO $conn Die Datenbankverbindung
 * @param int $recipient_id Die Empfänger-ID
 * @param int $notification_id Die Benachrichtigungs-ID
 */
function notification_make_read($conn, $recipient_id, $notification_id){
	$sql = "UPDATE notifications SET is_read=1 WHERE id=? AND recipient=?";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$notification_id, $recipient_id]);
}

?>