define(['can'], function (can) {

	can.Mustache.registerHelper('saveElement', function(options) {
		return function(element) {
			options.context.element = can.$(element);
		};
	});

});