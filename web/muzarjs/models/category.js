var subdir = (window.location.pathname.indexOf('app_dev.php') !== -1)
	? 'dev'
	: 'prod';

define([

	'models/' + subdir + '/category'

], function(model) {

	return model;

});

