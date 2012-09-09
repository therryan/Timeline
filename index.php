<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Timeline of History</title>
	<meta name="author" content="Teemu Vasama">

	<link rel="stylesheet" type="text/css" href="style.css" />
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
	<script type="text/javascript" src="jcanvas.min.js"></script>
	<script type="text/javascript" src="suitelib/suitejs.js"></script>
	<script type="text/javascript" src="model.js"></script>
	<script type="text/javascript">
	
	$(document).ready(function() {
		// Ask the user for a range
		var startingPoint = parseInt(prompt("Which should be the first year?"));
		var endingPoint = parseInt(prompt("From " + startingPoint + " to?"));
		
		// Calculate how many pixels each year should have
		// + 1 so that the last element is included in the page
		var pixelsPerYear = window.innerHeight / (endingPoint - startingPoint + 1);
		if (pixelsPerYear < 10) {
			pixelsPerYear = 10;
		}
		
		loadTimeline($("#timeline"), startingPoint, endingPoint, pixelsPerYear);
		
		// Hide the year markers when the view is too crammed
		/*if (pixelsPerYear < 15) {
			$("p.year").css("opacity", "0");
		
			$("p.year").hover(
				function() {
					$(this).css("opacity", "1");
			},
				function() {
					$(this).css("opacity", "0");
			});
		}*/
		$("body").ajaxSuccess(function(e, xhr, settings) {
			if (settings.url.match("fetchAll")) {
				$("canvas").hover(
					function() {
						console.log("hover");
						$(".popup").remove();
						var id = this.id;
						var e;
						$.get("fetchEvent.php", { id: id }, function(data) {
							e = new Event(id, data[0].date, data[0].type, data[0].desc);
							var $popup = $(
							'<div class="popup">' +
								'<p>' + e.getYear() + '</p>' +
								'<p>' + e.getDescription() + '</p>' +
							'</div>')
							$("body").append($popup);
						}, "json")
					},
					function () {
						//$(".popup").remove();
				});
			}
		});
	});
	</script>
</head>
<body>
<div id="timeline">
</div>
</body>
</html>