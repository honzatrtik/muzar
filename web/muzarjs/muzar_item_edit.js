requirejs(['common'], function() {

	requirejs([

		'can',
		'jquery',
		'models/category',
		'boot'

	], function(can, $, CategoryModel) {

		console.log(arguments);

		$(function() {
			// On ready

			var $form = $('#edit');
			var elements = $form.get(0).elements;

			$(elements['negotiablePrice']).prop('checked', 1);

			// Cena
			var negotiablePrice = can.compute(elements['negotiablePrice'], 'checked', 'change').bind('change', function(event, newVal) {
				$(elements['price']).prop('disabled', newVal);
			});
			can.trigger(negotiablePrice, 'change', negotiablePrice());




		});


		CategoryModel.findAll().done(function() {

		});




	});

});
