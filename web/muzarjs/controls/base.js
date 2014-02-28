define([

	'can'

], function (can) {

	return can.Control.extend({}, {

		init: function(element, options) {

			if (!options.state)
			{
				throw new Error('Options must contain state attribute.');
			}
			this.on();
		},

		deferUpdate: function(timeout) {

			var self = this;
			if (this.options.deferUpdateTimeout) {
				clearTimeout(this.options.deferUpdateTimeout);
			}
			this.options.deferUpdateTimeout = setTimeout(function() {
				self.update();
			}, timeout || 50);
		},

		/**
		 * @return {can.Deferred}
		 */
		update: function() {
			return can.Deferred.resolve();
		}


	});

});