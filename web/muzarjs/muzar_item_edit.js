
requirejs(['common'], function() {

	requirejs([

		'can',
		'jquery',
		'controls/category/select',
		'controls/contact/address',
		'boot'

	], function(can, $, CategorySelectControl, AddressControl) {


		$(function() {
			// On ready

			var $form = $('#edit');
			var elements = $form.prop('elements');

			// Cena
			var negotiablePrice = can.compute(elements['negotiablePrice'], 'checked', 'change').bind('change', function(event, newVal) {
				$(elements['price']).prop('disabled', newVal);
			});
			can.trigger(negotiablePrice, 'change', negotiablePrice());


			var $category = $('#category').hide().find('select');
			var select = new CategorySelectControl($('#category-widget'));
			select.update().done(function() {
				select.setValue($category.val());
			});
			select.options.input.bind('change', function(event, value) {
				$category.val(value);
			});


			var address = new AddressControl($('#address-widget'));
			address.setReference($(elements['contact[reference]']).val());
			var place = address.options.place;
			place.bind('change', function(event) {
				var location = place.attr('geometry.location');
				$(elements['contact[lat]']).val(location ? location.lat() : '');
				$(elements['contact[lng]']).val(location ? location.lng() : '');
				$(elements['contact[place]']).val(place.attr('formatted_address'));
				$(elements['contact[reference]']).val(place.attr('reference'));
			});

		});


	});

});
