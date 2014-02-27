define([

	'funcunit',
	'jquery',
	'models/ad',
	'can/util/string'

], function(F, $, AdModel, can) {

	QUnit.module("AdModel", {
		setup: function(){
		},
		teardown: function(){
		}
	});

	QUnit.asyncTest("List.loadNext", function () {


		AdModel.findAll({}, function(list) {

			QUnit.ok(list.attr('meta.nextLink'), 'List has "meta.nextLink attr"');

			var len = list.length;
			list.loadNext(function() {

				QUnit.ok(list.length > len);
				QUnit.ok(list.attr('meta.nextLink'), 'List has "meta.nextLink attr"');

				QUnit.start();

			});


		});



	});


});