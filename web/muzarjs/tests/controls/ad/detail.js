define([

	'funcunit',
	'jquery',
	'controls/ad/detail'

], function(F, $, AdDetailControl) {

	var $content = $('#content');
	var state;
	var $element;

	QUnit.module("AdDetailControl", {
		setup: function(){
			state = new can.Map({});
			$element = $('<div/>').appendTo($content);
		},
		teardown: function(){
			$element.remove();
		}
	});

	QUnit.asyncTest("update", function () {

		var control = new AdDetailControl($element, {
			state: state
		});

		state.attr('id', 1);

		control.update().done(function() {

			QUnit.ok($('#content .container').length > 0, 'Control updated content');
			QUnit.start();

		});

	});




});