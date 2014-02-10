define([

	'funcunit',
	'jquery',
	'controls/ad/ad_list'

], function(F, $, AdListControl) {

	QUnit.module("Ad module", {
		setup: function(){
		},
		teardown: function(){
			$('#content').empty();
		}
	});

	QUnit.test("AdListControl", function () {

		var $content = $('#content');

		var control = new AdListControl($content, {});
		control.load();

		F('#content div').exists(2000).then(function() {
			QUnit.ok($('#content div').length > 0, 'Control has > 0 children')
		});


	});


});