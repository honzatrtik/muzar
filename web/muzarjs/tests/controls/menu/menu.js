define([

	'funcunit',
	'jquery',
	'controls/menu/menu',
	'models/category'

], function(F, $, MenuControl, CategoryModel) {

	var $content= $('#content');
	var control;

	QUnit.module("Menu module", {
		setup: function() {
			control = new MenuControl($content, {
				model: new CategoryModel('http://muzarcz.apiary.io/category')
			});
		},
		teardown: function(){
			$('#content').empty();
		}
	});

	QUnit.asyncTest("MenuControl.render", function () {

		control.load({}, function() {

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

	QUnit.asyncTest("MenuControl.setSelected & MenuControl.getSelected", function () {

		control.load({}, function() {
			control.setSelected('elektricke-kytary');
			QUnit.equal(control.getSelected(), 'elektricke-kytary');
			QUnit.start();
		});

	});

	QUnit.asyncTest("MenuControl.getSelectedCategory", function () {

		control.load({}, function() {
			control.setSelected('elektricke-kytary');
			QUnit.equal(control.getSelectedCategory().strId, 'elektricke-kytary');
			QUnit.start();
		});

	});


	QUnit.asyncTest("MenuControl.getSelectedElement", function () {

		control.load({}, function() {
			control.setSelected('elektricke-kytary');
			QUnit.equal(control.getSelectedElement().data('category').strId, 'elektricke-kytary');
			QUnit.start();
		});

	});

	QUnit.asyncTest("MenuControl events", function () {

		control.load({}, function() {

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