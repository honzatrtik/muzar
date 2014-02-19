define([

	'funcunit',
	'jquery',
	'controls/menu/menu',
	'models/category'

], function(F, $, MenuControl, CategoryModel) {

	var $content= $('#content');
	var control;

	QUnit.module("MenuControl", {
		setup: function() {
			control = new MenuControl($content, {
				model: new CategoryModel('http://muzarcz.apiary.io/category')
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

				var $lis = $('#content > ul > li ');
				QUnit.ok($lis.length > 0, '"> ul > li" at least 1 exists');

				$lis.each(function(i, li) {
					QUnit.ok($(li).data('category'), 'Every "> ul > li" has data key "category".');
				});

			});
			QUnit.start();

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

			var $li = $('#content ul li').eq(1);

			F($li).click(500).then(function() {
				QUnit.ok($li.hasClass('selected'), 'Clicked element has class "selected"');
				QUnit.ok(control.getSelectedElement().hasClass('selected'), 'Selected element has class "selected"');
				QUnit.equal(control.getSelectedElement()[0], $li[0], 'Clicked and selected elements are same.');

				QUnit.start();
			});


		});

	});




});