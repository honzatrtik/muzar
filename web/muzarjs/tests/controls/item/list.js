define([

	'funcunit',
	'jquery',
	'controls/item/list',
	'can/util/string',
	'can/util/fixture',
	'can/model'

], function(F, $, ItemListControl, can) {

	var $content = $('#content');
	var $element;
	QUnit.module("ItemListControl", {
		setup: function(){
			$element = $('<div/>').appendTo($content);

			var store = can.fixture.store(100, function (i) {
				var id = i + 1; // Make ids 1 based instead of 0 based
				return {
					id: id,
					name: 'Item ' + id
				}
			});

			can.fixture({
				'GET /item': store.findAll
			});
		},
		teardown: function(){
			$element.remove();
			can.fixture({
				'GET /item': null
			});
		}
	});

	QUnit.asyncTest("update", function () {


		var model = can.Model.extend({
			findAll: 'GET /item'
		}, {});


		var state = new can.Map();

		var control = new ItemListControl($element, {
			model: model,
			itemRenderer: can.view.mustache('<div>{{id}}-{{name}}</div>'),
			state: state
		});

		control.update().done(function() {

			QUnit.equal($element.find('.item').length, 100, 'Control has 100 .item children.');
			QUnit.start();

		});


	});



});