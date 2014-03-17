define([

	'funcunit',
	'jquery',
	'controls/menu/menu',
	'models/category',
	'can',
	'can/util/object',
	'can/util/fixture'

], function(F, $, MenuControl, model, can) {

	var $content = $('#content');
	var $element;
	var control;
	var state;


	QUnit.module("MenuControl", {
		setup: function() {
			$element = $('<div/>').appendTo($content);
			can.fixture("GET /app_dev.php/api/categories",function(){
				var str = '{"meta":[],"data":[{"id":2,"strId":"kytary","name":"Kytary","children":[{"id":3,"strId":"elektricke-kytary","name":"Elektricke kytary","children":[{"id":4,"strId":"strat","name":"Stratocaster","children":[]},{"id":5,"strId":"tele","name":"Telecaster","children":[]},{"id":6,"strId":"lp","name":"LesPaul","children":[]},{"id":7,"strId":"sg","name":"SG","children":[]},{"id":8,"strId":"v-hardandheavy","name":"V, hard and heavy","children":[]},{"id":9,"strId":"semiakustika","name":"Semiakustick\u00e9 kytary","children":[]},{"id":10,"strId":"vicestrunne","name":"7 a v\u00edce strunn\u00e9","children":[]},{"id":11,"strId":"ostatni-tvary","name":"Ostatn\u00ed tvary","children":[]}]},{"id":12,"strId":"akusticke-a-klasicke-kytary","name":"Akustick\u00e9 a klasick\u00e9 kytary","children":[{"id":13,"strId":"klasicka-kytara","name":"Klasick\u00e1 kytara","children":[]},{"id":14,"strId":"dvanactistrunna","name":"12 - strunn\u00e1","children":[]},{"id":15,"strId":"jumbo-grand-dreadnaut","name":"Jumbo\/grand\/dreadnaut","children":[]}]},{"id":16,"strId":"ostatni-strunne","name":"Ostatn\u00ed strunn\u00e9","children":[{"id":17,"strId":"ukulele","name":"Ukulele","children":[]},{"id":18,"strId":"banjo","name":"Banjo","children":[]},{"id":19,"strId":"mandolina","name":"Mandolina","children":[]}]},{"id":20,"strId":"levoruke","name":"Levoruk\u00e9","children":[{"id":21,"strId":"levoruke-elektricke","name":"Elektrick\u00e9 kytary","children":[]},{"id":22,"strId":"levoruke-akusticke","name":"Akustick\u00e9 kytary","children":[]}]},{"id":23,"strId":"modeling-usb-hybrid","name":"Modeling, usb, hybrid","children":[]},{"id":24,"strId":"prislusenstvi","name":"P\u0159\u00edslu\u0161enstv\u00ed","children":[]},{"id":25,"strId":"snimace","name":"Sn\u00edma\u010de","children":[]},{"id":26,"strId":"kytarove-aparaty","name":"Kytarov\u00e9 apar\u00e1ty","children":[{"id":27,"strId":"komba","name":"Komba","children":[]},{"id":28,"strId":"zesilovace-hlavy","name":"Zesilova\u010de a hlavy","children":[]},{"id":29,"strId":"reproboxy","name":"Reproboxy","children":[]},{"id":30,"strId":"prislusenstvi-nahradnidily","name":"P\u0159\u00edslu\u0161enstv\u00ed a nahradn\u00ed d\u00edly","children":[]}]},{"id":31,"strId":"kytarove-efekty","name":"Kytarov\u00e9 efekty","children":[{"id":32,"strId":"multiefekty","name":"Multiefekty","children":[]},{"id":33,"strId":"synth","name":"Syn\u0165\u00e1ky","children":[]},{"id":34,"strId":"efektove-pedaly","name":"Efektov\u00e9 ped\u00e1ly","children":[]},{"id":35,"strId":"wah-wah","name":"Wah Wah","children":[]},{"id":36,"strId":"volume","name":"Volume","children":[]},{"id":37,"strId":"rackove-efekty","name":"Rackov\u00e9 efekty","children":[]},{"id":38,"strId":"sitove-efekty","name":"S\u00ed\u0165ov\u00e9 efekty","children":[]},{"id":39,"strId":"pedalboardy","name":"Pedalboardy","children":[]}]}]},{"id":40,"strId":"baskytary","name":"Baskytary","children":[{"id":41,"strId":"elektricke-basy","name":"Elektricke basy","children":[]},{"id":42,"strId":"akusticke-kontrabasy","name":"Akustick\u00e9 a kontrabasy","children":[]}]},{"id":43,"strId":"bici","name":"Bic\u00ed","children":[]},{"id":44,"strId":"klavesy","name":"Kl\u00e1vesy","children":[]},{"id":45,"strId":"dechove-nastroje","name":"Dechov\u00e9 n\u00e1stroje","children":[]},{"id":46,"strId":"smyccove-nastroje","name":"Smy\u010dcov\u00e9 n\u00e1stroje","children":[]},{"id":47,"strId":"zvukova-technika","name":"Zvukov\u00e1 technika","children":[]},{"id":48,"strId":"svetelna-technika","name":"Sv\u011bteln\u00e1 technika","children":[]},{"id":49,"strId":"djs","name":"Djs","children":[]}]}';
				return JSON.parse(str);
			});

			state = new can.Map({});
			control = new MenuControl($element, {
				model: model,
				state: state
			});
		},
		teardown: function(){
			$element.remove();
			can.fixture("GET /app_dev.php/api/categories", null);
		}
	});

	QUnit.asyncTest("render", function () {

		control.update().done(function() {

			QUnit.equal($element.find('> ul').length, 1, '"> ul" exists');

			var $items = $element.find('ul li .category');
			QUnit.ok($items.length > 0, '"> ul > li > .category" at least 1 exists');

			$items.each(function(i, item) {
				QUnit.ok($(item).data('category'), 'Every "> ul > li > .category" has data key "category".');
			});

			QUnit.start();

		});

	});

	QUnit.asyncTest("setSelected & getSelected", function () {

		control.update().done(function() {

			control.setSelected('elektricke-kytary');
			QUnit.equal(control.getSelected(), 'elektricke-kytary');

			QUnit.start();
		});

	});

	QUnit.asyncTest("getSelectedCategory", function () {

		control.update().done(function() {
			control.setSelected('elektricke-kytary');
			QUnit.equal(control.getSelectedCategory().strId, 'elektricke-kytary');

			QUnit.start();
		});

	});


	QUnit.asyncTest("getSelectedElement", function () {

		control.update().done(function() {
			control.setSelected('elektricke-kytary');
			QUnit.equal(control.getSelectedElement().data('category').strId, 'elektricke-kytary');

			QUnit.start();
		});

	});

	QUnit.asyncTest("visibility", function () {

		control.update().done(function() {

			$element.find(' > ul > li').each(function() {
				QUnit.ok($(this).is(':visible'));
			});

			$element.find('ul ul').each(function() {
				QUnit.ok(!$(this).is(':visible'));
			});

			QUnit.start();
		});

	});

});