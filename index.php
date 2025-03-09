<?php
// Starten der PHP-Session
session_start();

// Überprüfen, ob der Benutzer angemeldet ist
if (isset($_SESSION["role"]) && isset($_SESSION["id"])) {

    // Einbinden der Datenbankverbindung und der Model-Dateien
    include "DB_connection.php";
    include "app/Model/Task.php";
    include "app/Model/User.php";
    include "app/Model/Notification.php";

    // Unterscheidung zwischen Admin und Mitarbeiter
    if ($_SESSION["role"] == "admin") {
        // Abrufen verschiedener Statistiken für den Admin
        $todaydue_task = count_tasks_due_today($connection);
        $overdue_task = count_tasks_overdue($connection);
        $no_deadline_task = count_tasks_no_deadline($connection);
        $num_task = count_tasks($connection);
        $pending_tasks = count_pending_tasks($connection);
        $in_progress_tasks = count_in_progress_tasks($connection);
        $completed_tasks = count_completed_tasks($connection);
        $notification_count = count_notifications_by_user_id($connection, $_SESSION["id"]);
        $num_users = count_users($connection);
    } else {
        // Abrufen verschiedener Statistiken für den Mitarbeiter
        $num_task = count_tasks_by_user_id($connection, $_SESSION["id"]);
        $overdue_task = count_tasks_overdue_by_user_id($connection, $_SESSION["id"]);
        $todaydue_task = count_tasks_due_today_by_user_id($connection, $_SESSION["id"]);
        $no_deadline_task = count_tasks_no_deadline_by_user_id($connection, $_SESSION["id"]);
        $pending_tasks = count_pending_tasks_by_user_id($connection, $_SESSION["id"]);
        $in_progress_tasks = count_in_progress_tasks_by_user_id($connection, $_SESSION["id"]);
        $completed_tasks = count_completed_tasks_by_user_id($connection, $_SESSION["id"]);
        $notification_count = count_notifications_by_user_id($connection, $_SESSION["id"]);
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <input type="checkbox" id="checkbox">
    <?php include "inc/header.php" ?>
    <div class="body">
        <?php include "inc/nav.php" ?>
        <section class="section-1">
            <?php
            // Unterschiedliche Dashboard-Anzeige für Admin und Mitarbeiter
				if ($_SESSION["role"] == "admin") { ?>
					<!-- Admin Dashboard -->
					<div class="dashboard">
						<!-- Dashboard-Elemente für Admin -->
						<div class="dashboard-item">
							<a href="user.php">
								<i class="fa fa-users"></i>
								<span><?=$num_users?> Employee(s)</span>
							</a>
						</div>
						<div class="dashboard-item">
							<a href="tasks.php">
								<i class="fa fa-tasks"></i>
								<span><?=$num_task?> All Tasks</span>
							</a>
						</div>
						<div class="dashboard-item">
							<a href="tasks.php?due_date=Overdue">
								<i class="fa fa-window-close-o"></i>
								<span><?=$overdue_task?> Overdue</span>
							</a>
						</div>
						<div class="dashboard-item">
							<a href="tasks.php?due_date=No Deadline">
								<i class="fa fa-clock-o"></i>
								<span><?=$no_deadline_task?> No Deadline</span>
							</a>
						</div>
						<div class="dashboard-item">
							<a href="tasks.php?due_date=Due Today">
								<i class="fa fa-exclamation-triangle"></i>
								<span><?=$todaydue_task?> Due Today</span>
							</a>
						</div>
						<div class="dashboard-item">
							<a href="notifications.php">
								<i class="fa fa-bell"></i>
								<span><?=$notification_count?> Notifications</span>
							</a>
						</div>
						<div class="dashboard-item">
							<a href="tasks.php">
								<i class="fa fa-square-o"></i>
								<span><?=$pending_tasks?> Pending</span>
							</a>
						</div>
						<div class="dashboard-item">
						<a href="tasks.php">
							<i class="fa fa-spinner"></i>
							<span><?=$in_progress_tasks?> In Progress</span>
							</a>
						</div>
						<div class="dashboard-item">
							<a href="tasks.php">
								<i class="fa fa-check-square-o"></i>
								<span><?=$completed_tasks?> Completed</span>
							</a>
						</div>
					</div>
			<?php } else {  ?>
					<!-- Mitarbeiter Dashboard -->
					<div class="dashboard">
                    	<!-- Dashboard-Elemente für Mitarbeiter -->
						<div class="dashboard-item">
							<a href="my_task.php">
								<i class="fa fa-tasks"></i>
								<span><?=$num_task?> All Tasks</span>
							</a>
						</div>
						<div class="dashboard-item">
							<a href="my_task.php?due_date=Overdue">
								<i class="fa fa-window-close-o"></i>
								<span><?=$overdue_task?> Overdue</span>
							</a>
						</div>
						<div class="dashboard-item">
							<a href="my_task.php?due_date=No Deadline">
								<i class="fa fa-clock-o"></i>
								<span><?=$no_deadline_task?> No Deadline</span>
							</a>
						</div>
						<div class="dashboard-item">
							<a href="my_task.php?due_date=Due Today">
								<i class="fa fa-exclamation-triangle"></i>
								<span><?=$todaydue_task?> Due Today</span>
							</a>
						</div>
						<div class="dashboard-item">
							<a href="my_task.php?status=Pending">
								<i class="fa fa-square-o"></i>
								<span><?=$pending_tasks?> Pending</span>
							</a>
						</div>
						<div class="dashboard-item">
							<a href="my_task.php?status=In progress">
								<i class="fa fa-spinner"></i>
								<span><?=$in_progress_tasks?> In Progress</span>
							</a>
						</div>
						<div class="dashboard-item">
							<a href="my_task.php?status=Completed">
								<i class="fa fa-check-square-o"></i>
								<span><?=$completed_tasks?> Completed</span>
							</a>
						</div>
						<div class="dashboard-item">
							<a href="notifications.php">
								<i class="fa fa-bell"></i>
								<span><?=$notification_count?> Notifications</span>
							</a>
						</div>
					</div>
			<?php }  ?>
		</section>
	</div>
    <script>
        // Markiert den ersten Navigationspunkt als aktiv
        const active = document.querySelector("#navList li:nth-child(1)"); 
        active.classList.add("active");
    </script>
</body>
</html>

<?php } else {
	// Wenn der Benutzer nicht angemeldet ist, Weiterleitung zur Login-Seite
	$em = "First login";
	header("Location: login.php?error=$em");
	exit();

}

?>