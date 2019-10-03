var $ = require('jquery');

require('../css/adm.scss');
require('animate.css');

var passDisplay = require('./passDisplay');

// import the function from greet.js (the .js extension is optional)
// ./ (or ../) means to look for a local file
//var greet = require('./greet');
 
$(document).ready(function() {
	$(function() {
		$('[data-toggle="tooltip"]').tooltip();
	})
	passDisplay();

});
