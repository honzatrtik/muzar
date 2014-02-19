define([

	'funcunit',
	'jquery',
	'controls/item/item_list',
	'can/util/string',
	'mustache!./item_list',
	'can/util/fixture',
	'can/model'

], function(F, $, ItemListControl, can, renderer) {

	QUnit.module("ItemListControl", {
		setup: function(){
		},
		teardown: function(){
			$('#content').empty();
		}
	});

	QUnit.asyncTest("load", function () {

		var $content = $('#content');


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


		var control = new ItemListControl($content, {
			model: can.Model.extend({
				findAll: 'GET /item'
			}, {}),
			itemRenderer: can.view.mustache('<div class="item">{{id}}-{{name}}</div>')
		});

		control.load().done(function() {

			QUnit.equal($content.find('.item').length, 100, 'Control has 100 .item children.');
			QUnit.start();

		});


	});


});