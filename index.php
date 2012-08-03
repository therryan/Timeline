<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Timeline of History</title>
	<meta name="author" content="Teemu Vasama">

	<link rel="stylesheet" type="text/css" href="style.css" />
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
	<script type="text/javascript" src="model.js"></script>
	<script type="text/javascript">
	
	$(document).ready(function() {
		loadTimeline($("#timeline"), 1770, 2020, 10);
	});
	</script>
</head>
<body>
<div id="timeline">
	<div id="eras">
	</div>
	<div id="markers">
	</div>
	<div id="events">
	</div>
<?php
	// This presumes that the database already exists
	try {
		$db = new PDO("sqlite:/Users/teemu/Sites/timeline/data.db");
	} catch (PDOException $e) {
		die("ERROR:" . $e->getMessage());
	}
	
	$stmt = $db->prepare("SELECT * FROM events ORDER BY date");
	$stmt->execute();
	$result = $stmt->fetchAll();
	
	// Sets the timezone so PHP won't complain
	date_default_timezone_set("Europe/Helsinki");
	
	// Produces the actual, ordered HTML strucure from the data
	foreach ($result as $row) {
		$date = $row["date"];
		$type = $row["type"];
		$desc = $row["desc"];
		
		/* To make sure that centuries come before events, centuries use the format
		 * "1800", whereas an event that only specifies a year must use the format
		 * "1800-00-00". This way centuries are ordered before events in the same year.
		 * With events that only have a year, this code removes the -00-00 suffix and with
		 * events that have a specific date, this code transforms it into a nicer format.*/
		$dateComponents = explode("-", $date);
		
		// Deals with the "1800" case, i.e. a century
		if (count($dateComponents) == 1) {
			$date = $dateComponents[0];
		
		// Deals with the "1800-MM-DD" case
		} elseif (count($dateComponents) == 3) {
			$dateObj = new DateTime($date);
				
			// The special "1800-00-00" case
			if ($dateComponents[1] == "00") {
				$date = $dateComponents[0];
			
			// Any another case, like "1800-5-23"
			} else {
				$date = $dateObj->format("j.n.Y");
			}
		
		// Deals with negative cases, like "-378-00-00"
		// The above example will produce the array ["", "378", "00", "00"], which is 
		// why we check that there are four items and the first one's empty
		} elseif (count($dateComponents) == 4 && empty($dateComponents[0])) {

			// Removes the first item since it's empty
			$dateComponents = array_slice($dateComponents, 1);
			
			$dateObj = new DateTime(substr($date, 1));
				
			// The special "-300-00-00" case
			if ($dateComponents[1] == "00") {
				$date = $dateComponents[0] . " eaa.";
			
			// Any another case, like "-652-5-23"
			} else {
				$date = $dateObj->format("j.n.Y") . " eaa.";
			}
		
		// If something else, die
		} else {
			var_dump($dateComponents);
			die("Problem with date formatting!\n");
		}
		
		if ($type == "event" || $type == "death") {
			/*echo <<<DOC
		<span class="event">
			<span class="date">$date</span>
			<span class="desc">$desc</span>
		</span>\n
DOC;*/
		}
	}		
?>
</div>
</body>
</html>