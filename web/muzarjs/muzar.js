requirejs(['controls/menu/menu', 'controls/ad/ad_list', 'models/category', 'util/scroll_top_restorer', 'can', 'jquery'], function(MenuControl, AdListControl, CategoryModel, ScrollTopRestorer, can, $){

	can.ajaxPrefilter(function(options, originalOptions, request){
		request.fail(function() {
			console.log(arguments);
			throw new Error('Ajax loading error!');
		});
	});


	var adList = new AdListControl($('#ad-list'));


	var listener = new ScrollTopRestorer($('body'));
	can.bind.call(adList, 'beforeListSpliced', function() {
		listener.saveHeight();
	});
	can.bind.call(adList, 'afterListSpliced', function() {
		listener.restoreScrollTop();
	});


	var menu = new MenuControl($('#menu'), {
		model: new CategoryModel('http://muzarcz.apiary.io/category')
	});

	can.route.ready();

	menu.load({}).done(function() {

		adList.load({});
		menu.getSelectedCompute().bind('change', function(event, category) {
			adList.load({
				category: category
			});
		});

	});



});