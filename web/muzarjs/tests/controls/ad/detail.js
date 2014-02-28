define([

	'funcunit',
	'jquery',
	'controls/ad/detail'

], function(F, $, AdDetailControl) {

	var state;
	var $content;

	QUnit.module("AdDetailControl", {
		setup: function(){
			state = new can.Map({});
			$content = $('#content');
		},
		teardown: function(){
			$content.empty();
		}
	});

	QUnit.asyncTest("update", function () {

		var control = new AdDetailControl($content, {
			state: state
		});

		state.attr('id', 1);

		control.update().done(function() {

			QUnit.ok($('#content .container').length > 0, 'Control updated content');
			QUnit.start();

		});

	});




});