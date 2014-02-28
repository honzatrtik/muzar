var subdir = (window.location.pathname.indexOf('app_dev.php') !== -1)
	? 'dev'
	: 'prod';


define([

	'models/' + subdir + '/ad'

], function(model) {

	return model;

});