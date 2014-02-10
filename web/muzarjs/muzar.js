requirejs(['controls/menu/menu', 'controls/ad/ad_list', 'models/category'], function(MenuControl, AdListControl, CategoryModel){


	var menu = new MenuControl($('#menu'), {
		model: new CategoryModel('http://muzarcz.apiary.io/category')
	});
	menu.load({});

	var adList = new AdListControl($('#ad-list'));
	adList.load();

});