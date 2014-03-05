define([

	'funcunit',
	'jquery',
	'models/category',
	'can/util/string'

], function(F, $, model, can) {

	QUnit.module("CategoryModel", {
		setup: function(){
		},
		teardown: function(){
		}
	});

	QUnit.asyncTest("findAll", function () {

		model.findAll({}, function(data) {
			QUnit.ok(data, 'Some data was passed.');
			QUnit.ok(data.length, 'data instanceof Array');

			QUnit.start();
		});



	});


});