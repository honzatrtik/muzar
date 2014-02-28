requirejs([

	'pace',
	'can/util/library'

], function(pace, can){

	pace.start({
	});

	can.ajaxPrefilter(function(options, originalOptions, request){
		request.fail(function(request, error) {
			// Chybu vyhazujeme jen pri 4XX a 5XX chybach
			if (request.status[0] == 4 || request.status[0] == 5) {
				throw new Error('Request error: ' + request.status);
			} else {
				pace.restart();
			}
		});
	});

});