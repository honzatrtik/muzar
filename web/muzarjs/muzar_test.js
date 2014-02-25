QUnit.config.reorder = false;
requirejs([
	'tests/controls/item/item_list',
	'tests/controls/ad/ad_list',
	'tests/models/category',
	'tests/models/ad',
	'tests/controls/menu/menu'
], function() {

});
