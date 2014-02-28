define([
	'models/ad',
	'controls/item/list',
	'mustache!./templates/item',
	'util/helpers'
], function (AdModel, ItemListControl, itemRenderer) {

	return ItemListControl.extend({
		defaults: {
			model: AdModel,
			itemRenderer: itemRenderer
		}
	}, {
	});

});