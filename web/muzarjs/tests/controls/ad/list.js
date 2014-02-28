define([

	'funcunit',
	'jquery',
	'controls/ad/list'

], function(F, $, AdListControl) {

	var state;
	var $content;

	QUnit.module("AdListControl", {
		setup: function(){
			state = new can.Map({});
			$content = $('#content');
		},
		teardown: function(){
			$content.empty();
		}
	});

	QUnit.asyncTest("update", function () {

		var control = new AdListControl($content, {
			state: state
		});
		control.update().done(function() {

			QUnit.ok($('#content div').length > 0, 'Control has > 0 children');
			QUnit.start();

		});

	});

	QUnit.asyncTest("loadNext", function () {

		var control = new AdListControl($content, {
			state: state
		});
		control.update().done(function() {

			var length = control.options.list.length;
			control.loadNext().done(function() {
				QUnit.ok(control.options.list.length > length);
				QUnit.start();
			});

		});

	});

	QUnit.asyncTest("route change", function () {


		var control = new AdListControl($content, {
			state: state
		});


		control.update().done(function() {

			state.attr('limit', 1);



			F($content).wait(function(){

				return  $content.find('.item').length == 1;

			}, 5000, function() {

				QUnit.ok(true);
				QUnit.start();

			});


		});

	});


});