define([

	'funcunit',
	'jquery',
	'models/apiary/category',
	'can/util/string'

], function(F, $, model, can) {

	QUnit.module("CategoryModel", {
		setup: function(){
		},
		teardown: function(){
		}
	});

	QUnit.asyncTest("get", function () {

		model.get({}, function(data) {
			QUnit.ok(data, 'Some data was passed.');
			QUnit.ok(data.length, 'data instanceof Array');

			QUnit.start();
		});



	});


});