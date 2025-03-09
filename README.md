# Employee Task Management System

    Ein webbasiertes System zur Verwaltung von Aufgaben und Mitarbeitern.

## Inhaltsverzeichnis

- [Überblick](#überblick)
- [Funktionen](#funktionen)
- [Ordnerstruktur](#ordnerstruktur)
- [Lizenz](#lizenz)

## Überblick

    Das Employee Task Management System ist eine Anwendung zur Verwaltung von Aufgaben und Mitarbeitern. Es ermöglicht Administratoren, Aufgaben zu erstellen, zu verwalten und Mitarbeitern zuzuweisen, während Mitarbeiter den Status ihrer Aufgaben aktualisieren können. Das System bietet eine einfache Benutzeroberfläche und optimiert die Aufgabenverwaltung in einem Team.

## Funktionen

Mit Administratorrechten:

    - Benutzerprofile erstellen, bearbeiten und löschen
    - Aufgaben erstellen, bearbeiten und löschen
    - Aufgaben Mitarbeitern zuweisen
    - Fälligkeitsdatum für Aufgaben festlegen
    - Automatische Benachrichtigung bei der Erstellung einer Aufgabe
    - Aufgaben filtern (z. B. nach Status oder Fälligkeitsdatum)

Mit Mitarbeiterrechten:

    - Passwort ändern
    - Status einer Aufgabe aktualisieren (z. B. "In Bearbeitung", "Erledigt")
    - Aufgaben filtern


## Ordnerstruktur

    .
    ├── add-user.php
    ├── create_task.php
    ├── DB_connection.php
    ├── DB.sql
    ├── delete-task.php
    ├── delete-user.php
    ├── edit_profile.php
    ├── edit-task-employee.php
    ├── edit-task.php
    ├── edit-user.php
    ├── index.php
    ├── login.php
    ├── logout.php
    ├── my_task.php
    ├── notifications.php
    ├── profile.php
    ├── tasks.php
    ├── user.php
    ├── app/
    │   ├── add-task.php
    │   ├── add-user.php
    │   ├── login.php
    │   ├── notification-count.php
    │   ├── notification-read.php
    │   ├── notification.php
    │   ├── update-profile.php
    │   ├── update-task-employee.php
    │   ├── update-task.php
    │   ├── update-user.php
    │   ├── Helpers/
    │   │   ├── task_filters.php            # Funktionen zur Filterung von Aufgaben 
    │   │   ├── task_functions.php          # Logik für Aufgabenoperationen 
    │   │   ├── user_functions.php          # Logik für Benutzeroperationen 
    │   │   └── validation_functions.php    # Validierungsfunktionen 
    │   ├── Model/
    │   │   ├── Notification.php            # Modell für Benachrichtigungen 
    │   │   ├── Task.php                    # Modell für Aufgaben 
    │   │   └── User.php                    # Modell für Benutzer 
    ├── css/
    │   └── style.css                       # Stile für die Benutzeroberfläche 
    ├── img/                                # Verzeichnis für Bilder 
    ├── inc/
    │   ├── header.php                      # Header-Komponente 
    │   ├── nav.php                         # Navigationsleiste 

## Lizenz

    Dieses Projekt ist unter der MIT-Lizenz lizenziert. Weitere Informationen findest du in der [LICENSE](LICENSE) Datei.