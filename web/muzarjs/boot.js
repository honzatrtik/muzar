requirejs([

	'pace',
	'can/util/library'

], function(pace, can){

	pace.start({
	});

	can.ajaxPrefilter(function(options, originalOptions, request){
		request.fail(function() {
			console.log(arguments);
			throw new Error('Ajax loading error!');
		});
	});

});