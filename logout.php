<?php

// Starten der PHP-Session
session_start();

// Löschen aller Session-Variablen
session_unset();

// Zerstören der Session
session_destroy();

// Weiterleitung zur Login-Seite
header("Location: login.php");

// Beenden des Skripts
exit();

?>