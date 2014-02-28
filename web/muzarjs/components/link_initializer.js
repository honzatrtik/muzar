define([

	'can'

], function (can) {

	return (function(state) {

		can.Component.extend({
			tag: 'a',
			scope: {
				state: state,
				'data-route': '@',
				'data-route-remove': '@'
			},
			events: {
				click: function(element, event) {

					var route = element.data('route');
					var remove = element.data('routeRemove') || false;

					if (route) {

						event.preventDefault();
						event.stopPropagation();

						var params = can.route.deparam(route);

						can.batch.start();

						// Prepiseme route params
						var state = this.scope.state;
						state.attr(params, remove);

						// Vsechny prazdne params z routy vyhodime
						can.each(params, function(value, key) {
							if (!value) {
								state.removeAttr(key);
							}
						});

						can.batch.stop();
					}

				}
			}

		});
	});

});

