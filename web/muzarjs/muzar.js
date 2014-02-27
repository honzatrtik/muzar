requirejs([

	'can',
	'jquery',
	'controls/menu/menu',
	'controls/ad/ad_list',
	'models/category',
	'util/scroll_top_restorer'

], function(can, $, MenuControl, AdListControl, categoryModel, ScrollTopRestorer){

	can.ajaxPrefilter(function(options, originalOptions, request){
		request.fail(function() {
			console.log(arguments);
			throw new Error('Ajax loading error!');
		});
	});


	var adList = new AdListControl($('#ad-list'));


	// Udrzuje pozici po nacteni dalsi stranky vypisu
	var listener = new ScrollTopRestorer($('body'));
	can.bind.call(adList, 'beforeListSpliced', function() {
		listener.saveHeight();
	});
	can.bind.call(adList, 'afterListSpliced', function() {
		listener.restoreScrollTop();
	});


	var menu = new MenuControl($('#menu'), {
		model: categoryModel
	});

	can.route.ready();

	if (!can.route.attr('category')) {
		// Pokud nemame kategorii, nacteme vsechno
		adList.load();
	}

	menu.load({});




});