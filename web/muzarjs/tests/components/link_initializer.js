define([

	'funcunit',
	'jquery',
	'can',
	'components/link_initializer'

], function(F, $, can, linkInitializer) {

	var $content;

	QUnit.module("LinkComponent initializer", {
		setup: function(){
			$content = $('#content');
		},
		teardown: function(){
			$content.empty();
		}
	});

	QUnit.test("link click triggers state change", function () {


		var state = new can.Map();
		linkInitializer(state);

		$content.html((can.view.mustache('<a data-route="someKey=someValue&otherKey=otherValue">test</a>'))({}));
		$content.find('a').trigger('click');


		QUnit.equal(state.attr('someKey'), 'someValue');
		QUnit.equal(state.attr('otherKey'), 'otherValue');

	});


	QUnit.test("link click triggers state change & remove others", function () {

		var state = new can.Map();
		linkInitializer(state);
		state.attr('removed', 'stillHere');

		$content.html((can.view.mustache('<a data-route="someKey=someValue&otherKey=otherValue" data-route-remove="true">test</a>'))({}));
		$content.find('a').trigger('click');

		QUnit.equal(state.attr('someKey'), 'someValue');
		QUnit.equal(state.attr('otherKey'), 'otherValue');
		QUnit.ok(!state.attr('removed'), 'Param removed must be not present.');

	});



});