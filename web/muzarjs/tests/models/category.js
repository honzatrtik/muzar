define([

	'funcunit',
	'jquery',
	'models/category',
	'can/util/string'

], function(F, $, CategoryModel, can) {

	QUnit.module("CategoryModel module", {
		setup: function(){
		},
		teardown: function(){
		}
	});

	QUnit.asyncTest("CategoryModel.get", function () {

		var m = new CategoryModel('http://muzarcz.apiary.io/category');

		m.get({}, function(data) {
			QUnit.ok(data, 'Some data was passed.');
			QUnit.ok(data.length, 'data instanceof Array');

			QUnit.start();
		});



	});


});