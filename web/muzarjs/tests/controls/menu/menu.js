define([

	'funcunit',
	'jquery',
	'controls/menu/menu',
	'models/category'

], function(F, $, MenuControl, model) {

	var $content= $('#content');
	var control;

	QUnit.module("MenuControl", {
		setup: function() {
			control = new MenuControl($content, {
				model: model
			});
		},
		teardown: function(){
			$('#content').empty();
		}
	});

	QUnit.asyncTest("render", function () {

		control.load().done(function() {

			F('#content ul').exists(200).then(function() {
				QUnit.equal($('#content > ul').length, 1, '"> ul" exists');

				var $items = $('#content ul li .category');
				QUnit.ok($items.length > 0, '"> ul > li > .category" at least 1 exists');

				$items.each(function(i, item) {
					QUnit.ok($(item).data('category'), 'Every "> ul > li > .category" has data key "category".');
				});

				QUnit.start();
			});


		});

	});

	QUnit.asyncTest("setSelected & getSelected", function () {

		control.load().done(function() {
			control.setSelected('elektricke-kytary');
			QUnit.equal(control.getSelected(), 'elektricke-kytary');
			QUnit.start();
		});

	});

	QUnit.asyncTest("getSelectedCategory", function () {

		control.load().done(function() {
			control.setSelected('elektricke-kytary');
			QUnit.equal(control.getSelectedCategory().strId, 'elektricke-kytary');
			QUnit.start();
		});

	});


	QUnit.asyncTest("getSelectedElement", function () {

		control.load().done(function() {
			control.setSelected('elektricke-kytary');
			QUnit.equal(control.getSelectedElement().data('category').strId, 'elektricke-kytary');
			QUnit.start();
		});

	});

	QUnit.asyncTest("events", function () {

		control.load().done(function() {

			var $item = $('#content ul li .category').eq(1);

			can.route.attr('limit', 2);
			can.route.attr('startId', 800);

			$item.trigger('click');

			QUnit.ok($item.hasClass('active'), 'Clicked element has class "active"');
			QUnit.ok(control.getSelectedElement().hasClass('active'), 'Selected element has class "active"');
			QUnit.equal(control.getSelectedElement()[0], $item[0], 'Clicked and selected elements are same.');

			QUnit.ok(!can.route.attr('limit'));
			QUnit.ok(!can.route.attr('startId'));

			QUnit.start();
		});

	});


});