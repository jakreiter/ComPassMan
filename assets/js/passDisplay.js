var $ = require('jquery');

// addTagFormDeleteLink function

module.exports = function passDisplay() {
	console.log('11!');

	$('.secret-field').on('click', function(e) {
		// prevent the link from creating a "#" on the URL
		e.preventDefault();

		console.log('clicken1!');
		$(this).removeClass('secret-hts');
	});
	
}
