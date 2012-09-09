<?php

// This presumes that the database already exists
try {
	$db = new PDO("sqlite:/Users/teemu/Sites/timeline/data.db");
} catch (PDOException $e) {
	die("ERROR:" . $e->getMessage());
}

// Fetches a single event, for example "fetch.php?id=24"
if (!empty($_GET["id"])) {
	$id = $_GET["id"];
	$stmt = $db->prepare("SELECT * FROM events WHERE id = $id");
	$stmt->execute();
	$result = $stmt->fetchAll();
	echo json_encode($result);
}

date_default_timezone_set("Europe/Helsinki");
?>