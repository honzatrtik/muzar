define([

	'can'

], function (can) {

	return (function(state) {

		can.Component.extend({
			tag: 'a',
			scope: {
				state: state
			},
			events: {
				click: function(element, event) {
				}
			}

		});
	});

});

