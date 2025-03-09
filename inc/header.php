<?php
// Generiere einen sicheren Token für die Session, falls noch keiner existiert
if (empty($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(32));
}
?>

<!-- Header-Bereich der Seite -->
<header class="header">
    <h2 class="u-name">Task <b>Pro</b>
        <label for="checkbox">
            <!-- Icon für die Navigation (Hamburger-Menü) -->
            <i id="navbtn" class="fa fa-bars" aria-hidden="true"></i>
        </label>
    </h2>
    <!-- Benachrichtigungssymbol mit Zähler -->
    <span class="notification" id="notificationBtn">
        <i class="fa fa-bell" aria-hidden="true"></i>
        <span id="notificationNum"></span>
    </span>
</header>

<!-- Benachrichtigungsleiste -->
<div class="notification-bar" id="notificationBar">
    <ul class="notifications" id="notifications">
        <!-- Hier werden die Benachrichtigungen dynamisch eingefügt -->
    </ul>
</div>

<script type="text/javascript">
    // Variable zum Verfolgen des Zustands der Benachrichtigungsleiste
    let openNotification = false;

    // Funktion zum Öffnen/Schließen der Benachrichtigungsleiste
    const notification = () => {
        let notificationBar = document.querySelector("#notificationBar");
        if (openNotification){
            notificationBar.classList.remove("open-notification");
            openNotification = false;
        } else {
            notificationBar.classList.add("open-notification");
            openNotification = true;
        }
    };

    // Event-Listener für das Benachrichtigungssymbol
    const notificationBtn = document.querySelector("#notificationBtn");
    notificationBtn.addEventListener("click", notification);
</script>

<!-- Einbindung von jQuery -->
<script
    src="https://code.jquery.com/jquery-2.2.4.min.js"
    integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
    crossorigin="anonymous">
</script>

<script type="text/javascript">
    $(document).ready(function(){
        // Lade die Anzahl der Benachrichtigungen
        $("#notificationNum").load("app/notification-count.php");
        // Lade die Benachrichtigungen
        $("#notifications").load("app/notification.php");
    });
</script>
