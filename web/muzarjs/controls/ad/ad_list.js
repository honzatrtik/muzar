define(['models/ad', 'controls/item/item_list', 'mustache!./ad_list'], function (AdModel, ItemListControl, renderer) {

	return ItemListControl.extend({
		defaults: {
			model: AdModel,
			renderer: renderer
		}
	}, {
	});

});