<?php

/**
 * Bereinigt und validiert Benutzereingaben
 *
 * Diese Funktion führt mehrere Schritte zur Sicherung von Benutzereingaben durch:
 * 1. Entfernt Leerzeichen am Anfang und Ende des Strings
 * 2. Entfernt Backslashes, die zur Escaping von Zeichen verwendet wurden
 * 3. Konvertiert spezielle Zeichen in HTML-Entities, um XSS-Angriffe zu verhindern
 *
 * @param string $data Die zu bereinigende Benutzereingabe
 * @return string Die bereinigte und gesicherte Eingabe
 */
function validate_input($data) {
    // Entfernt Leerzeichen am Anfang und Ende des Strings
    $data = trim($data);
    
    // Entfernt Backslashes, die zur Escaping von Zeichen verwendet wurden
    $data = stripslashes($data);
    
    // Konvertiert spezielle Zeichen in HTML-Entities
    // Dies verhindert die Ausführung von bösartigem Code (XSS-Schutz)
    $data = htmlspecialchars($data);
    
    return $data;
}