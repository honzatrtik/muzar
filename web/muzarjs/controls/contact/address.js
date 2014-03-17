define([

	'can',
	'mustache!./templates/address',
	'async!https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=true&libraries=places'

], function (can, renderer) {


	return can.Control.extend({
	}, {

		init: function(element, options) {

			can.Control.prototype.init.apply(this, arguments);

			this.options.place = new can.Map();
			this.options.reference = can.compute(null);

			this.element.html(renderer({
				place: this.options.place
			}));

			this.inputElement = this.element.find('.input');
			this.resultElement = this.element.find('.result');

			this.service = new google.maps.places.PlacesService(this.resultElement.get(0));

			var self = this;
			this.onPlaceChange = can.proxy(onPlaceChanged = function() {

				self.inputElement.one('blur', function(){
					self.inputElement.val('');
				});
				var place = self.autocomplete.getPlace();
				console.log(place);

				self.options.place.attr(place || {});
				self.options.reference = self.options.place.compute('reference');
				self.on();

				setTimeout(function() {
					self.inputElement.val('');
				}, 10);

			}, this);

			this.autocomplete = new google.maps.places.Autocomplete(this.inputElement.get(0), {
				types: ['(cities)']
			});
			google.maps.event.addListener(this.autocomplete, 'place_changed', this.onPlaceChange);


			this.on();
		},

		'.clear click': function(element, event) {
			this.clear();
			this.inputElement.trigger('focus');
		},

		'keydown': function(element, event) {
			if (event.keyCode == 13) {
				event.preventDefault(); // Autocompleter select
			}
		},

		clear: function() {
			this.options.reference(null);
		},

		setReference: function(reference) {
			this.options.reference(reference);
		},

		getReference: function() {
			return self.options.reference();
		},

		'{reference} change': function(compute, event, newVal, oldVal) {
			this.inputElement.val('');
			if (newVal) {

				var self = this;
				this.service.getDetails({ reference: newVal }, function(place, status) {
					if (status == google.maps.places.PlacesServiceStatus.OK) {
						self.options.place.attr(place);
					}
				});

			} else {
				this.options.place.attr({});
			}
		},

		update: function() {
		},

		destroy: function() {
			google.maps.event.removeListener(this.autocomplete, 'place_changed', this.onPlaceChange);
			can.Control.prototype.destroy.apply(this, arguments);
		}

	});

});