define([
	'models/ad',
	'controls/item/item_list',
	'mustache!./ad',
	'view/helpers'
], function (AdModel, ItemListControl, itemRenderer) {

	return ItemListControl.extend({
		defaults: {
			model: AdModel,
			itemRenderer: itemRenderer
		}
	}, {
	});

});