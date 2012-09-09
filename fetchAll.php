<?php
// Fetches the data in the specified range and returns it in a convenient format

// This presumes that the database already exists
try {
	$db = new PDO("sqlite:/Users/teemu/Sites/timeline/data.db");
} catch (PDOException $e) {
	die("ERROR:" . $e->getMessage());
}

if (!empty($_GET["startdate"]) && !empty($_GET["enddate"])) {
	$startDate = $_GET["startdate"];
	$endDate = $_GET["enddate"];
	
	if ($endDate < $startDate) {
		die("End date '$endDate' is smaller than start date '$startDate'");
	}
	
	$stmt = $db->prepare("SELECT * FROM events " .
		"WHERE date BETWEEN $startDate AND $endDate ORDER BY date DESC");
	$stmt->execute();
	$result = $stmt->fetchAll();
	echo json_encode($result);
}

// Sets the timezone so PHP won't complain
date_default_timezone_set("Europe/Helsinki");
?>