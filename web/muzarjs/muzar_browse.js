requirejs(['common'], function() {

	requirejs([

		'can',
		'jquery',
		'boot'

	], function(can, $){

		var Router = can.Control.extend({}, {

			init: function(element, options) {
			},

			'{can.route} view': function(route, event, newVal, oldVal) {
				this.update();
			},

			update: function() {
				var self = this;

				console.log(can.route.attr('view'));

				var view = (can.route.attr('view') || '').replace('.', '/');
				if (view) {

					requirejs(['controls/views/' + view ], function(Control) {



						var $inner = $('<div />').hide().appendTo(self.element);
						var tempControl = new Control($inner, {
							state: can.route
						});

						tempControl.update().done(function() {

							if (self.innerElement) {
								self.innerElement.remove();
							}
							$inner.show();

							self.innerElement = $inner;

							if (self.control) {
								self.control.destroy();
							}
							self.control = tempControl;
						});




					});
				} else {
					this.element.html('');
				}


			}

		});


		var router = new Router($('#content'), {});
		can.route.ready();

		if (!can.route.attr('view')) {
			can.route.attr('view', 'ad.list');
		}


	});

});
