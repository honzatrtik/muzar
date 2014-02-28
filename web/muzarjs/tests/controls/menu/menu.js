define([

	'funcunit',
	'jquery',
	'controls/menu/menu',
	'models/category'

], function(F, $, MenuControl, model) {

	var $content= $('#content');
	var control;
	var state;

	QUnit.module("MenuControl", {
		setup: function() {
			state = new can.Map({});
			control = new MenuControl($content, {
				model: model,
				state: state
			});
		},
		teardown: function(){
			$content.empty();
		}
	});

	QUnit.asyncTest("render", function () {

		control.update().done(function() {

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

		control.update().done(function() {
			control.setSelected('elektricke-kytary');
			QUnit.equal(control.getSelected(), 'elektricke-kytary');
			QUnit.start();
		});

	});

	QUnit.asyncTest("getSelectedCategory", function () {

		control.update().done(function() {
			control.setSelected('elektricke-kytary');
			QUnit.equal(control.getSelectedCategory().strId, 'elektricke-kytary');
			QUnit.start();
		});

	});


	QUnit.asyncTest("getSelectedElement", function () {

		control.update().done(function() {
			control.setSelected('elektricke-kytary');
			QUnit.equal(control.getSelectedElement().data('category').strId, 'elektricke-kytary');
			QUnit.start();
		});

	});

	QUnit.asyncTest("visibility", function () {

		control.update().done(function() {

			$content.find(' > ul > li').each(function() {
				QUnit.ok($(this).is(':visible'));
			});

			$content.find('ul ul').each(function() {
				QUnit.ok(!$(this).is(':visible'));
			});

			QUnit.start();
		});

	});

});