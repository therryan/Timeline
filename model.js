var changeColour = function($target, htmlColour) {
	$target.css("color", htmlColour);
}

var loadTimeline = function($timeline, cb) {
	$timeline.fadeIn("slow", function() {
		cb();
	});
}