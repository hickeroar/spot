<?php

require_once('settings.php');

date_default_timezone_set(TIMEZONE);

require_once('locations.php');
require_once('schedule.php');

// Form values
$zone = $location = $link = '';

if (isset($_POST['newZone'])) {
	$locations = new locations();
	$errorMessage = $locations->add($_POST['newZone'], $_POST['newLocation'], $_POST['newLink']);

	if ($errorMessage) {
		$zone = $_POST['newZone'];
		$location = $_POST['newLocation'];
		$link = $_POST['newLink'];
	}
}

$schedule = new schedule();
$schedule->updateSchedule();

require_once('view.php');