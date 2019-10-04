var $ = require('jquery');

// addTagFormDeleteLink function

module.exports = function passDisplay() {

	$('.secret-field').on('click', function(e) {
		// prevent the link from creating a "#" on the URL
		e.preventDefault();
		$(this).removeClass('secret-hts');
	});
	
}
;
