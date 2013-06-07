jQuery(document).ready(function($) {
	$('.menu-toggle').on('click', function() {
		$('.nav-menu > ul').slideToggle();
	});
});