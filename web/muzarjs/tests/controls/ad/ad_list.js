define([

	'funcunit',
	'jquery',
	'controls/ad/ad_list'

], function(F, $, AdListControl) {

	QUnit.module("AdListControl", {
		setup: function(){
		},
		teardown: function(){
			$('#content').empty();
		}
	});

	QUnit.asyncTest("load", function () {

		var $content = $('#content');
		var control = new AdListControl($content, {});
		control.load().done(function() {

			QUnit.ok($('#content div').length > 0, 'Control has > 0 children');
			QUnit.start();

		});

	});

	QUnit.asyncTest("loadNext", function () {

		var $content = $('#content');

		var control = new AdListControl($content, {

		});
		control.load().done(function() {

			var length = control.options.list.length;
			control.loadNext().done(function() {
				QUnit.ok(control.options.list.length > length);
				QUnit.start();
			});

		});

	});

	QUnit.asyncTest("route change", function () {

		var $content = $('#content');

		var control = new AdListControl($content, {});


		control.load().done(function() {


			can.route.attr('limit', 1);

			F('#content').wait(function(){
				return  $('#content .item').length == 1;
			}, 2000, function() {
				QUnit.ok(true);
				QUnit.start();
			});


		});

	});


});