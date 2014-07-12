<html>
<head>
<title><?php echo PAGE_TITLE; ?></title>

<link rel="stylesheet" type="text/css" href="assets/style.css" />
<link rel="stylesheet" type="text/css" href="assets/site.css" />
<script type="text/javascript" src="assets/javascript.js"></script>

</head>
<body><div class="wrapper">

<div class="header">
<?php echo HEADER_CONTENT; ?>
</div>

<div class="title">Roleplay Schedule:</div><ul class="schedule_list">

<?php

$i = 0;
foreach (schedule::getSchedule() as $day) {
	$lastClass = (++$i == 7) ? ' last' : ''; 

	echo '<li class="schedule_list_item'.$lastClass.'">';

	echo 	'<div class="label">'.date('l, F d, Y', strtotime($day->date)).'</div><div class="location">';

	$locLink = $day->location->link;

	if (!$locLink) {
		$locLink = 'https://www.google.com/search?q=';
		$locLink .= str_replace(' ', '+', urlencode(GAME_NAME.' '.$day->location->zone.' '.$day->location->location));
	}

	echo $day->location->zone.' - <a href="'.$locLink.'" target="_blank">'.$day->location->location.'</a>';

	echo '</div></li>';
}

?>
</ul>


<div class="title">Random Suggestion:</div>

<ul><li>

<?php
$locations = locations::getLocations();
$rLoc = $locations[rand(0, max(0, (count($locations)-1)))];

$locLink = $rLoc->link;

if (!$locLink) {
	$locLink = 'https://www.google.com/search?q=';
	$locLink .= str_replace(' ', '+', urlencode(GAME_NAME.' '.$rLoc->zone.' '.$rLoc->location));
}

echo $rLoc->zone.' - <a href="'.$locLink.'" target="_blank">'.$rLoc->location.'</a>';

?>

</li></ul>


<div class="title">Add New Location:</div>

<form method="post">
	<ul class="formlist">
		<?php
		if (isset($errorMessage) && $errorMessage) {
			echo '<li class="error">Error: '.$errorMessage.'</li>';
		}
		?>
		<li><div class="formlabel">Zone/City</div><div><input type="text" name="newZone" value="<?php echo htmlspecialchars($zone); ?>"></div></li>
		<li><div class="formlabel">Specific Location</div><div><input type="text" name="newLocation" value="<?php echo htmlspecialchars($location); ?>"><br/></div></li>
		<li><div class="formlabel">Link (optional)</div><div><input type="text" name="newLink" value="<?php echo htmlspecialchars($link); ?>"><br/></div></li>
		<li><div class="button"><input type="submit" value="Add Location"></div></li>
	</ul>
</form>


<div class="title known">Known Roleplay Locations:</div>

<ul class="location_list">

<?php
$i = 0;
$count = count($locations);
foreach ($locations as $location) {
	$lastClass = (++$i == $count) ? ' last' : '';

	echo '<li class="location_list_item'.$lastClass.'">';

	$locLink = $location->link;

	if (!$locLink) {
		$locLink = 'https://www.google.com/search?q=';
		$locLink .= str_replace(' ', '+', urlencode(GAME_NAME.' '.$location->zone.' '.$location->location));
	}

	echo $location->zone.' - <a href="'.$locLink.'" target="_blank">'.$location->location.'</a>';

	echo '<img src="assets/delete.png" onclick="spot.deleteLocation(\''.$location->id.'\');"/>';
	echo '<img src="assets/edit.png"/>';

	echo '</li>';
}
?>

</ul>
</div></body>
</html>